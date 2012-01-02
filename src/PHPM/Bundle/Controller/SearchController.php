<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     * @Template()
	 * @Method("post")
     */
    public function searchAction()
    {
        $request = $this->getRequest();
		
		$searchString= $request->request->get('s', '');	
		
		$em = $this->getDoctrine()->getEntityManager();

        $orgas = $em->getRepository('PHPMBundle:Orga')->search($searchString);
		$taches = $em->getRepository('PHPMBundle:Tache')->search($searchString);

		
        return array('searchString' => $searchString, 'orgas' => $orgas, 'taches' => $taches);
    }
}
