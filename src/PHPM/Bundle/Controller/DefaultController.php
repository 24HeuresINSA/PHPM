<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * 
     */
    public function indexAction()
    {
    	
        return $this->render("PHPMBundle:Default:index.html.twig");
    }
}
