<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Timespan;
use PHPM\Bundle\Form\TimespanType;

/**
 * Timespan controller.
 *
 * @Route("/timespan")
 */
class TimespanController extends Controller
{
    /**
     * Lists all Timespan entities.
     *
     * @Route("/", name="timespan")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Timespan')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Timespan entity.
     *
     * @Route("/{id}/show", name="timespan_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Timespan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Timespan entity.');
        }
		//exit(var_dump($em->getRepository('PHPMBundle:Timespan')));
        $hours = $em->getRepository('PHPMBundle:Timeslot')->getHours();
        var_dump($hours);
        
        
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(), 
        		);
    }

    /**
     * Displays a form to create a new Timespan entity.
     *
     * @Route("/new", name="timespan_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Timespan();
        $form   = $this->createForm(new TimespanType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Timespan entity.
     *
     * @Route("/create", name="timespan_create")
     * @Method("post")
     * @Template("PHPMBundle:Timespan:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Timespan();
        $request = $this->getRequest();
        $form    = $this->createForm(new TimespanType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('timespan_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Timespan entity.
     *
     * @Route("/{id}/edit", name="timespan_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Timespan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Timespan entity.');
        }

        $editForm = $this->createForm(new TimespanType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Timespan entity.
     *
     * @Route("/{id}/update", name="timespan_update")
     * @Method("post")
     * @Template("PHPMBundle:Timespan:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Timespan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Timespan entity.');
        }

        $editForm   = $this->createForm(new TimespanType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('timespan_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Timespan entity.
     *
     * @Route("/{id}/delete", name="timespan_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Timespan')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Timespan entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('timespan'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
