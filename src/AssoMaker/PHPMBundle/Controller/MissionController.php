<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Mission;
use AssoMaker\PHPMBundle\Form\MissionType;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Mission controller.
 *
 * @Route("/mission")
 */
class MissionController extends Controller
{
   

    /**
     * Displays a form to create a new Mission entity.
     *
     * @Route("/new", name="mission_new")
     * @Template()
     */
    public function newAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $entity = new Mission();
        $form   = $this->createForm(new MissionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Mission entity.
     *
     * @Route("/create", name="mission_create")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:Mission:new.html.twig")
     */
    public function createAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $entity  = new Mission();
        $request = $this->getRequest();
        $form    = $this->createForm(new MissionType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mission', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Mission entity.
     *
     * @Route("/{id}/edit", name="mission_edit")
     * @Template()
     */
    public function editAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Mission')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mission entity.');
        }

        $editForm = $this->createForm(new MissionType(), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing Mission entity.
     *
     * @Route("/{id}/update", name="mission_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:Mission:edit.html.twig")
     */
    public function updateAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Mission')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mission entity.');
        }

        $editForm   = $this->createForm(new MissionType(), $entity);
        

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mission', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Mission entity.
     *
     * @Route("/{id}/delete", name="mission_delete")
     */
    public function deleteAction($id)
    {

    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
       	$em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AssoMakerPHPMBundle:Mission')->find($id);

        if (!$entity) {
             throw $this->createNotFoundException('Unable to find Mission entity.');
        }

        $em->remove($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('mission'));
        

        
    }

   
}
