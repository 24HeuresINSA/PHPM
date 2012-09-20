<?php

namespace AssoMaker\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
	
/**
     * @Route("/", name="base_accueil")
	 * @Route("/")
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
    
    	
    	   	
    	return array();
    	
        
    }
    
}
