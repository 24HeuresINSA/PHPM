<?php

namespace AssoMaker\BaseBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use JMS\SecurityExtraBundle\Annotation\Secure;
use AssoMaker\PHPMBundle\Form\InputDisposType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\BaseBundle\Entity\Orga;
use AssoMaker\PHPMBundle\Form\PrintPlanningType;

/**
 * Orga controller.
 *
 * @Route("/orga")
 */
class OrgaController extends Controller {

    /**
     *
     *
     * @Route("/affectation", name="orga_affectation")
     * @Route("/affectation/", name="orga_affectation")
     * @Secure("ROLE_HUMAIN")
     * @Template()
     */
    public function affectationAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('AssoMakerBaseBundle:Orga')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Lists all Orga entities.
     *
     * @Route("/index/{statut}/{confiance}",defaults={"statut"="1", "confiance"="all"}, name="orga")
     * @Secure("ROLE_HARD")
     * @Template()
     */
    public function indexAction($statut, $confiance) {

        $em = $this->getDoctrine()->getEntityManager();

        $confiances = $em
                ->createQuery("SELECT c FROM AssoMakerBaseBundle:Confiance c")
                ->getResult();


        $orgasDQL = "SELECT o,e,d FROM AssoMakerBaseBundle:Orga o JOIN o.equipe e JOIN e.confiance c LEFT JOIN o.disponibilites d WHERE o.statut = $statut ";

        if ($confiance != 'all') {
            $orgasDQL .= " AND c.id = $confiance";
        }
        $orgasDQL.="ORDER BY o.lastActivity DESC, d.debut";

        $entities = $em
                ->createQuery($orgasDQL)
                ->getResult();


        return array('confiances' => $confiances,
            'entities' => $entities);
    }

    /**
     * Trombi
     *
     * @Route("/trombi", name="orga_trombi")
     * @Template()
     * @Secure("ROLE_ORGA")
     */
    public function trombiAction() {

        $em = $this->getDoctrine()->getEntityManager();

        $orgasDQL = "SELECT e,o FROM AssoMakerBaseBundle:Equipe e JOIN e.orgas o WHERE e.showOnTrombi = 1";

        $entities = $em
                ->createQuery($orgasDQL)
                ->getResult();

        return array('entities' => $entities);
    }

    /**
     * Génère la signature de Mail pour un orga hard
     * @Route("/signature", name="orga_signature")
     * @Secure("ROLE_HARD")
     * @Template()
     */
    public function signatureAction() {

        $user = $this->get('security.context')->getToken()->getUser();

        return array(
            'orga' => $user
        );
    }

