<?php

namespace PHPM\Bundle\Controller;

use PHPM\Bundle\Form\DisponibiliteInscription\DisponibiliteInscriptionListType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\DisponibiliteInscription;
use PHPM\Bundle\Form\DisponibiliteInscription\DisponibiliteInscriptionType;

/**
 * DisponibiliteInscription controller.
 *
 * @Route("/disponibiliteinscription")
 */
class DisponibiliteInscriptionController extends Controller
{
    /**
     * Lists all DisponibiliteInscription entities.
     *
     * @Route("/", name="disponibiliteinscription")
     * @Template()
     */
    public function indexAction()
    {
    	$request = $this->get('request');
    	
    	$param = $request->request->all();
        $em = $this->getDoctrine()->getEntityManager();
        $config=$this->get('config.extension');
        $admin=$this->get('security.context')->isGranted('ROLE_ADMIN');
        $entitiesQuery="SELECT d, o FROM PHPMBundle:DisponibiliteInscription d LEFT JOIN d.orgas o ORDER BY d.debut"; 

        $entities = $em->createQuery($entitiesQuery)->getResult();
        
        
        $data = array(
        		'disponibiliteInscriptionItems'=>$entities
        );
        $form    = $this->createForm(new DisponibiliteInscriptionListType($admin, $config));

        if ($this->get('request')->getMethod() == 'POST') {
        	$form->bindRequest($request);
        	$data = $form->getData();
        	$valid=$form->isValid();

        
        
        
        	if ($valid) {
        		
        		$decalage = $data['decalage'];
        		$categorie = $data['categorie'];
        		
        		foreach ($data['disponibiliteInscriptionItems'] as $di){
        			
        			if($param['action']=='delete'){
        				 $em->remove($di);
        			
        			}
        			
        			if($param['action']=='changecat'){
        				$di->setCategorie($categorie);
        				 
        			}
        			
        			if($param['action']=='duplicate'){
        			
        				$ndi = new DisponibiliteInscription();
        				$debutDi = clone $di->getDebut();
        				$finDi = clone $di->getFin();
        				$debutDi->add(new \DateInterval('PT'.($decalage).'S'));
        				$finDi->add(new \DateInterval('PT'.($decalage).'S'));
        				$ndi->setDebut($debutDi);
        				$ndi->setFin($finDi);
        				
        				$em->persist($ndi);
        				
        				
        			}
        			
        		
        		}
        		
        		
        		
        		
        		
        		
        		$em->flush();
        		
        
        	}
        
        	$form    = $this->createForm(new DisponibiliteInscriptionListType($admin, $config));
        	$entities = $em->createQuery($entitiesQuery)->getResult();
        }
		
        return array('entities' => $entities,
        		'form'=>$form->createView());
    }

    /**
     * Finds and displays a DisponibiliteInscription entity.
     *
     * @Route("/{id}/show", name="disponibiliteinscription_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:DisponibiliteInscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new DisponibiliteInscription entity.
     *
     * @Route("/new", name="disponibiliteinscription_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new DisponibiliteInscription();
        $form   = $this->createForm(new DisponibiliteInscriptionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new DisponibiliteInscription entity.
     *
     * @Route("/create", name="disponibiliteinscription_create")
     * @Method("post")
     * @Template("PHPMBundle:DisponibiliteInscription:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new DisponibiliteInscription();
        $request = $this->getRequest();
        $form    = $this->createForm(new DisponibiliteInscriptionType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('disponibiliteinscription'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing DisponibiliteInscription entity.
     *
     * @Route("/{id}/edit", name="disponibiliteinscription_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:DisponibiliteInscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
        }

        $editForm = $this->createForm(new DisponibiliteInscriptionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing DisponibiliteInscription entity.
     *
     * @Route("/{id}/update", name="disponibiliteinscription_update")
     * @Method("post")
     * @Template("PHPMBundle:DisponibiliteInscription:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:DisponibiliteInscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
        }

        $editForm   = $this->createForm(new DisponibiliteInscriptionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('disponibiliteinscription'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a DisponibiliteInscription entity.
     *
     * @Route("/{id}/delete", name="disponibiliteinscription_delete")
     * 
     */
    public function deleteAction($id)
    {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:DisponibiliteInscription')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
            }

            $em->remove($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('disponibiliteinscription'));
        
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
    /**
     * Stat DisponibiliteInscription entities.
     *
     * @Route("/stats/{permis}", name="disponibiliteinscription_stats")
     * @Template()
     */
    public function statsAction($permis)
    {
        $em = $this->getDoctrine()->getEntityManager();
    
        //         $entities = $em->getRepository('PHPMBundle:DisponibiliteInscription')->findAllWithOrgacount();
        $data = $em->getRepository('PHPMBundle:DisponibiliteInscription')->getStats($permis);
    
    
        return array('data' => $data);
    }
    

    /**
     * Export as array
     *
     * @Route("/array", name="disponibiliteinscription_array")
     * @Template()
     */
    public function arrayAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        
        $a = array();
        $dispos = $em
        ->createQuery("SELECT d FROM PHPMBundle:DisponibiliteInscription d ORDER BY d.debut")
        ->getResult();
        
        $orgas = $em
        ->createQuery("SELECT o FROM PHPMBundle:Orga o ORDER BY o.nom")
        ->getResult();
        
        foreach ($orgas as $orga){
            $a[$orga->getPrenom().' '.$orga->getNom()]=array();
            foreach ($dispos as $dispo)
            {
                $value = 'x';
                if($orga->getDisponibilitesInscription()->contains($dispo))
                    $value = $orga->getPermis();
                    
                
                $a[$orga->getPrenom().' '.$orga->getNom()][$dispo->getDebut()->format('l H:i')] = $value;
        
            }
        }
        

        return array('data' => $a, 'dispos'=>$dispos);
    }
    
    
  
    
    
    
}
