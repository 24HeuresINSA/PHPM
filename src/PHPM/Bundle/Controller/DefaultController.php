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
    
    
    /**
     * Perform general analysis
     *
     * @Route("/analyse", name="analyse")
     * @Template()
     */
    public function analyseAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw new AccessDeniedException();
    	}
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$tachesResult = $em
    	->createQuery("SELECT e.nom,t.statut,count(t) AS total FROM PHPMBundle:Tache t JOIN t.groupeTache g JOIN g.equipe e GROUP BY g.equipe, t.statut")
    	->getResult();
    	
    	$tachesStats = array();
    	$tachesStatsSum = array("-1"=>0,"0"=>0,"1"=>0,"2"=>0,"3"=>0);
    	
    	
    	foreach ($tachesResult as $i){
    		if(!array_key_exists($i['nom'], $tachesStats)){
    			$tachesStats[$i['nom']]=array("-1"=>0,"0"=>0,"1"=>0,"2"=>0,"3"=>0);
    		}
        
    	$tachesStats[$i['nom']][$i['statut']]=$i['total']	;
    	$tachesStatsSum[$i['statut']]+=$i['total'];
    		
    	}
    	$tachesStats['Total']=$tachesStatsSum;
    	
    	
    	$orgasResult = $em
    	->createQuery("SELECT e.nom as equipe, count(o) as total FROM PHPMBundle:Orga o JOIN o.equipe e GROUP BY e ORDER BY total DESC")
    	->getArrayResult();
    	 
    	$orgasStats = array();
    	    	 
    	$orgasStatsSum=0;
    	foreach ($orgasResult as $i){
    	
    		$orgasStats[$i['equipe']]=$i['total']	;
    		$orgasStatsSum+=$i['total'];
    	}
    	$orgasStats['Total']=$orgasStatsSum;
    
    	return array(	'tachesStats'=>$tachesStats,
    					'orgasStats'=>$orgasStats
    			
    		);
    }
    
    
    
}
