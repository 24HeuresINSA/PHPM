<?php

namespace PHPM\Bundle\Controller;

use Symfony\Component\Validator\Constraints\Url;

use Symfony\Component\Security\Acl\Exception\Exception;

use Symfony\Component\HttpFoundation\Response;
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
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Orga')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Orga entity.
     *
     * @Route("/{id}/show", name="orga_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Orga entity.
     *
     * @Route("/new", name="orga_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Orga();
        $form   = $this->createForm(new OrgaType(), $entity);

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
        $entity  = new Orga();
        $request = $this->getRequest();
        $form    = $this->createForm(new OrgaType(), $entity);
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

        $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        $editForm = $this->createForm(new OrgaType(), $entity);
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

        $editForm   = $this->createForm(new OrgaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('orga_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
	public function validationAction()	
	{
		
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




	
	 /**
     * Import Orgas from website.
     *
     * @Route("/import", name="orga_import")
     * @Template
     */
	public function importAction()	
	{
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
 	
		foreach($listeOrgaArray as  $inscriptionOrga)
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
		
		$request = $this->getRequest();
		
		$permis= $request->request->get('permis', '');
		$age= $request->request->get('age', '0');
		$plage_id= $request->request->get('plage_id', '');
		$niveau_confiance= $request->request->get('confiance_id', '');
		$maxDateNaissance = new \DateTime();
		$id_creneau = $request->request->get('creneau_id', '');
		$bloc = $request->request->get('bloc', '0');
		
		if($age!='')
		$maxDateNaissance->modify('-'.$age.' year');
		
		
		
		$em = $this->getDoctrine()->getEntityManager();
		$entities = $em->getRepository('PHPMBundle:Orga')->getOrgasWithCriteria($permis, $maxDateNaissance->format('Y-m-d'), $plage_id, $niveau_confiance, $id_creneau, $bloc);
		
		$orgaArray = array();
		foreach ($entities as $orga){
			
			$a = array();
			foreach ($orga->getDisponibilites() as $dispo){
				if ($dispo->toArrayOrgaWebService() != null){
					$a[$dispo->getId()] = $dispo->toArrayOrgaWebService();
				}
				
				
			}
			
			
			$orgaArray[$orga->getId()]= array(
			    		
			    	    
			    		
			        	"nom" => $orga->getNom(),
			        	"prenom" => $orga->getPrenom(),
						"confiance" => $orga->getConfiance()->getId(),
						"permis"=>$orga->getPermis(),
			    		"dateDeNaissance" => $orga->getDateDeNaissance()->format('Y-m-d H:i:s'),
			    		"departement" => $orga->getDepartement(),
			    		"commentaire" => $orga->getCommentaire(), 	
			        	"disponibilites" => $a);
			
		
			
			
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
	    $em = $this->getDoctrine()->getEntityManager();
	
	    
	    $entities = $em->getRepository('PHPMBundle:Orga')->findAll();
	    
	    return array('entities' => $entities);
	}
		
}
