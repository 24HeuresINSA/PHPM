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
    	$logged= $this->get('security.context')->isGranted('ROLE_USER');
    	
    	if (!$pref){
    	return $this->redirect($this->generateUrl('config_initiale'));
    	}
    	
//     	if (!$logged){
//     	return $this->redirect($this->generateUrl('login'));
//     	}
    	
    	
    	
    	
        return array();
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
                return array("m"=>'error');
            } else {
//                 $message= 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
                $attrs = $openid->getAttributes();
                
                $email = $attrs['contact/email'];
                
                $em = $this->getDoctrine()->getEntityManager();
                
                $user = $em->getRepository('PHPMBundle:Orga')->findOneByEmail($email);
                
                if (!$user) {
                    return array('m'=>'notfound', 'email'=>$email);
                }
                
                
                
                if($user->getPrivileges()==2)
                {
                    $options = array('ROLE_ADMIN');
                }
                elseif($user->getPrivileges()==1)
                {
                    $options = array('ROLE_USER');
                }
                elseif($user->getPrivileges()==0)
                {
                	$options = array('ROLE_VISITOR');
                }
                
                
                $token = new UsernamePasswordToken($user, null, 'main', $options);
                $this->get('security.context')->setToken($token);
                
                
                

                return array('m'=>'success', 'email'=>$email);
            }
        } catch(ErrorException $e) {
            return array("m"=>'error');
        }
    
         
    return array("m"=>'error');
         
         
    }
    
}
