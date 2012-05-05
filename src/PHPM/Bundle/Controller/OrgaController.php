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
use PHPM\Bundle\Entity\BesoinOrga;




/**
 * Orga controller.
 *
 * @Route("/orga")
 */
class OrgaController extends Controller
{
	 /**
     * 
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
     * @Route("/index/{statut}/{confiance}",defaults={"statut"="1", "confiance"="all"}, name="orga")
     * @Template()
     */
    public function indexAction($statut, $confiance)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();

        $confiances =$em
        ->createQuery("SELECT c FROM PHPMBundle:Confiance c")
        ->getResult();
        
        
        $orgasDQL = "SELECT o,e,d FROM PHPMBundle:Orga o JOIN o.equipe e JOIN e.confiance c LEFT JOIN o.disponibilites d WHERE o.statut = $statut ";
        
        if ($confiance !='all'){
        	$orgasDQL .= " AND c.id = $confiance";
        }
        $orgasDQL.="ORDER BY o.lastActivity DESC, d.debut";
        
        $entities =$em
        ->createQuery($orgasDQL)
        ->getResult();
        

        return array('confiances' => $confiances, 
        			'entities' => $entities);
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
     * @Route("/inscriptionorgasoft/{equipeId}/{confianceCode}/{libelle}", defaults={"libelle"="", "confianceCode"="","equipeId"="1"}, name="orga_registersoft")
     *
     * @Template()
     */
    public function registerSoftAction($confianceCode,$equipeId){
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	$config = $e=$this->get('config.extension');
    	$request = $this->getRequest();
    	
    	$equipe = $em->getRepository('PHPMBundle:Equipe')->find($equipeId);
    	
    	if (!$equipe || $equipe->getConfiance()->getCode() !=$confianceCode) {
    		throw new AccessDeniedException();
    	}
    	
    	$entity = new Orga();
    	$entity->setStatut(0);
    	$entity->setEquipe($equipe);
    	$form   = $this->createForm(new OrgaSoftType($config), $entity);
    	
    
    
    	if ($this->get('request')->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		$data = $form->getData();
    		 
    		 
    		if ($form->isValid()) {
    			$entity->setPrivileges($equipe->getConfiance()->getPrivileges());
    			$entity->setEquipe($equipe);
    			$entity->setLastActivity(new \DateTime());
    			$em->persist($entity);
    			$em->flush();
    			 
    			$this->get('security.context')->setToken($entity->generateUserToken());
    			
    			return $this->redirect($this->generateUrl('orga_inputdispos',array('id'=>$entity->getId(),"new"=>true)));
    			 
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
        $config = $this->get('config.extension');
        
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
        
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') && $user = $this->get('security.context')->getToken()->getUser() != $entity) {
        	throw new AccessDeniedException();
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
        

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
    
    
    /**
    * Change le statut d'un orga
    *
    * @Route("/{id}/statut/{statut}/", name="orga_change_statut")
    *
    */
    public function changeStatutAction($id, $statut)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw new AccessDeniedException();
    	}
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	$entity = $em->getRepository('PHPMBundle:Orga')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Orga entity.');
    	}
    	
    	$entity->setStatut($statut);
    	
    	$em->flush();
    
    
    	return $this->redirect($this->getRequest()->headers->get('referer'));
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
	    $em = $this->getDoctrine()->getEntityManager();
	    $orga =  $em->getRepository('PHPMBundle:Orga')->find($id);
	    $user = $this->get('security.context')->getToken()->getUser();
	    $config = $e=$this->get('config.extension');

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
	    	
	    	$fmt =  new \IntlDateFormatter(null ,\IntlDateFormatter::FULL, \IntlDateFormatter::FULL,    null,null,'EEEE d MMMM'  );
	    	$DIs[$entity->getMission()->getId()][$fmt->format($entity->getDebut()->getTimestamp())][$entity->getId()]=$entity;
	    }
	    $form = $this->createForm(new InputDisposType());
	    
	    
	
