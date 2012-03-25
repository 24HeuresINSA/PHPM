<?php

namespace PHPM\Bundle\Controller;

use PHPM\Bundle\Form\BesoinMaterielType;

use PHPM\Bundle\Entity\BesoinMateriel;

use PHPM\Bundle\Entity\PlageHoraire;

use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/", name="tache")
     * @Template()
     */
 public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        
        $g =$em
        ->createQuery("SELECT g FROM PHPMBundle:GroupeTache g JOIN g.equipe e WHERE g.statut= 0 ORDER BY e.id ")
        ->getResult();

        $r =$em
        ->createQuery("SELECT t FROM PHPMBundle:Tache t JOIN t.groupeTache g JOIN g.equipe e WHERE t.statut = 0 ORDER BY e.id, g.id")
        ->getResult();
        
        $s =$em
        ->createQuery("SELECT t FROM PHPMBundle:Tache t JOIN t.groupeTache g JOIN g.equipe e WHERE t.statut = 1 ORDER BY e.id, g.id")
        ->getResult();
        
        $v =$em
        ->createQuery("SELECT t FROM PHPMBundle:Tache t JOIN t.groupeTache g JOIN g.equipe e WHERE t.statut = 2 ORDER BY e.id, g.id ")
        ->getResult();
        
        $deletedTaches =$em
        ->createQuery("SELECT t FROM PHPMBundle:Tache t JOIN t.groupeTache g JOIN g.equipe e WHERE t.statut = -1 ORDER BY e.id, g.id ")
        ->getResult();
        
        $deletedGroups =$em
        ->createQuery("SELECT g FROM PHPMBundle:GroupeTache g JOIN g.equipe e JOIN g.responsable r WHERE g.statut = -1 ORDER BY e.id")
        ->getResult();
        
        
        
        
        $eid = $this->get('security.context')->getToken()->getUser()->getEquipe()->getId();
        $oid =  $this->get('security.context')->getToken()->getUser()->getId();
        
        
        $sr =$em
        ->createQuery("SELECT g FROM PHPMBundle:GroupeTache g JOIN g.equipe e JOIN g.responsable r WHERE r.id = :oid AND g.statut = 0 ORDER BY r.id")
        ->setParameter('oid',$oid)
        ->getResult();
        
        $e =$em
        ->createQuery("SELECT g FROM PHPMBundle:GroupeTache g JOIN g.equipe e JOIN g.responsable r WHERE e.id = :eid AND g.statut = 0 ORDER BY r.id")
        ->setParameter('eid',$eid)
        ->getResult();
        

        

        return array('r'=>$r,'s'=>$s,'v'=>$v, 'g'=>$g, 'e'=>$e, 'sr'=>$sr, 'deletedTaches' => $deletedTaches,
                'deletedGroups' => $deletedGroups);
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
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$this->get('config.extension');
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');

        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $defaultValues = array('entity'=>$entity, 'Materiel'=> $entity->getMateriel(), "commentaire"=>'');
        $rOnly = ($entity->getStatut()>=1 ) && (!$admin);
        
        
        $editForm = $this->createForm(new TacheType($admin,$em,$config,$rOnly),$defaultValues);
