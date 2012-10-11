<?php

namespace AssoMaker\ComptesPersoBundle\Controller;
use Doctrine\DBAL\Platforms\Keywords\KeywordList;

use AssoMaker\ComptesPersoBundle\Entity\Transaction;
use AssoMaker\ComptesPersoBundle\Form\VirementType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Comptes Perso  controller.
 *
 * @Route("/comptesPerso")
 */
class ComptesPersoController extends Controller {
	    
    /**
     * Lists all Orga comptes
     *
     * @Route("/", name="comptesPerso")
     * @Template()
     */
    public function comptesPersoAction(Request $request) {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $prixConso = $config->getValue('comptes_perso_prix_conso_standard');
    
    
        $consosBuilder = $this->createFormBuilder();
        $builder = $this->createFormBuilder();
        $builder->add('operation', null, array('required' => false));
        $builder->add($consosBuilder);
        $form = $builder->getForm();
    
    
        return array('prixConsoStandard' => $prixConso,
                 'form' => $form->createView());
    }
    
    
	
	/**
	 * Lists all Orga comptes as JSON
	 *
	 * @Route("/comptes.json", name="comptesPersoJSON")
	 * @Method("get")
	 * @Template()
	 */
	public function comptesPersoDataAction(Request $request) {
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
			throw new AccessDeniedException();
		}
		$em = $this->getDoctrine()->getEntityManager();
		
		$entities = $em
		->createQuery("SELECT o FROM AssoMakerBaseBundle:Orga o ORDER BY o.prenom")
		->getResult();
		
		
		$comptesArray = array();
		//création du Json de retour selon le modèle définit dans la spec (cf wiki)
		foreach ($entities as $orga) {

			$orgaArray[] = array(
						"id" => $orga->getId(),
			            "name" => $orga->__toString(),
			        	"balance"=> $em
					    ->getRepository('AssoMakerComptesPersoBundle:Transaction')
					    ->getOrgaBalance($orga->getId())
						);
		}
		
    	//exit(var_dump($orgaArray));
    	
    	$response = new Response();
    	$response->setContent(json_encode($orgaArray));
		$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
	}
	
	/**
	 * Lists all Orga comptes as JSON
	 *
	 * @Route("/process", name="comptesPersoProcess")
	 * @Method("post")
	 * @Template()
	 */
	public function comptesPersoProcessAction(Request $request) {
	    if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
	        throw new AccessDeniedException();
	    }
	    $em = $this->getDoctrine()->getEntityManager();
	    
	    $data=json_decode($request->getContent(),true);
	    $config = $e = $this->get('config.extension');
        $prixConso = $config->getValue('comptes_perso_prix_conso_standard');
	    
	    foreach ($data["alter"] as $id => $conso) {
	        if ($conso != null) {
	            $param = $request->request->all();
	            $orga = $em
	            ->getRepository('AssoMakerBaseBundle:Orga')
	            ->find($id);
	    
	            if ($data['mode'] == 0) {
	                $nbConsos = $data['alter'][$id];
	                $amount = -$prixConso * $nbConsos;
	                $comment = "$nbConsos consommations à $prixConso € = $amount €";
	            } elseif ($data['mode'] == 1) {
	                $operation = $data['operation'];
	                $amount = $data['alter'][$id];
	                $comment = "$operation : $amount €";
	            } else {
	                $this->getResponse()->setStatusCode('400');
	                return;
	            }
	    
	            
	            $transaction = new Transaction($orga, $amount, $comment);
	            $em->persist($transaction);  
	        }
	    }
	    $em->flush();
	    
	    foreach ($data["alter"] as $id => $conso) {
	        $orga = $em
	        ->getRepository('AssoMakerBaseBundle:Orga')
	        ->find($id);
	        if ($conso != null) {
	        $this->checkNegativeAccountAndSendEmail($orga);
	        }
	    }
	
	    
	     
	    return new Response();
	}
	
	

	/**
	 * Virement
	 *
	 * @Route("/virement", name="comptesPerso_virement")
	 * @Template()
	 */
	public function virementAction(Request $request) {
		if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
			throw new AccessDeniedException();
		}
		$em = $this->getDoctrine()->getEntityManager();
		$config = $e = $this->get('config.extension');
		$user = $this->get('security.context')->getToken()->getUser();
		$userId = $user->getId();
		$form   = $this->createForm(new VirementType($config,$userId));
		$soldeCP =  $em->getRepository('AssoMakerComptesPersoBundle:Transaction')->getOrgaBalance($user->getId());
		
		if ($request->getMethod() == 'POST') {
			$form->bind($request);
			if ($form->isValid()) {
				    $data = $form->getData();
				    $destinataire = $data['destinataire'];
				    $amount = $data['montant'];
				    $raison = $data['raison'];
				    
				    $em->persist(new Transaction($destinataire, $amount, "Virement reçu de $user : '$raison'."));
				    $em->persist(new Transaction($user, -$amount, "Virement vers $destinataire : '$raison'."));

				    $em->flush();
				    $this->checkNegativeAccountAndSendEmail($user);
					return $this->redirect($this->generateUrl('base_accueil'));
				}

			

		}

		return array('form' => $form->createView(), 'soldeCP'=>$soldeCP);
	}
	
	public function checkNegativeAccountAndSendEmail($orga){
	    $em = $this->getDoctrine()->getEntityManager();
	    $soldeCP =  $em->getRepository('AssoMakerComptesPersoBundle:Transaction')->getOrgaBalance($orga->getId());
	    if($soldeCP<0){
	        
	        $message = \Swift_Message::newInstance()
    			->setSubject('[Alerte Compte Perso] Ton compte perso est dans le rouge!')
    			->setFrom(array('secretaire.general@24heures.org' => 'SG 24 Heures'))
    			->setReplyTo('secretaire.general@24heures.org')
    			->setTo($orga->getEmail())
    			->setBody($this->renderView('AssoMakerComptesPersoBundle:ComptesPerso:emailNegativeAccount.html.twig', array('orga' => $orga, 'soldeCP'=>$soldeCP)), 'text/html')
    			;
    		
    			
	        $this->get('mailer')->send($message);
	    }
	    
	    
	}
	

}