	    if ($request->getMethod() == 'POST') {
	        $form->bindRequest($request);
	        $data=$form->getData();
	        $submittedDI=$data['disponibiliteInscriptionItems'];
	        
	        if ($form->isValid()) {
	        	
	        	$allDI = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM PHPMBundle:DisponibiliteInscription d")->getResult();
	        	
	        	$minCharisme = $config->getValue('manifestation_charisme_minimum');
	        	
	        	$totalCharisme = 0;
	        	
	        	foreach ($submittedDI as $di)
	            {
	               $totalCharisme+=$di->getPointsCharisme();
	            }
	            $totalCharisme+=$orga->getCharisme();
	            
	            if ($totalCharisme<$minCharisme){
	            	return array( 'form' => $form->createView(),
	            			'entities' => $DIs,
	            			'missions' => $groupesDI,
	            			'orga'=>$orga,
	            			'charismeInsuffisant'=>true
	            			);
	            }

	            foreach ($allDI as $di)
	            {
	                 
	            	if($submittedDI->contains($di)){
	            		if(!$orga->getDisponibilitesInscription()->contains($di) && ($this->get('security.context')->isGranted('ROLE_ADMIN') || ($di->getStatut() > 0))){
	            			$orga->addDisponibiliteInscription($di);
	            			$di->addOrga($orga);
	            		}
	            	}
	            	
	            }
	            $orga->cleanDisponibilites();
	            $em->flush();
	         	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {

	         		return $this->redirect($this->generateUrl('orga_thankyou'));
            
	            }
	            
	            $form = $this->createForm(new InputDisposType());
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
		$annee_permis = $request->request->get('annee_permis', '');
		$age = $request->request->get('age', '');
		$plage_id = $request->request->get('plage_id', '');
		$niveau_confiance = $request->request->get('confiance_id', '');
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
		$entities = $em->getRepository('PHPMBundle:Orga')->getOrgasWithCriteria($annee_permis, $maxDateNaissance->format("Y-m-d H:i:s"), $plage_id, $niveau_confiance, $creneau, $equipe_id);
		
		$orgaArray = array();
		//création du Json de retour selon le modèle définit dans la spec (cf wiki)
		foreach ($entities as $orga) {
			$equipe = $orga[0]->getEquipe();
			
			$dispos = array();
			foreach ($orga[0]->getDisponibilites() as $dispo)
			{
				if ($dispo->toArrayOrgaWebService() != null)
				{
					$dispos[$dispo->getId()] = $dispo->toArrayOrgaWebService();
				}			
			}

			$orgaArray[] = array(
						"id" => $orga[0]->getId(),
			        	"nom" => $orga[0]->getNom(),
			        	"prenom" => $orga[0]->getPrenom(),
			        	"surnom" => $orga[0]->getSurnom(),
						"confiance" => $equipe->getConfiance()->getId(),
						"charisme" => $orga['charisme'],
						"equipe" => $equipe->getId(),
// 						"permis"=>$orga->getPermis(),
			    		"dateDeNaissance" => $orga[0]->getDateDeNaissance()->format('Y-m-d H:i:s'),
			    		"departement" => $orga[0]->getDepartement(),
			    		"commentaire" => $orga[0]->getCommentaire(), 	
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
	 * Print orga planning.
	 *
	 * @Route("/{orgaid}/print",  defaults={"orgaid"="all"}, name="orga_print")
	 * @Template()
	 */
	public function printAction($orgaid)
	{
	    $em = $this->getDoctrine()->getEntityManager();
        $config = $e=$this->get('config.extension');
        
        $debut = new \DateTime();
        $fin = new \DateTime($config->getValue('phpm_planning_fin'));
        
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') && $user = $this->get('security.context')->getToken()->getUser()->getId() != $orga->getId()) {
        	throw new AccessDeniedException();
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $orgas = $em->getRepository('PHPMBundle:Orga')-> getPlanning($orgaid,$debut,$fin);
             
	    
	    return array('orgas' => $orgas,'debut'=>$debut, 'fin'=>$fin
	    		);
	}
	
	
	/**
	 * iCal orga planning.
	 *
	 * @Route("/{orgaid}/planning.ics",  name="orga_ical")
	 * @Template()
	 */
	public function iCalAction($orgaid)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$config = $e=$this->get('config.extension');
	
		$debut = new \DateTime();
		$fin = new \DateTime($config->getValue('phpm_planning_fin'));
	
		$em = $this->getDoctrine()->getEntityManager();
		$orgas = $em->getRepository('PHPMBundle:Orga')-> getPlanning($orgaid,$debut,$fin);
		 
		 
		return array('orga' => $orgas[0],'debut'=>$debut, 'fin'=>$fin
		);
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
	    ->createQuery("SELECT o.departement, sum(d.pointsCharisme) as pc FROM PHPMBundle:Orga o LEFT JOIN o.disponibilitesInscription d  WHERE o.statut >=0 GROUP BY o.departement ORDER BY pc DESC  ")
	    ->getResult();
	    
	
	    $orgas = $em
	    ->createQuery("SELECT o, sum(d.pointsCharisme) as pc FROM PHPMBundle:Orga o JOIN o.disponibilitesInscription d  WHERE o.statut >=0 GROUP BY o.id ORDER BY pc DESC")
	    ->getResult();
	    
	    $equipes = $em
	    ->createQuery("SELECT e, sum(d.pointsCharisme) as pc FROM PHPMBundle:Equipe e LEFT JOIN e.orgas o LEFT JOIN o.disponibilitesInscription d  WHERE o.statut >=0 GROUP BY e ORDER BY pc DESC  ")
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
		->setFrom(array('orga@24heures.org' => 'Orga 24H INSA'))
		->setReplyTo('orga@24heures.org')
		->setTo($user->getEmail())
 		->setBody($this->renderView('PHPMBundle:Orga:emailConfirmationSoft.html.twig', array('orga' => $user)), 'text/html')
		;
		$this->get('mailer')->send($message);
		
		$this->get('request')->getSession()->invalidate();
		$this->get("security.context")->setToken(new AnonymousToken(null, 'anon'));
		return array('orga'=>$user,'stats' => $stats);
	}
	
	/**
	 * Manually generate DI -> Dispo
	 *
	 * @Route("/{id}/genDispo", name="orga_gendispo")
	 */
	public function genDispoAction($id)
	{
	
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
			throw new AccessDeniedException();
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		
// 		if($id = "all"){
// 			$orgas = $em->createQuery("SELECT o FROM PHPMBundle:Orga o")->getResult();
			
// 		}else
		{
		$orgas = $em->getRepository('PHPMBundle:Orga')->find($id);
		
		if (!$orgas ) {
			throw $this->createNotFoundException('Unable to find Orga.');
		}
		
		}
		 
		$allDI = $em->createQuery("SELECT d FROM PHPMBundle:DisponibiliteInscription d")->getResult();
	    	        	
		foreach ($orgas as $orga){
            foreach ($allDI as $di)
            {
            		if(!$orga->getDisponibilitesInscription()->contains($di)){
            			
            			$orga->removeDIFromDisponibilites($di);
            		}
            		
            }
            foreach ($allDI as $di)
            {
            	if($orga->getDisponibilitesInscription()->contains($di)){
            		$orga->addDIToDisponibilites($di);
            	}
            	 
            }
            
            	$em->flush();
	            $orga->cleanDisponibilites();
	            $em->flush();
		}
		
		
		return $this->redirect($this->getRequest()->headers->get('referer'));
	
		
	}
	
	/**
	 * Convert respNecessaire->Bo.OrgaHint
	 *
	 * @Route("/convertresp",  name="orga_convert_resp")
	 */
	public function convertRespAction()
	{
	
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
			throw new AccessDeniedException();
		}
	
		$em = $this->getDoctrine()->getEntityManager();
	
		$phs = $em
		->createQuery("SELECT ph FROM PHPMBundle:PlageHoraire ph")
		->getResult();
		 
		foreach ($phs as $ph){
			if($ph->getRespNecessaire()){
				var_dump($ph->getId());
				$bo = new BesoinOrga();
				$bo->setOrgaHint($ph->getTache()->getResponsable());
				$bo->setPlageHoraire($ph);
				$ph->setRespNecessaire(false);
				$em->persist($bo);
		
			}
		}
		 
		 
		
		 
		
		 
		$em->flush();
	
		return $this->redirect($this->getRequest()->headers->get('referer'));
	
	
	}
	
	/**
	 * Auto affect Orga using orga Hints
	 *
	 * @Route("/{id}/affectHints", name="orga_affectHint")
	 * @Template()
	 */
	public function affectHintsAction($id)
	{
	
		if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
			throw new AccessDeniedException();
		}
		$em = $this->getDoctrine()->getEntityManager();
		$orga = $em->getRepository('PHPMBundle:Orga')->find($id);
		$validator = $this->get('validator');
		
		
		if (!$orga ) {
			throw $this->createNotFoundException('Unable to find Orga.');
		}
		
		$messages = array();
		
		$creneaux = $em
	    ->createQuery("SELECT c  FROM PHPMBundle:Creneau c JOIN c.orgaHint o JOIN c.plageHoraire p JOIN p.tache t WHERE o.id = :id AND t.statut = 3")
	    ->setParameter('id', $id)
	    ->getResult();
		
		
		foreach($creneaux as $creneau){
			$cid=$creneau->getId();
			$messages[$cid]=array('creneau'=>$creneau);
			if($creneau->getDisponibilite() != null){
					$creneau->getDisponibilite()->getOrga()->getPrenom();
					$messages[$cid]['msg']='Créneau déjà affecté à '.$creneau->getDisponibilite()->getOrga()->getPrenom()
					.' '.$creneau->getDisponibilite()->getOrga()->getNom().'.';

			}else{
				
					
				$dispos = $em->getRepository('PHPMBundle:Disponibilite')->getContainingDisponibilite($orga, $creneau);
				
				
				
				if (count($dispos)==0) {
					$messages[$cid]['msg']="L' orga n'est pas disponible sur ce créneau.";
					
				}else{
					$creneau->setDisponibilite($dispos[0]);
					$errors = $validator->validate($creneau);
					if(count($errors) > 0){
						$messages[$cid]['msg']="Impossible d'affecter : ";
						foreach ($errors as $error){
							$messages[$cid]['msg'].=$error->getMessage().' ';
						}
						
					
					}else{
						$messages[$cid]['msg']="Affecté.";
						
					}
					
				}
				
			}
			
		}		
	

		return array('messages'=>$messages,'orga'=>$orga);
	
	
	}
	
	
	
	
	
		
}
