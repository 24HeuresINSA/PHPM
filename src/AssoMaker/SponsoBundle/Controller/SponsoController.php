<?php

namespace AssoMaker\SponsoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AssoMaker\SponsoBundle\Entity\Contact;
use AssoMaker\SponsoBundle\Form\ContactType;


/**
 * Sponso controller.
 *
 * @Route("/sponso")
 */
class SponsoController extends Controller
{
    /**
     * @Route("/", name="sponso_home")
     * 
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('sponso_projet_home'));
    }
    
}
