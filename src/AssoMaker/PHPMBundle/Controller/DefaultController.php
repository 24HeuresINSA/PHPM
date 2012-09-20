<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Config;
use AssoMaker\PHPMBundle\Form\PrintPlanningType;


class DefaultController extends Controller
{
    /**
     * @Route("/phpm", name="phpm_accueil")
	 * @Route("/phpm")
     * @Template()
     */
    public function homeAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$config = $this->get('config.extension');
    	

    	
    	$user=$this->get('security.context')->getToken()->getUser();
    	
    	if (!$this->get('security.context')->isGranted('ROLE_VISITOR')) {
    		if ($config->getValue('phpm_admin_login')==1){
    			return $this->adminLogin();
    		}
    		return array();
    	}
    	
    	if (!$this->get('security.context')->isGranted('ROLE_USER')) {

    		$config->getValue('manifestation_permis_libelles');
    	}
    	
    	
    	if ($this->get('security.context')->isGranted('ROLE_VISITOR')) {
    		$statsUser=$em->getRepository('AssoMakerBaseBundle:Orga')->getStats($user);
    		$statsUser['taches']=$em->getRepository('AssoMakerPHPMBundle:Tache')->getOrgaStats($user);
//     		$conflictingPlages=$em->getRepository('AssoMakerPHPMBundle:PlageHoraire')-> getConflictingPlages($user);

    		   		
    		$debutPlanning = new \DateTime();
    		$finPlanning = new \DateTime($config->getValue('phpm_planning_fin'));
    		$planning=$em->getRepository('AssoMakerBaseBundle:Orga')->getPlanning($user->getId(),null,$debutPlanning,$finPlanning);
    		
    		$deadlineFT=$config->getValue('phpm_tache_heure_limite_validation');
    			
    		$printPlanningForm = $this->createForm(new PrintPlanningType(), array('debut'=>new \DateTime(),'fin'=>new \DateTime()));
    		
    		
    		
    		
    		return array('statsOrga'=>$statsUser,
    				'planning'=>$planning,
    				'debutPlanning'=>$debutPlanning,
    				'finPlanning'=>$finPlanning,
    				'deadlineFT'=>$deadlineFT,
    				'printPlanningForm'=>$printPlanningForm->createView()
    				);
    	}
    	

    	
        
    }
    
    

    
}
