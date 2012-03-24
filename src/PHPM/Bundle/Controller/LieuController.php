<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Lieu;
use PHPM\Bundle\Form\LieuType;

/**
 * Lieu controller.
 *
 * @Route("/lieu")
 */
class LieuController extends Controller
{
    /**
     * Lists all Lieu entities.
     *
     * @Route("/", name="lieu")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Lieu')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Lieu entity.
     *
     * @Route("/{id}/show", name="lieu_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Lieu entity.
     *
     * @Route("/new", name="lieu_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Lieu();
        $form   = $this->createForm(new LieuType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Lieu entity.
     *
     * @Route("/create", name="lieu_create")
     * @Method("post")
     * @Template("PHPMBundle:Lieu:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Lieu();
        $request = $this->getRequest();
        $form    = $this->createForm(new LieuType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('config_manif'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Lieu entity.
     *
     * @Route("/{id}/edit", name="lieu_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
        }

        $editForm = $this->createForm(new LieuType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Lieu entity.
     *
     * @Route("/{id}/update", name="lieu_update")
     * @Method("post")
     * @Template("PHPMBundle:Lieu:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
        }

        $editForm   = $this->createForm(new LieuType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lieu_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Lieu entity.
     *
     * @Route("/{id}/delete", name="lieu_delete")
     * 
     */
    public function deleteAction($id)
    {
           $request = $this->getRequest();

            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Lieu')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Lieu entity.');
                
            }

            $em->remove($entity);
            $em->flush();
        
        

        return $this->redirect($this->generateUrl('config_manif'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}