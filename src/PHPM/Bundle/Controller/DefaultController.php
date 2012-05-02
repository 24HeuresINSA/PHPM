<?php

namespace PHPM\Bundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Config;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="accueil")
	 * @Route("/")
     * @Template()
     */
    public function homeAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$pref = $em->getRepository('PHPMBundle:Config')->findOneByField('phpm_config_initiale');
    	$config = $this->get('config.extension');
    	if (!$pref){
    	return $this->redirect($this->generateUrl('config_initiale'));
    	}
    	
    	$user=$this->get('security.context')->getToken()->getUser();
    	
    	if ($this->get('security.context')->isGranted('ROLE_VISITOR')) {
    		$statsUser=$em->getRepository('PHPMBundle:Orga')->getStats($user);
    		$statsUser['taches']=$em->getRepository('PHPMBundle:Tache')->getOrgaStats($user);
//     		$conflictingPlages=$em->getRepository('PHPMBundle:PlageHoraire')-> getConflictingPlages($user);

    		$config = $e=$this->get('config.extension');    		
    		$debutPlanning = new \DateTime($config->getValue('phpm_planning_debut'));
    		$finPlanning = new \DateTime($config->getValue('phpm_planning_fin'));
    		$planning=$em->getRepository('PHPMBundle:Orga')->getPlanning($user->getId(),$debutPlanning,$finPlanning);
    		
    		return array('statsOrga'=>$statsUser,
    				'planning'=>$planning
    				);
    	}
    	return array();

    	if (!$this->get('security.context')->isGranted('ROLE_USER') && $this->get('security.context')->isGranted('ROLE_VISITOR')) {
    		$redirectURL = $config->getValue('manifestation_permis_libelles');
    		return $this->redirect('http://www.24heures.org/orga');
    	}
    	
        
    }
    
    
    /**
     * OpenId login
     *
     * @Route("/login/{registered}",defaults={"registered"=""}, name="login")
     * @Template()
     *
     */
    public function loginAction($registered)
    {
    
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
    	$pref = $em->getRepository('PHPMBundle:Config')->findOneByField('server_baseurl');
    	if($pref)
    	    $serverurl = $pref->getvalue();
    	else 
    	    $serverurl = 'localhost';
         
        try {
            
    
            $openid = new \LightOpenID($serverurl);
            if(!$openid->mode) {
//                 if(isset($_GET['login'])) {
                    $openid->identity = 'https://www.google.com/accounts/o8/id';
                    $openid->required = array('namePerson/friendly', 'contact/email');
                    //header('Location: ' . $openid->authUrl());
    
                    $response = new RedirectResponse($openid->authUrl());
                    //$response->headers->set('Location:' , $openid->authUrl());
    
                    return $response;
    
//                 }
                return array("registered"=>$registered);
    
            } elseif($openid->mode == 'cancel') {
                exit;
            } else {
//                 $message= 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
                $attrs = $openid->getAttributes();
                
                $email = $attrs['contact/email'];
                
                $em = $this->getDoctrine()->getEntityManager();
                
                $user = $em->getRepository('PHPMBundle:Orga')->findOneByEmail($email);
                
                if (!$user) {
                    $redirectURL = $config->getValue('manifestation_permis_libelles');
    				return $this->redirect('http://www.24heures.org/orga');
                }
                
                $this->get('security.context')->setToken($user->generateUserToken());
                


                return $this->redirect($this->generateUrl('accueil'));
            }
        } catch(ErrorException $e) {
            exit;
        }
    
         
         
    }
    

    
}
