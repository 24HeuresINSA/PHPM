<?php

namespace AssoMaker\ComptesPersoBundle\Controller;
use Doctrine\DBAL\Platforms\Keywords\KeywordList;

use AssoMaker\ComptesPersoBundle\Entity\Transaction;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AssoMaker\BaseBundle\Entity\OrgaRepository;
use Symfony\Component\Validator\Constraints\Min;
use Symfony\Component\Validator\Constraints\Collection;

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

		$entities = $em
				->createQuery("SELECT o FROM AssoMakerBaseBundle:Orga o")
				->getResult();

		$consosBuilder = $this->createFormBuilder();

		$totalBalance = 0;
		$orgas = array();
		foreach ($entities as $entity) {
			$orga = array();
			$orga['id'] = $entity->getId();
			$orga['nom'] = $entity->getNom();
			$orga['prenom'] = $entity->getPrenom();
			$orga['surnom'] = $entity->getSurnom();
			$orga['balance'] = $em
					->getRepository('AssoMakerComptesPersoBundle:Transaction')
					->getOrgaBalance($entity->getId());
			$orgas[$entity->getId()] = $orga;

			$consosBuilder
					->add((string) $orga['id'], 'number',
							array('required' => false));

			$totalBalance += $orga['balance'];
		}
		;

		$builder = $this->createFormBuilder();
		$builder->add('operation', null, array('required' => false));

		$builder->add($consosBuilder);

		$form = $builder->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				$data = $form->getData();
				foreach ($data['form'] as $id => $conso) {
					if ($conso != null) {
						$param = $request->request->all();
						$action = $param['action'];

						if ($action == 'consoStandard') {
							$nbConsos = $data['form'][$id];
							$orga = $em
									->getRepository('AssoMakerBaseBundle:Orga')
									->find($id);
							$amount = -$prixConso * $nbConsos;
							$comment = "$nbConsos consommations à $prixConso € = $amount €";
						} elseif ($action == 'autre') {
							$operation = $data['operation'];
							$amount = $data['form'][$id];
							$orga = $em
									->getRepository('AssoMakerBaseBundle:Orga')
									->find($id);
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
				return $this->redirect($this->generateUrl('comptesPerso'));
			}
		}

		return array('prixConsoStandard' => $prixConso, 'orgas' => $orgas,
				'totalBalance' => $totalBalance, 'form' => $form->createView());
	}

	/**
	 * Lists all Orga comptes
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

		$orgas = $em
				->createQuery(
						"SELECT o FROM AssoMakerBaseBundle:Orga o WHERE o.privileges >=1")
				->getResult();

		$collectionConstraint = new Collection(
				array(
				        'confirm'=>array(),
				        'raison'=>array(),
						'montant' => new Min(
								array('limit' => 0,
										'message' => "Veuillez entrer un montant positif")),
						'destinataire' => array()));

		$builder = $this
				->createFormBuilder(null,
						array('validation_constraint' => $collectionConstraint))
				->add('montant', 'money', array())
				->add('destinataire', 'entity',
						array('label' => 'Bénéficiaire',
								'class' => 'AssoMakerBaseBundle:Orga',
								'query_builder' => function (
										OrgaRepository $or) use ($userId) {
									return $or->findAllUsersExcept($userId);
								}))
				->add('raison', null, array('label'=>'Raison'))
								;
		$builder->add('confirm', 'hidden');
		$builder->setData(array('confirm' => '0'));

		$form = $builder->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bind($request);
			if ($form->isValid()) {
				$data = $form->getData();
				if ($data['confirm'] == '0') {
					$data['confirm'] = '1';
					$builder->setData($data);
					$form2 = $builder->getForm();

					return array('form' => $form2->createView());
				}

				if ($data['confirm'] == '1') {
				    $destinataire = $data['destinataire'];
				    $amount = $data['montant'];
				    $raison = $data['raison'];
				    
				    $em->persist(new Transaction($destinataire, $amount, "Virement reçu de $user : '$raison'."));
				    $em->persist(new Transaction($user, -$amount, "Virement vers $destinataire : '$raison'."));

				    $em->flush();
					return $this->redirect($this->generateUrl('base_accueil'));
				}

			}

		}

		return array('form' => $form->createView());
	}

}
