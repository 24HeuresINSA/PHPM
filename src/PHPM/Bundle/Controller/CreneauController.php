<?php

namespace PHPM\Bundle\Controller;

use PHPM\Bundle\Entity\Disponibilite;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Creneau;
use PHPM\Bundle\Form\CreneauType;
use PHPM\Bundle\Validator\QuartHeure;
/**
 * Creneau controller.
 *
 * @Route("/creneau")
 */
class CreneauController extends Controller
{
    /**
     * Lists all Creneau entities.
     *
     * @Route("/", name="creneau")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Creneau')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Creneau entity.
     *
     * @Route("/{id}/show", name="creneau_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Creneau')->find($id);
		


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creneau entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Creneau entity.
     *
     * @Route("/new", name="creneau_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Creneau();
        $form   = $this->createForm(new CreneauType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Creneau entity.
     *
     * @Route("/create", name="creneau_create")
     * @Method("post")
     * @Template("PHPMBundle:Creneau:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Creneau();
        $request = $this->getRequest();
        $form    = $this->createForm(new CreneauType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('creneau_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Creneau entity.
     *
     * @Route("/{id}/edit", name="creneau_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Creneau')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creneau entity.');
        }

        $editForm = $this->createForm(new CreneauType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Creneau entity.
     *
     * @Route("/{id}/update", name="creneau_update")
     * @Method("post")
     * @Template("PHPMBundle:Creneau:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Creneau')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creneau entity.');
        }

        $editForm   = $this->createForm(new CreneauType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('creneaumaker_tache',array('id'=>$entity->getPlageHoraire()->getTache()->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Creneau entity.
     *
     * @Route("/{id}/delete", name="creneau_delete")
     * 
     */
    public function deleteAction($id)
    {
       
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Creneau')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Creneau entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('creneaumaker_tache',array('id'=>$entity->getPlageHoraire()->getTache()->getId())));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
    * Lists all Creneaux entities according to post criteria.
    *
    * @Route("/query.json", name="creneau_query_json")
    * @Method("post")
    */
    public function queryJsonAction() {
    	// fonction qui permet de selectionner une list de creneau en fonction de certains critères
    	$request = $this->getRequest();
    
    	//on recupère les paramètres passés en post
    	$permis = $request->request->get('permis', '');
    	$niveau_confiance = $request->request->get('confiance_id', '');
//     	$categorie = $request->request->get('categorie_id', '');
    	$duree = $request->request->get('duree', '');
    	$orgaId = $request->request->get('orga_id', '');
    	$plage = $request->request->get('plage_id', '');
		$jour = $request->request->get('jour', '');
    	$date_time = $request->request->get('date_time', '');
		
		if ($jour != '') {
			$jour = new \DateTime($jour);
		}
    
    	$em = $this->getDoctrine()->getEntityManager();
    	$entities = $em->getRepository('PHPMBundle:Creneau')->getCreneauxCompatibleWithCriteria($niveau_confiance, $permis, $duree, $orgaId, $plage, $jour, $date_time);
		
		// nécessaire pour la suite, la priorité
    	if ($orgaId != '') {
    		$orga =  $em->createQuery("SELECT o FROM PHPMBundle:Orga o WHERE o.id = $orgaId")->getSingleResult();
    		$equipe = $orga->getEquipe();
    	}
	    			
    	$creneauArray = array();
    	
    	foreach ($entities as $creneau) {
    		// la priorité, faut tester pour déterminer la valeur
    		$priorite = '';
    		if ($creneau->getOrgaHint() != null) {
    			$priorite = 'orga';
    		} else if ($creneau->getEquipeHint() == $equipe) {
    			$priorite = 'equipe';
    		}
    		
    		$creneauArray[$creneau->getId()]= array(
    			        	"nom" => $creneau->getPlageHoraire()->getTache()->getNom(),
    						"lieu" => $creneau->getPlageHoraire()->getTache()->getLieu(),
//  TODO   						"categorie" => $creneau->getPlageHoraire()->getTache()->getCategorie()->getId(),
				    		"debut" => $creneau->getDebut(),
				    		"fin" => $creneau->getFin(),
    			        	"duree" => $creneau->getDuree(),
    			    		"permis_necessaire"=> $creneau->getPlageHoraire()->getTache()->getPermisNecessaire(),
    			    		"confiance"=>$creneau->getEquipeHint()->getConfiance()->getId(),
    			    		"priorite" => $priorite
    			        	);
    	}
		
		usort($creneauArray, function ($a, $b) {
	    		return ($a['priorite'] === 'orga' || $a['confiance'] > $b['confiance']) ? -1 : 1;
			});
		
    	$response = new Response();
    	$response->setContent(json_encode($creneauArray));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }

    /**
    * 
    *
    * @Route("/{cid}/affecter/{oid}", name="creneau_affecter")
    * 
    */
    
    
    public function affecterCreneau($cid, $oid)
    { 
    	//affecter à l'unique dispo qui commence avant et qui fini après
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	$creneau= $em->getRepository('PHPMBundle:Creneau')->find($cid);
    	$response = new Response();
    	
    	
    	if (!$creneau) {
    		$response->setContent(json_encode("Unable to find Creneau entity.")); 
    		return $response;
    	}
		
    	$orga = $em->getRepository('PHPMBundle:Orga')->find($oid);
    	if (!$orga) {
    		$response->setContent(json_encode('Orga invalide.'));
    		return $response;
    	}
    	$dispo = $em->getRepository('PHPMBundle:Disponibilite')->getContainingDisponibilite($orga, $creneau);
    	if (($dispo)==NULL) {
    		$response->setContent(json_encode("L' orga n'est pas disponible sur ce créneau."));
    		return $response;
    	}
    	
    	
    		
    		$creneau->setDisponibilite($dispo);
    		$em->flush();
    		$response->setContent('OK');
    	
    	
    	return $response;
    	
    	
    }
    
    /**
    *
    *
    * @Route("/{cid}/desaffecter/{oid}", name="creneau_desaffecter")
    *
    */
    public function desaffecterCreneau($cid)
    {
    	//on desaffecte un creneau à un orga
    	//à ne pas utiliser, on ne va quand même pas enlever du boulot à quelqu'un.
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	$creneau= $em->getRepository('PHPMBundle:Creneau')->find($cid);
    	$response = new Response();
    	$response->headers->set('Content-Type', 'application/json');
    	
    	if (!$creneau) {
    		$response->setContent(json_encode($err->getMessageTemplate()));
    		return $response;
    	}

    	$creneau->setDisponibilite(null);
    	$em->flush();
    	$response->setContent('OK');
    	return $response;
    	 
    
    	 
    	 
    }
    
    
    /**
    *
    *
    * @Route("/diviser.json", name="creneau_diviser")
    * @Template
    * @Method("post")
    */
    //On passe en post un creneau_id et un date_time
    public function diviserAction()
    { // permet de couper un créneau en 2
    	$em = $this->getDoctrine()->getEntityManager();
    	$request = $this->getRequest();
    	$creneau_id= $request->request->get('creneau_id', '');
    	$creneau= $em->getRepository('PHPMBundle:Creneau')->find($creneau_id);
    	$date_times= $request->request->get('date_time', '');
		$date_time = new \DateTime($date_times);
    	//var_dump($date_time);
    	//TODO remplacer les tgc par les erreurs ad hoc
		if (!$creneau)
		{
			return array('response'=>'tgc');
		}
		if (!$date_time)
		{
			return array('response'=>'tgc');
		}
//     	On vérifie que l'heure est bien incluse dans le créneau
    	if ($date_time < $creneau->getDebut() || $date_time > $creneau->getFin())
    	{
			return array('response'=>'tgc');
    	}
    	else
    	{
//     	=>crée un créneau B possédant les memes propriétés que le créneau A, mais commençant à l'heure fournie
//     	=>met la fin du créneau A à l'heure fournie
    		$nouveauCreneau = new Creneau(); 
    		$nouveauCreneau->setDebut($date_time);
    		$nouveauCreneau->setFin($creneau->getFin());
    		$nouveauCreneau->setDisponibilite($creneau->getDisponibilite());
    		$nouveauCreneau->setPlageHoraire($creneau->getPlageHoraire());
    		
    		$creneau->setFin($date_time);
			
    		$em->persist($nouveauCreneau);
    		$em->flush();
    		return array('response'=>'cay cool');
    	}

    
    }
    
    
}



