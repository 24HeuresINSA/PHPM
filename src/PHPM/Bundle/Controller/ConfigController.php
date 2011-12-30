<?php

namespace PHPM\Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Config;
use PHPM\Bundle\Form\ConfigType;

/**
 * Config controller.
 *
 * @Route("/config")
 */
class ConfigController extends Controller
{
    /**
     * Lists all Config entities.
     *
     * @Route("/", name="config")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Config')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Config entity.
     *
     * @Route("/{id}/show", name="config_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Config')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Config entity.
     *
     * @Route("/new", name="config_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Config();
        $form   = $this->createForm(new ConfigType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Config entity.
     *
     * @Route("/create", name="config_create")
     * @Method("post")
     * @Template("PHPMBundle:Config:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Config();
        $request = $this->getRequest();
        $form    = $this->createForm(new ConfigType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('config_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Config entity.
     *
     * @Route("/{id}/edit", name="config_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Config')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config entity.');
        }

        $editForm = $this->createForm(new ConfigType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Config entity.
     *
     * @Route("/{id}/update", name="config_update")
     * @Method("post")
     * @Template("PHPMBundle:Config:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Config')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Config entity.');
        }

        $editForm   = $this->createForm(new ConfigType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('config_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Config entity.
     *
     * @Route("/{id}/delete", name="config_delete")
     * 
     */
    public function deleteAction($id)
    {
       
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Config')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Config entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('config'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
    * Renvoie la préférence "string" 
    *
    * @Route("/get/{string}", name="config_get")
    * @Template()
    */
    public function getAction($string)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$pref = $em->getRepository('PHPMBundle:Config')->findOneByField($string);
    
    	if (!$pref) {
    		throw $this->createNotFoundException('Unable to find Config entity.');
    	}
    	    	
    	$response = new Response();
    	$response->setContent($pref->getValue());
		$response->headers->set('Content-Type', 'text/plain');
    	
    
    	return $response;
    }
    
    /**
    * 
    *
    * @Route("/initiale", name="config_initiale")
    *
    */
    public function genAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$entity = $em->getRepository('PHPMBundle:Config')->findOneByField("phpm.config.initiale");
    	
    	if (!$entity) {
    		$entity = new Config();
    		$entity->setField("phpm.config.initiale");
    		$entity->setValue("1");
    		$em->persist($entity);
    		$em->flush();
    	}
    	
    	
    	$entity = new Config();
    	$form   = $this->createForm(new ConfigType(), $entity);
    	
    	
    	
    	
    	//Config des plages de la manif 
    	 $plage1 = array("nom" => "Prémanif", "debut" => "2012-05-16 00:00","fin" => "2012-05-23 00:00");
    	 $plage2 = array("nom" => "Manif", "debut" => "2012-05-23 00:00","fin" => "2012-05-27 00:00");
    	 $plage3 = array("nom" => "Postmanif", "debut" => "2012-05-28 00:00","fin" => "2012-06-01 00:00");
    	 $a=array("1"=>$plage1,"2"=>$plage2, "3"=>$plage3);
    	 
    	 return array();
    	
    }
    
    
    
    
}
