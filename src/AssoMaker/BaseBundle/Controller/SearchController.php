<?php

namespace AssoMaker\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SearchController extends Controller {

    /**
     * @Route("/search", name="search")
     * @Template()
     * @Method("post")
     */
    public function searchAction() {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $request = $this->getRequest();

        $searchString = $request->request->get('s', '');

        $em = $this->getDoctrine()->getEntityManager();


        $orgas = $em->getRepository('AssoMakerBaseBundle:Orga')->search($searchString);
        $taches = $em->getRepository('AssoMakerPHPMBundle:Tache')->search($searchString);
        $groupe_taches = $em->getRepository('AssoMakerPHPMBundle:GroupeTache')->search($searchString);
        $animations = $em->getRepository('AssoMakerAnimBundle:Animation')->search($searchString);

        return array('searchString' => $searchString, 'orgas' => $orgas, 'taches' => $taches, 'groupe_taches' => $groupe_taches, 'animations' => $animations);
    }

    /**
     * @Route("/search.json", name="search_json")
     * @Method("post")
     */
    public function searchJsonAction() {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $request = $this->getRequest();

        $searchString = $request->request->get('s', '');

        $em = $this->getDoctrine()->getEntityManager();

        $orgas = $em->getRepository('AssoMakerBaseBundle:Orga')->search($searchString);
        $taches = $em->getRepository('AssoMakerPHPMBundle:Tache')->search($searchString);
        $groupe_taches = $em->getRepository('AssoMakerPHPMBundle:GroupeTache')->search($searchString);

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
        foreach ($groupe_taches as $groupe_tache) {
            $results[$i] = $groupe_tache->toSearchArray();
            $i++;
        }

        $response = new Response();
        $response->setContent(json_encode($results));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}

