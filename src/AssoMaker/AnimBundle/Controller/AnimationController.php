<?php

namespace AssoMaker\AnimBundle\Controller;

use Doctrine\DBAL\Types\JsonArrayType;

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
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();
        
        $animations = $em->createQuery("SELECT a.id, a.nom, a.statut, e.nom as equipe, e.id as equipeId, a.type, CONCAT(r.prenom, CONCAT(' ',r.nom)) as responsable FROM AssoMakerAnimBundle:Animation a JOIN a.responsable r JOIN a.equipe e ORDER BY a.statut DESC ")->getArrayResult();
        return array('animations' => json_encode($animations),
                'types'=>json_encode(Animation::$animTypes)
                );

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
        $defaultValues = array('entity' => $entity);
        $editForm = $this->createForm(new AnimationType($admin,$config,true), $defaultValues);
    
        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);
    
            if ($editForm->isValid()) {
                $entity->addCommentaire($user, "Création de la fiche");
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
        $user = $this->get('security.context')->getToken()->getUser();     
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $request = $this->getRequest();
        $param = $request->request->all();
        
       
    
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Animation entity.');
        }
    
        $defaultValues = array('entity' => $entity, "commentaire" => '');
        $editForm   = $this->createForm(new AnimationType($admin,$config,false), $defaultValues);
        
        $rawListeLieux = $em->createQuery("SELECT a.lieu FROM AssoMakerAnimBundle:Animation a WHERE a.lieu IS NOT NULL GROUP BY a.lieu")->getScalarResult();
        $listeLieux=array();
        foreach ($rawListeLieux as $l){
            $listeLieux[]=$l['lieu'];
        }    
        
        
        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);
            $data = $editForm->getData();
                      
    
            if ($editForm->isValid()) {
                $data = $editForm->getData();
                
                $texteCommentaire = $data['commentaire'];
                
                
                if($param['action']=='submit_validation'){
                    $entity->setStatut(1);
                    $texteCommentaire=$texteCommentaire."<b>&rarr;Fiche soumise à Validation.</b>";
                }
                
                if($param['action']=='validate'){
                    $entity->setStatut(2);
                    $texteCommentaire=$texteCommentaire."<b>&rarr;Fiche validée.</b>";
                }
                
                if($param['action']=='reject'){
                    $entity->setStatut(0);
                    $texteCommentaire=$texteCommentaire."<b>&rarr;Fiche rejetée.</b>";
                }
                
                if($param['action']=='delete'){
                    $entity->setStatut(-1);                   
                    $texteCommentaire=$texteCommentaire."<b>&rarr;Fiche supprimée.</b>";
                }
                if($param['action']=='restore'){
                    $entity->setStatut(0);
                    $texteCommentaire=$texteCommentaire."<b>&rarr;Fiche restaurée.</b>";
                }
                
                
                
                if($texteCommentaire!=''){
                    $entity->addCommentaire($user, $texteCommentaire);
                }                
                
                $em->persist($entity);
                $em->flush();
    
                return $this->redirect($this->generateUrl('anim_animation_edit',array('id'=>$entity->getId())));
            }
        }
    
        return array(
                'entity'      => $entity,
                'form'   => $editForm->createView(),
                'listeLieux'=>json_encode($listeLieux)
        );
    }
}
