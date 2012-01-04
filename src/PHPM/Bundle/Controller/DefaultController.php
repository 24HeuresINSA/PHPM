<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Config;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function homeAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$pref = $em->getRepository('PHPMBundle:Config')->findOneByField('phpm.config.initiale');
    	
    	
    	if (!$pref)
    	return $this->redirect($this->generateUrl('config_initiale'));
    	
    	
        return array();
    }
}
