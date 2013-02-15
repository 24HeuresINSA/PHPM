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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        if (false === $this->get('security.context')->isGranted('ROLE_BUREAU')) {
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
     * Print
     *
     * @Route("/print", name="comptesPersoPrint")
     * @Method("get")
     * @Template()
     */
    public function printAction(Request $request) {
        if (false === $this->get('security.context')->isGranted('ROLE_BUREAU')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();

        $comptes = $em
                ->getRepository('AssoMakerComptesPersoBundle:Transaction')
                ->getComptes();

        return array('comptes' => $comptes);
    }

    /**
     * Lists all Orga comptes as JSON
     *
     * @Route("/comptes.json", name="comptesPersoJSON")
     * @Method("get")
     * @Template()
     */
    public function comptesPersoDataAction(Request $request) {
        if (false === $this->get('security.context')->isGranted('ROLE_BUREAU')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();

        $comptes = $em
                ->getRepository('AssoMakerComptesPersoBundle:Transaction')
                ->getComptes();

        $response = new Response();
        $response->setContent(json_encode($comptes));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Process comptesPerso update
     *
     * @Route("/process", name="comptesPersoProcess")
     * @Method("post")
     * @Template()
     */
    public function comptesPersoProcessAction(Request $request) {
        if (false === $this->get('security.context')->isGranted('ROLE_BUREAU')) {
            $this->getResponse()->setStatusCode('404');
            return new Response();
        }
        $em = $this->getDoctrine()->getEntityManager();

        $data = json_decode($request->getContent(), true);
        $config = $e = $this->get('config.extension');
        $prixConso = $config->getValue('comptes_perso_prix_conso_standard');

        foreach ($data["alter"] as $id => $conso) {
            if ($conso != null) {
                $param = $request->request->all();
                $orga = $em->getRepository('AssoMakerBaseBundle:Orga')
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
            $orga = $em->getRepository('AssoMakerBaseBundle:Orga')->find($id);
            if ($conso != null) {
                $this->checkNegativeAccountAndSendEmail($orga);
            }
        }

        return new Response();
    }

    /**
     * Lists all transactions as JSON
     *
     * @Route("/transactions.json", name="transactionsJSON")
     * @Method("get")
     * @Template()
     */
    public function transactionsDataAction(Request $request) {
        if (false === $this->get('security.context')->isGranted('ROLE_BUREAU')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();

        $transactions = $em
                ->getRepository('AssoMakerComptesPersoBundle:Transaction')
                ->getTransactionsArray();

        $response = new Response();
        $response->setContent(json_encode($transactions));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Virement
     *
     * @Route("/virement", name="comptesPerso_virement")
     * @Template()
     */
    public function virementAction(Request $request) {
        $user = $this->get('security.context')->getToken()->getUser();
        if (false === $user->getEquipe()->getComptesPersoEnabled()) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');

        $userId = $user->getId();
        $form = $this->createForm(new VirementType($config, $userId));
        $soldeCP = $em
                ->getRepository('AssoMakerComptesPersoBundle:Transaction')
                ->getOrgaBalance($user->getId());

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $destinataire = $data['destinataire'];
                $amount = $data['montant'];
                $raison = $data['raison'];

                $em
                        ->persist(
                                new Transaction($destinataire, $amount, "Virement reçu de $user : '$raison'."));
                $em
                        ->persist(
                                new Transaction($user, -$amount, "Virement vers $destinataire : '$raison'."));

                $em->flush();
                $this->checkNegativeAccountAndSendEmail($user);
                return $this->redirect($this->generateUrl('base_accueil'));
            }
        }

        return array('form' => $form->createView(), 'soldeCP' => $soldeCP);
    }

    public function checkNegativeAccountAndSendEmail($orga) {
        $em = $this->getDoctrine()->getEntityManager();
        $soldeCP = $em
                ->getRepository('AssoMakerComptesPersoBundle:Transaction')
                ->getOrgaBalance($orga->getId());
        if ($soldeCP < 0) {

            $message = \Swift_Message::newInstance()
                    ->setSubject(
                            '[Alerte Compte Perso] Ton compte perso est dans le rouge!')
                    ->setFrom(
                            array(
                                'secretaire.general@24heures.org' => 'SG 24 Heures'))
                    ->setReplyTo('secretaire.general@24heures.org')
                    ->setTo($orga->getEmail())
                    ->setBody(
                    $this
                    ->renderView(
                            'AssoMakerComptesPersoBundle:ComptesPerso:emailNegativeAccount.html.twig', array('orga' => $orga,
                        'soldeCP' => $soldeCP)), 'text/html');

            $this->get('mailer')->send($message);
        }
    }

    /**
     * ComputeInterests
     *
     * @Route("/computeInterests.json", name="comptesPerso_computeInterests")
     * @Method("post")
     * @Template()
     */
    public function computeInterestsAction(Request $request) {
        $accessCode = $request->request->get('accessCode');
        $config = $this->get('config.extension');

        if ($accessCode != $config->getValue('phpm_ws_access_code')) {
            $response = new Response();
            $response->setStatusCode('401');
            return $response;
        }

        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');

        $orgas = $em->getRepository("AssoMakerBaseBundle:Orga")
                        ->findAllComptesPersoUsersExcept(0)->getQuery()->getResult();

        foreach ($orgas as $orga) {
            $soldeCP = $em
                    ->getRepository('AssoMakerComptesPersoBundle:Transaction')
                    ->getOrgaBalance($orga->getId());
            if ($soldeCP < 0) {
                $interests = round(-$soldeCP * 11 / 100 * 7 / 365, 2);
                $em
                        ->persist(
                                new Transaction($orga, -$interests, "Intérêts sur compte déficitaire: $soldeCP € x (1+10)% x 7 jours = $interests €"));
                $em->flush();
                $this->checkNegativeAccountAndSendEmail($orga);
            }
        }

        return array();
    }

    /*
      LogConso

      @Route("/logConso.json", name="comptesPerso_logConso")
      @Method("put")

      public function logConsoAction(Request $request)
      {
      $em = $this->getDoctrine()->getEntityManager();
      $config = $e = $this->get('config.extension');

      $prixConso = $config->getValue('comptes_perso_prix_conso_standard');

      $data = json_decode($request->getContent(), true);
      $NFCId = $data['NFCId'];
      $orga = $em->getRepository("AssoMakerBaseBundle:Orga")
      ->findOneByNfcId($NFCId);

      if (!$orga) {
      $response = new Response("#0");
      return $response;
      }


      $em
      ->persist(
      new Transaction($orga, -$prixConso,
      "Conso: $prixConso €"));
      $em->flush();
      $this->checkNegativeAccountAndSendEmail($orga);

      $response = new Response("#1");
      return $response;

      }
     */
}
