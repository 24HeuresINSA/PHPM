<?php

namespace PHPM\Bundle\Controller;

use PHPM\Bundle\Form\BesoinMaterielType;

use PHPM\Bundle\Entity\BesoinMateriel;

use PHPM\Bundle\Entity\PlageHoraire;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Tache;
use PHPM\Bundle\Entity\Commentaire;
use PHPM\Bundle\Entity\Confiance;
// use PHPM\Bundle\Entity\Categorie;
use PHPM\Bundle\Form\TacheType;
use PHPM\Bundle\Form\TacheBesoinsType;

/**
 * Tache controller.
 *
 * @Route("/tache")
 */
class TacheController extends Controller
{
    /**
     * Lists all Tache entities.
     *
     * @Route("/index/{equipeid}/{statut}/{orgaid}", defaults={"equipeid"="all","statut"="all","orgaid"="all"}, name="tache")
     * @Template()
     */
 public function indexAction($equipeid,$statut,$orgaid)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();
        
        $equipes =$em
        ->createQuery("SELECT e FROM PHPMBundle:Equipe e")
        ->getResult();
        
		$tachesDQL = "SELECT t,l,r,g FROM PHPMBundle:Tache t LEFT JOIN t.groupeTache g JOIN g.equipe e JOIN t.responsable r LEFT JOIN t.commentaires l WHERE 1=1 ";
		
		if($statut !='all'){
			$tachesDQL .= " AND t.statut = $statut ";
		}
		
		if($statut !=-1){
			$tachesDQL .= " AND t.statut <> -1 ";
		}
		
		if($equipeid !='all'){
			$tachesDQL .= " AND e.id = $equipeid ";
			
		}
		if($orgaid !='all'){
			$tachesDQL .= " AND r.id = $orgaid ";
				
		}
		
		$tachesDQL .= " ORDER BY e.id, g.id, t.statut";
        
        
        $taches =$em
        ->createQuery($tachesDQL)
        ->getResult();
        
        return array('equipes' => $equipes,
        		'taches' => $taches
        		
        		);
    }
    
    /**
    * Lists all Tache entities as JSON.
    *
    * @Route("/index.json", name="tache_json")
    *
    */
    public function indexJsonAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$entities = $em->getRepository('PHPMBundle:Tache')->findAll();
    	 
    	$a = array();
    	 
    	foreach ($entities as $entity){
    		$a[$entity->getId()] = $entity->toArray();
    
    	}
    	$response = new Response();
    	$response->setContent(json_encode($a));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    
    	return $response;
    }



    /**
     * Creates a new Tache entity.
     *
     * @Route("/create/{gid}", name="tache_create")
     *
     *
     */
    public function createAction($gid)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$this->get('config.extension');
        $entity  = new Tache();
        $entity->setStatut(0);
        $request = $this->getRequest();
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($gid!=""){
            $groupe = $em->getRepository('PHPMBundle:GroupeTache')->find($gid);
        
            if (!$groupe) {
                throw $this->createNotFoundException('Pas de groupe correspondant.');
            }
        
            $entity->setGroupeTache($groupe);
            $entity->setLieu($groupe->getLieu());
            $entity->setResponsable($groupe->getResponsable());
            $entity->setStatut(0);
            $entity->setNom('Tâche sans nom');
            $entity->setPermisNecessaire(0);
            
        }
        $em->persist($entity);
        $em->flush();
            
            
        return $this->redirect($this->generateUrl('tache_edit', array('id' => $entity->getId())));
            
       
    }

    /**
     * Displays a form to edit an existing Tache entity.
     *
     * @Route("/{id}/edit", name="tache_edit")
     * @Template()
     */
    public function editAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$this->get('config.extension');
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
	
        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }
        
        if($config->getValue('phpm_tache_heure_limite_validation')){
        	$heureLimite=$config->getValue('phpm_tache_heure_limite_validation');
        	$deadlinePassed = (new \DateTime($heureLimite) < new \DateTime());
        }else{
        	$deadlinePassed=false;
        }

       
        $defaultValues = array('entity' => $entity, 'Materiel' => $entity->getMateriel(), "commentaire" => '');
        $rOnly = (($entity->getStatut()>=1 ) && (!$admin)) || ($entity->getStatut()==3 );
        
        $editForm = $this->createForm(new TacheType($admin, $em, $config, $rOnly), $defaultValues);
