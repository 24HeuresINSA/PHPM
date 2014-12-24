<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Materiel;
use AssoMaker\PHPMBundle\Form\MaterielType;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Materiel controller.
 *
 * @Route("/materiel")
 */
class MaterielController extends Controller
{
    /**
     * Lists all Materiel entities.
     *
     * @Route("/", name="materiel")
     * @Template()
     */
    public function indexAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('AssoMakerPHPMBundle:Materiel')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Materiel entity.
     *
     * @Route("/{id}/show", name="materiel_show")
     * @Template()
     */
    public function showAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
    	$em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        

        return array(
            'entity'      => $entity,
                    );
    }

    /**
     * Displays a form to create a new Materiel entity.
     *
     * @Route("/new", name="materiel_new")
     * @Template()
     */
    public function newAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $entity = new Materiel();
        $form   = $this->createForm(new MaterielType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Materiel entity.
     *
     * @Route("/create", name="materiel_create")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:Materiel:new.html.twig")
     */
    public function createAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $entity  = new Materiel();
        $request = $this->getRequest();
        $form    = $this->createForm(new MaterielType(), $entity);
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
     * Displays a form to edit an existing Materiel entity.
     *
     * @Route("/{id}/edit", name="materiel_edit")
     * @Template()
     */
    public function editAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        $editForm = $this->createForm(new MaterielType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing Materiel entity.
     *
     * @Route("/{id}/update", name="materiel_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:Materiel:edit.html.twig")
     */
    public function updateAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        $editForm   = $this->createForm(new MaterielType(), $entity);
        

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('materiel_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Materiel entity.
     *
     * @Route("/{id}/delete", name="materiel_delete")
     * 
     */
    public function deleteAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AssoMakerPHPMBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        $em->remove($entity);
        $em->flush();
        

        return $this->redirect($this->generateUrl('config'));
    }

    
}
