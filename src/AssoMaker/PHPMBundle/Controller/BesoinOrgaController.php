<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\BesoinOrga;
use AssoMaker\PHPMBundle\Form\BesoinOrgaType;

/**
 * BesoinOrga controller.
 *
 * @Route("/besoinorga")
 */
class BesoinOrgaController extends Controller
{
    /**
     * Lists all BesoinOrga entities.
     *
     * @Route("/", name="besoinorga")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AssoMakerPHPMBundle:BesoinOrga')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a BesoinOrga entity.
     *
     * @Route("/{id}/show", name="besoinorga_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:BesoinOrga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinOrga entity.');
        }

        

        return array(
            'entity'      => $entity,
                    );
    }

    /**
     * Displays a form to create a new BesoinOrga entity.
     *
     * @Route("/new", name="besoinorga_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new BesoinOrga();
        $form   = $this->createForm(new BesoinOrgaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new BesoinOrga entity.
     *
     * @Route("/create", name="besoinorga_create")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:BesoinOrga:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new BesoinOrga();
        $request = $this->getRequest();
        $form    = $this->createForm(new BesoinOrgaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('besoinorga_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing BesoinOrga entity.
     *
     * @Route("/{id}/edit", name="besoinorga_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:BesoinOrga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinOrga entity.');
        }

        $editForm = $this->createForm(new BesoinOrgaType(), $entity);
        

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Edits an existing BesoinOrga entity.
     *
     * @Route("/{id}/update", name="besoinorga_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:BesoinOrga:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:BesoinOrga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BesoinOrga entity.');
        }

        $editForm   = $this->createForm(new BesoinOrgaType(), $entity);
        

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('besoinorga_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Deletes a BesoinOrga entity.
     *
     * @Route("/{id}/delete", name="besoinorga_delete")
     * 
     */
    public function deleteAction($id)
    {
        
       
        
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AssoMakerPHPMBundle:BesoinOrga')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BesoinOrga entity.');
            }
            
            $tacheId=$entity->getPlageHoraire()->getTache()->getId();
            exit();
            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('tache_edit', array('id'=>$tacheId)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