//         $besoinsForm = $this->createForm(new TacheBesoinsType(false,$em,$config,$entity));
        $deleteForm = $this->createDeleteForm($id);
        
        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'admin' =>$admin,
                'rOnly'=>$rOnly

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
        
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$this->get('config.extension');
        $request = $this->getRequest();
        $param = $request->request->all();
        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);
        $prevStatut= $entity->getStatut();
        $user = $this->get('security.context')->getToken()->getUser();
        $rOnly = ($entity->getStatut()>=1 ) && (!$admin);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }
        $defaultValues = array('entity'=>$entity, 'Materiel'=> $entity->getMateriel());
        $editForm   = $this->createForm(new TacheType($admin,$em,$config,$rOnly), $defaultValues);

        
            $editForm->bindRequest($request);
            
            $data=$editForm->getData();
            
        $texteCommentaire = $data['commentaire'];
            
        if ($editForm->isValid()) {
            $valid= true;
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
                        
                        if(!array_key_exists(0, $bms)){
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
            $rOnly = ($entity->getStatut()>=1 ) && (!$admin);
            $editForm   = $this->createForm(new TacheType($admin,$em,$config,$rOnly), $defaultValues);
            
            
//             return $this->redirect($this->generateUrl('tache_edit', array('id' => $id)));
        }else{
            $valid= false;
        }
        
        
            
                
                
            
            
        

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'valid' => $valid,
                'rOnly'=>$rOnly
        );
    }

    /**
     * Deletes a Tache entity.
     *
     * @Route("/{id}/delete", name="tache_delete")
     *
     */
    public function deleteAction($id)
    {
        
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tache entity.');
            }
			
            $em->remove($entity);
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
	* Import all Tache entities.
	*
	* @Route("/import", name="tache_import")
	* @Template()
	*/
	public function importAction()
	{
		
		$jason = "{\"1\":{\"id\":1,\"nom\":\"Tenir le bar\",\"lieu\":\"Bar AIP\",\"materielNecessaire\":\"Rien\",\"consignes\":\"C'est cool!\",\"confiance_id\":1,\"categorie_id\":1,\"permisNecessaire\":0,\"plagesHoraire\":{\"1\":{\"debut\":{\"date\":\"2011-12-01 00:00:00\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"fin\":{\"date\":\"2011-12-02 00:00:00\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"nbOrgasNecessaires\":10,\"creneaux\":[]},\"2\":{\"debut\":{\"date\":\"2011-12-02 00:00:00\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"fin\":{\"date\":\"2011-12-03 00:00:00\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"nbOrgasNecessaires\":1,\"creneaux\":[]}}},\"2\":{\"id\":2,\"nom\":\"Installer le PS1\",\"lieu\":\"Laurent Bonnevay\",\"materielNecessaire\":null,\"consignes\":\"Tu le met, profond.\",\"confiance_id\":1,\"categorie_id\":1,\"permisNecessaire\":0,\"plagesHoraire\":{\"3\":{\"debut\":{\"date\":\"2011-12-01 00:00:00\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"fin\":{\"date\":\"2011-12-06 00:00:00\",\"timezone_type\":3,\"timezone\":\"Europe\/Paris\"},\"nbOrgasNecessaires\":3,\"creneaux\":[]}}}}";
		
		$em = $this->getDoctrine()->getEntityManager();
		$entities = $em->getRepository('PHPMBundle:Tache')->findAll();
		
		$tabArray = json_decode($jason, TRUE);
		
		//Affichage de l'import et de la db
		/*
		print"<pre>";
		var_dump($tabArray);
		print"</pre>";

		print"<pre>";
		foreach ($entities as $elements){
			print $elements->getId();
		var_dump($elements->toArray());
		}
		print"</pre>";
		//*/
		
		foreach ($tabArray as $tache_en_traitement) {
			$found = FALSE;
			foreach ($entities as $elements){
				if ($elements->getImportId() == $tache_en_traitement['id']){
					$found = TRUE;
					break;
				}
				
			}
			if (!$found){
				//On ajoute la tache
				print "ajout de la tache ";
				print $tache_en_traitement['id'];
				print "<br />";
				//*
				$confiance = $em->getRepository('PHPMBundle:Confiance')->findOneById($tache_en_traitement['confiance_id']);
// 				$categorie = $em->getRepository('PHPMBundle:Categorie')->findOneById($tache_en_traitement['categorie_id']);
				
				
				$entity  = new tache();
				$entity->setImportId($tache_en_traitement['id']);
				$entity->setNom($tache_en_traitement['nom']);
				$entity->setConsignes($tache_en_traitement['consignes']);
				$entity->setMaterielNecessaire($tache_en_traitement['materielNecessaire']);
				$entity->setPermisNecessaire($tache_en_traitement['permisNecessaire']);
				$entity->setLieu($tache_en_traitement['lieu']);
// 				$entity->setCategorie( $categorie);
				$entity->setConfiance( $confiance);
				
					
				$validator = $this->get('validator');
				$errors = $validator->validate($entity);
				
				if (count($errors) > 0) {
					$err =$errors[0];
					$simplifiedError = array($err->getMessageTemplate(),$err->getPropertyPath(), $err->getInvalidValue());
					$validationErrors[$tache_en_traitement['id']." ".$tache_en_traitement['nom']]=$simplifiedError;
					 
				}else{
					$em->persist($entity);
					$em->flush();
	
					
				}
				
				foreach ($tache_en_traitement['plagesHoraire'] as $plageHoraire){
				$plageHoraireObject = new PlageHoraire();
				$plageHoraireObject->setDebut(new \DateTime($plageHoraire['debut']['date']));
				$plageHoraireObject->setFin(new \DateTime($plageHoraire['fin']['date']));
				$plageHoraireObject->setNbOrgasNecessaires($plageHoraire['nbOrgasNecessaires']);
					
				$errors = $validator->validate($plageHoraireObject);
				
				if (count($errors) > 0) {
					$err =$errors[0];
					$simplifiedError = array($err->getMessageTemplate(),$err->getPropertyPath(), $err->getInvalidValue());
					//$validationErrors[$tache_en_traitement['id']." ".$tache_en_traitement['nom']]=$simplifiedError;
				
				}else{
					
					$em->persist($plageHoraireObject);
					$em->flush();
					$entity->addPlageHoraire($plageHoraireObject );
						
				}
				
				}
				
				
				
				//*/
			}else{
				print "tache ";
				print $tache_en_traitement['id'];
				print " deja a jour <br />";
			}
		}
			
		
	exit(print($tabArray));
	return array();
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
		$duree= $request->request->get('duree', '');
		// $categorie= $request->request->get('categorie_id', '');
		$permis= $request->request->get('permisNecessaire', '');
		// $age= $request->request->get('ageNecessaire', '0');
		$niveau_confiance= $request->request->get('confiance_id', '');
		$plage = $request->request->get('plage_id', '');
		$bloc = $request->request->get('bloc', '0');
	
		$em = $this->getDoctrine()->getEntityManager();
		// création de la requête SQL et récupération de son retour
		$entities = $em->getRepository('PHPMBundle:Tache')->getTacheWithCriteria($duree, $permis, $niveau_confiance, $plage, $bloc);
		
		// creation du json de retour
		$taches = array();
		foreach ($entities as $entity) {
			$creneaux = array();
			foreach ($entity->getPlagesHoraire() as $creneau) {
				$creneaux[$creneau->getId()] = $creneau->toSimpleArray();
			}
			
			$tacheArray = array(
				"id" => $entity->getId(),
				"nom" => $entity->getNom(),
				"lieu" => $entity->getLieu(),
				"confiance" => $entity->getConfiance()->getId(),
// 				"categorie" => $entity->getCategorie()->getId(),
				"creneaux" => $creneaux,
				"permisNecessaire" => $entity->getPermisNecessaire());
				
				$taches[$entity->getId()] = $tacheArray;
		}
	
    	$response = new Response();
    	$response->setContent(json_encode($taches));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
}
