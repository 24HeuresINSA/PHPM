<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\GroupeDI;
use PHPM\Bundle\Form\GroupeDIType;

/**
 * GroupeDI controller.
 *
 * @Route("/groupedi")
 */
class GroupeDIController extends Controller
{
   

    /**
     * Displays a form to create a new GroupeDI entity.
     *
     * @Route("/new", name="groupedi_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new GroupeDI();
        $form   = $this->createForm(new GroupeDIType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new GroupeDI entity.
     *
     * @Route("/create", name="groupedi_create")
     * @Method("post")
     * @Template("PHPMBundle:GroupeDI:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new GroupeDI();
        $request = $this->getRequest();
        $form    = $this->createForm(new GroupeDIType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupedi', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing GroupeDI entity.
     *
     * @Route("/{id}/edit", name="groupedi_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:GroupeDI')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupeDI entity.');
        }

        $editForm = $this->createForm(new GroupeDIType(), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing GroupeDI entity.
     *
     * @Route("/{id}/update", name="groupedi_update")
     * @Method("post")
     * @Template("PHPMBundle:GroupeDI:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:GroupeDI')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupeDI entity.');
        }

        $editForm   = $this->createForm(new GroupeDIType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupedi', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a GroupeDI entity.
     *
     * @Route("/{id}/delete", name="groupedi_delete")
     */
    public function deleteAction($id)
    {


            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:GroupeDI')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GroupeDI entity.');
            }

            $em->remove($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('groupedi'));
        

        
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
