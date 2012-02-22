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
    	$pref = $em->getRepository('PHPMBundle:Config')->findOneByField('phpm.config.initiale');
    	
    	
    	if (!$pref)
    	return $this->redirect($this->generateUrl('config_initiale'));
    	
    	
        return array();
    }
    
    
    /**
     * OpenId login
     *
     * @Route("/login", name="login")
     * @Template()
     *
     */
    public function loginAction()
    {
    
        $message= "";
         
        try {
    
            $openid = new \LightOpenID('localhost');
            if(!$openid->mode) {
                if(isset($_GET['login'])) {
                    $openid->identity = 'https://www.google.com/accounts/o8/id';
                    $openid->required = array('namePerson/friendly', 'contact/email');
                    //header('Location: ' . $openid->authUrl());
    
                    $response = new RedirectResponse($openid->authUrl());
                    //$response->headers->set('Location:' , $openid->authUrl());
    
                    return $response;
    
                }
                return array();
    
            } elseif($openid->mode == 'cancel') {
                $message= 'User has canceled authentication!';
                return array("m"=>$message);
            } else {
                $message= 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
                $attrs = $openid->getAttributes();
                
                $email = $attrs['contact/email'];
                
                $em = $this->getDoctrine()->getEntityManager();
                
                $user = $em->getRepository('PHPMBundle:Orga')->findOneByEmail($email);
                
                if (!$user) {
                    throw $this->createNotFoundException('Unable to find Orga entity.');
                }
                
                
                $token = new UsernamePasswordToken($user, null, 'main', array('ROLE_ADMIN'));
                $this->get('security.context')->setToken($token);
                
                
                

                return array("email"=>$email);
            }
        } catch(ErrorException $e) {
            return array("m"=>$message);
        }
    
         
    return array("m"=>$message);
         
         
    }
    
    
    
}
