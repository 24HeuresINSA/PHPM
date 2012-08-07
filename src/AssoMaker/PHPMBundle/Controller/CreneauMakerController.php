<?php

namespace AssoMaker\PHPMBundle\Controller;

use AssoMaker\PHPMBundle\Entity\Creneau;
use AssoMaker\PHPMBundle\Form\BesoinMaterielType;
use AssoMaker\PHPMBundle\Entity\BesoinMateriel;
use AssoMaker\PHPMBundle\Entity\PlageHoraire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Tache;
use AssoMaker\PHPMBundle\Entity\Commentaire;
use AssoMaker\BaseBundle\Entity\Confiance;
// use AssoMaker\PHPMBundle\Entity\Categorie;
use AssoMaker\PHPMBundle\Form\TacheType;
use AssoMaker\PHPMBundle\Form\TacheBesoinsType;

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
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw new AccessDeniedException();
    	}
    	return array();
    }
    
    /**
     * @Route("/tache/{id}", name="creneaumaker_tache")
     * @Template()
     */
    public function tacheAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw new AccessDeniedException();
    	}
    	$em = $this->getDoctrine()->getEntityManager();
    	$config  =$this->get('config.extension');
    	$admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    	
    	
    	
    	$entity = $em->getRepository('AssoMakerPHPMBundle:Tache')->find($id);
    	
    	if (!$entity) {
    		throw $this->createNotFoundException('Cette tâche n\'existe pas.');
    	}
    	if ($entity->getStatut()<2){
    		throw new \Exception("La tâche doit être validée");
    	}
    	
    	
    	
    	
    	
    	return array(
    			'tache'      => $entity
    	
    	);

    }
    
    
    
    private function genererCreneaux($plageHoraire,$debut,$fin,$em,$validator){
    	foreach ($plageHoraire->getBesoinsOrga() as $besoinOrga){
    		if($besoinOrga->getOrgaHint() == NULL){
    			for ($i=0;$i<$besoinOrga->getNbOrgasNecessaires();$i++){
    				$creneau = new Creneau();
    				$creneau->setDebut($debut);
    				$creneau->setFin($fin);
    				$creneau->setPlageHoraire($plageHoraire);
    				$creneau->setEquipeHint($besoinOrga->getEquipe());
    				$em->persist($creneau);
    			}
    		}else{
    			$creneau = new Creneau();
    			$creneau->setDebut($debut);
    			$creneau->setFin($fin);
    			$creneau->setPlageHoraire($plageHoraire);
    			$creneau->setOrgaHint($besoinOrga->getOrgaHint());
    			$creneau->setEquipeHint($besoinOrga->getOrgaHint()->getEquipe());
    			$em->persist($creneau);
    		}
    		
    	}
    	if($plageHoraire->getRespNecessaire()){
    		    	$creneau = new Creneau();
    				$creneau->setDebut($debut);
    				$creneau->setFin($fin);
    				$creneau->setPlageHoraire($plageHoraire);
    				$creneau->setOrgaHint($plageHoraire->getTache()->getResponsable());
    				$creneau->setEquipeHint($plageHoraire->getTache()->getGroupeTache()->getEquipe());
    				$em->persist($creneau);
    	}

    }
    
    /**
     * @Route("/genererph/{id}", name="creneaumaker_genererph")
     */
    public function genererphAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw new AccessDeniedException();
    	}
    	$em = $this->getDoctrine()->getEntityManager();
    	$config  =$this->get('config.extension');
    	$admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    	$validator = $this->get('validator');
    	
    	 
    	$entity = $em->getRepository('AssoMakerPHPMBundle:PlageHoraire')->find($id);
    	 
    	if (!$entity) {
    		throw $this->createNotFoundException('Cette plage horaire n\'existe pas.');
    	}
    	if ($entity->getTache()->getStatut()<2){
    		throw new \Exception("La tâche doit être validée");
    	}
    	// Géneration des créneaux

    	
    	if($entity->getCreneauUnique()){
    		$this->genererCreneaux($entity,$entity->getDebut(),$entity->getFin(),$em, $validator);
    	}else{
    		$duree = $entity->getDureeCreneau();

    		 
    		$debutCreneau = clone $entity->getDebut();
    		$finCreneau = clone $entity->getDebut();
    		$finCreneau->add(new \DateInterval('PT'.($duree + 1*$entity->getRecoupementCreneau()).'S'));
	    		
	    	$creationTerminee = false;
	    	
	    	while ($finCreneau <=$entity->getFin()){
	    	$this->genererCreneaux($entity,$debutCreneau,$finCreneau,$em, $validator);
	    	
	    	if($finCreneau >= $entity->getFin()){
	    		$creationTerminee = true;
	    		var_dump("ok");	
	    	}
	    	$debutCreneau = clone $debutCreneau;
	    	$debutCreneau->add(new \DateInterval('PT'.($duree).'S'));
	    	$finCreneau = clone $debutCreneau;
	    	$finCreneau->add(new \DateInterval('PT'.($duree + 1*$entity->getRecoupementCreneau()).'S'));
	    	
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
     * Deletes all generated creneaux for one ph
     * @Route("/deleteallph/{id}", name="creneaumaker_deleteallph")
     */
    public function deleteallphAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw new AccessDeniedException();
    	}
    	$em = $this->getDoctrine()->getEntityManager();
    	$config  =$this->get('config.extension');
    	$admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    
    	$entity = $em->getRepository('AssoMakerPHPMBundle:PlageHoraire')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Cette plage horaire n\'existe pas.');
    	}
    	if ($entity->getTache()->getStatut()<2){
    		throw new \Exception("La tâche doit être validée");
    	}
    	 
    	foreach ($entity->getCreneaux() as $creneau){
    		$em->remove($creneau);
    	}
    	$em->flush();
    	 
    	 
    	 
    	 
    
    	return $this->redirect($this->generateUrl('creneaumaker_tache', array('id' => $entity->getTache()->getId())));
    }
    
    /**
     * @Route("/deoktache/{id}", name="creneaumaker_deoktache")
     */
    public function deoktacheAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw new AccessDeniedException();
    	}
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	$config  =$this->get('config.extension');
    	$admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    	
    	$user = $this->get('security.context')->getToken()->getUser();
    
    	$entity = $em->getRepository('AssoMakerPHPMBundle:Tache')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Cette tâche n\'existe pas.');
    	}
    	if ($entity->getStatut()<2){
    		throw new \Exception("La tâche doit être validée");
    	}
    
   	
    	$commentaire = new Commentaire();
    	$commentaire->setAuteur($user);
    	$commentaire->setHeure(new \DateTime());
    	$commentaire->setTache($entity);
    	$commentaire->setTexte('<b>&rarr;Tache validée.</b>');
    	$em->persist($commentaire);
    	
    	$entity->setStatut(2);
    	
    	$em->flush();
    
    
    	return $this->redirect($this->generateUrl('tache_edit', array('id' => $entity->getId())));
    }


	
}
