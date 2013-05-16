<?php

namespace AssoMaker\BaseBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use AssoMaker\BaseBundle\Entity\Orga;
use AssoMaker\PHPMBundle\Entity\Config;

/**
 * OrgaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrgaRepository extends EntityRepository {

    public function findAllUsersExcept($userId) {

        return $this->createQueryBuilder('o')
                        ->where('o.privileges >= 1')
                        ->where('o.id != :userId')
                        ->setParameter('userId', $userId);
        ;
    }

    public function findAllComptesPersoUsersExcept($userId) {

        return $this->createQueryBuilder('o')
                        ->join('o.equipe', 'e')
                        ->where('o.id != :userId')
                        ->andWhere('e.comptesPersoEnabled = 1')
                        ->setParameter('userId', $userId)
        ;
    }

    public function getOrgasWithCriteria($annee_permis, $maxDateNaissance, $plage_id, $niveau_confiance, $equipe_id) {
        $dql = "SELECT o, SUM(di.pointsCharisme) charisme FROM AssoMakerBaseBundle:Orga AS o JOIN o.disponibilitesInscription di JOIN o.disponibilites d JOIN o.equipe e WHERE o.statut>=1";

        // filtre sur la date de naissance
        if ($maxDateNaissance !== '') {
            $dql .= " AND o.dateDeNaissance <= '$maxDateNaissance'";
        }

        if ($plage_id !== '') {
            $pref = json_decode($this->getEntityManager()->getRepository('AssoMakerPHPMBundle:Config')->findOneByField('manifestation_plages')->getValue(), TRUE);
            $plage = $pref[$plage_id];
            $fin = $plage["fin"];
            $debut = $plage["debut"];
            // attention à ne pas oublier les deriers jours, puisque DQL cast les date en datetime à 00:00:00 !
            // utiliser un DATE_DIFF est bien plus rapide que de passer par un DateTime auquel on rajoute 1 jour
            $dql .= " AND DATE_DIFF('$fin', d.debut) >= 0 AND DATE_DIFF(d.fin, '$debut') >= 0";
        }

        // on le fait ici pour récupérer la variable $debut
        if ($annee_permis !== '') {
            if ($annee_permis == 0) {
                $dql .= " AND o.datePermis IS NOT NULL";
            } else if (is_numeric($annee_permis)) {
                if (isset($debut)) {
                    $now = new \DateTime($debut);
                } else {
                    $now = new \DateTime();
                }

                $dql .= " AND o.datePermis <= '" . $now->sub(new \DateInterval('P' . $annee_permis . 'Y'))->format('Y-m-d') . "'";
            }
        }

        if ($niveau_confiance !== '') {
            $dql .= " AND e.confiance = '$niveau_confiance'";
        }

        if ($equipe_id !== '') {
            $dql .= " AND e.id = $equipe_id ";
        }

        // on trie par nombre de points de charisme
        $dql .= "GROUP BY o.id, d.id, e.id ORDER BY charisme DESC";

        $q = $this->getEntityManager()->createQuery($dql);
        return $q->execute();
    }

    // sort la liste des orgas compatibles avec un créneau
    public function getOrgasCompatibleWithCreneau($creneau_id) {
        // on regarde successivement :
        // s'il est dispo à ce moment-là
        // s'il a le bon niveau de confiance/équipe
        // s'il n'est pas déjà affecté
        $dql = "SELECT o, SUM(di.pointsCharisme) charisme FROM AssoMakerBaseBundle:Orga AS o JOIN o.disponibilites d " .
                "JOIN o.disponibilitesInscription di JOIN o.equipe e JOIN e.confiance oc, AssoMakerPHPMBundle:Creneau AS c " .
                "LEFT OUTER JOIN c.orgaHint oh LEFT OUTER JOIN c.equipeHint eh LEFT JOIN eh.confiance ehc " .
                "WHERE c.id = '$creneau_id' AND (d.debut <= c.debut) AND (d.fin >= c.fin) AND o.statut=1 " .
                "AND ((c.orgaHint IS NOT NULL AND oh.id = o.id) OR eh.id = e.id OR oc.valeur > ehc.valeur) " .
                "AND o.id NOT IN (SELECT org.id FROM AssoMakerPHPMBundle:Creneau AS cr JOIN cr.disponibilite dis JOIN dis.orga org, " .
                "AssoMakerPHPMBundle:Creneau cre WHERE cre.id = '$creneau_id' AND (cr.debut < cre.fin) AND (cr.fin > cre.debut)) " .
                "GROUP BY o.id, d.id, e.id ORDER BY charisme DESC";

        $q = $this->getEntityManager()->createQuery($dql);
        return $q->execute();
    }

    public function getOrgasToValidate() {

        return $this->getEntityManager()
                        ->createQuery("SELECT o ,SUM(d.fin-d.debut)/3600 AS nbHeures FROM AssoMakerBaseBundle:Orga o, AssoMakerPHPMBundle:Disponibilite d WHERE (d.orga = o AND o.statut=0)")
                        ->getResult();
    }

    public function getStats(\AssoMaker\BaseBundle\Entity\Orga $orga) {


        $pcmax = $this->getEntityManager()
                ->createQuery("SELECT sum(d.pointsCharisme)FROM AssoMakerPHPMBundle:DisponibiliteInscription d")
                ->getSingleScalarResult();

        $totalpc = $this->getEntityManager()
                ->createQuery("SELECT sum(d.pointsCharisme)FROM AssoMakerBaseBundle:Orga o JOIN o.disponibilitesInscription d WHERE o.statut >=0")
                ->getSingleScalarResult();


        $DIs = $this->getEntityManager()
                ->createQuery("SELECT o  , sum(d.pointsCharisme) as pc FROM AssoMakerBaseBundle:Orga o JOIN o.disponibilitesInscription d WHERE o.statut >=0 GROUP BY o.id ORDER BY pc DESC")
                ->getResult();

        $nonValidated = $this->getEntityManager()
                ->createQuery("SELECT count(o) FROM AssoMakerBaseBundle:Orga o WHERE  o.statut =0")
                ->getSingleScalarResult();

        $rang = 1;
        $nbOrgas = count($DIs);

        foreach ($DIs as $row) {
            $orgaDI = $row[0];
            $pc = $row['pc'];
            if ($orgaDI == $orga) {
                return array('rangCharisme' => $rang, 'nbOrgas' => $nbOrgas, 'PCOrga' => $pc, 'PCTotal' => $totalpc, 'PCMax' => $pcmax, 'nonValidated' => $nonValidated)
                ;
            }
            $rang++;
        }
        return(array('rangCharisme' => -1, 'nbOrgas' => $nbOrgas, 'PCOrga' => 0, 'PCTotal' => $totalpc, 'PCMax' => $pcmax, 'nonValidated' => $nonValidated));
    }

    public function getOrgasFromRegistration() {

        return $this->getEntityManager()
                        ->createQuery("SELECT o FROM AssoMakerBaseBundle:Orga o WHERE (o.statut=0)")
                        ->getResult();
    }

    public function getNombreOrgas() {
        return $this->getEntityManager()
                        ->createQuery("SELECT COUNT (o.id) AS nbOrgas FROM AssoMakerBaseBundle:Orga o WHERE (o.statut=1)")
                        ->getResult();
    }

    public function getNombreHeureDesCreneauNonAffecte() {
        return $this->getEntityManager()
                        ->createQuery("SELECT SUM(c.fin-c.debut)/3600 AS nbHeures FROM AssoMakerPHPMBundle:Creneau c  WHERE (c.disponibilite is NULL)")
                        ->getResult();
    }

    public function getNombreHeureInscription($oid) {
        return number_format($this->getEntityManager()
                        ->createQuery("SELECT SUM(d.fin-d.debut)/3600 AS nbHeures FROM AssoMakerBaseBundle:Orga o JOIN o.disponibilitesInscription d")
                        ->getSingleScalarResult(), 1);
    }

    /*    Voir comment on peut récupérer le résultat d'une requête SQL en natif
      public function getTacheSansCreneau()
      {

      /*
      return $this->getEntityManager()
      ->createQuery("SELECT t FROM AssoMakerPHPMBundle:Tache t WHERE (t.id = (SELECT p.tache FROM AssoMakerPHPMBundle:PlageHoraire WHERE (p.id NOT IN (SELECT c.plageHoraire FROM AssoMakerPHPMBundle:Creneau))")
      ->getResult();



      $conn = $em->getConnection();

      $sql = "SELECT t FROM AssoMakerPHPMBundle:Tache t WHERE (t.id = (SELECT p.tache FROM AssoMakerPHPMBundle:PlageHoraire WHERE (p.id NOT IN (SELECT c.plageHoraire FROM AssoMakerPHPMBundle:Creneau))";

      $conn->query($sql);

      $conn->close();
      }

     */

    public function search($s) {
        return $this->getEntityManager()
                        ->createQuery("SELECT o FROM AssoMakerBaseBundle:Orga o WHERE (o.nom LIKE :s OR o.prenom LIKE :s OR o.surnom LIKE :s OR o.telephone LIKE :s OR o.email LIKE :s OR o.commentaire LIKE :s) AND o.statut != '-1'")
                        ->setParameter('s', "%" . $s . "%")
                        ->getResult();
    }

    public function findAllWithConfianceValueMin($value) {

        return $this->createQueryBuilder('o')
                        ->join('o.equipe', 'e')
                        ->join('e.confiance', 'c')
                        ->where('c.valeur >= :value')
                        ->orderBy('o.nom')
                        ->setParameter('value', $value);
    }

    public function getPlanning($orga_id = 'all', $equipe_id = 'all', \DateTime $debut, \DateTime $fin) {

        if ($orga_id == '') {
            echo 'o';
            if ($equipe_id == '') {
                echo 'e';
                $result = $this->getEntityManager()->createQuery("SELECT o,d,c,p,t,g,r,bm,m,c2,d2,o2 FROM AssoMakerBaseBundle:Orga o JOIN o.disponibilites d JOIN d.creneaux c JOIN
					c.plageHoraire p JOIN p.tache t JOIN t.groupeTache g JOIN t.responsable r  LEFT JOIN t.besoinsMateriel bm LEFT JOIN bm.materiel m
					JOIN p.creneaux c2 JOIN c2.disponibilite d2 JOIN d2.orga o2
					WHERE c.fin >= :debut  AND c.debut <=  :fin
					AND c2.debut = c.debut AND c2.fin = c.fin
					ORDER BY o.nom,d.debut, c.debut")
                        ->setParameter('debut', $debut, \Doctrine\DBAL\Types\Type::DATETIME)
                        ->setParameter('fin', $fin, \Doctrine\DBAL\Types\Type::DATETIME)
                        ->getArrayResult();
            } else {
                echo 'ne';
                $result = $this->getEntityManager()->createQuery("SELECT o,d,c,p,t,g,r,bm,m,c2,d2,o2 FROM AssoMakerBaseBundle:Orga o JOIN o.disponibilites d JOIN d.creneaux c JOIN
						c.plageHoraire p JOIN p.tache t JOIN t.groupeTache g JOIN t.responsable r  LEFT JOIN t.besoinsMateriel bm LEFT JOIN bm.materiel m
						JOIN p.creneaux c2 JOIN c2.disponibilite d2 JOIN d2.orga o2 JOIN o.equipe e
						WHERE e.id = :eid AND c.fin >= :debut AND c.debut <= :fin
						AND c2.debut = c.debut AND c2.fin = c.fin
						ORDER BY o.nom,d.debut, c.debut")
                        ->setParameter('eid', $equipe_id)
                        ->setParameter('debut', $debut, \Doctrine\DBAL\Types\Type::DATETIME)
                        ->setParameter('fin', $fin, \Doctrine\DBAL\Types\Type::DATETIME)
                        ->getArrayResult();
            }
        } else {
            $result = $this->getEntityManager()->createQuery("SELECT o,d,c,p,t,g,r,bm,m,c2,d2,o2 FROM AssoMakerBaseBundle:Orga o JOIN o.disponibilites d JOIN d.creneaux c JOIN
					c.plageHoraire p JOIN p.tache t JOIN t.groupeTache g JOIN t.responsable r  LEFT JOIN t.besoinsMateriel bm LEFT JOIN bm.materiel m
					JOIN p.creneaux c2 JOIN c2.disponibilite d2 JOIN d2.orga o2
					WHERE o.id = :oid AND c.fin >= :debut AND c.debut <= :fin
					AND c2.debut = c.debut AND c2.fin = c.fin
					ORDER BY o.nom,d.debut, c.debut")
                    ->setParameter('oid', $orga_id)
                    ->setParameter('debut', $debut, \Doctrine\DBAL\Types\Type::DATETIME)
                    ->setParameter('fin', $fin, \Doctrine\DBAL\Types\Type::DATETIME)
                    ->getArrayResult();
        }


        foreach ($result as &$orga) {
            $prevCreneau = null;
            foreach ($orga['disponibilites'] as &$disponibilite) {

                foreach ($disponibilite['creneaux'] as $id => &$creneau) {
                    if (($creneau['plageHoraire']['tache'] == $prevCreneau['plageHoraire']['tache']) && ($creneau['debut'] == $prevCreneau['fin'])) {
                        $prevCreneau['del'] = true;
                        $creneau['debut'] = $prevCreneau['debut'];
                    }
                    $prevCreneau = &$creneau;
                }
                unset($creneau);
            }
            unset($prevCreneau);
        }

        foreach ($result as $oid => &$orga) {
            foreach ($orga['disponibilites'] as $did => &$disponibilite) {
                foreach ($disponibilite['creneaux'] as $id => &$creneau) {
                    if (array_key_exists('del', $creneau)) {
                        unset($disponibilite['creneaux'][$id]);
                    }
                }
            }
        }



        return $result;
    }

//	getOrgasWithCriteriaTache numéro 2 pour gérer le tache id
    /*
      public function getOrgasWithCompatibleTache($tache_id)
      {

      $qb = $this->getEntityManager()->createQueryBuilder();
      $expr = $qb->expr();

      $andx = $expr->andx(



      //$expr->eq('t.id',$tache_id),
      $expr->neq('t.id',$tache_id),

      $expr->eq('d.orga','o'),
      $expr->neq('d.orga','0'),
      $expr->eq('co.disponibilite','d'),
      $expr->eq('ct.plageHoraire','p'),
      $expr->eq('p.tache','t')


      $expr->eq('ct.disponibilite','0'),
      $expr->lte('ct.debut','d.fin'),
      $expr->gte('ct.fin','d.debut'),

      'ct.id NOT IN (SELECT ci.id FROM AssoMakerPHPMBundle:Creneau ci
      WHERE

      ( (ci.debut < co.fin) AND (ci.fin > co.debut ) )
      OR
      (((ci.debut<p.debut)OR(ci.fin > p.fin))OR((ci.debut >= p.fin)OR(ci.fin <= p.debut)))

      )'


      );

      $qb
      ->select('o, ct')

      ->from('AssoMakerBaseBundle:Orga','o')



      ->from('AssoMakerPHPMBundle:Disponibilite', 'd')
      ->from('AssoMakerPHPMBundle:Creneau', 'co')
      ->from('AssoMakerPHPMBundle:PlageHoraire', 'p')
      ->from('AssoMakerPHPMBundle:Tache', 't')
      ->from('AssoMakerPHPMBundle:Creneau', 'ct')


      ->where($andx);

      //exit(var_dump($qb->getQuery()->getDQL()));



      return $qb->getQuery()->getResult();


      }
      // */
}

