<?php

namespace PHPM\Bundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
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
    	
    	if (!$this->get('security.context')->isGranted('ROLE_VISITOR')) {
    		return array();
    	}
    	
    	if (!$this->get('security.context')->isGranted('ROLE_USER')) {

    		$config->getValue('manifestation_permis_libelles');
    	}
    	
    	
    	if ($this->get('security.context')->isGranted('ROLE_VISITOR')) {
    		$statsUser=$em->getRepository('PHPMBundle:Orga')->getStats($user);
    		$statsUser['taches']=$em->getRepository('PHPMBundle:Tache')->getOrgaStats($user);
//     		$conflictingPlages=$em->getRepository('PHPMBundle:PlageHoraire')-> getConflictingPlages($user);

    		   		
    		$debutPlanning = new \DateTime();
    		$finPlanning = new \DateTime($config->getValue('phpm_planning_fin'));
    		$planning=$em->getRepository('PHPMBundle:Orga')->getPlanning($user->getId(),null,$debutPlanning,$finPlanning);
    		
    		$deadlineFT=$config->getValue('phpm_tache_heure_limite_validation');
    			
    		$printPlanningForm = $this->createFormBuilder(array('debut'=>new \DateTime(),'fin'=>new \DateTime()))
			->add('debut','date',array(
								'format' => 'yyyy-MM-dd',
								'widget' => 'single_text',
			                    'label'=>'Début',
			                    'attr'=>array('class'=>'datep')))
			->add('fin','date',array(
								'format' => 'yyyy-MM-dd',
								'widget' => 'single_text',
	                    		'label'=>'Fin',
	                    		'attr'=>array('class'=>'datep')))
            ->add('orga', 'entity', array('label'=>'Orga',  'required'=> false,
        			'class' => 'PHPMBundle:Orga'    			))
        	->add('equipe', 'entity', array('label'=>'Équipe',  'required'=> false,
        					'class' => 'PHPMBundle:Equipe'    			))

    		->getForm();
    		
    		
    		
    		
    		return array('statsOrga'=>$statsUser,
    				'planning'=>$planning,
    				'debutPlanning'=>$debutPlanning,
    				'finPlanning'=>$finPlanning,
    				'deadlineFT'=>$deadlineFT,
    				'printPlanningForm'=>$printPlanningForm->createView()
    				);
    	}
    	

    	
        
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
    	
    	$user = $em->getRepository('PHPMBundle:Orga')->findOneById($id);
    	$secretSalt=$config->getValue('phpm_secret_salt');
    	if ((!$user)||($loginkey!==crypt($secretSalt.$id, 24))) {
    		return $this->redirect($config->getValue('phpm_orgasoft_inscription_returnURL'));
    	}
    	
    	$this->get('security.context')->setToken($user->generateUserToken());
    	
    		return $this->redirect($this->generateUrl('accueil'));
    	
    	
    	
    	
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
    				return $this->redirect($config->getValue('phpm_orgasoft_inscription_returnURL'));
                }
                
                $this->get('security.context')->setToken($user->generateUserToken());
                


                return $this->redirect($this->generateUrl('accueil'));
            }
        } catch(ErrorException $e) {
            exit;
        }
    
         
         
    }
    

    
}
