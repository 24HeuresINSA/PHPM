<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\PlageHoraire;
use AssoMaker\PHPMBundle\Form\PlageHoraireType;
use AssoMaker\PHPMBundle\Entity\Creneau;
use AssoMaker\PHPMBundle\Validator\QuartHeure;
use AssoMaker\PHPMBundle\Validator\PlageHoraireRecoupe;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * PlageHoraire controller.
 *
 * @Route("/plagehoraire")
 */
class PlageHoraireController extends Controller
{
//     /**
//      * Lists all PlageHoraire entities.
//      *
//      * @Route("/", name="plagehoraire")
//      * @Template()
//      */
//     public function indexAction()
//     {
//         $em = $this->getDoctrine()->getEntityManager();

//         $entities = $em->getRepository('AssoMakerPHPMBundle:PlageHoraire')->findAll();

//         return array('entities' => $entities);
//     }

    /**
     * Finds and displays a PlageHoraire entity.
     *
     * @Route("/{id}/show", name="plagehoraire_show")
     * @Template()
     */
    public function showAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:PlageHoraire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PlageHoraire entity.');
        }


        return array(
            'entity'      => $entity,
                    );
    }

    /**
     * Displays a form to create a new PlageHoraire entity.
     *
     * @Route("/new/{id}", name="plagehoraire_new")
     * @Template("AssoMakerPHPMBundle:PlageHoraire:new.html.twig")
     */
    public function newAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e=$this->get('config.extension');
        $tache=$em->getRepository('AssoMakerPHPMBundle:Tache')->find($id);
        
        if (!$tache) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }
                
        $entity = new PlageHoraire();

        
        $plages = json_decode($config->getValue('manifestation_plages'),true);
        $debut = new \DateTime($plages[1]['debut']);
        
        $entity->setTache($tache);
        $entity->setDebut($debut);
        $entity->setFin($debut);
        $entity->setDureeCreneau(0);
        $entity->setRecoupementCreneau(0);
        
        $em->persist($entity);
        $em->flush();
        
        
        return $this->redirect($this->generateUrl('plagehoraire_edit', array('id' => $entity->getId())));

    }
    
    
    

    /**
     * Creates a new PlageHoraire entity.
     *
     * @Route("/create", name="plagehoraire_create")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:PlageHoraire:new.html.twig")
     */
    public function createAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
        $entity  = new PlageHoraire();
        $request = $this->getRequest();
        $config = $e=$this->get('config.extension');
        $form    = $this->createForm(new PlageHoraireType($config), $entity);

        $form->handleRequest($request);
        
        

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            
            $action = $request->request->all();
            
            
            
            
            if($action['action']=='validate_new'){
            return $this->redirect($this->generateUrl('plagehoraire_new', array('id' => $entity->getId()))); 
                
            }
            
            
            

            return $this->redirect($this->generateUrl('plagehoraire_show', array('id' => $entity->getId())));
            
        }
        

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new PlageHoraire entity assigned to a tache.
     *
     * @Route("/newTache/{idtache}", name="plagehoraire_tache_new")
     * @Template("AssoMakerPHPMBundle:PlageHoraire:new.html.twig")
     */
    public function newTacheAction($idtache)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
    	$em = $this->getDoctrine()->getEntityManager();

        $tache = $em->getRepository('AssoMakerPHPMBundle:Tache')->find($idtache);
				
    	$entity = new PlageHoraire();
		$entity->setTache($tache);
        $form   = $this->createForm(new PlageHoraireType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );

    }

    /**
     * Displays a form to edit an existing PlageHoraire entity.
     *
     * @Route("/{id}/edit", name="plagehoraire_edit")
     * @Template()
     */
    public function editAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $entity = $em->getRepository('AssoMakerPHPMBundle:PlageHoraire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PlageHoraire entity.');
        }

        $editForm = $this->createForm(new PlageHoraireType($config), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing PlageHoraire entity.
     *
     * @Route("/{id}/update", name="plagehoraire_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:PlageHoraire:edit.html.twig")
     */
    public function updateAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        
        $entity = $em->getRepository('AssoMakerPHPMBundle:PlageHoraire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PlageHoraire entity.');
        }
        
        $originalBesoins = $entity->getBesoinsOrga();

        $editForm   = $this->createForm(new PlageHoraireType($config), $entity);        

        $request = $this->getRequest();

        $editForm->handleRequest($request);
        $data = $editForm->getData();

        $valid=$editForm->isValid();
        
        if ($valid) {            
            $nouveauxBesoins=$data->getBesoinsOrga();
        
            foreach ($originalBesoins as $ob){
                if(!$nouveauxBesoins->contains($ob)){
                    $em->remove($ob);
                }
            }
       
            $em->persist($entity);
            $em->flush();

            $param = $request->request->all();
            
     
            
            return $this->redirect($this->generateUrl('tache_edit', array('id' => $entity->getTache()->getId())));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        	'valid'	=>$valid	
        );
    }

    /**
     * Deletes a PlageHoraire entity.
     *
     * @Route("/{id}/delete", name="plagehoraire_delete")
     * 
     */
    public function deleteAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AssoMakerPHPMBundle:PlageHoraire')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PlageHoraire entity.');
            }

            $em->remove($entity);
            $em->flush();
        
            $tacheId=$entity->getTache()->getId();
           
            
            
            return $this->redirect($this->generateUrl('tache_edit', array('id'=>$tacheId)));
            

    }


}
