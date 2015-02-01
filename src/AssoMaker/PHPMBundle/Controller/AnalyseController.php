<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Config;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Analyse controller.
 *
 * @Route("/analyse")
 */
class AnalyseController extends Controller {

    /**
     * Analyse main page
     *
     * @Route("/", name="analyse")
     * @Template()
     */
    public function analyseAction() {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }


        return array(
        );
    }

    /**
     * Statistiques
     *
     * @Route("/stats", name="analyse_stats")
     * @Template()
     */
    public function statsAction() {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();

        $tachesResult = $em
                ->createQuery("SELECT e.nom,t.statut,count(t) AS total FROM AssoMakerPHPMBundle:Tache t JOIN t.groupeTache g JOIN g.equipe e GROUP BY g.equipe, t.statut")
                ->getResult();

        $tachesStats = array();
        $tachesStatsSum = array("-1" => 0, "0" => 0, "1" => 0, "2" => 0, "3" => 0);


        foreach ($tachesResult as $i) {
            if (!array_key_exists($i['nom'], $tachesStats)) {
                $tachesStats[$i['nom']] = array("-1" => 0, "0" => 0, "1" => 0, "2" => 0, "3" => 0);
            }

            $tachesStats[$i['nom']][$i['statut']] = $i['total'];
            $tachesStatsSum[$i['statut']]+=$i['total'];
        }
        $tachesStats['Total'] = $tachesStatsSum;


        $orgasResult = $em
                ->createQuery("SELECT e.nom as equipe, count(o) as total FROM AssoMakerBaseBundle:Orga o JOIN o.equipe e GROUP BY e ORDER BY total DESC")
                ->getArrayResult();

        $orgasStats = array();

        $orgasStatsSum = 0;
        foreach ($orgasResult as $i) {

            $orgasStats[$i['equipe']] = $i['total'];
            $orgasStatsSum+=$i['total'];
        }
        $orgasStats['Total'] = $orgasStatsSum;

        return array('tachesStats' => $tachesStats,
            'orgasStats' => $orgasStats
        );
    }

    /**
     * Rapport usage Matériel
     *
     * @Route("/usagemateriel", name="analyse_usageMateriel")
     * @Template()
     */
    public function usageMaterielAction() {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        $bmDQL = "SELECT m,bm,t,p,g FROM AssoMakerPHPMBundle:Materiel m JOIN m.besoinsMateriel bm JOIN bm.tache t JOIN t.plagesHoraire p LEFT JOIN t.groupeTache g
    	 WHERE  bm.quantite <> 0 AND t.statut >= 0 ORDER BY p.debut
    	 ";


        $bmResult = $em
                ->createQuery($bmDQL)
                ->getResult();


        return array('bmResult' => $bmResult);
    }

    /**
     * Rapport logistique
     *
     * @Route("/logistique", name="phpm_analyse_logistique")
     * @Template()
     */
    public function logistiqueAction() {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();




        $rawResult = $em
                ->createQuery("SELECT t,g,b,m,p,e,a FROM AssoMakerPHPMBundle:Tache t JOIN t.groupeTache g JOIN g.equipe e JOIN t.plagesHoraire p LEFT OUTER JOIN t.besoinsMateriel b LEFT OUTER JOIN b.materiel m LEFT OUTER JOIN g.animLiee a WHERE t.statut >=0 AND t.statut<=2 ")
                ->getArrayResult();


        foreach ($rawResult as $key => &$t) {




            foreach ($t['plagesHoraire'] as &$p) {

                $fmt = new \IntlDateFormatter(null, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, 'Europe/Paris', null, 'EEEE d MMMM HH:mm');
                $p['debut'] = $fmt->format(date_timestamp_get($p['debut']));
                $p['fin'] = $fmt->format(date_timestamp_get($p['fin']));
            }

            if (($t['materielSupplementaire'] == null) && (count($t['besoinsMateriel']) == 0)) {
                //var_dump($t['id']);
                unset($rawResult[$key]);
            }
        }

        $materiel = $em->createQuery("SELECT m FROM AssoMakerPHPMBundle:Materiel m ORDER BY m.categorie ")->getArrayResult();

        return array('taches' => json_encode($rawResult), 'materiel' => json_encode($materiel));
    }

    /**
     * Rapport responsables
     *
     * @Route("/plagesorga/{orgaid}", defaults={"orgaid"="all"}, name="analyse_plagesorga")
     * @Template()
     */
    public function plagesOrgaAction($orgaid) {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        if ($orgaid == 'all') {
            $respDQL = "SELECT o,bo,p,t,g FROM AssoMakerBaseBundle:Orga o
    		JOIN o.besoinsOrgaHint bo  JOIN bo.plageHoraire p JOIN p.tache t JOIN t.groupeTache g  WHERE t.statut >=0
    		ORDER BY o.nom, p.debut ";
        } else {

            $respDQL = "SELECT o,bo,p,t,g FROM AssoMakerBaseBundle:Orga o
    		JOIN o.besoinsOrgaHint bo  JOIN bo.plageHoraire p JOIN p.tache t JOIN t.groupeTache g  WHERE t.statut >=0 AND o.id = $orgaid
    		ORDER BY o.nom, p.debut ";
        }


        $respResult = $em
                ->createQuery($respDQL)
                ->getArrayResult();


//     	var_dump($respResult);

        return array('respResult' => $respResult);
    }

    /**
     * Rapport besoinsOrga
     *
     * @Route("/besoinsorga/{plageId}/{showBonusOrgas}", defaults={"plageId"=0,"showBonusOrgas"=false}, name="analyse_besoinsorga")
     * @Template()
     */
    public function besoinsOrgaAction($plageId, $showBonusOrgas) {

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');



        $plages = json_decode($config->getValue('manifestation_plages'), TRUE);
        $plage = $plages[$plageId];
        $debutPlage = new \DateTime($plage['debut']);
        $finPlage = new \DateTime($plage['fin']);


        $result = array();
        $id = 0;
        $debut = $debutPlage;
        $fin = clone $debutPlage;
        $fin->add(new \DateInterval('PT' . (900) . 'S'));
        $days = array();

        while ($debut < $finPlage) {


            $t = $em
                    ->createQuery("SELECT count(c) FROM AssoMakerPHPMBundle:Creneau c JOIN c.plageHoraire p JOIN p.tache t WHERE c.debut < :fin AND c.fin > :debut AND t.statut >= 2")
                    ->setParameter('debut', $debut->format('Y-m-d H:i:s'))
                    ->setParameter('fin', $fin->format('Y-m-d H:i:s'))
                    ->getSingleScalarResult();

            if ($showBonusOrgas || $t != 0) {
                $o = $em
                        ->createQuery("SELECT count(di) FROM AssoMakerPHPMBundle:DisponibiliteInscription di JOIN di.orgas o WHERE di.debut < :fin AND di.fin > :debut")
                        ->setParameter('debut', $debut->format('Y-m-d H:i:s'))
                        ->setParameter('fin', $fin->format('Y-m-d H:i:s'))
                        ->getSingleScalarResult();
            } else {
                $o = false;
            }


            if ($t != 0) {
                $a = $em
                        ->createQuery("SELECT count(c) FROM AssoMakerPHPMBundle:Creneau c JOIN c.disponibilite d WHERE c.debut < :fin AND c.fin > :debut")
                        ->setParameter('debut', $debut->format('Y-m-d H:i:s'))
                        ->setParameter('fin', $fin->format('Y-m-d H:i:s'))
                        ->getSingleScalarResult();
            } else {
                $a = false;
            }


            if ($o == 0) {
                $color = "white";
                $data = "";
            } else {
                if ($t == 0) {
                    $data = "+$o";
                    $color = "#CCE0FF";
                } else {
                    if ($o > $t) {
                        if ($a == $t) {
                            $r = 50;
                            $g = 128;
                            $b = 255;
                        } else {
                            $g = 255;
                            $r = $b = 128;
                        }
                    } elseif ($o == $t) {
                        $g = 255;
                        $r = $b = 200;
                    } elseif ($o / $t < .75) {
                        $r = 255;
                        $g = $b = 0;
                    } else {
                        $r = 255;
                        $g = $b = round(192 * ($o / $t));
                    }
                    $color = "rgb($r,$g,$b)";
                    $data = "$o/$t ($a)";
                }


                if ($a === false) {

                }
            }

            $row = array('debut' => $debut->format('Y-m-d H:i:s'), 'fin' => $fin->format('Y-m-d H:i:s'), 'data' => $data, 'color' => $color);

            $result[$debut->format('H:i') . ' - ' . $fin->format('H:i')][$debut->format('d')] = $row;

            $id++;
            $days[$debut->format('d')] = $debut->format('l d F');
            $debut->add(new \DateInterval('PT' . (900) . 'S'));
            $fin->add(new \DateInterval('PT' . (900) . 'S'));
        }

        return array('result' => $result,
            'days' => $days,
            'plageId' => $plageId,
            'debutPlage' => $debutPlage,
            'finPlage' => $finPlage,
            'plages' => $plages,
            'showBonusOrgas' => $showBonusOrgas
        );
    }

    /**
     * Rapport tâches
     *
     * @Route("/taches/{groupeid}", defaults={"groupeid"="all"}, name="analyse_taches")
     * @Template()
     */
    public function tachesAction($groupeid) {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        if ($groupeid == 'all') {
            $tacheDQL = "SELECT g,t,p,c,d,o FROM AssoMakerPHPMBundle:GroupeTache g JOIN g.taches t
    		JOIN t.plagesHoraire p JOIN p.creneaux c LEFT JOIN c.disponibilite d LEFT JOIN d.orga o
    		 ORDER BY g.id ";
            $tacheResult = $em->createQuery($tacheDQL)->getArrayResult();
            return array('tacheResult' => $tacheResult);
        } else {
            $tacheDQL = "SELECT g,t,p,c,d,o FROM AssoMakerPHPMBundle:GroupeTache g JOIN g.taches t
    		JOIN t.plagesHoraire p JOIN p.creneaux c LEFT OUTER JOIN c.disponibilite d JOIN d.orga o
    		WHERE g.id = :groupeId ORDER BY g.id ";
            $tacheResult = $em->createQuery($tacheDQL)->setParameter('groupeId', $groupeid)->getArrayResult();
            $groupe = $em->getRepository('AssoMakerPHPMBundle:GroupeTache')->find($groupeid);

            if (!$groupe) {
                throw $this->createNotFoundException('GroupeTache inconnu.');
            }
            return array('tacheResult' => $tacheResult, 'groupe' => $groupe);
        }
    }

    /**
     * Besoins Orga v2
     *
     * @Route("/besoinsorga2/{plageId}/{confianceId}", defaults={"plageId"=0,"confianceId"="all"}, name="analyse_besoinsorga2")
     * @Template()
     */
    public function besoinsOrga2Action($plageId, $confianceId) {

        ini_set("memory_limit", "1024M");

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');



        $plages = json_decode($config->getValue('manifestation_plages'), TRUE);
        $plage = $plages[$plageId];
        $debutPlage = new \DateTime($plage['debut']);
        $finPlage = new \DateTime($plage['fin']);
        $debutPlage = $debutPlage->gettimestamp();
        $finPlage = $finPlage->gettimestamp();

        $debut = $debutPlage;
        $fin = $debutPlage + 900;
        $tabHoraires = array();

        while ($debut < $finPlage) {

            $debut+=900;
            $fin+=900;
            $tabHoraires[$debut] = 0;
        }

        if ($confianceId == 'all') {

            $taches = $em
                    ->createQuery("SELECT t,p,c FROM AssoMakerPHPMBundle:Tache t JOIN t.plagesHoraire p JOIN p.creneaux c ORDER BY c.debut")
                    ->getResult();
            var_dump(2);
        } else {

            $taches = $em
                    ->createQuery("SELECT t,p,c,e,ec,g,ge,r FROM AssoMakerPHPMBundle:Tache t JOIN t.plagesHoraire p JOIN p.creneaux c JOIN c.equipeHint e JOIN e.confiance ec JOIN t.responsable r JOIN t.groupeTache g JOIN g.equipe ge WHERE ec.id= :ecid ORDER BY c.debut")
                    ->setParameter("ecid", $confianceId)
                    ->getResult();
        }

        $r = array();
        $listeTaches = array();

        foreach ($taches as $tache) {
            $r[$tache->getId()] = $tabHoraires;
            $listeTaches[$tache->getId()] = $tache;
            foreach ($tache->getPlagesHoraire() as $ph) {
                print "\n";
                foreach ($ph->getCreneaux() as $c) {
                    $debutCreneau = $c->getDebut()->getTimestamp();
                    $finCreneau = $c->getFin()->getTimestamp();
                    foreach ($tabHoraires as $key => $total) {
                        if (($key >= $debutCreneau) && ($key < $finCreneau)) {
                            $r[$tache->getId()][$key]+=1;
                        }
                    }
                }
            }
        }


        return array("horaires" => $tabHoraires,
            "data" => $r,
            "taches" => $listeTaches
        );
    }

}
