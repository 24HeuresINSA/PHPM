<?php

namespace AssoMaker\PHPMBundle\Controller;

use AssoMaker\PHPMBundle\Form\DisponibiliteInscription\DisponibiliteInscriptionListType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\DisponibiliteInscription;
use AssoMaker\PHPMBundle\Form\DisponibiliteInscription\DisponibiliteInscriptionType;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
    	$request = $this->get('request');
    	
    	$param = $request->request->all();
        $em = $this->getDoctrine()->getEntityManager();
        $config=$this->get('config.extension');
        $admin=$this->get('security.context')->isGranted('ROLE_HUMAIN');
        $entitiesQuery="SELECT d, o FROM AssoMakerPHPMBundle:DisponibiliteInscription d LEFT JOIN d.orgas o ORDER BY d.debut"; 

        $entities = $em->createQuery($entitiesQuery)->getResult();
        
        
        $data = array(
        		'disponibiliteInscriptionItems'=>$entities
        );
        $form    = $this->createForm(new DisponibiliteInscriptionListType($admin, $config));

        
        if ($this->get('request')->getMethod() == 'POST') {
        	$form->handleRequest($request);
        	$data = $form->getData();
        	$valid=$form->isValid();

        
        
        
        	if ($valid) {
        		
        		$decalage = $data['decalage'];
        		$mission = $data['mission'];
        		$statut = $data['statut'];
        		$pointsCharisme = $data['pointsCharisme'];
        		$confiance = $data['confiance'];
        		$confianceDesaffect = $data['confiance2'];
        		
        		foreach ($data['disponibiliteInscriptionItems'] as $di){
        			
        			if($param['action']=='delete'){
        				 $em->remove($di);
        			
        			}
        			
        			if($param['action']=='affect'){
        				
        				foreach($confiance->getEquipes() as $equipe)
        				{
        					foreach ($equipe->getOrgas() as $orga)
        					{
        						if (!($orga->getDisponibilitesInscription()->contains($di)))
        						{	
        							$orga->addDisponibiliteInscription($di);
        						}
        					}
        					
        				}
        			}
        			
        			
        			if($param['action']=='desaffect'){
        			 
        				foreach($confianceDesaffect->getEquipes() as $equipe)
        				{
        		        	foreach ($equipe->getOrgas() as $orga)
        					{
        						if ($orga->getDisponibilitesInscription()->contains($di))
        						{	
        							$orga->getDisponibilitesInscription()->removeElement($di);
        						}
        			
        				     }
        				 }
        		
        			}
        			
        			
        			if($param['action']=='edit'){
      				
        				if($decalage!=null){
	        				$ndi = new DisponibiliteInscription();
	        				$debutDi = clone $di->getDebut();
	        				$finDi = clone $di->getFin();
	        				$debutDi->add(new \DateInterval('PT'.($decalage).'S'));
	        				$finDi->add(new \DateInterval('PT'.($decalage).'S'));
	        				$ndi->setDebut($debutDi);
	        				$ndi->setFin($finDi);
	        				if($mission!=null){
	        					$ndi->setMission($mission);
	        				}else{
	        					$ndi->setMission($di->getMission());
	        				}
	        				 
	        				if($statut!=null){
	        					$ndi->setStatut($statut);
	        				}else{	        				
	        					$ndi->setStatut($di->getStatut());
	        				}
	        				
	        				if($pointsCharisme!=null){
	        					$ndi->setPointsCharisme($pointsCharisme);
	        				}else{
	        					$ndi->setPointsCharisme($di->getPointsCharisme());
	        				}
	        				
	        				$em->persist($ndi);
        				}else{
        					
        					if($mission!=null){
        						$di->setMission($mission);
        					}
        					
        					if($statut!=null){
        						$di->setStatut($statut);
        					}
        					if($pointsCharisme!=null){
        						$di->setPointsCharisme($pointsCharisme);
        					}
        				}
        				 
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
     * Lists all Mission entities.
     *
     * @Route("/missions", name="mission")
     * @Template("AssoMakerPHPMBundle:Mission:index.html.twig")
     */
    public function missionsAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$entities = $em->getRepository('AssoMakerPHPMBundle:Mission')->findAll();
    
    	return array('entities' => $entities);
    }

    /**
     * Finds and displays a DisponibiliteInscription entity.
     *
     * @Route("/{id}/show", name="disponibiliteinscription_show")
     * @Template()
     */
    public function showAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:DisponibiliteInscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
        }

        

        return array(
            'entity'      => $entity,
                    );
    }

    /**
     * Displays a form to create a new DisponibiliteInscription entity.
     *
     * @Route("/new", name="disponibiliteinscription_new")
     * @Template()
     */
    public function newAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
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
     * @Template("AssoMakerPHPMBundle:DisponibiliteInscription:new.html.twig")
     */
    public function createAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $entity  = new DisponibiliteInscription();
        $request = $this->getRequest();
        $form    = $this->createForm(new DisponibiliteInscriptionType(), $entity);
        $form->handleRequest($request);

        
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
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:DisponibiliteInscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
        }

        $editForm = $this->createForm(new DisponibiliteInscriptionType(), $entity);
        

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Edits an existing DisponibiliteInscription entity.
     *
     * @Route("/{id}/update", name="disponibiliteinscription_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:DisponibiliteInscription:edit.html.twig")
     */
    public function updateAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:DisponibiliteInscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
        }

        $limitesOrigine = $entity->getLimitesInscriptions();

        $editForm   = $this->createForm(new DisponibiliteInscriptionType(), $entity);

        $request = $this->getRequest();

        $editForm->handleRequest($request);
        $data = $editForm->getData();

        if ($editForm->isValid()) {

            $limitesNouvelles=$data->getLimitesInscriptions();

            if($limitesOrigine!=null&&$limitesNouvelles!=null) {
                foreach ($limitesOrigine as $ob) {
                    if (!$limitesNouvelles->contains($ob)) {
                        $em->remove($ob);
                    }
                }
                foreach ($limitesNouvelles as $limite) {
                    $limite->setDisponibiliteInscription($entity);
                }
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('disponibiliteinscription'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
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
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AssoMakerPHPMBundle:DisponibiliteInscription')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DisponibiliteInscription entity.');
            }

            $em->remove($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('disponibiliteinscription'));
        
    }


    
    
    /**
     * Stat DisponibiliteInscription entities.
     *
     * @Route("/stats/{permis}", name="disponibiliteinscription_stats")
     * @Template()
     */
    public function statsAction($permis)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();
    
        //         $entities = $em->getRepository('AssoMakerPHPMBundle:DisponibiliteInscription')->findAllWithOrgacount();
        $data = $em->getRepository('AssoMakerPHPMBundle:DisponibiliteInscription')->getStats($permis);
    
    
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
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();
        
        
        $a = array();
        $dispos = $em
        ->createQuery("SELECT d FROM AssoMakerPHPMBundle:DisponibiliteInscription d ORDER BY d.debut")
        ->getResult();
        
        $orgas = $em
        ->createQuery("SELECT o FROM AssoMakerBaseBundle:Orga o ORDER BY o.nom")
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
