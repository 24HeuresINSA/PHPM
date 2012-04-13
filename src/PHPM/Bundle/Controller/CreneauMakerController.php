<?php

namespace PHPM\Bundle\Controller;

use PHPM\Bundle\Entity\Creneau;

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
 * CM controller.
 *
 * @Route("/creneaumaker")
 */
class CreneauMakerController extends Controller
{
    
    
    /**
    * @Route("/", name="creneaumaker")
    * @Template()
    */
    public function homeAction()
    {
    	return array();
    }
    
    /**
     * @Route("/tache/{id}", name="creneaumaker_tache")
     * @Template()
     */
    public function tacheAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$config  =$this->get('config.extension');
    	$admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    	
    	$entity = $em->getRepository('PHPMBundle:Tache')->find($id);
    	
    	if (!$entity) {
    		throw $this->createNotFoundException('Cette tâche n\'existe pas.');
    	}
    	
    	
    	
    	return array(
    			'tache'      => $entity
    	
    	);

    }
    
    
    
    private function genererCreneaux($plageHoraire,$debut,$fin,$em,$validator){
    	foreach ($plageHoraire->getBesoinsOrga() as $besoinOrga){
    			for ($i=0;$i<$besoinOrga->getNbOrgasNecessaires();$i++){
    				$creneau = new Creneau();
    				$creneau->setDebut($debut);
    				$creneau->setFin($fin);
    				$creneau->setPlageHoraire($plageHoraire);
    				$em->persist($creneau);
    			}
    		
    	}
    	if($plageHoraire->getRespNecessaire()){
    		    	$creneau = new Creneau();
    				$creneau->setDebut($debut);
    				$creneau->setFin($fin);
    				$creneau->setPlageHoraire($plageHoraire);
    				$em->persist($creneau);
    	}

    }
    
    /**
     * @Route("/genererph/{id}", name="creneaumaker_genererph")
     */
    public function genererphAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$config  =$this->get('config.extension');
    	$admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    	$validator = $this->get('validator');
    	
    	 
    	$entity = $em->getRepository('PHPMBundle:PlageHoraire')->find($id);
    	 
    	if (!$entity) {
    		throw $this->createNotFoundException('Cette plage horaire n\'existe pas.');
    	}
    	if ($entity->getTache()->getStatut()!=2){
    		throw new Exception("La tâche doit être validée");
    	}
    	// Géneration des créneaux

    	
    	if($entity->getCreneauUnique()){
    		$this->genererCreneaux($entity,$entity->getDebut(),$entity->getFin(),$em, $validator);
    	}else{
    		$duree = $entity->getDureeCreneau() + 1*$entity->getRecoupementCreneau();

    		 
    		$debutCreneau = clone $entity->getDebut();
    		$finCreneau = clone $entity->getDebut();
    		$finCreneau->add(new \DateInterval('PT'.$duree.'S'));
	    		
	    	$creationTerminee = false;
	    	
	    	while ($finCreneau <=$entity->getFin()){
	    	$this->genererCreneaux($entity,$debutCreneau,$finCreneau,$em, $validator);
	    	
	    	if($finCreneau >= $entity->getFin()){
	    		$creationTerminee = true;
	    		var_dump("ok");	
	    	}
	    	$debutCreneau = clone $finCreneau;
	    	$finCreneau = clone $debutCreneau;
	    	$finCreneau->add(new \DateInterval('PT'.$duree.'S'));
	    	
	    	}
	    	
	    	if(!$creationTerminee){
	    		var_dump("complement:");
				$this->genererCreneaux($entity,$debutCreneau,$entity->getFin(),$em, $validator);
	    	}
    	}
    	
    	$em->flush();
//     	exit();
    	
    	
    	
    	
    	 
    	return $this->redirect($this->generateUrl('creneaumaker_tache', array('id' => $entity->getTache()->getId())));
    }
    
    /**
     * @Route("/deleteallph/{id}", name="creneaumaker_deleteallph")
     */
    public function deleteallphAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$config  =$this->get('config.extension');
    	$admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    
    	$entity = $em->getRepository('PHPMBundle:PlageHoraire')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Cette plage horaire n\'existe pas.');
    	}
    	 
    	foreach ($entity->getCreneaux() as $creneau){
    		$em->remove($creneau);
    	}
    	$em->flush();
    	 
    	 
    	 
    	 
    
    	return $this->redirect($this->generateUrl('creneaumaker_tache', array('id' => $entity->getTache()->getId())));
    }


	
}
