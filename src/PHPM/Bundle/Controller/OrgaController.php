<?php

namespace PHPM\Bundle\Controller;

use PHPM\Bundle\Entity\DisponibiliteInscription;

use PHPM\Bundle\Form\InscriptionHardType;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Orga;
use PHPM\Bundle\Entity\Disponibilite;
use PHPM\Bundle\Form\OrgaType;

/**
 * Orga controller.
 *
 * @Route("/orga")
 */
class OrgaController extends Controller
{
	 /**
     * Lists all Orga entities.
     *
	 * @Route("/affectation", name="orga_affectation")
     * @Route("/affectation/", name="orga_affectation")
     * @Template()
     */
    public function affectationAction()
    {
     if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Orga')->findAll();

        return array('entities' => $entities);
      
    }
	
	
	
    /**
     * Lists all Orga entities.
     *
     * @Route("/", name="orga")
     * @Template()
     */
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Orga')->findAll();

        return array('entities' => $entities);
    }

 

    /**
     * Displays a form to create a new Orga entity.
     *
     * @Route("/new", name="orga_new")
     * @Template()
     */
    public function newAction()
    {
        $config = $this->get('config.extension');
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $entity = new Orga();
        $form   = $this->createForm(new OrgaType(true, $config), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }
    
    /**
     * Displays a form to create a new Orga Hard entity.
     *
     * @Route("/register", name="orga_register")
     * 
     * @Template()
     */
    public function registerAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e=$this->get('config.extension');
        $request = $this->getRequest();
        $confianceId= $request->request->get('confiance', '');
        $email = $request->request->get('email', '');
        
        if ($confianceId=='' || $email=='') {
            throw new AccessDeniedException();
        }
        
        $entity = new Orga();
        $entity->setEmail($email);
        $entity->setStatut(0);
        
        $confianceObject = $em->getRepository('PHPMBundle:Confiance')->find($confianceId);
        
        if (!$confianceObject) {
            throw $this->createNotFoundException('Unable to find Confiance entity.');
        }
        
        $entity->setConfiance($confianceObject);
        $form   = $this->createForm(new OrgaType(false,$config), $entity);
    
        return array(
                'entity' => $entity,
                'form'   => $form->createView()
        );
    }
    
    /**
     * Creates a new Orga entity.
     *
     * @Route("/registerprocess", name="orga_registerprocess")
     * @Method("post")
     * @Template("PHPMBundle:Orga:register.html.twig")
     */
    public function registerprocessAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$e=$this->get('config.extension');
        $entity  = new Orga();
        $request = $this->getRequest();
        $entity->setStatut(0);
        $entity->setPrivileges(1);
        $entity->setConfiance($em->getRepository('PHPMBundle:Confiance')->find(3));
        
        $form    = $this->createForm(new OrgaType(false,$config), $entity);
        $form->bindRequest($request);
    
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
    
            return $this->redirect($this->generateUrl('login', array('registered' => 1)));
    
        }
    
        return array(
                'entity' => $entity,
                'form'   => $form->createView()
        );
    }

 /**
     * Creates a new Orga entity.
     *
     * @Route("/create", name="orga_create")
     * @Method("post")
     * @Template("PHPMBundle:Orga:new.html.twig")
     */
    public function createAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $config = $e=$this->get('config.extension');
        
        $entity  = new Orga();
        $request = $this->getRequest();
        $form    = $this->createForm(new OrgaType($this->get('security.context')->isGranted('ROLE_ADMIN'),$config), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('orga_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Orga entity.
     *
     * @Route("/{id}/edit", name="orga_edit")
     * @Template()
     */
    public function editAction($id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $config = $e=$this->get('config.extension');
        $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') && $user = $this->get('security.context')->getToken()->getUser() != $entity) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(new OrgaType($this->get('security.context')->isGranted('ROLE_ADMIN'),$config), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Orga entity.
     *
     * @Route("/{id}/update", name="orga_update")
     * @Method("post")
     * @Template("PHPMBundle:Orga:edit.html.twig")
     */
    public function updateAction($id)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Orga')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }
        
        

        $config = $e=$this->get('config.extension');
        $editForm   = $this->createForm(new OrgaType($this->get('security.context')->isGranted('ROLE_ADMIN'),$config), $entity);
        

        $request = $this->getRequest();

        $editForm->bindRequest($request);
        
        
        
        
        
        
        
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('orga_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Orga entity.
     *
     * @Route("/{id}/delete", name="orga_delete")
     * 
     */
    public function deleteAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
       
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Orga entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('orga'));
    }

    private function createDeleteForm($id)
    {
        
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	
	 /**
     * Import Orgas from website.
     *
     * @Route("/validation", name="orga_validation")
     * @Template
     */
    /*
	public function validationAction()	
	{
	    if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
	        throw new AccessDeniedException();
	    }
	    
        $request = $this->get('request')->request;      
		$em = $this->getDoctrine()->getEntityManager();
						
		if ($this->get('request')->getMethod() == 'POST') 
		{
        $data = $request->all();
     	    foreach ($data as $idOrgaATraiter => $codeFormulaire) // code formulaire : 0 rien à faire, 1 validé, 2 supprimé
       
            {
            	if($codeFormulaire == 0){
            		continue;
            	}
            	
            	$orga = $em->getRepository('PHPMBundle:Orga')->findOneById($idOrgaATraiter);
	                  
               if ($codeFormulaire==1)
               {
                  $orga->setStatut(1); // validation de l'orga
               } 
               
               if ($codeFormulaire==2)
               {
                   $em->remove($orga);  
               } 
           
               $em->persist($orga);
               $em->flush();
			}
		}
        
		$entities = $em->getRepository('PHPMBundle:Orga')->getOrgasToValidate();
		return array("entities" => $entities);
	}

*/

     /**
     * Import Orgas from website.
     *
     * @Route("/validation", name="orga_validation")
     * @Template
     */
	public function validationAction()  
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        
        $request = $this->get('request')->request;      
        $em = $this->getDoctrine()->getEntityManager();              
        if ($this->get('request')->getMethod() == 'POST') 
        {
        $data = $request->all();
            foreach ($data as $idOrgaATraiter => $codeFormulaire) // code formulaire : 0 rien à faire, 1 validé, 2 supprimé
       
            {
                if($codeFormulaire == 0){
                    continue;
                }
                
                $orga = $em->getRepository('PHPMBundle:Orga')->findOneById($idOrgaATraiter);
                      
               if ($codeFormulaire==1)
               {
                  $orga->setStatut(1); // validation de l'orga
                  $em->persist($orga);
                  $disponibiliteInscription = $orga->getDisponibilitesInscription();
                 
                  if ($disponibiliteInscription[0] != NULL) // l'orga n'a pas de disponibilite
                  {
                          $entitydisponibilite = new disponibilite();
                          $orga->addDisponibilite($entitydisponibilite);
                          $entitydisponibilite->setOrga($orga);
                          
                          $entitydisponibilite->setDebut(new \DateTime(date('Y-m-d H:i:s',$disponibiliteInscription[0]->getDebut()->getTimestamp())));
                          $entitydisponibilite->setFin(new \DateTime(date('Y-m-d H:i:s',$disponibiliteInscription[0]->getFin()->getTimestamp())));
                                            
                      foreach ($disponibiliteInscription as $di)
                      {
                          
                          $debutDI = $di->getDebut()->getTimestamp();
                          $finDI = $di->getFin()->getTimestamp();
                          
                          if ($entitydisponibilite->getFin()->getTimestamp() == $debutDI)
                          {
                             $entitydisponibilite->setFin(new \DateTime(date('Y-m-d H:i:s',$finDI))); 
                          }
                          else if ($entitydisponibilite->getDebut()->getTimestamp() != $debutDI)
                          {
                              $entitydisponibilite = new disponibilite();
                              $orga->addDisponibilite($entitydisponibilite);
                              $entitydisponibilite->setOrga($orga);
                              $entitydisponibilite->setDebut(new \DateTime(date('Y-m-d H:i:s',$debutDI)));
                              $entitydisponibilite->setFin(new \DateTime(date('Y-m-d H:i:s',$finDI)));
                          }    
                                                          
                          
                      }
                  
                  } 
               }
               if ($codeFormulaire==2)
               {
                   $em->remove($orga);  
               } 
           
               
               $em->flush();
            }
        }
       
        $entities = $em->getRepository('PHPMBundle:Orga')->getOrgasFromRegistration();
                
        return array("entities" => $entities);
    }
	
	/**
	 * Inscription Hard
	 *
	 * @Route("/{id}/inscriptionhard", name="orga_inscriptionhard")
	 * @Template
	 */
	public function inscriptionhardAction($id)
	{
	    $request = $this->getRequest();
	    $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
	    $em = $this->getDoctrine()->getEntityManager();
	    $orga =  $em->getRepository('PHPMBundle:Orga')->find($id);
	    $user = $this->get('security.context')->getToken()->getUser();

	    if (!$orga) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }
        
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') && $user != $orga) {
            throw new AccessDeniedException();
        }
	    
	    $em = $this->getDoctrine()->getEntityManager();
	    $entities = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM PHPMBundle:DisponibiliteInscription d ORDER BY d.debut")->getResult();
	    $form = $this->createForm(new InscriptionHardType($admin,$em,$orga));
	
	    if ($request->getMethod() == 'POST') {
	        $form->bindRequest($request);
	        $data=$form->getData();
	        	         
	        if ($form->isValid()) {
	            foreach ($data as $group)
	                foreach ($group as $key=>$value){
	                 
	                $di = $entities[$key];

	                if ($value ==true && !$orga->getDisponibilitesInscription()->contains($di)){
	                    $orga->addDisponibiliteInscription($di);
	                    $di->addOrga($orga);
	                }
	                
	                if ($admin == true && $value == false){
	                    $orga->getDisponibilitesInscription()->removeElement($di);
	                    
	                }
	            }
	             
	            $em->flush();
	            $form = $this->createForm(new InscriptionHardType($admin,$em,$orga));
	        }
	    }
	    
	    return array( 'form' => $form->createView(), 'orga'=>$orga); 
	}

	
	 /**
     * Import Orgas from website.
     *
     * @Route("/import", name="orga_import")
     * @Template
     */
	public function importAction()	
	{
	    if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
	        throw new AccessDeniedException();
	    }
		$em = $this->getDoctrine()->getEntityManager();
		if ($this->get('request')->getMethod() == 'GET') {
			
			return array(""=> array());
			
		}else{
			
			$urlConstraint = new Url();
			$urlConstraint->message = 'Adresse URL Invalide';
			$errorList = $this->get('validator')->validateValue($_POST["pathJson"], $urlConstraint);
			
		if (count($errorList) != 0)       
        throw new Exception($errorList[0]->getMessage());
		$url=$_POST["pathJson"];	
		$json = file_get_contents($url);
		$listeOrgaArray = json_decode($json,TRUE);
		$validationErrors = array();
 	
		foreach ($listeOrgaArray as  $inscriptionOrga)
				{
					
					$confiance = $em->getRepository('PHPMBundle:Confiance')->findOneById(1);  // pour récupérer confiance
					
					$entity  = new orga();
					$entity->setImportId($inscriptionOrga['id']);
					$entity->setNom($inscriptionOrga['nom']);
					$entity->setPrenom($inscriptionOrga['prenom']);
					$entity->setConfiance($confiance);
					$entity->settelephone($inscriptionOrga['telephone']);
					$entity->setemail($inscriptionOrga['email']);
					$entity->setdepartement($inscriptionOrga['departement']);
					$entity->setcommentaire($inscriptionOrga['commentaire']);
					$entity->setpermis($inscriptionOrga['permis']);
					
					$entity->setDateDeNaissance(new \DateTime($inscriptionOrga['dateDeNaissance']));
					$entity->setSurnom($inscriptionOrga['surnom']);	
					$entity->setStatut(0);			
					
					$validator = $this->get('validator');
					$errors = $validator->validate($entity);
    				
    				
				    if (count($errors) > 0) {
				    	$err =$errors[0];
				    	$errorMessage = $err->getPropertyPath()." ( ".$err->getInvalidValue()." ) : ".    $err->getMessageTemplate();
				    	$simplifiedError = array("erreur" => $errorMessage, "orga" => $err->getRoot()->toArray());
				    	array_push($validationErrors,$simplifiedError);
				    	
				    }else{
				    	foreach($inscriptionOrga['disponibilites'] as $dispoAAjoute)
				    	{
				    		
				    		$entitydisponibilite = new disponibilite();
				    		$entity->addDisponibilite($entitydisponibilite);
				    		$entitydisponibilite->setOrga($entity);
				    		$entitydisponibilite->setDebut(new \DateTime($dispoAAjoute['debut']));
				    		$entitydisponibilite->setFin(new \DateTime($dispoAAjoute['fin']));
				    		var_dump($entitydisponibilite);
				    			
				    	}
				    	$em->persist($entity);
								$em->flush();							
										
				    } 
				
				}
		
		
		return array("entities" => $validationErrors);
		}
	}
	

	 /**
     * Planning Orgas from website.
     *
     * @Route("/{id}/planning", name="orga_planning")
     * @Template
     */
	public function planningAction($id)	
	{
	    if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
	        throw new AccessDeniedException();
	    }
		$em = $this->getDoctrine()->getEntityManager();
		
		$orga = $em->getRepository('PHPMBundle:Orga')->find($id);
		$CreneauxParJour = $em->getRepository('PHPMBundle:Creneau')->getCreneauxParJour($orga);
		
		if (!$orga) {
			throw $this->createNotFoundException('Unable to find Orga entity.');
		}
		else {
       	 	return array('entity' => $orga,'creneauxParJour' => $CreneauxParJour );
			}
	}
	
	/**
	* Lists all Orga entities according to post criteria.
	*
	* @Route("/query.json", name="orga_query_json")
	* @Method("post")
	*/
	public function queryJsonAction()
	{
		// fonction qui permet de selectionner une list d'orga en fonction de certains critères
		$request = $this->getRequest();
		
		//on recupère les paramètres passés en post
		$permis = $request->request->get('permis', '');
		$age = $request->request->get('age', '0');
		$plage_id = $request->request->get('plage_id', '');
		$niveau_confiance = $request->request->get('confiance_id', '');
		$maxDateNaissance = new \DateTime();
		$creneau = $request->request->get('creneau_id', '');
		
		if ($age != '') 
		{ 
			// petite conversion pour changer l'age en date de naissance
			$maxDateNaissance->modify('-'.$age.' year');
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		// on appelle la fonction qui va faire la requête SQL et nous renvoyer le resultat
		$entities = $em->getRepository('PHPMBundle:Orga')->getOrgasWithCriteria($permis, $maxDateNaissance->format('Y-m-d'), $plage_id, $niveau_confiance, $creneau);
		
		$orgaArray = array();
		//création du Json de retour selon le modèle définit dans la spec (cf wiki)
		foreach ($entities as $orga) {
			$a = array();
			foreach ($orga->getDisponibilites() as $dispo)
			{
				if ($dispo->toArrayOrgaWebService() != null)
				{
					$a[$dispo->getId()] = $dispo->toArrayOrgaWebService();
				}			
			}

			$orgaArray[$orga->getId()] = array(
			        	"nom" => $orga->getNom(),
			        	"prenom" => $orga->getPrenom(),
						"confiance" => $orga->getConfiance()->getId(),
// 						"permis"=>$orga->getPermis(),
			    		"dateDeNaissance" => $orga->getDateDeNaissance()->format('Y-m-d H:i:s'),
			    		"departement" => $orga->getDepartement(),
			    		"commentaire" => $orga->getCommentaire(), 	
			        	"disponibilites" => $a
						);
		}
		
    	//exit(var_dump($orgaArray));
    	
    	$response = new Response();
    	$response->setContent(json_encode($orgaArray));
		$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
	}
	
	/**
	 * Print all orga plannings.
	 *
	 * @Route("/plannings/print", name="orga_plannings_impression")
	 * @Template()
	 */
	public function plannings_impressionAction()
	{
	    if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
	        throw new AccessDeniedException();
	    }
	    
	    $em = $this->getDoctrine()->getEntityManager();
	
	    
	    $entities = $em->getRepository('PHPMBundle:Orga')->findAll();
	    
	    return array('entities' => $entities);
	}
	
	
	/**
	 * Charisme .
	 *
	 * @Route("/charisme", name="orga_charisme")
	 * @Template()
	 */
	public function charismeAction()
	{
	    

	    $em = $this->getDoctrine()->getEntityManager();
	
	
	    $data = $em->getRepository('PHPMBundle:Orga')->findAll();
	
	    $departs = $em
	    ->createQuery("SELECT o.departement, count(d) as nbc FROM PHPMBundle:Orga o LEFT OUTER  JOIN o.disponibilitesInscription d GROUP BY o.departement ORDER BY nbc DESC  ")
	    ->getResult();
	    
	
	    $orgas = $em
	    ->createQuery("SELECT o, count(d) as nbc FROM PHPMBundle:Orga o JOIN o.disponibilitesInscription d GROUP BY o.id ORDER BY nbc DESC")
	    ->getResult();
	    
	    $coms = $em
	    ->createQuery("SELECT o.equipe, count(d) as nbc FROM PHPMBundle:Orga o LEFT OUTER  JOIN o.disponibilitesInscription d GROUP BY o.equipe ORDER BY nbc DESC  ")
	    ->getResult();
	
	    return array('orgas' => $orgas,
	                 'departs'=>$departs,
	                 'coms'=>$coms);
	}
	
	public function updateLastActivityAction(){
	    $user = $this->get('security.context')->getToken()->getUser();
	    $user->setLastActivity(new \DateTime());
	    $em = $this->getDoctrine()->getEntityManager();
	    $em->flush();
	    return new Response();
	    
	    
	}
	
	
		
}
