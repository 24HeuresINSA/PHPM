<?php

namespace PHPM\Bundle\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Config;
use PHPM\Bundle\Entity\User;
use PHPM\Bundle\Form\ConfigType;
use PHPM\Bundle\Form\UserType;

/**
 * Config controller.
 *
 * @Route("/config")
 */
class ConfigController extends Controller {
	/**
	 * Lists all Config entities.
	 *
	 * @Route("/", name="config")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getEntityManager();

		$entities = $em->getRepository('PHPMBundle:Config')->findAll();

		return array('entities' => $entities);
	}

	/**
	 * Finds and displays a Config entity.
	 *
	 * @Route("/{id}/show", name="config_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getEntityManager();

		$entity = $em->getRepository('PHPMBundle:Config')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Config entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return array('entity' => $entity,
				'delete_form' => $deleteForm->createView(),);
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
	 * @Template("PHPMBundle:Config:new.html.twig")
	 */
	public function createAction() {
		$entity = new Config();
		$request = $this->getRequest();
		$form = $this->createForm(new ConfigType(), $entity);
		$form->bindRequest($request);

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

		$entity = $em->getRepository('PHPMBundle:Config')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Config entity.');
		}

		$editForm = $this->createForm(new ConfigType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return array('entity' => $entity,
				'edit_form' => $editForm->createView(),
				'delete_form' => $deleteForm->createView(),);
	}

	/**
	 * Edits an existing Config entity.
	 *
	 * @Route("/{id}/update", name="config_update")
	 * @Method("post")
	 * @Template("PHPMBundle:Config:edit.html.twig")
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getEntityManager();

		$entity = $em->getRepository('PHPMBundle:Config')->find($id);

		if (!$entity) {
			throw $this
					->createNotFoundException('Unable to find Config entity.');
		}

		$editForm = $this->createForm(new ConfigType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->bindRequest($request);

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
				'delete_form' => $deleteForm->createView(),);
	}

	/**
	 * Deletes a Config entity.
	 *
	 * @Route("/{id}/delete", name="config_delete")
	 * 
	 */
	public function deleteAction($id) {

		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('PHPMBundle:Config')->find($id);

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
	 * Renvoie la préférence "string" 
	 *
	 * @Route("/get/{string}", name="config_get")
	 * 
	 */
	public function getAction($string) {
		$em = $this->getDoctrine()->getEntityManager();

		$pref = $em->getRepository('PHPMBundle:Config')
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

		$initiale = $em->getRepository('PHPMBundle:Config')
				->findOneByField('phpm.config.initiale');

		if (!(!$initiale)
				& !$this->get('security.context')->isGranted('ROLE_ADMIN')) {
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

INSERT INTO `Config` (`id`, `field`, `value`, `label`) VALUES(1, 'manifestation.plages', '{\"1\":{\"nom\":\"Pr\\u00e9manif\",\"debut\":\"2012-05-16 00:00\",\"fin\":\"2012-05-23 00:00\"},\"2\":{\"nom\":\"Manif\",\"debut\":\"2012-05-23 00:00\",\"fin\":\"2012-05-27 00:00\"},\"3\":{\"nom\":\"Postmanif\",\"debut\":\"2012-05-28 00:00\",\"fin\":\"2012-06-01 00:00\"}}', 'Plages de la manifestation');
INSERT INTO `Config` (`id`, `field`, `value`, `label`) VALUES(2, 'manifestation.organisation.nom', '24 Heures de l''INSA', 'Nom de l''organisation');
INSERT INTO `Config` (`id`, `field`, `value`, `label`) VALUES(3, 'phpm.config.initiale', '1', 'PHPM configuré');

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
	 *
	 * @Route("/manif", name="config_manif")
	 * @Template
	 */
	public function manifAction() {
		$request = $this->get('request');
		$em = $this->getDoctrine()->getEntityManager();
		$orga = $em->getRepository('PHPMBundle:Config')
				->findOneByField('manifestation.organisation.nom');
		$plages = $em->getRepository('PHPMBundle:Config')
				->findOneByField('manifestation.plages');
		$user = $em->getRepository('PHPMBundle:User')->find(1);


		
		$builder = $this->createFormBuilder(array(
                                                    "user" => array("Utilisateur" => $user),
		                                            "configItems"=>array("Nom de la manifestation"=>$orga,
		                                                    "Plages de la manif"=>$plages)
		                                                ));
		
		$builder->add('configItems', 'collection',array('type' => new ConfigType()));
		$builder->add('user', 'collection', array('type' => new UserType(), 'label' =>' '));
		
		$form = $builder->getForm();
		
		//var_dump($form->createView());

		if ($this->get('request')->getMethod() == 'POST') {
			$form->bindRequest($request);
			$data = $form->getData();
			$validator = $this->get('validator');
			foreach ($data['configItems'] as $item) {

				$errors = $validator->validate($item);

				if (count($errors) > 0) {
					return new Response(print_r($errors, true));
				} else {
					$em->persist($item);
					$em->flush();
				}
			}

			foreach ($data['user'] as $item) {

				$errors = $validator->validate($item);

				if (count($errors) > 0) {
					return new Response(print_r($errors, true));
				} else {
					$em->persist($item);
					$em->flush();
				}
			}

		}

		return array('form' => $form->createView());

	}

}
