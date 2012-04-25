<?php

namespace PHPM\Bundle\Controller;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

use PHPM\Bundle\Entity\DisponibiliteInscription;

use PHPM\Bundle\Form\InputDisposType;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Orga;
use PHPM\Bundle\Entity\Disponibilite;
use PHPM\Bundle\Form\OrgaType;
use PHPM\Bundle\Form\OrgaSoftType;





/**
 * Orga controller.
 *
 * @Route("/orga")
 */
class OrgaController extends Controller
{
	 /**
     * Lists all Orga entities.
     *
	 * @Route("/affectation", name="orga_affectation")
     * @Route("/affectation/", name="orga_affectation")
     * @Template()
     */
    public function affectationAction()
    {
     if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Orga')->findAll();

        return array('entities' => $entities);
      
    }
	
	
	
    /**
     * Lists all Orga entities.
     *
     * @Route("/", name="orga")
     * @Template()
     */
    public function indexAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Orga')->findAll();

        return array('entities' => $entities);
    }

 

    /**
     * Displays a form to create a new Orga entity.
     *
     * @Route("/new", name="orga_new")
     * @Template()
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $config = $this->get('config.extension');
        
        $entity = new Orga();
        $form   = $this->createForm(new OrgaType(true, $config), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }
    
    /**
     * Displays a form to create a new Orga Hard entity.
     *
     * @Route("/register/{email}/{confianceCode}/{equipeId}",  defaults={"email"="none","equipeId"="-1"}, name="orga_register")
     * 
     * @Template()
     */
    public function registerAction($email,$confianceCode,$equipeId)
    {
    	
    	
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e=$this->get('config.extension');
        $request = $this->getRequest();
        
        if ($confianceCode=='') {
            throw new AccessDeniedException();
        }
        
        $entity = new Orga();
        $entity->setEmail($email);
        $entity->setStatut(0);
        

        
        $form   = $this->createForm(new OrgaType(false,$config,$confianceCode), $entity);
    
        
        if ($this->get('request')->getMethod() == 'POST') {
        	$form->bindRequest($request);
        	$data = $form->getData();
        	
	        if ($form->isValid()) {
	        	$equipe = $data->getEquipe();
	            $entity->setPrivileges($equipe->getConfiance()->getPrivileges());
	        	
	        	
	            $em->persist($entity);
	            $em->flush();
	            
	            return $this->redirect($this->generateUrl('login'));
	    
	        }
        	
        	
        	
        	
        }
        
        
        
        
        return array(
                'entity' => $entity,
                'form'   => $form->createView(),
        		'confianceCode'=>$confianceCode,
        		'email' =>$email
        );
    }
    
    /**
     * Displays a form to create a new Orga Soft entity.
     *
     * @Route("/inscriptionorgasoft", name="orga_registersoft")
     *
     * @Template()
     */
    public function registerSoftAction(){
    	 
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	$config = $e=$this->get('config.extension');
    	$request = $this->getRequest();
    	
    	$equipeSoft = $em->getRepository('PHPMBundle:Equipe')->find(1);
    	$entity = new Orga();
    	$entity->setStatut(0);
    	$form   = $this->createForm(new OrgaSoftType($config), $entity);
    
//     	$tableauDi = array();
    	
    	
//     	$diResult = $em->createQuery("SELECT d FROM PHPMBundle:DisponibiliteInscription d WHERE d.mission=10 ORDER BY d.debut")
//     	->getResult();
    	
//     	$prevjour='';
//     	foreach ($diResult as $di){
//     		$jour = $di->getDebut()->format('d m');
// //     		if($prevjour !=$jour){
// //     			$tableauDi[$jour]=array(0=>0,2=>0,4=>0,6=>0,8=>0,10=>0,12=>0,14=>0,16=>0,18=>0,20=>0,22=>0);
// //     			$prevjour=$jour;
// //     		}
//     		$heure = $di->getDebut()->format('G');
//     		$tableauDi[$jour][$heure] = $di->getId();
    		 
//     	}
//     	var_dump($tableauDi);
    	
    	
    	
    	
    
    	if ($this->get('request')->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		$data = $form->getData();
    		 
    		 
    		if ($form->isValid()) {
    			$entity->setPrivileges(0);
    			$entity->setEquipe($equipeSoft);
    			$entity->setLastActivity(new \DateTime());
    			$em->persist($entity);
    			$em->flush();
    			 
    			$this->get('security.context')->setToken($entity->generateUserToken());
    			
    			return $this->redirect($this->generateUrl('orga_inputdispos',array('id'=>$entity->getId())));
    			 
    		}
    		 
    		 
    		 
    		 
    	}
    
    
    
    
    	return array(
    			'entity' => $entity,
    			'form'   => $form->createView()
    	);
    }
    
    /**
     * Creates a new Orga entity.
     *
     * @Route("/registerprocess", name="orga_registerprocess")
     * @Method("post")
     * @Template("PHPMBundle:Orga:register.html.twig")
     */
    public function registerprocessAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$e=$this->get('config.extension');
        $entity  = new Orga();
        $request = $this->getRequest();
        $entity->setStatut(0);
        $entity->setPrivileges(1);
        
        $form    = $this->createForm(new OrgaType(false,$config), $entity);
        $form->bindRequest($request);
    
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            
            return $this->redirect($this->generateUrl('login'));
    
        }
    
        return array(
                'entity' => $entity,
                'form'   => $form->createView()
        );
    }

 /**
     * Creates a new Orga entity.
     *
     * @Route("/create", name="orga_create")
     * @Method("post")
     * @Template("PHPMBundle:Orga:new.html.twig")
     */
    public function createAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $config = $e=$this->get('config.extension');
        
        $entity  = new Orga();
        $request = $this->getRequest();
        $form    = $this->createForm(new OrgaType($this->get('security.context')->isGranted('ROLE_ADMIN'),$config), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('orga_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Orga entity.
     *
     * @Route("/{id}/edit", name="orga_edit")
     * @Template()
     */
    public function editAction($id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $config = $e=$this->get('config.extension');
        $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        $confianceCode=$entity->getEquipe()->getConfiance()->getCode();
		
        $editForm = $this->createForm(new OrgaType($this->get('security.context')->isGranted('ROLE_ADMIN'),$config,$confianceCode), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Orga entity.
     *
     * @Route("/{id}/update", name="orga_update")
     * @Method("post")
     * @Template("PHPMBundle:Orga:edit.html.twig")
     */
    public function updateAction($id)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Orga')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }
        
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') && $user = $this->get('security.context')->getToken()->getUser() != $entity) {
            throw new AccessDeniedException();
        }
        
        $confianceCode=$entity->getEquipe()->getConfiance()->getCode();
        $config = $e=$this->get('config.extension');
        $editForm   = $this->createForm(new OrgaType($this->get('security.context')->isGranted('ROLE_ADMIN'),$config,$confianceCode), $entity);
        

        $request = $this->getRequest();

        $editForm->bindRequest($request);
        
        
        
        
        
        
        
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('orga_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Orga entity.
     *
     * @Route("/{id}/delete", name="orga_delete")
     * 
     */
    public function deleteAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
       
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Orga entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('orga'));
    }

    private function createDeleteForm($id)
    {
        
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	

	
	/**
	 * Inscription Hard
	 *
	 * @Route("/{id}/inputdispos", name="orga_inputdispos")
	 * @Template
	 */
	public function inputDisposAction($id)
	{
	    $request = $this->getRequest();
	    $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
	    $em = $this->getDoctrine()->getEntityManager();
	    $orga =  $em->getRepository('PHPMBundle:Orga')->find($id);
	    $user = $this->get('security.context')->getToken()->getUser();

	    if (!$orga) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }
        
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') && $user != $orga) {
            throw new AccessDeniedException();
        }
        $confianceOrga=$orga->getEquipe()->getConfiance()->getValeur();
	    
	    $groupesDIresult = $this->getDoctrine()->getEntityManager()->createQuery("SELECT g FROM PHPMBundle:Mission g WHERE g.confianceMin <= $confianceOrga ORDER BY g.ordre")->getResult();
	    $groupesDI = array();
	     
	    foreach ($groupesDIresult as $entity){
	    	$groupesDI[$entity->getId()]=$entity;
	    }

	    
	    $queryResult = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM PHPMBundle:DisponibiliteInscription d JOIN d.mission m WHERE m.confianceMin <= $confianceOrga ORDER BY d.debut")->getResult();
	    $DIs = array();
	    
	    foreach ($queryResult as $entity){
	    	$DIs[$entity->getMission()->getId()][\IntlDateFormatter::create(null, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT,null,null,'EEEE d MMMM')->format($entity->getDebut())][$entity->getId()]=$entity;
	    }
	    $form = $this->createForm(new InputDisposType($admin));
	    
	    
	
	    if ($request->getMethod() == 'POST') {
	        $form->bindRequest($request);
	        $data=$form->getData();
	        $submittedDI=$data['disponibiliteInscriptionItems'];
	        if ($form->isValid()) {
	        	
	        	$allDI = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM PHPMBundle:DisponibiliteInscription d")->getResult();
	        	
	        	
	            foreach ($allDI as $di)
	            {
	                 
	            	if($submittedDI->contains($di)){
	            		if(!$orga->getDisponibilitesInscription()->contains($di) && ($admin || ($di->getStatut() > 0))){
	            			$orga->addDisponibiliteInscription($di);
	            			$di->addOrga($orga);
	            		}
	            	}else{
	            		if($admin || ($di->getStatut() == 2)){
	            			$orga->getDisponibilitesInscription()->removeElement($di);
	            		}
	            		
	            	}
	            	
	            }
	            $em->flush();
	         	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
	         		//Auto convert DI to dispos for softs
	         		$orga->addDIstoDisponibilites();
	         		$orga->cleanDisponibilites();
	         		$em->flush();
	         		
	         		return $this->redirect($this->generateUrl('orga_thankyou'));
            
	            }
	            
	            $form = $this->createForm(new InputDisposType($admin,$em,$orga));
	        }
	    }
	    
	    return array( 	'form' => $form->createView(),
	    				'entities' => $DIs,
	    				'missions' => $groupesDI,
	    				'orga'=>$orga); 
	}

	
	

	 /**
     * Planning Orgas from website.
     *
     * @Route("/{id}/planning", name="orga_planning")
     * @Template
     */
	public function planningAction($id)	
	{
	    if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
	        throw new AccessDeniedException();
	    }
		$em = $this->getDoctrine()->getEntityManager();
		
		$orga = $em->getRepository('PHPMBundle:Orga')->find($id);
		$CreneauxParJour = $em->getRepository('PHPMBundle:Creneau')->getCreneauxParJour($orga);
		
		if (!$orga) {
			throw $this->createNotFoundException('Unable to find Orga entity.');
		}
		else {
       	 	return array('entity' => $orga,'creneauxParJour' => $CreneauxParJour );
			}
	}
	
	/**
	* Lists all Orga entities according to post criteria.
	*
	* @Route("/query.json", name="orga_query_json")
	* @Method("post")
	*/
	public function queryJsonAction()
	{
		// fonction qui permet de selectionner une list d'orga en fonction de certains critères
		$request = $this->getRequest();
		
		//on recupère les paramètres passés en post
		$permis = $request->request->get('permis', '');
		$age = $request->request->get('age', '');
		$plage_id = $request->request->get('plage_id', '');
		$niveau_confiance = $request->request->get('confiance_id', '');
		$request->request->get('creneau_id', '');
		$maxDateNaissance = new \DateTime();
		$creneau = $request->request->get('creneau_id', '');
		$equipe_id = $request->request->get('equipe_id', '');
		
		
		if ($age != '') 
		{ 
			// petite conversion pour changer l'age en date de naissance max
			$maxDateNaissance->modify('-'.$age.' year');
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		// on appelle la fonction qui va faire la requête SQL et nous renvoyer le resultat
		$entities = $em->getRepository('PHPMBundle:Orga')->getOrgasWithCriteria($permis,$maxDateNaissance->format("Y-m-d H:i:s"), $plage_id, $niveau_confiance, $creneau, $equipe_id);
		
		$orgaArray = array();
		//création du Json de retour selon le modèle définit dans la spec (cf wiki)
		foreach ($entities as $orga) {
			$equipe = $orga->getEquipe();
			
			$dispos = array();
			foreach ($orga->getDisponibilites() as $dispo)
			{
				if ($dispo->toArrayOrgaWebService() != null)
				{
					$dispos[$dispo->getId()] = $dispo->toArrayOrgaWebService();
				}			
			}

			$orgaArray[$orga->getId()] = array(
			        	"nom" => $orga->getNom(),
			        	"prenom" => $orga->getPrenom(),
			        	"surnom" => $orga->getSurnom(),
						"confiance" => $equipe->getConfiance()->getId(),
						"equipe" => $equipe->getId(),
// 						"permis"=>$orga->getPermis(),
			    		"dateDeNaissance" => $orga->getDateDeNaissance()->format('Y-m-d H:i:s'),
			    		"departement" => $orga->getDepartement(),
			    		"commentaire" => $orga->getCommentaire(), 	
			        	"disponibilites" => $dispos
						);
		}
		
    	//exit(var_dump($orgaArray));
    	
    	$response = new Response();
    	$response->setContent(json_encode($orgaArray));
		$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
	}
	
	/**
	 * Print all orga plannings.
	 *
	 * @Route("/plannings/print", name="orga_plannings_impression")
	 * @Template()
	 */
	public function plannings_impressionAction()
	{
	    if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
	        throw new AccessDeniedException();
	    }
	    
	    $em = $this->getDoctrine()->getEntityManager();
	
	    
	    $entities = $em->getRepository('PHPMBundle:Orga')->findAll();
	    
	    return array('entities' => $entities);
	}
	
	
	/**
	 * Charisme .
	 *
	 * @Route("/charisme", name="orga_charisme")
	 * @Template()
	 */
	public function charismeAction()
	{
		if (false === $this->get('security.context')->isGranted('ROLE_VISITOR')) {
			throw new AccessDeniedException();
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		
		$stats = $em->getRepository('PHPMBundle:Orga')->getStats($user);
	
	    $data = $em->getRepository('PHPMBundle:Orga')->findAll();
	
	    $departs = $em
	    ->createQuery("SELECT o.departement, sum(d.pointsCharisme) as pc FROM PHPMBundle:Orga o LEFT JOIN o.disponibilitesInscription d GROUP BY o.departement ORDER BY pc DESC  ")
	    ->getResult();
	    
	
	    $orgas = $em
	    ->createQuery("SELECT o, sum(d.pointsCharisme) as pc FROM PHPMBundle:Orga o JOIN o.disponibilitesInscription d GROUP BY o.id ORDER BY pc DESC")
	    ->getResult();
	    
	    $equipes = $em
	    ->createQuery("SELECT e, sum(d.pointsCharisme) as pc FROM PHPMBundle:Equipe e LEFT JOIN e.orgas o LEFT JOIN o.disponibilitesInscription d GROUP BY e ORDER BY pc DESC  ")
	    ->getResult();
	
	    return array('stats' => $stats,
	    			 'orgas' => $orgas,
	                 'departs'=>$departs,
	                 'equipes'=>$equipes);
	}
	
	public function updateLastActivityAction(){
	    $user = $this->get('security.context')->getToken()->getUser();
	    $user->setLastActivity(new \DateTime());
	    $em = $this->getDoctrine()->getEntityManager();
	    $em->flush();
	    return new Response();
	    
	    
	}
	
	/**
	 * Thank You!
	 *
	 * @Route("/thankyou", name="orga_thankyou")
	 * @Template()
	 */
	public function thankYouAction()
	{
		
		if (false === $this->get('security.context')->isGranted('ROLE_VISITOR')) {
			throw new AccessDeniedException();
		}		
		
		$em = $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();
		$stats = $em->getRepository('PHPMBundle:Orga')->getStats($user);
		
		$message = \Swift_Message::newInstance()
		->setSubject('Inscription orga soft 24 Heures de l\'INSA')
		->setFrom('orga@24heures.org')
		->setReplyTo('orga@24heures.org')
		->setTo($user->getEmail())
 		->setBody($this->renderView('PHPMBundle:Orga:emailConfirmationSoft.html.twig', array('orga' => $user)), 'text/html')
		;
		$this->get('mailer')->send($message);
		
		$this->get('request')->getSession()->invalidate();
		$this->get("security.context")->setToken(new AnonymousToken(null, 'anon'));
		return array('orga'=>$user,'stats' => $stats);
	}
	
	
		
}
