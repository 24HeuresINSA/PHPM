<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\DisponibiliteInscription;
use PHPM\Bundle\Form\DisponibiliteInscriptionType;

/**
 * DisponibiliteInscription controller.
 *
 * @Route("/disponibiliteinscription")
 */
class DisponibiliteInscriptionController extends Controller
{
    /**
     * Lists all DisponibiliteInscription entities.
     *
     * @Route("/", name="disponibiliteinscription")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:DisponibiliteInscription')->findAllWithOrgacount();

        
        
        return array('entities' => $entities);
    }

    /**
     * Finds and displays a DisponibiliteInscription entity.
     *
     * @Route("/{id}/show", name="disponibiliteinscription_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:DisponibiliteInscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new DisponibiliteInscription entity.
     *
     * @Route("/new", name="disponibiliteinscription_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new DisponibiliteInscription();
        $form   = $this->createForm(new DisponibiliteInscriptionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new DisponibiliteInscription entity.
     *
     * @Route("/create", name="disponibiliteinscription_create")
     * @Method("post")
     * @Template("PHPMBundle:DisponibiliteInscription:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new DisponibiliteInscription();
        $request = $this->getRequest();
        $form    = $this->createForm(new DisponibiliteInscriptionType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('disponibiliteinscription_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing DisponibiliteInscription entity.
     *
     * @Route("/{id}/edit", name="disponibiliteinscription_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:DisponibiliteInscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
        }

        $editForm = $this->createForm(new DisponibiliteInscriptionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing DisponibiliteInscription entity.
     *
     * @Route("/{id}/update", name="disponibiliteinscription_update")
     * @Method("post")
     * @Template("PHPMBundle:DisponibiliteInscription:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:DisponibiliteInscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
        }

        $editForm   = $this->createForm(new DisponibiliteInscriptionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('disponibiliteinscription_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a DisponibiliteInscription entity.
     *
     * @Route("/{id}/delete", name="disponibiliteinscription_delete")
     * 
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:DisponibiliteInscription')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('disponibiliteinscription'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
