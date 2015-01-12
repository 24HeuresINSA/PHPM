<?php

namespace AssoMaker\BaseBundle\Controller;
use AssoMaker\BaseBundle\Entity\RegistrationToken;
use AssoMaker\PHPMBundle\Form\RegistrationTokenType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Config;
use AssoMaker\PHPMBundle\Entity\User;
use AssoMaker\PHPMBundle\Form\Config\ConfigType;
use AssoMaker\PHPMBundle\Form\UserType;
use AssoMaker\PHPMBundle\Form\Config\ManifType;

/**
 * Config controller.
 *
 * @Route("/configv")
 */
class ConfigController extends Controller {

	/**
	 * Finds and displays a Config entity.
	 *
	 * @Route("/{id}/show", name="config_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getEntityManager();

		$entity = $em->getRepository('AssoMakerPHPMBundle:Config')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Config entity.');
		}

		

		return array('entity' => $entity,
				);
	}
	
	/**
	 * Displays a Config field.
	 *
	 * @Route("/{field}/display/{key}",defaults={"key"=""}, name="config_display")
	 * @Template()
	 */
	public function displayAction($field,$key="") {
	    $em = $this->getDoctrine()->getEntityManager();
	
	    $entity = $em->getRepository('AssoMakerPHPMBundle:Config')->findOneByField($field);
	
	    if (!$entity) {
	        throw $this
	        ->createNotFoundException('Unable to find Config entity.');
	    }
	    $array = (array)json_decode($entity->getValue(),true);
	    if(array_key_exists($key, $array))
	        return array('value' => $array[$key]);
	        else
	        return array('value' => $entity->getValue()	 );
	        
	        
	    
	
	    
	
	    
	}

