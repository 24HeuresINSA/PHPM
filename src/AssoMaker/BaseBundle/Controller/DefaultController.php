<?php

namespace AssoMaker\BaseBundle\Controller;

use HWI\Bundle\OAuthBundle\Templating\Helper\OAuthHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Config;
use AssoMaker\PHPMBundle\Form\PrintPlanningType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/home", name="base_accueil")
     * @Route("/home")
     * @Template()
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');

        $user = $this->get('security.context')->getToken()->getUser();

        if (!$this->get('security.context')->isGranted('ROLE_VISITOR')) {
            if ($config->getValue('base_admin_login') == 1) {
                return $this->adminLogin();
            } else {
                throw new AccessDeniedException();
            }
        }

        $statsUser = $em->getRepository('AssoMakerBaseBundle:Orga')->getStats($user);
        $soldeCP = $em->getRepository('AssoMakerComptesPersoBundle:Transaction')->getOrgaBalance($user->getId());
        $transactionsCP = $em->getRepository('AssoMakerComptesPersoBundle:Transaction')->getTransactionsByOrga($user->getId());

        $statsUser['taches'] = $em->getRepository('AssoMakerPHPMBundle:Tache')->getOrgaStats($user);
        $debutPlanning = new \DateTime();
        $finPlanning = new \DateTime($config->getValue('phpm_planning_fin'));
        $planning = $em->getRepository('AssoMakerBaseBundle:Orga')->getPlanning($user->getId(), null, $debutPlanning, $finPlanning);
        $deadlineFT = $config->getValue('phpm_tache_heure_limite_validation');

        $printPlanningForm = $this->createForm(new PrintPlanningType(), array('debut' => new \DateTime(), 'fin' => new \DateTime()));

        $user->setLastActivity(new \DateTime());
        $em->flush();

        return array('soldeCP' => $soldeCP,
            'transactionsCP' => $transactionsCP,
            'statsOrga' => $statsUser,
            'planning' => $planning,
            'debutPlanning' => $debutPlanning,
            'finPlanning' => $finPlanning,
            'deadlineFT' => $deadlineFT,
            'printPlanningForm' => $printPlanningForm->createView()
        );
    }

    /**
     * @Route("/", name="base_publichome")
     * @Route("/")
     * @Template()
     */
    public function publicHomeAction()
    {

        if ($this->get('security.context')->isGranted('ROLE_VISITOR')) {
            return $this->redirect($this->generateUrl('base_accueil'));
        }

        return array();
    }

    /**
     * Link login
     *
     * @Route("/autologin/{id}/{loginkey}",requirements={"loginkey" = ".+"}, name="autologin")
     *
     */
    public function autologinAction($id, $loginkey)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');

        $user = $em->getRepository('AssoMakerBaseBundle:Orga')->findOneById($id);
        $secretSalt = $config->getValue('phpm_secret_salt');


        if ((!$user) || ($loginkey != urlencode(md5($secretSalt . $id, 'slt')))) {

            return $this->redirect($config->getValue('phpm_orgasoft_inscription_returnURL'));
        }

        $this->get('security.context')->setToken($user->generateUserToken());

        return $this->redirect($this->generateUrl('base_accueil'));
    }

    public function adminLogin()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $orgas = $em->getRepository('AssoMakerBaseBundle:Orga')->findAll();
        $admin = $orgas[0];
        $this->get('security.context')->setToken($admin->generateUserToken());
        return $this->redirect($this->generateUrl('base_accueil'));
    }

    /**
     * Login
     *
     * @Route("/login/token/{token}", defaults={"token":""}, name="login_token")
     *
     */
    public function loginTokenAction(Request $request, $token) {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $token=$em->getRepository('AssoMakerBaseBundle:RegistrationToken')->findOneBy(array('token'=>$token));
        if($token == null){
            throw new LogicException('La clef donnée est invalide');
        } else {
            $session->set('token_id', $token->getId());
            return $this->redirect($this->generateUrl('hwi_oauth_service_redirect',array('service'=>'google')));
        }
    }

    /**
     * Login
     *
     * Cette action permet juste d'afficher une page avec tous les fournisseur de connexion.
     *
     * @Route("/login", name="login")
     * @Template()
     *
     */
    public function loginAction(Request $request) {
    }

}
