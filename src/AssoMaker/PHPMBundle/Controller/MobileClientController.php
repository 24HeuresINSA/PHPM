<?php

namespace AssoMaker\PHPMBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use AssoMaker\PHPMBundle\Entity\MobileClient;
 
 
/**
 * MobileClient controller.
 *
 * @Route("/mobileclient")
 */
class MobileClientController extends Controller
{
    /**
     * Pings all MobileClients.
     *
     * @Route("/ping", name="mobileclient_ping")
     */
    public function pingAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery("SELECT m.gcm_regid FROM AssoMakerPHPMBundle:MobileClient m");
        $devices = $query->getArrayResult();
         
        $gcm = $this->get('endroid.gcm');
        $data = array(
            'title' => 'a',
            'message' => 'b'
        );

        $chunks = array_chunk($devices,950);
        
        $results = array();
        
        foreach ( $chunks as $chunk ) {
            $d = array();
            foreach ($chunk as $key => $value) {
                $d[] = $value['gcm_regid'];
            }
            $results[] = $gcm->send($data, $d);
        }
        $response = new Response(json_encode($results));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/", name="mobileclient_add")
     * @Method({"POST"})
     */
    public function newAction() {
        $request = $this->getRequest();
        $gcm_regid = $request->request->get('regid', '');
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:MobileClient')->findBy(array('gcm_regid' => $gcm_regid));
        if (!$entity) {
            $entity  = new MobileClient();
            $entity->setGcm_regid($gcm_regid);
            $em->persist($entity);
            $em->flush();
        }

        $response = new Response();
        $response->setContent(json_encode("ok"));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
        
}
