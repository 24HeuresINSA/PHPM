<?php

namespace AssoMaker\SponsoBundle\Controller;

use AssoMaker\SponsoBundle\Form\AvancementType;
use AssoMaker\SponsoBundle\Entity\Avancement;
use Symfony\Component\Validator\Constraints\DateTime;
use AssoMaker\SponsoBundle\Entity\Note;
use AssoMaker\SponsoBundle\Form\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Avancement controller.
 *
 * @Route("/sponso/avancement")
 */
class AvancementController extends Controller {

    /**
     * Lists all contacts  as JSON
     *
     * @Route("/index.json", name="sponso_contactsJSON")
     * @Method("get")
     */
    public function contactsDataAction(Request $request) {
        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();


        $contacts = $em->createQuery("SELECT c FROM AssoMakerSponsoBundle:Avancement a ")->getResult();



        $array = array();
        foreach ($contacts as $contact) {
            $notes = array();
            foreach ($contact->getNotes() as $note) {
                $notes[] = $note->toArray();
            }

            $array[] = array("id" => $contact->getId(),
                "nom" => $contact->getNom(),
                "telephone" => $contact->getTelephone(),
                "email" => $contact->getEmail(),
                "entreprise" => $contact->getEntreprise(),
                "poste" => $contact->getPoste(),
                "adresse" => $contact->getAdresse(),
                "notes" => $notes
            );
        }


        $response = new Response();
        $response->setContent(json_encode($array));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * new Avancement entity.
     *
     * @Route("/new/{projectId}", name="sponso_avancement_new", defaults={"projectId"=""})
     * @Template()
     */
    public function newAction($projectId) {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');

        $entity = new Avancement();
        if ($this->get('request')->getMethod() == 'GET') {
            $project = $em->getRepository('AssoMakerSponsoBundle:Projet')->find($projectId);
            $entity->setProjet($project);
            $entity->setResponsable($user);

            if (!$project) {
                throw $this->createNotFoundException('Unable to find project.');
            }
        }


        $editForm = $this->createForm(new AvancementType($admin), $entity);


        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('sponso_avancement_edit', array("id" => $entity->getId())));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView()
        );
    }

    /**
     * Edits an existing Avancement entity.
     *
     * @Route("/{id}/edit", name="sponso_avancement_edit")
     * @Template
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $entity = $em->getRepository('AssoMakerSponsoBundle:Avancement')->find($id);
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }

        $editForm = $this->createForm(new AvancementType($admin), $entity);
        $noteForm = $this->createForm(new NoteType);


        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('sponso_avancement_edit', array('id' => $entity->getId())));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'formNote' => $noteForm->createView()
        );
    }

    /**
     * AddNote
     * @Method("post")
     * @Route("/{id}/addNote", name="sponso_avancement_addNote")
     */
    public function addNoteAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $avancement = $em->getRepository('AssoMakerSponsoBundle:Avancement')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = new Note();

        $entity->setOrga($user);
        $entity->setAvancement($avancement);

        $editForm = $this->createForm(new NoteType(), $entity);

        $request = $this->getRequest();
        $editForm->handleRequest($request);
        $entity->setDate(new \DateTime());



        if ($editForm->isValid()) {
            $param = $request->request->all();
            $action = $param['action'];
            $statut = $avancement->getStatut();

            if ($action == 'valid') {

                if (($statut == 1 || $statut == 5 || $statut == 7) && (false === $this->get('security.context')->isGranted('ROLE_HUMAIN'))) {
                    throw new AccessDeniedException();
                }

                if ($statut == 10) {
                    $avancement->setStatut(0);
                } else {
                    $avancement->setStatut($statut + 1);
                }
                $messageNouveauStatut = $avancement->getMessageStatut();
                $entity->setTexte($entity->getTexte() . "<i>&rarr;$messageNouveauStatut</i>");
            }

            if ($action == 'invalid') {

                if (!($statut == 1 || $statut == 5 || $statut == 7) || (false === $this->get('security.context')->isGranted('ROLE_HUMAIN'))) {
                    throw new AccessDeniedException();
                }

                $avancement->setStatut($statut - 1);
                $messageNouveauStatut = $avancement->getMessageStatut();
                $entity->setTexte($entity->getTexte() . "<i>&rarr;$messageNouveauStatut</i>");
            }

            if ($action == 'cancel') {

                if ((false === $this->get('security.context')->isGranted('ROLE_HUMAIN'))) {
                    throw new AccessDeniedException();
                }

                $avancement->setStatut(-1);
                $messageNouveauStatut = $avancement->getMessageStatut();
                $entity->setTexte($entity->getTexte() . "<i>&rarr;Projet Annulé</i>");
            }


            $entity->uploadDossierSponso();
            $em->persist($entity);
            $em->flush();

            if ($user != $avancement->getResponsable()) {
                $message = \Swift_Message::newInstance()
                        ->setSubject(
                                'Le projet ' . $avancement->getProjet() . '/' . $avancement->getEntreprise() . ' mis à jour par ' . $entity->getOrga()->__toString() . '.')
                        ->setFrom(
                                array(
                                    $entity->getOrga()->getEmail() => $entity->getOrga()->__toString() . ' via SponsoMaker'))
                        ->setReplyTo($entity->getOrga()->getEmail())
                        ->setTo($avancement->getResponsable()->getEmail())
                        ->setBody($this
                        ->renderView(
                                'AssoMakerSponsoBundle:Avancement:emailNotification.html.twig', array('avancement' => $avancement, 'note' => $entity)), 'text/html');
                $this->get('mailer')->send($message);
            }

            return $this
                            ->redirect($this->generateUrl('sponso_projet_home'));
        } else {
            $errors = "";
            $errors .= $editForm->getErrorsAsString();
            foreach ($editForm->getChildren() as $c) {
                $errors .= $c->getErrorsAsString();
            }
            $this->get('session')->setFlash(
                    'notice', $errors
            );
            return $this->redirect($this->generateUrl('sponso_avancement_edit', array('id' => $avancement->getId())) . "#noteModal");
        }
    }

}
