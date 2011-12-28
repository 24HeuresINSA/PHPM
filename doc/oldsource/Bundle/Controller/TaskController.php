<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Task;
use PHPM\Bundle\Entity\Timeslot;
use PHPM\Bundle\Entity\Orga;
use PHPM\Bundle\Form\TaskType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Task controller.
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * Lists all Task entities.
     *
     * @Route("/", name="task")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Task')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Task entity.
     *
     * @Route("/{id}/show", name="task_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Task')->find($id);
        

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @Route("/new", name="task_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Task();
        $form   = $this->createForm(new TaskType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Task entity.
     *
     * @Route("/create", name="task_create")
     * @Method("post")
     * @Template("PHPMBundle:Task:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Task();
        $request = $this->getRequest();
        $form    = $this->createForm(new TaskType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('task_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createForm(new TaskType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Task entity.
     *
     * @Route("/{id}/update", name="task_update")
     * @Method("post")
     * @Template("PHPMBundle:Task:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm   = $this->createForm(new TaskType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('task_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("/{id}/delete", name="task_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Task')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Task entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('task'));
    }
    
    /**
     * Generates Timeslots
     *
     * @Route("/{id}/gentimeslots", name="task_gentimeslots")
     * 
     */
    public function gentimeslotsAction($id)
    {
    $result='ok';
    
        $em = $this->getDoctrine()->getEntityManager();

        $task = $em->getRepository('PHPMBundle:Task')->find($id);
        $orga = $em->getRepository('PHPMBundle:Orga')->find(1);
        
        for ($i=0; $i<=11*3; $i++){
            $orig = strtotime('2011-01-01');
            $hd= new \DateTime('@'.($orig + 2*3600*$i)) ;
            $hf= new \DateTime('@'.($orig + 2*3600*($i+1)));
            
            
    $timeslot = new Timeslot();
    
    $timeslot->setTask($task);
    $timeslot->setBegintime($hd);
    $timeslot->setEndtime($hf);
    //$timeslot->setOrga($orga);
    
    
    $em->persist($timeslot);
    $em->flush();
    $result.='ok';
            
            
        }
        
        
        
        
        
        
        
        
        
        $result='ok';
       return new Response($result);
    }
    
    
    /**
    * Shows the timeslots of a task
    *
    * @Route("/{id}/planning", name="task_planning")
    * @Template()
    */
    public function planningAction($id)
    {
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	 
    
    
    	return array(
                'entity' => $em->getRepository('PHPMBundle:Task')->find($id)
    	 
    	);
    }
    
    
    
    
    
    
    
    
    
    
    
    

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
    
    
    
    
    
    
}