	/**
	 * Displays a form to create a new Config entity.
	 *
	 * @Route("/new", name="config_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Config();
		$form = $this->createForm(new ConfigType(), $entity);

		return array('entity' => $entity, 'form' => $form->createView());
	}

	/**
	 * Creates a new Config entity.
	 *
	 * @Route("/create", name="config_create")
	 * @Method("post")
	 * @Template("AssoMakerPHPMBundle:Config:new.html.twig")
	 */
	public function createAction() {
		$entity = new Config();
		$request = $this->getRequest();
		$form = $this->createForm(new ConfigType(), $entity);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('config_show',
											array('id' => $entity->getId())));

		}

		return array('entity' => $entity, 'form' => $form->createView());
	}

	/**
	 * Displays a form to edit an existing Config entity.
	 *
	 * @Route("/{id}/edit", name="config_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getEntityManager();

		$entity = $em->getRepository('AssoMakerPHPMBundle:Config')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Config entity.');
		}

		$editForm = $this->createForm(new ConfigType(), $entity);
		

		return array('entity' => $entity,
				'edit_form' => $editForm->createView(),
				);
	}

	/**
	 * Edits an existing Config entity.
	 *
	 * @Route("/{id}/update", name="config_update")
	 * @Method("post")
	 * @Template("AssoMakerPHPMBundle:Config:edit.html.twig")
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getEntityManager();

		$entity = $em->getRepository('AssoMakerPHPMBundle:Config')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Config entity.');
		}

		$editForm = $this->createForm(new ConfigType(), $entity);
		

		$request = $this->getRequest();

		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl('config_edit',
											array('id' => $id)));
		}

		return array('entity' => $entity,
				'edit_form' => $editForm->createView(),
				);
	}

	/**
	 * Deletes a Config entity.
	 *
	 * @Route("/{id}/delete", name="config_delete")
	 * 
	 */
	public function deleteAction($id) {

		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('AssoMakerPHPMBundle:Config')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Config entity.');
		}

		$em->remove($entity);
		$em->flush();

		return $this->redirect($this->generateUrl('config'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}

	/**
	 * Supprime un Jeton
	 *
	 * @Route("/token/{id}/delete", name="token_delete")
	 *
	 */
	public function deleteTokenAction($id)
	{
		if($this->get('security.context')->isGranted('ROLE_HUMAIN')) {
			$em = $this->getDoctrine()->getEntityManager();
			$entity = $em->getRepository('AssoMakerBaseBundle:RegistrationToken')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Token entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('config'));
	}

    /**
     * Crée un nouveau token à partir des paramètres
     * @Route("/token/new/{e_id}/{e_count}", name="token_new")
     */

    public function newTokenAction($e_id=null,$e_count=1){
        $em=$this->getDoctrine()->getManager();
        $equipe = $em->getRepository('AssoMakerBaseBundle:Equipe')->find($e_id);
        $token = new RegistrationToken();
        $token->setEquipe($equipe);
        $token->setEmail("");
        $token->setCount($e_count);
        $em->persist($token);
        $em->flush();
        return $this->redirect($this->generateUrl('config'));
    }

	/**
	 * Renvoie la préférence "string" 
	 *
	 * @Route("/get/{string}", name="config_get")
	 * 
	 */
	public function getAction($string) {
		$em = $this->getDoctrine()->getEntityManager();

		$pref = $em->getRepository('AssoMakerPHPMBundle:Config')
				->findOneByField($string);

		if (!$pref) {
			throw $this
					->createNotFoundException('Unable to find Config entity.');
		}

		$response = new Response();
		$response->setContent($pref->getValue());
		$response->headers->set('Content-Type', 'text/plain');

		return $response;
	}

	/**
	 * 
	 *
	 * @Route("/initiale", name="config_initiale")
	 * @Template
	 */
	public function initialeAction() {
		$request = $this->get('request')->request;
		$em = $this->getDoctrine()->getEntityManager();

		$initiale = $em->getRepository('AssoMakerPHPMBundle:Config')
				->findOneByField('phpm_config_initiale');
		$admin=$this->get('security.context')->isGranted('ROLE_HUMAIN');

		if (!(!$initiale)
				& !$admin) {
			throw new AccessDeniedHttpException(
					"PHPM a déjà été configuré, veuillez vous connecter pour réinitialiser la configuration.");
		}

		if ($this->get('request')->getMethod() == 'POST') {

			$data = $request->all();
			if ($request->get("reinitconfirm") != "#1337!")
				throw new \Exception(
						"Code de réinitialisation invalide.");

			//Vidage des tables

			$conn = $em->getConnection();

// 			$sql = 'TRUNCATE TABLE `Creneau`;';
// 			$conn->query($sql);

// 			$sql = 'TRUNCATE TABLE `Disponibilite`';
// 			$conn->query($sql);

// 			$sql = 'TRUNCATE TABLE `PlageHoraire`';
// 			$conn->query($sql);

// 			$sql = 'TRUNCATE TABLE `Tache`;	';
// 			$conn->query($sql);

// 			$sql = 'TRUNCATE TABLE `Orga`;';
// 			$conn->query($sql);

// 			$sql = 'TRUNCATE TABLE `Categorie`';
// 			$conn->query($sql);
// 			$sql = 'TRUNCATE TABLE `Confiance`';
// 			$conn->query($sql);
// 			$sql = 'TRUNCATE TABLE `Config`';
// 			$conn->query($sql);
// 			$sql = 'TRUNCATE TABLE `User`';
// 			$conn->query($sql);

			$sql = "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = \"+00:00\";

--
-- Database: `phpm`
--

--
-- Dumping data for table `Config`
--

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `username`, `pass`, `email`) VALUES(1, 'orga', 'orga', 'orga@24heures.org');
COMMIT;
			";
			$conn->query($sql);

			$conn->close();

			

			return $this->redirect($this->generateUrl('login'));

		}

		return array("" => "");

	}

	/**
	 *
	 * @Route("/", name="config")
	 * @Template
	 */
	public function manifAction() {
		$request = $this->get('request');
		$em = $this->getDoctrine()->getEntityManager();
		$admin=$this->get('security.context')->isGranted('ROLE_HUMAIN');
		$config = $e=$this->get('config.extension');
	
		
		$configItems = $em->getRepository('AssoMakerPHPMBundle:Config')->findAll();
        $tokens = $em->getRepository('AssoMakerBaseBundle:RegistrationToken')->findAll();
		$equipeItems = $em->createQuery("SELECT e,r,c FROM AssoMakerBaseBundle:Equipe e JOIN e.responsable r JOIN e.confiance c ")->getResult();
		$confianceItems = $em->getRepository('AssoMakerBaseBundle:Confiance')->findAll();
		$materielItems = $em->createQuery("SELECT m FROM AssoMakerPHPMBundle:Materiel m ORDER BY m.categorie ")->getResult();
		$data = array(
		        'configItems'=>$configItems,
		        'equipeItems'=>$equipeItems,
		        'confianceItems'=>$confianceItems,
		        'materielItems'=>$materielItems,
            'registrationTokenItems'=>$tokens
		        );
		


		
		$form    = $this->createForm(new ManifType($admin,$config), $data);
		
		$valid = null;
		if ($this->get('request')->getMethod() == 'POST') {
		$form->handleRequest($request);
		$data = $form->getData();
		$valid=$form->isValid();
		
		
        
        if ($valid) {
		    $em->flush();
		    
		}
		

		}

		return array(	
				'form' => $form->createView(),
            'equipes' => $this->getDoctrine()->getManager()->getRepository('AssoMakerBaseBundle:Equipe')->findAll(),
						'valid' => $valid);

	}

}