//         $besoinsForm = $this->createForm(new TacheBesoinsType(false,$em,$config,$entity));
        
        
        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'admin' => $admin,
            'rOnly'=>$rOnly,
        	'deadlinePassed'=>$deadlinePassed
        );
    }

    /**
     * Edits an existing Tache entity.
     *
     * @Route("/{id}/update", name="tache_update")
     * @Method("post")
     * @Template("PHPMBundle:Tache:edit.html.twig")
     */
    public function updateAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$this->get('config.extension');
        $request = $this->getRequest();
        $param = $request->request->all();
        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);
        $prevStatut= $entity->getStatut();
        $user = $this->get('security.context')->getToken()->getUser();
        $rOnly = (($entity->getStatut()>=1 ) && (!$admin)) || ($entity->getStatut()==3 );
        if($config->getValue('phpm_tache_heure_limite_validation')){
        	$heureLimite=$config->getValue('phpm_tache_heure_limite_validation');
        	$deadlinePassed = (new \DateTime($heureLimite) < new \DateTime());
        }else{
        	$deadlinePassed=false;
        }
        
		
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }
        $defaultValues = array('entity'=>$entity, 'Materiel'=> $entity->getMateriel());
        $editForm   = $this->createForm(new TacheType($admin,$em,$config,$rOnly), $defaultValues);

        
            $editForm->bindRequest($request);
            
            $data=$editForm->getData();
            
        $texteCommentaire = $data['commentaire'];
            
        $valid=$editForm->isValid();
        
        if ($valid) {
        	
            $tmpm = $data['Materiel'];
            
            
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
                $entity->removeAllCreneaux();
                $texteCommentaire=$texteCommentaire."<b>&rarr;Fiche supprimée.</b>";
            }
            if($param['action']=='restore'){
                $entity->setStatut(0);
                $texteCommentaire=$texteCommentaire."<b>&rarr;Fiche restaurée.</b>";
            }
            
            
            if($tmpm){
            
            foreach ($tmpm as $group){
                foreach ($group as $key=> $value){
                    
                    
                        
                        $bms = $em->createQuery("SELECT b FROM PHPMBundle:BesoinMateriel b JOIN b.materiel m JOIN b.tache t WHERE t.id = :tid AND m.id=:mid")
                        ->setParameter('tid',$id)
                        ->setParameter('mid',$key)
                        
                        ->getResult();
                        
                        if(($value*1)!=0){
	                        if(!array_key_exists(0, $bms)){ //Le bm liant m et t n'existe pas
	                            $bm= new BesoinMateriel();
	                            $bm->setTache($entity);
	                            $m = $em->createQuery("SELECT m FROM PHPMBundle:Materiel  m  WHERE  m.id=:mid")
	                            ->setParameter('mid',$key)
	                            ->getSingleResult();
	                            $bm->setMateriel($m);
	                            $em->persist($bm);
	                            
	                            
	                            
	                        }else{
	                            $bm=$bms[0];
	                        }
	                        
	                        $bm->setQuantite($value*1);
	                        $entity->addBesoinMateriel($bm);
	                        
                        }else{
                        	if(array_key_exists(0, $bms)){ //Le bm liant m et t n'existe pas
                        		$bm=$bms[0];
                        		$entity->getBesoinsMateriel()->removeElement($bm);
                        		$m = $em->createQuery("SELECT m FROM PHPMBundle:Materiel  m  WHERE  m.id=:mid")
                        		->setParameter('mid',$key)
                        		->getSingleResult();
                        		$m->getBesoinsMateriel()->removeElement($bm);
                        		$em->remove($bm);                        		 
                        		 
                        		 
                        	}
                        	
                        }
                        
                        
                        
                    
                }
                }
            }
                if($texteCommentaire!=""){
	                $commentaire = new Commentaire();
	                $commentaire->setAuteur($user);
	                $commentaire->setHeure(new \DateTime());
	                $commentaire->setTache($entity);
	                $commentaire->setTexte($texteCommentaire);
	                $em->persist($commentaire);
                }
            
            $em->persist($entity);
            $em->flush();

            $defaultValues = array('entity'=>$entity, 'Materiel'=> $entity->getMateriel());
            $rOnly = (($entity->getStatut()>=1 ) && (!$admin)) || ($entity->getStatut()==3 );
            $editForm   = $this->createForm(new TacheType($admin,$em,$config,$rOnly), $defaultValues);
            
            
