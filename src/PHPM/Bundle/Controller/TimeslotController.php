<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Timeslot;
use PHPM\Bundle\Form\TimeslotType;

/**
 * Timeslot controller.
 *
 * @Route("/timeslot")
 */
class TimeslotController extends Controller
{
    /**
     * Lists all Timeslot entities.
     *
     * @Route("/", name="timeslot")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        
        $entities = $em->getRepository('PHPMBundle:Timeslot')->findAll();

        return array('entities' => $entities);
    }
    
    

    /**
     * Finds and displays a Timeslot entity.
     *
     * @Route("/{id}/show", name="timeslot_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Timeslot')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Timeslot entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Timeslot entity.
     *
     * @Route("/new", name="timeslot_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Timeslot();
        $form   = $this->createForm(new TimeslotType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Timeslot entity.
     *
     * @Route("/create", name="timeslot_create")
     * @Method("post")
     * @Template("PHPMBundle:Timeslot:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Timeslot();
        $request = $this->getRequest();
        $form    = $this->createForm(new TimeslotType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('timeslot_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Timeslot entity.
     *
     * @Route("/{id}/edit", name="timeslot_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Timeslot')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Timeslot entity.');
        }

        $editForm = $this->createForm(new TimeslotType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Timeslot entity.
     *
     * @Route("/{id}/update", name="timeslot_update")
     * @Method("post")
     * @Template("PHPMBundle:Timeslot:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Timeslot')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Timeslot entity.');
        }

        $editForm   = $this->createForm(new TimeslotType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('timeslot_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Timeslot entity.
     *
     * @Route("/{id}/delete", name="timeslot_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Timeslot')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Timeslot entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('timeslot'));
    }
    
    /**
    * Affect the timeslot to an orga
    *
    * @Route("/{id}/affect/{orgaid}", name="timeslot_affect")
    * 
    */
    public function affectAction($id,$orgaid)
    {
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$orga = $em->getRepository('PHPMBundle:Orga')->find($orgaid);
    	
    	if (!$orga) {
    		throw $this->createNotFoundException('Unable to find Orga entity.');
    	}
     	$timeslot = $em->getRepository('PHPMBundle:Timeslot')->find($id);

        if (!$timeslot) {
            throw $this->createNotFoundException('Unable to find Timeslot entity.');
        }
        
       // exit(var_dump($timeslot->setOrga($orga)));
        $timeslot->setOrga($orga);
        $em->persist($timeslot);
        $em->flush();
    	
    	return $this->redirect($this->generateUrl('orga_planning',array( 'id'=> $orgaid )));
    }
    
    /**
    * Desaffect the timeslot to an orga
    *
    * @Route("/{id}/desaffect/{orgaid}", name="timeslot_desaffect")
    *
    */
    public function deaffectAction($id,$orgaid)
    {
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$orga = $em->getRepository('PHPMBundle:Orga')->find(1);
    	 
    	if (!$orga) {
    		throw $this->createNotFoundException('Unable to find Orga entity.');
    	}
    	$timeslot = $em->getRepository('PHPMBundle:Timeslot')->find($id);
    
    	if (!$timeslot) {
    		throw $this->createNotFoundException('Unable to find Timeslot entity.');
    	}
    
    	// exit(var_dump($timeslot->setOrga($orga)));
    	$timeslot->setOrga($orga);
    	$em->persist($timeslot);
    	$em->flush();
    	 
    	return $this->redirect($this->generateUrl('orga_planning',array( 'id'=> $orgaid )));
    }
    

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
