<?php

namespace AssoMaker\AnimBundle\Controller;

use AssoMaker\AnimBundle\Form\AnimationType;

use AssoMaker\AnimBundle\Entity\Animation;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * 
 * @Route("/anim")
 *
 *
 */
class AnimationController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     *
     * @Route("/new", name="anim_animation_new")
     * @Template
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();        
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');        
    
        $entity = new Animation();
        $entity->setStatut(0);
        $editForm = $this->createForm(new AnimationType($admin,$config,true), $entity);
    
        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);
    
            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();
    
                return $this
                ->redirect($this->generateUrl('anim_animation_edit',array('id'=>$entity->getId())));
            }
        }
    
        return array('entity' => $entity, 'form' => $editForm->createView());
    
    }
    
    /**
     *
     * @Route("/{id}/edit", name="anim_animation_edit")
     * @Template
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e=$this->get('config.extension');
        $entity = $em->getRepository('AssoMakerAnimBundle:Animation')->find($id);
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Animation entity.');
        }
    
        $editForm   = $this->createForm(new AnimationType($admin,$config,false), $entity);
    
    
        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);
    
            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();
    
                return $this->redirect($this->generateUrl('anim_animation_edit',array('id'=>$entity->getId())));
            }
        }
    
        return array(
                'entity'      => $entity,
                'form'   => $editForm->createView()
        );
    }
}