//             return $this->redirect($this->generateUrl('tache_edit', array('id' => $id)));
            if($param['action']=='save_return'){
            	return $this->redirect($this->generateUrl('groupetache_edit', array('id' => $entity->getGroupeTache()->getId())));
            }
            if($param['action']=='creneaumaker'){
				return $this->redirect($this->generateUrl('creneaumaker_tache', array('id' => $entity->getId())));
            }
        
        }  
        
        if($param['action']=='add_plage'){
        	return $this->redirect($this->generateUrl('plagehoraire_new', array('id' => $entity->getId())));
        }

                   
        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'valid' => $valid,
            'rOnly'=>$rOnly,
        	'deadlinePassed'=>$deadlinePassed
        );
    }
    
    /**
     * Sets a tache ok for affectation
     *
     * @Route("/{id}/ok", name="tache_okaffectation")
     */
    public function okaffectationAction($id)
    {
	    if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
	    		throw new AccessDeniedException();
	    	}
    	$admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    	$em = $this->getDoctrine()->getEntityManager();
    	$config  =$this->get('config.extension');
    	$request = $this->getRequest();
    	$entity = $em->getRepository('PHPMBundle:Tache')->find($id);
    	$user = $this->get('security.context')->getToken()->getUser();
    	
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Tache entity.');
    	}
    	
    	if ($entity->getStatut()<2){
    		throw new \Exception("La tâche doit être validée");
    	}
    	
    	
    	
    	$commentaire = new Commentaire();
    	$commentaire->setAuteur($user);
    	$commentaire->setHeure(new \DateTime());
    	$commentaire->setTache($entity);
    	$commentaire->setTexte('<b>&rarr;Tache ok pour affectation.</b>');
    	$em->persist($commentaire);
    	
    	$entity->setStatut(3);
    	
    	$em->flush();
    
    	
    	return $this->redirect($this->generateUrl('creneaumaker_tache', array('id' => $entity->getId())));
    	
    }

    /**
     * Deletes a Tache entity.
     *
     * @Route("/{id}/delete", name="tache_delete")
     *
     */
    public function deleteAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw new AccessDeniedException();
    	}
        
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tache entity.');
            }
			
            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('tache'));
    }
    
    /**
     * Moves a Tache entity to trash.
     *
     * @Route("/{id}/trash", name="tache_trash")
     *
     */
    public function trashAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
    
    	$em = $this->getDoctrine()->getEntityManager();
    	$entity = $em->getRepository('PHPMBundle:Tache')->find($id);
    	
    	$user = $this->get('security.context')->getToken()->getUser();
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Tache entity.');
    	}
    	
    	$entity->removeAllCreneaux();
    		
    	$entity->setStatut(-1);
    	$commentaire = new Commentaire();
    	$commentaire->setAuteur($user);
    	$commentaire->setHeure(new \DateTime());
    	$commentaire->setTache($entity);
    	$commentaire->setTexte("<b>&rarr;Fiche supprimée.</b>");
    	$em->persist($commentaire);
    	
    	
    	$em->flush();
    
    
    	return $this->redirect($this->generateUrl('tache'));
    }
    

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

	
	/**
	* Lists all Tache entities.
	*
	* @Route("/query.json", name="tache_query_json")
	* @Method("post")
	*/
	public function queryJsonAction()
	{
		// fonction qui permet de selectionner une liste de tache en fonction de certains critères
		$request = $this->getRequest();
		
		//on recupere les paramètres passés en post
		$equipe = $request->request->get('equipe_id', '');
		$permis = $request->request->get('permisNecessaire', '');
		$plage = $request->request->get('plage_id', '');
	
		$em = $this->getDoctrine()->getEntityManager();
		// création de la requête SQL et récupération de son retour
		$entities = $em->getRepository('PHPMBundle:Tache')->getTacheWithCriteria($plage, $permis, $equipe);
		
		// creation du json de retour
		$taches = array();
		foreach ($entities as $entity) {
			$taches[] = array(
						"id" => $entity->getId(),
						"nom" => $entity->getNom(),
						"lieu" => $entity->getLieu(),
						"permisNecessaire" => $entity->getPermisNecessaire()
						);
		}
	
    	$response = new Response();
    	$response->setContent(json_encode($taches));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
}