    /**
     * Edits an existing Orga entity.
     *
     * @Route("/{id}/edit", name="orga_edit")
     * @Template("AssoMakerBaseBundle:Orga:edit.html.twig")
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AssoMakerBaseBundle:Orga')->find($id);
        $request = $this->getRequest();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN') && $user = $this->get('security.context')->getToken()->getUser() != $entity) {
            throw new AccessDeniedException("Vous n'êtes pas administrateur");
        }
        $editForm = $this->createForm($this->get('form.type.orga'), $entity, array("confianceCode" => $entity->getEquipe()->getConfiance()->getCode()));

        if ($this->get('request')->getMethod() == 'POST') {

            $request = $this->getRequest();
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {

                $param = $request->request->all();
                if ($param['action'] == 'delPermis') {
                    $entity->setFichierPermisSet(false);
                }

                $entity->uploadProfilePicture();
                $entity->uploadFichierPermis();
                $entity->updateRoles();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('base_accueil'));
            }
        }


        return array(
            'entity' => $entity,
            'form' => $editForm->createView()
        );
    }

    /**
     * Deletes a Orga entity.
     *
     * @Route("/{id}/delete", name="orga_delete")
     * @Secure("ROLE_HUMAIN")
     *
     */
    public function deleteAction($id) {

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AssoMakerBaseBundle:Orga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * Change le statut d'un orga
     *
     * @Route("/{id}/changestatut.{_format}/{statut}/", defaults={"_format"="html"}, requirements={"_format"="html|json"}, name="orga_change_statut")
     * @Secure("ROLE_HUMAIN")
     *
     */
    public function changeStatutAction($id, $statut) {


        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AssoMakerBaseBundle:Orga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        $entity->setStatut($statut);
        $em->flush();

        $format = $this->get('request')->getRequestFormat();

        if ($format == 'html') {
            return $this->redirect($this->getRequest()->headers->get('referer'));
        }

        if ($format == 'json') {
            $response = new Response();
            $response->setContent(json_encode("OK"));
            return $response;
        }
    }

    /**
     * @param $orgaid int           L'ID de l'orga en question
     * @param $em     EntityManager L'entity Manger pour charger les données
     * @param $debut  \DateTime     Le début du planning
     * @param $fin    \DateTime     La fin du planning
     * @return array
     */
    public function getPlanningDataForOrga($orgaid, $em, $debut, $fin)
    {
// On prend le planning de l'orga
        $orga = $em->getRepository('AssoMakerBaseBundle:Orga')->getPlanning($orgaid, 'all', $debut, $fin)[0];

        // Si il est resp on cherche les infos de ses taches
        $tachesResp = $em->createQueryBuilder()->select('t')->from('AssoMakerPHPMBundle:Tache', 't')->where('t.responsable = ?1')->andWhere('t.statut>=2')->getQuery()->setParameter(1, $orgaid)->getArrayResult();
        $max = count($tachesResp);
        $request = "SELECT o0_.id AS oid, o0_.nom AS nom, o0_.prenom AS prenom, o0_.telephone AS telephone, c1_.debut AS debut, c1_.fin AS fin
FROM Creneau c1_
INNER JOIN Disponibilite d2_ ON ( d2_.id = c1_.disponibilite_id )
INNER JOIN PlageHoraire p3_ ON ( c1_.plageHoraire_id = p3_.id )
INNER JOIN Tache t4_ ON ( t4_.id = p3_.tache_id )
INNER JOIN Orga o0_ ON ( o0_.id = d2_.orga_id )
WHERE t4_.id = ? ORDER BY debut";
        for ($i = 0; $i < $max; $i++) {
            $result = $em->getConnection()->fetchAll($request, array('0' => $tachesResp[$i]['id']));
            $tachesResp[$i]["creneaux"] = $result;
        }
        return array($orga, $tachesResp);
    }

    private function createDeleteForm($id) {

        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Exporte plusieurs ou tous les planning des orgas validés.
     *
     * Permet de télécharger un fichier zip des plannings.
     *
     * @Route("/print/all",name="printall")
     * @return Response
     */
    public function exportAction() {

        $config = $this->get('config.extension');

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }

        // Setup data
        $debut = new \DateTime();
        $fin = new \DateTime($config->getValue('phpm_planning_fin'));
        $em = $this->getDoctrine()->getEntityManager();
        $orgas = $em->getConnection()->fetchAll("SELECT id FROM Orga WHERE statut>=1");

        // Create the zip archive
        $zip = new \ZipArchive();
        $zipName = tempnam("/tmp", "PLN");;
        $zip->open($zipName,  \ZipArchive::CREATE);
        $pdfGenerator = $this->get('spraed.pdf.generator');

        // Generate planning for each orga (could take a long)
        foreach ($orgas as $o) {
            try{
                list($orga, $tachesResp) = $this->getPlanningDataForOrga($o['id'], $em, $debut, $fin);
                $pdf = $pdfGenerator->generatePDF($this->renderView('AssoMakerBaseBundle:Orga:printPlanning.html.twig',array('orga'=>$orga,'tachesResp'=>$tachesResp)));
            } catch(\Exception $e){
                $pdf = $pdfGenerator->generatePDF("User ".$o['id']." Erreur : ".$e->getMessage());
            }
            $zip->addFromString($o['id'].".pdf",  $pdf);
        }
        $zip->close();
        // Generate response
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($zipName));
        $response->headers->set('Content-Disposition', 'attachment; filename="planning.zip";');
        $response->headers->set('Content-length', filesize($zipName));

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(readfile($zipName));
        return $response;
    }

