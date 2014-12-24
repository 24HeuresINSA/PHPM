<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Lieu;
use AssoMaker\PHPMBundle\Form\LieuType;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AssoMakerPHPMBundle:Lieu')->findAll();

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
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
        }

        

        return array(
            'entity'      => $entity,
                    );
    }

    /**
     * Displays a form to create a new Lieu entity.
     *
     * @Route("/new", name="lieu_new")
     * @Template()
     */
    public function newAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
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
     * @Template("AssoMakerPHPMBundle:Lieu:new.html.twig")
     */
    public function createAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $entity  = new Lieu();
        $request = $this->getRequest();
        $form    = $this->createForm(new LieuType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('config'));
            
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
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
        }

        $editForm = $this->createForm(new LieuType(), $entity);
        

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Edits an existing Lieu entity.
     *
     * @Route("/{id}/update", name="lieu_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:Lieu:edit.html.twig")
     */
    public function updateAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
        }

        $editForm   = $this->createForm(new LieuType(), $entity);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lieu_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
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
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
    	$request = $this->getRequest();

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AssoMakerPHPMBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
            }

        $em->remove($entity);
        $em->flush();
        
        return $this->redirect($this->generateUrl('config'));
    }

}
