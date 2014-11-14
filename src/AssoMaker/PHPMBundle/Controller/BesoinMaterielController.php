<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\BesoinMateriel;
use AssoMaker\PHPMBundle\Form\BesoinMaterielType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * BesoinMateriel controller.
 *
 * @Route("/besoinmateriel")
 */
class BesoinMaterielController extends Controller {

    /**
     * Lists all BesoinMateriel entities.
     *
     * @Route("/", name="besoinmateriel")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AssoMakerPHPMBundle:BesoinMateriel')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a BesoinMateriel entity.
     *
     * @Route("/{id}/show", name="besoinmateriel_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:BesoinMateriel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinMateriel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array('entity' => $entity);
    }

    /**
     * Displays a form to create a new BesoinMateriel entity.
     *
     * @Route("/new", name="besoinmateriel_new")
     * @Template()
     */
    public function newAction() {
        $entity = new BesoinMateriel();
        $form = $this->createForm(new BesoinMaterielType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new BesoinMateriel entity.
     *
     * @Route("/create", name="besoinmateriel_create")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:BesoinMateriel:new.html.twig")
     */
    public function createAction() {
        $entity = new BesoinMateriel();
        $request = $this->getRequest();
        $form = $this->createForm(new BesoinMaterielType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('besoinmateriel_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing BesoinMateriel entity.
     *
     * @Route("/{id}/edit", name="besoinmateriel_edit")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:BesoinMateriel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinMateriel entity.');
        }

        $editForm = $this->createForm(new BesoinMaterielType(), $entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     * Edits an existing BesoinMateriel entity.
     *
     * @Route("/{id}/update", name="besoinmateriel_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:BesoinMateriel:edit.html.twig")
     */
    public function updateAction($id) {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:BesoinMateriel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinMateriel entity.');
        }

        $editForm = $this->createForm(new BesoinMaterielType(), $entity);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('besoinmateriel_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
        );
    }

    /**
     *
     *
     * @Route("/changeCom", name="phpm_besoinmateriel_changecom")
     * @Secure("ROLE_LOG")
     * @Method("post")
     *
     */
    public function changeComAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();


        $data = json_decode($request->getContent(), true);

        $id = $data['id'];

        $entity = $em->getRepository('AssoMakerPHPMBundle:BesoinMateriel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinMateriel entity.');
        }

        $com = $data['com'];

        $entity->setCommentaireLog($com);

        $em->persist($entity);
        $em->flush();


        return new Response();
    }

    /**
     * Deletes a BesoinMateriel entity.
     *
     * @Route("/{id}/delete", name="besoinmateriel_delete")
     * @Method("post")
     */
    public function deleteAction($id) {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AssoMakerPHPMBundle:BesoinMateriel')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BesoinMateriel entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('besoinmateriel'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