    /**
     * Inscription Hard
     *
     * @Route("/{id}/inputdispos", name="orga_inputdispos")
     * @Template
     */
    public function inputDisposAction($id) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $orga = $em->getRepository('AssoMakerBaseBundle:Orga')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $config = $e = $this->get('config.extension');

        if (!$orga) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN') && $user != $orga) {
            throw new AccessDeniedException();
        }
        $confianceOrga = $orga->getEquipe()->getConfiance()->getValeur();

        $groupesDIresult = $this->getDoctrine()->getEntityManager()->createQuery("SELECT g FROM AssoMakerPHPMBundle:Mission g WHERE g.confianceMin <= $confianceOrga ORDER BY g.ordre")->getResult();
        $groupesDI = array();

        foreach ($groupesDIresult as $entity) {
            $groupesDI[$entity->getId()] = $entity;
        }


        $queryResult = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM AssoMakerPHPMBundle:DisponibiliteInscription d JOIN d.mission m WHERE m.confianceMin <= $confianceOrga ORDER BY d.debut")->getResult();
        $DIs = array();


        foreach ($queryResult as $entity) {

            $fmt = new \IntlDateFormatter(null, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, 'Europe/Paris', null, 'EEEE d MMMM');
            $fmt2 = new \IntlDateFormatter(null, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, 'Europe/Paris');
            $DIs[$entity->getMission()->getId()][$fmt->format($entity->getDebut()->getTimestamp())][$entity->getId()] = $entity;
        }
        $form = $this->createForm(new InputDisposType());


        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $data = $form->getData();
            $submittedDI = $data['disponibiliteInscriptionItems'];

            if ($form->isValid()) {

                $allDI = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM AssoMakerPHPMBundle:DisponibiliteInscription d")->getResult();

                $minCharisme = $config->getValue('manifestation_charisme_minimum');

                $totalCharisme = 0;

                foreach ($submittedDI as $di) {
                    $totalCharisme+=$di->getPointsCharisme();
                }
                $totalCharisme+=$orga->getCharisme();

                if ($totalCharisme < $minCharisme) {
                    return array('form' => $form->createView(),
                        'entities' => $DIs,
                        'missions' => $groupesDI,
                        'orga' => $orga,
                        'charismeInsuffisant' => true,
                        'now' => new \DateTime(),
                        'messagesCharisme' => $config->getValue('phpm_messages_charisme')
                    );
                }

                foreach ($allDI as $di) {

                    if (!$submittedDI->contains($di)) {
                        if ($orga->getDisponibilitesInscription()->contains($di) && ($this->get('security.context')->isGranted('ROLE_HUMAIN')) && ($di->getDebut() > new \DateTime())) {
                            $orga->removeDisponibiliteInscription($di);
                        }
                    }
                }

                foreach ($allDI as $di) {

                    if ($submittedDI->contains($di)) {
                        if (!$orga->getDisponibilitesInscription()->contains($di) && ($this->get('security.context')->isGranted('ROLE_HUMAIN') || ($di->getStatut() > 0)) && ($di->getDebut() > new \DateTime())) {
                            $orga->addDisponibiliteInscription($di);
                            $di->addOrga($orga);
                        }
                    }
                }

                $orga->cleanDisponibilites();

                if ($orga->getStatut() == 2) {
                    $orga->setStatut(1);
                }

                $em->flush();

                if (false === $this->get('security.context')->isGranted('ROLE_USER')) {


                    return $this->redirect($this->generateUrl('orga_thankyou'));
                }

                $form = $this->createForm(new InputDisposType());
            }
        }


        return array('form' => $form->createView(),
            'entities' => $DIs,
            'missions' => $groupesDI,
            'orga' => $orga,
            'now' => new \DateTime(),
            'messagesCharisme' => $config->getValue('phpm_messages_charisme')
        );
    }

    /**
     * Lists all Orga entities according to post criteria.
     *
     * @Route("/query.json", name="orga_query_json")
     * @Method("post")
     */
    public function queryJsonAction() {
        // fonction qui permet de selectionner une list d'orga en fonction de certains critères
        $request = $this->getRequest();

        //on recupère les paramètres passés en post
        $annee_permis = $request->request->get('annee_permis', '');
        $age = $request->request->get('age', '');
        $plage_id = $request->request->get('plage_id', '');
        $niveau_confiance = $request->request->get('confiance_id', '');
        $maxDateNaissance = '';
        $creneau_id = $request->request->get('creneau_id', '');
        $equipe_id = $request->request->get('equipe_id', '');

        if ($age !== '') {
            $maxDateNaissance = new \DateTime();
            // petite conversion pour changer l'age en date de naissance max
            $maxDateNaissance->modify('-' . $age . ' year');
            $maxDateNaissance = $maxDateNaissance->format("Y-m-d H:i:s");
        }

        $em = $this->getDoctrine()->getManager();

        // on peut avoir 2 usages assez différents, donc 2 fonctions dans le repo
        if ($creneau_id === '') {
            $entities = $em->getRepository('AssoMakerBaseBundle:Orga')->getOrgasWithCriteria($annee_permis, $maxDateNaissance, $plage_id, $niveau_confiance, $equipe_id);
        } else {
            $entities = $em->getRepository('AssoMakerBaseBundle:Orga')->getOrgasCompatibleWithCreneau($creneau_id);
        }

        $orgaArray = array();
        //création du Json de retour selon le modèle définit dans la spec (cf wiki)
        foreach ($entities as $orga) {
            $equipe = $orga[0]->getEquipe();

            $orgaArray[] = array(
                "id" => $orga[0]->getId(),
                "nom" => $orga[0]->getNom(),
                "prenom" => $orga[0]->getPrenom(),
                "surnom" => $orga[0]->getSurnom(),
                "confiance" => $equipe->getConfiance()->getId(),
                "charisme" => $orga['charisme'],
                "equipe" => $equipe->getId(),
                "dateDeNaissance" => ($orga[0]->getDateDeNaissance()!=null)?$orga[0]->getDateDeNaissance()->format('Y-m-d H:i:s'):'1900-1-1 0:0:0',
                "departement" => $orga[0]->getDepartement(),
                "statut" => $orga[0]->getStatut(),
                "commentaire" => $orga[0]->getCommentaire()
            );
        }


        $response = new Response();
        $response->setContent(json_encode($orgaArray));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Print orga planning.
     *
     * @Route("/{orgaid}/print",  name="orga_print")
     */
    public function printAction($orgaid) {
        $config = $this->get('config.extension');

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN') && $user = $this->get('security.context')->getToken()->getUser()->getId() != $orgaid) {
            throw new AccessDeniedException();
        }

        $debut = new \DateTime();
        $fin = new \DateTime($config->getValue('phpm_planning_fin'));

        $em = $this->getDoctrine()->getEntityManager();
        $pdfGenerator = $this->get('spraed.pdf.generator');
        try{
            list($orga, $tachesResp) = $this->getPlanningDataForOrga($orgaid, $em, $debut, $fin);

            $html = $this->renderView('AssoMakerBaseBundle:Orga:printPlanning.html.twig',array('orga'=>$orga,'tachesResp'=>$tachesResp));

            return new Response($pdfGenerator->generatePDF($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="out.pdf"'
                )
            );
        } catch(\Exception $e){
            return new Response($pdfGenerator->generatePDF("Erreur : ".$e->getMessage()),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="out.pdf"'
                )
            );
        }

    }

    /**
     * Print all orga summary planning.
     *
     * @Route("/print/bibleMaman",  name="orga_print_all")
     */
    public function printAllAction() {
        $config = $this->get('config.extension');

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }

        $debut = new \DateTime();
        $fin = new \DateTime($config->getValue('phpm_planning_fin'));

        $em = $this->getDoctrine()->getEntityManager();
        $pdfGenerator = $this->get('spraed.pdf.generator');
        {

            $orgas = $em->createQuery("SELECT o,d,cr,p,t,g,r FROM AssoMakerBaseBundle:Orga o JOIN o.disponibilites d JOIN d.creneaux cr JOIN
					cr.plageHoraire p JOIN p.tache t JOIN t.groupeTache g JOIN t.responsable r
					ORDER BY o.nom,d.debut,cr.debut")->execute();

            $taches = $em->createQueryBuilder()->
            select("t,g,r")->from("AssoMakerPHPMBundle:Tache","t")->
            join("t.groupeTache",'g')->join("t.responsable",'r')
                ->where("t.statut>=2")->orderBy("t.id")->getQuery()->getArrayResult();
            $max = count($taches);
            $request = "SELECT o0_.id AS oid, o0_.nom AS nom, o0_.prenom AS prenom, o0_.telephone AS telephone, c1_.debut AS debut, c1_.fin AS fin
FROM Creneau c1_
INNER JOIN Disponibilite d2_ ON ( d2_.id = c1_.disponibilite_id )
INNER JOIN PlageHoraire p3_ ON ( c1_.plageHoraire_id = p3_.id )
INNER JOIN Tache t4_ ON ( t4_.id = p3_.tache_id )
INNER JOIN Orga o0_ ON ( o0_.id = d2_.orga_id )
WHERE t4_.id = ? ORDER BY debut";
            for($i = 0; $i < $max; $i++){
                $result = $em->getConnection()->fetchAll($request,array('0'=>$taches[$i]['id']));
                $taches[$i]["creneaux"]=$result;
            }

            $html = $this->renderView('AssoMakerBaseBundle:Orga:printAllPlanning.html.twig',array('orgas'=>$orgas,'taches'=>$taches,'debut'=>$debut));

            return new Response($pdfGenerator->generatePDF($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="out.pdf"'
                )
            );
        }

    }

    /**
     * Print orga planning, custom
     *
     * @Route("/plannings", name="orga_plannings")
     * @Secure("ROLE_HUMAIN")
     *
     */
    public function planningsAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');


        $request = $this->get('request');
        $param = $request->request->all();
        $action = $param['action'];

        $data = $request->get('phpm_bundle_printplanningtype');

        $pDebut = $data['debut'];
        $pFin = $data['fin'];
        $equipeid = $data['equipe'];
        $orgaid = $data['orga'];


        $orgai = $em->getRepository('AssoMakerBaseBundle:Orga')->find($orgaid);

        $equipei = $em->getRepository('AssoMakerBaseBundle:Orga')->find($equipeid);




        $debut = new \DateTime();
        $fin = new \DateTime($config->getValue('phpm_planning_fin'));


        if ($pDebut != false) {
            $debut = new \DateTime($pDebut);
        }
        if ($pFin != false) {
            $fin = new \DateTime($pFin);
        }


        $em = $this->getDoctrine()->getEntityManager();
        $orgas = $em->getRepository('AssoMakerBaseBundle:Orga')->getPlanning($orgaid, $equipeid, $debut, $fin);
        foreach ($orgas as &$orga) {

            if ($action == 'mail') {

                $timeFormatter = new \IntlDateFormatter(null, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, 'Europe/Paris', null, 'EEEE d MMMM HH:mm');
                $subject = 'Planning Orga ' . $timeFormatter->format($debut) . ' - ' . $timeFormatter->format($fin);

                $message = \Swift_Message::newInstance()
                        ->setSubject($subject)
                        ->setFrom(array('orga@24heures.org' => 'Orga 24H INSA'))
                        ->setReplyTo('orga@24heures.org')
                        ->setTo($orga['email'])
                        ->setBody($this->renderView('AssoMakerBaseBundle:Orga:emailPlanning.html.twig', array('orga' => $orga)), 'text/html')
                ;
                $this->get('mailer')->send($message);
            }
        }






        if ($action == 'show' || $action == 'mail') {
            $printPlanningForm = $this->createForm(new PrintPlanningType(), array('debut' => $debut, 'fin' => $fin, 'orga' => $orgai, 'equipe' => $equipei));
            return $this->render('AssoMakerBaseBundle:Orga:showPlanning.html.twig', array('orgas' => $orgas, 'debut' => $debut, 'fin' => $fin, 'printPlanningForm' => $printPlanningForm->createView()));
        }





        return $this->render('AssoMakerBaseBundle:Orga:printPlanning.html.twig', array('orgas' => $orgas, 'debut' => $debut, 'fin' => $fin));
    }

    /**
     * iCal orga planning.
     *
     * @Route("/export/{orgaid}/planning.ics",  name="orga_ical")
     *
     */
    public function iCalAction($orgaid) {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');

        $debut = new \DateTime();
        $fin = new \DateTime($config->getValue('phpm_planning_fin'));

        $em = $this->getDoctrine()->getEntityManager();
        $orgas = $em->getRepository('AssoMakerBaseBundle:Orga')->getPlanning($orgaid, null, $debut, $fin);


        $content = $this->renderView('AssoMakerBaseBundle:Orga:planning.ics.twig', array('orga' => $orgas[0], 'debut' => $debut, 'fin' => $fin));
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/calendar');

        return $response;
    }

    /**
     * Charisme .
     *
     * @Route("/charisme", name="orga_charisme")
     * @Secure("ROLE_VISITOR")
     * @Template()
     */
    public function charismeAction() {


        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $stats = $em->getRepository('AssoMakerBaseBundle:Orga')->getStats($user);

        $data = $em->getRepository('AssoMakerBaseBundle:Orga')->findAll();

        $departs = $em
                ->createQuery("SELECT o.departement, sum(d.pointsCharisme) as pc FROM AssoMakerBaseBundle:Orga o LEFT JOIN o.disponibilitesInscription d  WHERE o.statut >=0 GROUP BY o.departement ORDER BY pc DESC  ")
                ->getResult();


        $orgas = $em
                ->createQuery("SELECT o, sum(d.pointsCharisme) as pc FROM AssoMakerBaseBundle:Orga o JOIN o.disponibilitesInscription d  WHERE o.statut >=0 GROUP BY o.id ORDER BY pc DESC")
                ->getResult();

        $equipes = $em
                ->createQuery("SELECT e, sum(d.pointsCharisme) as pc FROM AssoMakerBaseBundle:Equipe e LEFT JOIN e.orgas o LEFT JOIN o.disponibilitesInscription d  WHERE o.statut >=0 GROUP BY e ORDER BY pc DESC  ")
                ->getResult();

        return array('stats' => $stats,
            'orgas' => $orgas,
            'departs' => $departs,
            'equipes' => $equipes);
    }

    /**
     * Thank You!
     *
     * @Route("/thankyou", name="orga_thankyou")
     * @Template()
     * @Secure("ROLE_VISITOR")
     */
    public function thankYouAction() {

        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $stats = $em->getRepository('AssoMakerBaseBundle:Orga')->getStats($user);

        $this->get('request')->getSession()->invalidate();
        $this->get("security.context")->setToken(new AnonymousToken(null, 'anon'));
        return array('orga' => $user, 'stats' => $stats);
    }

    /**
     * Auto affect Orga using orga Hints
     *
     * @Route("/{id}/affectHints", name="orga_affectHint")
     * @Template()
     */
    public function affectHintsAction($id) {

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $orga = $em->getRepository('AssoMakerBaseBundle:Orga')->find($id);
        $validator = $this->get('validator');


        if (!$orga) {
            throw $this->createNotFoundException('Unable to find Orga.');
        }

        $messages = array();

        $creneaux = $em
                ->createQuery("SELECT c  FROM AssoMakerPHPMBundle:Creneau c JOIN c.orgaHint o JOIN c.plageHoraire p JOIN p.tache t WHERE o.id = :id AND t.statut = 3")
                ->setParameter('id', $id)
                ->getResult();


        foreach ($creneaux as $creneau) {
            $cid = $creneau->getId();
            $messages[$cid] = array('creneau' => $creneau);
            if ($creneau->getDisponibilite() != null) {
                $creneau->getDisponibilite()->getOrga()->getPrenom();
                $messages[$cid]['msg'] = 'Créneau déjà affecté à ' . $creneau->getDisponibilite()->getOrga()->getPrenom()
                        . ' ' . $creneau->getDisponibilite()->getOrga()->getNom() . '.';
            } else {


                $dispos = $em->getRepository('AssoMakerPHPMBundle:Disponibilite')->getContainingDisponibilite($orga, $creneau);



                if (count($dispos) == 0) {
                    $messages[$cid]['msg'] = "L' orga n'est pas disponible sur ce créneau.";
                } else {
                    $creneau->setDisponibilite($dispos[0]);
                    $errors = $validator->validate($creneau);
                    if (count($errors) > 0) {
                        $messages[$cid]['msg'] = "Impossible d'affecter : ";
                        foreach ($errors as $error) {
                            $messages[$cid]['msg'].=$error->getMessage() . ' ';
                        }
                        $creneau->setDisponibilite(null);
                    } else {
                        $messages[$cid]['msg'] = "Affecté.";
                    }
                }
            }
        }


        return array('messages' => $messages, 'orga' => $orga);
    }

    /**
     *
     *
     * @Route("/export/tableau", name="orga_export_tableau")
     * @Template()
     */
    public function exportTableauAction() {
        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();


        $orgas = $em
                ->createQuery("SELECT o,e FROM AssoMakerBaseBundle:Orga o JOIN o.equipe e WHERE o.statut >=0")
                ->getResult();

        return array('orgas' => $orgas);
    }

    /**
     *
     *
     * @Route("/export.csv", name="orga_export_csv")
     */
    public function exportCSVAction() {

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $orgas = $em
                ->createQuery("SELECT o,e FROM AssoMakerBaseBundle:Orga o JOIN o.equipe e WHERE o.statut >=0")
                ->getArrayResult();

        $response = $this->render('AssoMakerBaseBundle:Orga:export.csv.twig', array('orgas' => $orgas));
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=Orgas.csv');
        return $response;
    }

    /**
     * Print orga badges.
     *
     * @Route("/printbadges",  name="orga_print_badges")
     * @Template()
     */
    public function printBadgesAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $orgas = $em
                ->createQuery("SELECT o,e FROM AssoMakerBaseBundle:Orga o JOIN o.equipe e JOIN e.confiance c JOIN o.disponibilites d JOIN d.creneaux cr WHERE o.statut >=0")
                ->getResult();

        $casesHoraires = array(
            1360443600,
            1360447200,
            1360450800,
            1360454400,
            1360458000,
            1360461600);

        $orgasCreneaux = array();
        foreach ($orgas as $o) {

            $orgaCreneau = array();
            foreach ($o->getDisponibilites() as $d) {
                foreach ($d->getCreneaux() as $c) {
                    foreach ($casesHoraires as $key => $debutCase) {
                        $debutCreneau = $c->getDebut()->getTimestamp();
                        $finCreneau = $c->getFin()->getTimestamp();
                        $finCase = $debutCase + 3600;
                        if (($debutCreneau <= $finCase) && ($finCreneau >= $debutCase)) {
                            $orgaCreneau[$key] = true;
                        }
                    }
                }
            }
            $orgasCreneaux[$o->getId()] = $orgaCreneau;
        }


        return array('orgas' => $orgas, 'creneaux' => $orgasCreneaux);
    }

}
