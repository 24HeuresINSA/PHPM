<?php

namespace AssoMaker\BaseBundle\Controller;

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
    
    /**
     * Link login
     *
     * @Route("/autologin/{id}/{loginkey}",requirements={"loginkey" = ".+"}, name="autologin")
     *
     */
    public function autologinAction($id,$loginkey)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$config = $this->get('config.extension');
    	 
    	$user = $em->getRepository('AssoMakerBaseBundle:Orga')->findOneById($id);
    	$secretSalt=$config->getValue('phpm_secret_salt');
    	if ((!$user)||($loginkey!==crypt($secretSalt.$id, 24))) {
    		return $this->redirect($config->getValue('phpm_orgasoft_inscription_returnURL'));
    	}
    	 
    	$this->get('security.context')->setToken($user->generateUserToken());
    	 
    	return $this->redirect($this->generateUrl('base_accueil'));
    	 
    	 
    	 
    	 
    }
    
    
    public function adminLogin()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$config = $this->get('config.extension');
    	$admin = $em->getRepository('AssoMakerBaseBundle:Orga')->findOneById(1);
    	$this->get('security.context')->setToken($admin->generateUserToken());
    	return $this->redirect($this->generateUrl('base_accueil'));
    
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
    	$pref = $em->getRepository('AssoMakerPHPMBundle:Config')->findOneByField('server_baseurl');
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
    
    			$user = $em->getRepository('AssoMakerBaseBundle:Orga')->findOneByEmail($email);
    
    			if (!$user) {
    				$redirectURL = $config->getValue('manifestation_permis_libelles');
    				return $this->redirect($config->getValue('phpm_orgasoft_inscription_returnURL'));
    			}
    
    			$this->get('security.context')->setToken($user->generateUserToken());
    
    
    
    			return $this->redirect($this->generateUrl('base_accueil'));
    		}
    	} catch(ErrorException $e) {
    		exit;
    	}
    
    	 
    	 
    }
    
}
