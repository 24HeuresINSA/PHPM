<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

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
	
    /**
     * @Route("/search.json", name="search_json")
	 * @Method("post")
     */
    public function searchJsonAction()
    {
        $request = $this->getRequest();
		
		$searchString= $request->request->get('s', '');	
		
		$em = $this->getDoctrine()->getEntityManager();

        $orgas = $em->getRepository('PHPMBundle:Orga')->search($searchString);
		$taches = $em->getRepository('PHPMBundle:Tache')->search($searchString);
		
		// on va recopier tous les résultats dans un grand tableau
		$results = array();
		$i = 0;
    	 
    	foreach ($orgas as $orga) {
    		$results[$i] = $orga->toSearchArray();
			$i++;
    	}
		
    	foreach ($taches as $tache) {
    		$results[$i] = $tache->toSearchArray();
			$i++;
    	}
		
    	$response = new Response();
    	$response->setContent(json_encode($results));
    	$response->headers->set('Content-Type', 'application/json');

    	return $response;
    }
}