<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\BesoinMateriel;
use PHPM\Bundle\Form\BesoinMaterielType;

/**
 * BesoinMateriel controller.
 *
 * @Route("/besoinmateriel")
 */
class BesoinMaterielController extends Controller
{
    /**
     * Lists all BesoinMateriel entities.
     *
     * @Route("/", name="besoinmateriel")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:BesoinMateriel')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a BesoinMateriel entity.
     *
     * @Route("/{id}/show", name="besoinmateriel_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:BesoinMateriel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinMateriel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array('entity'      => $entity);
    }

    /**
     * Displays a form to create a new BesoinMateriel entity.
     *
     * @Route("/new", name="besoinmateriel_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new BesoinMateriel();
        $form   = $this->createForm(new BesoinMaterielType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new BesoinMateriel entity.
     *
     * @Route("/create", name="besoinmateriel_create")
     * @Method("post")
     * @Template("PHPMBundle:BesoinMateriel:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new BesoinMateriel();
        $request = $this->getRequest();
        $form    = $this->createForm(new BesoinMaterielType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('besoinmateriel_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing BesoinMateriel entity.
     *
     * @Route("/{id}/edit", name="besoinmateriel_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:BesoinMateriel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinMateriel entity.');
        }

        $editForm = $this->createForm(new BesoinMaterielType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing BesoinMateriel entity.
     *
     * @Route("/{id}/update", name="besoinmateriel_update")
     * @Method("post")
     * @Template("PHPMBundle:BesoinMateriel:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:BesoinMateriel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinMateriel entity.');
        }

        $editForm   = $this->createForm(new BesoinMaterielType(), $entity);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('besoinmateriel_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a BesoinMateriel entity.
     *
     * @Route("/{id}/delete", name="besoinmateriel_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:BesoinMateriel')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BesoinMateriel entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('besoinmateriel'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
