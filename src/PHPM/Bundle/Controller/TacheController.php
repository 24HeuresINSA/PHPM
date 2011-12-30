<?php

namespace PHPM\Bundle\Controller;

use PHPM\Bundle\Entity\PlageHoraire;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Tache;
use PHPM\Bundle\Form\TacheType;

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

        $entities = $em->getRepository('PHPMBundle:Tache')->findAll();
        
        

        return array('entities' => $entities);
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
     * Finds and displays a Tache entity.
     *
     * @Route("/{id}/show", name="tache_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Tache entity.
     *
     * @Route("/new", name="tache_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tache();
        $form   = $this->createForm(new TacheType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Tache entity.
     *
     * @Route("/create", name="tache_create")
     * @Method("post")
     * @Template("PHPMBundle:Tache:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Tache();
        $request = $this->getRequest();
        $form    = $this->createForm(new TacheType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tache_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
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

        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $editForm = $this->createForm(new TacheType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $editForm   = $this->createForm(new TacheType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tache_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
				$categorie = $em->getRepository('PHPMBundle:Categorie')->findOneById($tache_en_traitement['categorie_id']);
				
				
				$entity  = new tache();
				$entity->setImportId($tache_en_traitement['id']);
				$entity->setNom($tache_en_traitement['nom']);
				$entity->setConsignes($tache_en_traitement['consignes']);
				$entity->setMaterielNecessaire($tache_en_traitement['materielNecessaire']);
				$entity->setPermisNecessaire($tache_en_traitement['permisNecessaire']);
				$entity->setLieu($tache_en_traitement['lieu']);
				$entity->setCategorie( $categorie);
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
		$request = $this->getRequest();
		
		
		
		
		$duree= $request->request->get('duree', '');
		$categorie= $request->request->get('categorie_id', '');
		$permis= $request->request->get('permisNecessaire', '');
		$age= $request->request->get('ageNecessaire', '');
		$id_orga= $request->request->get('id_orga', '');
		$id_plage= $request->request->get('id_plage', '');
		$niveau_confiance= $request->request->get('confiance_id', '');
	
		
		//exit(var_dump($request));
	
		$em = $this->getDoctrine()->getEntityManager();
	
		$entities = $em->getRepository('PHPMBundle:Tache')->getTacheWithCriteria($duree, $categorie, $permis, $age, $id_orga, $id_plage, $niveau_confiance);
	
		//exit(var_dump($entities));
		$response = new Response();
	
		$a = array();
			
		foreach ($entities as $entity){
			$a[$entity->getId()] = $entity->toArray();
	
		}
	
	
	
		$response->setContent(json_encode($a));
	
	
		 
	
		 
	
		return $response;
	}
	

}
