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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Analyse controller.
 *
 * @Route("/analyse")
 */
class AnalyseController extends Controller
{
     
    /**
     * Analyse main page
     *
     * @Route("/", name="analyse")
     * @Template()
     */
    public function analyseAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
    	
    
    	return array(			
    		);
    }
    
    /**
     * Statistiques
     *
     * @Route("/stats", name="analyse_stats")
     * @Template()
     */
    public function statsAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
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
    
    /**
     * Rapport usage Matériel
     *
     * @Route("/usagemateriel", name="analyse_usageMateriel")
     * @Template()
     */
    public function usageMaterielAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
    	    	
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$bmDQL = "SELECT m,bm,t,p,g FROM PHPMBundle:Materiel m JOIN m.besoinsMateriel bm JOIN bm.tache t JOIN t.plagesHoraire p LEFT JOIN t.groupeTache g
    	 WHERE  bm.quantite <> 0 AND t.statut >= 2 ORDER BY p.debut
    	 ";
    	    	
    	
    	$bmResult = $em
    	->createQuery($bmDQL)
    	->getResult();
    	
    
    	return array('bmResult'=>$bmResult);
    }
    
    /**
     * Rapport responsables
     *
     * @Route("/plagesorga/{orgaid}", defaults={"orgaid"="all"}, name="analyse_plagesorga")
     * @Template()
     */
    public function plagesOrgaAction($orgaid)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	if ($orgaid=='all') {
    		$respDQL = "SELECT o,bo,p,t,g FROM PHPMBundle:Orga o
    		JOIN o.besoinsOrgaHint bo  JOIN bo.plageHoraire p JOIN p.tache t JOIN t.groupeTache g  WHERE t.statut >=0
    		ORDER BY o.nom, p.debut ";
    	}else{
    		
    		$respDQL = "SELECT o,bo,p,t,g FROM PHPMBundle:Orga o
    		JOIN o.besoinsOrgaHint bo  JOIN bo.plageHoraire p JOIN p.tache t JOIN t.groupeTache g  WHERE t.statut >=0 AND o.id = $orgaid
    		ORDER BY o.nom, p.debut ";
    	}
    
    	
    	$respResult = $em
    	->createQuery($respDQL)
    	->getArrayResult();
	
    	
//     	var_dump($respResult);
    
    	return array('respResult'=>$respResult);
    }
    
    
    
}
