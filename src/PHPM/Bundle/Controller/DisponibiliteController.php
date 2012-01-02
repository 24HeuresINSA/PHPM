<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Disponibilite;
use PHPM\Bundle\Form\DisponibiliteType;

/**
 * Disponibilite controller.
 *
 * @Route("/disponibilite")
 */
class DisponibiliteController extends Controller
{
    /**
     * Lists all Disponibilite entities.
     *
     * @Route("/", name="disponibilite")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Disponibilite')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Disponibilite entity.
     *
     * @Route("/{id}/show", name="disponibilite_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Disponibilite')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Disponibilite entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Disponibilite entity.
     *
     * @Route("/new", name="disponibilite_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Disponibilite();
        $form   = $this->createForm(new DisponibiliteType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Disponibilite entity.
     *
     * @Route("/create", name="disponibilite_create")
     * @Method("post")
     * @Template("PHPMBundle:Disponibilite:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Disponibilite();
        $request = $this->getRequest();
        $form    = $this->createForm(new DisponibiliteType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('disponibilite_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Disponibilite entity.
     *
     * @Route("/{id}/edit", name="disponibilite_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Disponibilite')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Disponibilite entity.');
        }

        $editForm = $this->createForm(new DisponibiliteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Disponibilite entity.
     *
     * @Route("/{id}/update", name="disponibilite_update")
     * @Method("post")
     * @Template("PHPMBundle:Disponibilite:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Disponibilite')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Disponibilite entity.');
        }

        $editForm   = $this->createForm(new DisponibiliteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('disponibilite_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Disponibilite entity.
     *
     * @Route("/{id}/delete", name="disponibilite_delete")
     * 
     */
    public function deleteAction($id)
    {
        
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Disponibilite')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Disponibilite entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('disponibilite'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	
	/**
     * Displays a form to create a new Disponibilite entity, already assigned to an Orga.
     *
     * @Route("/neworga/{orgaid}", name="disponibilite_orga_new")
     * @Template("PHPMBundle:Disponibilite:new.html.twig")
     */
    public function newOrgaAction($orgaid)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $orga = $em->getRepository('PHPMBundle:Orga')->find($orgaid);	
			
        $entity = new Disponibilite();
		$entity->setOrga($orga);
		$dispoform = new DisponibiliteType($orga);
		$dispoform->disableOrga();
        $form   = $this->createForm($dispoform, $entity );

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }
	
	
}
