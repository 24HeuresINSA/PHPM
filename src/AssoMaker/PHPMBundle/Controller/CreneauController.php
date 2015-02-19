<?php

namespace AssoMaker\PHPMBundle\Controller;

use AssoMaker\PHPMBundle\Entity\Disponibilite;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AssoMaker\PHPMBundle\Entity\Creneau;
use AssoMaker\PHPMBundle\Form\CreneauType;
use AssoMaker\PHPMBundle\Validator\QuartHeure;
/**
 * Creneau controller.
 *
 * @Route("/creneau")
 */
class CreneauController extends Controller
{

    /**
     * Deletes a Creneau entity.
     *
     * @Route("/{id}/delete", name="creneau_delete")
     * 
     */
    public function deleteAction($id)
    {
       
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AssoMakerPHPMBundle:Creneau')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Creneau entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('creneaumaker_tache',array('id'=>$entity->getPlageHoraire()->getTache()->getId())));
    }

    /**
    * List all Creneaux entities according to post criteria.
    *
    * @Route("/query.json", name="creneau_query_json")
    * @Method("post")
    */
    public function queryJsonAction() {
    	// fonction qui permet de selectionner une list de creneau en fonction de certains critères
    	$request = $this->getRequest();
    
    	//on recupère les paramètres passés en post
    	$niveau_confiance = $request->request->get('confiance_id', '');
		$permis = $request->request->get('permis', '');
     	$equipe_id = $request->request->get('equipe_id', '');
    	$duree = $request->request->get('duree', '');
    	$orga_id = $request->request->get('orga_id', '');
    	$plage = $request->request->get('plage_id', '');
		$jour = $request->request->get('jour', '');
    	$date_time = $request->request->get('date_time', '');
		
		if ($jour !== '') {
			$jour = new \DateTime($jour);
		}
    
    	$em = $this->getDoctrine()->getEntityManager();
    	$entities = $em->getRepository('AssoMakerPHPMBundle:Creneau')->getCreneauxCompatibleWithCriteria($niveau_confiance, $permis, $equipe_id, $duree, $orga_id, $plage, $jour, $date_time);
		
		// nécessaire pour la suite, la priorité
    	if ($orga_id !== '') {
    		$orga =  $em->createQuery("SELECT o FROM AssoMakerBaseBundle:Orga o WHERE o.id = $orga_id")->getSingleResult();
    		$equipe_orga = $orga->getEquipe();
    	}
	    			
    	$creneauArray = array();
    	
    	foreach ($entities as $creneau) {
    		// la priorité, faut tester pour déterminer la valeur
    		$priorite = '';
    		if ($creneau->getOrgaHint() != null) {
    			$priorite = 'orga';
    		} else if (isset($equipe_orga) && $creneau->getEquipeHint() === $equipe_orga) {
    			$priorite = 'equipe';
    		}
			
			$equipe = $creneau->getEquipeHint();
			$tache = $creneau->getPlageHoraire()->getTache();
    		
    		$creneauArray[]= array(
    						"id" => $creneau->getId(),
    						"tache_id" => $tache->getId(),
    			        	"nom" => $tache->getNom(),
    						"lieu" => $tache->getLieu(),
	   						"equipe" => $equipe->getId(),
	   						"equipe_couleur" => $tache->getGroupeTache()->getEquipe()->getCouleur(),
				    		"debut" => $creneau->getDebut(),
				    		"fin" => $creneau->getFin(),
    			        	"duree" => $creneau->getDuree(),
    			    		"permis_necessaire" => $tache->getPermisNecessaire(),
    			    		"confiance" =>$equipe->getConfiance()->getId(),
    			    		"priorite" => $priorite
    			        	);
    	}

    	$response = new Response();
    	$response->setContent(json_encode($creneauArray));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }

	/**
	* Lists all creneaux of a Tache entity
	*
	* @Route("/query_tache_creneaux.json", name="tache_creneaux_query_json")
	* @Method("post")
	*/
	public function queryCreneauxJsonAction()
	{
		$request = $this->getRequest();
		
		// on recupère les paramètres passés en post
		$tache_id = $request->request->get('tache_id', '');
		$plage_id = $request->request->get('plage_id', '');
		
		$em = $this->getDoctrine()->getEntityManager();
		// on appelle la fonction qui va faire la requête SQL et nous renvoyer le resultat
		$entities = $em->getRepository('AssoMakerPHPMBundle:Creneau')->getTacheCreneau($tache_id, $plage_id);
		
		// magie, on a rien à faire comme mise en forme !
		
    	$response = new Response();
    	$response->setContent(json_encode($entities));
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
    	$creneau= $em->getRepository('AssoMakerPHPMBundle:Creneau')->find($cid);
    	$response = new Response();
    	
    	
    	if (!$creneau) {
    		$response->setContent(json_encode("Unable to find Creneau entity.")); 
    		return $response;
    	}
		
    	$orga = $em->getRepository('AssoMakerBaseBundle:Orga')->find($oid);
    	if (!$orga) {
    		$response->setContent(json_encode('Orga invalide.'));
    		return $response;
    	}
    	$dispo = $em->getRepository('AssoMakerPHPMBundle:Disponibilite')->getContainingDisponibilite($orga, $creneau);
    	if (count($dispo)==0) {
    		$response->setContent(json_encode("L' orga n'est pas disponible sur ce créneau."));
    		return $response;
    	}
    	
    	
    		
    		$creneau->setDisponibilite($dispo[0]);
    		$em->flush();
    		$response->setContent('OK');
    	
    	
    	return $response;
    	
    	
    }
    /**
    *
    *
    * @Route("/{cid}/desaffecter{_format}/{oid}", defaults={"oid"="0","_format"=".html"},requirements={"_format"=".html|"}, name="creneau_desaffecter")
    *
    */
    public function desaffecterCreneau($cid,$_format)
    {
    	//on desaffecte un creneau à un orga
    	//à ne pas utiliser, on ne va quand même pas enlever du boulot à quelqu'un.
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	$creneau= $em->getRepository('AssoMakerPHPMBundle:Creneau')->find($cid);
    	$response = new Response();
    	$response->headers->set('Content-Type', 'application/json');
    	
    	if (!$creneau) {
    		$response->setContent('KO');
    		return $response;
    	}
    	$orga=$creneau->getDisponibilite()->getOrga();
    	
    	if ($orga->getStatut() === 2) {
    		$orga->setStatut(1);
    	}

    	$creneau->setDisponibilite(null);
    	$em->flush();
    	if($_format==".html"){
    		return $this->redirect($this->getRequest()->headers->get('referer'));
    		
    	}else{
    	$response->setContent('OK');
    	return $response;
    	}
    	 
    	 
    	 
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
    	$creneau= $em->getRepository('AssoMakerPHPMBundle:Creneau')->find($creneau_id);
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



