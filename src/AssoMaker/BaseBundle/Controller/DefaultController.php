<?php

namespace AssoMaker\BaseBundle\Controller;

use HWI\Bundle\OAuthBundle\Templating\Helper\OAuthHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use AssoMaker\PHPMBundle\Entity\Config;
use AssoMaker\BaseBundle\Entity\Orga as BaseUser;
use AssoMaker\PHPMBundle\Form\PrintPlanningType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @var TokenInterface
     */
    private $token;

    /**
     * @Route("/home", name="base_accueil")
     * @Template()
     * @Secure("ROLE_ORGA")
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
     * Login
     *
     * @Route("/login/token/{token}", defaults={"token":""}, name="login_token")
     * @Template()
     */
    public function loginTokenAction(Request $request, $token) {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $token=$em->getRepository('AssoMakerBaseBundle:RegistrationToken')->findOneBy(array('token'=>$token));
        if($token == null){
            $request->getSession()->getFlashBag()->add('error', "La clef d'inscription n'est pas valide");
            return $this->redirect($this->generateUrl('base_publichome'));
        } else {
            $session->set('token_id', $token->getId());
            //return $this->redirect($this->generateUrl('hwi_oauth_service_redirect',array('service'=>'google')));
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
        return $this->redirect($this->generateUrl('base_publichome'));
    }

    /**
     *
     * @Route("/preRegistration", name="preregistration")
     * @Template()
     */
    public function preRegisterAction(){

    }

    /**
     * Cette action propose à l'utilisateur de s'enregistrer (avec ou sans fournisseur de connexion).
     *
     * @Route("/register/", name="register")
     * @Template()
     *
     */
    public function registerAction(Request $request) {

        /**
         * @var AbstractManagerRegistry
         */
        $em = $this->getDoctrine()->getManager();
        $this->securityContext=$this->get('security.context');
        $this->token = $this->securityContext->getToken();
        $user = $this->token->getUser();

        // Token défini ?
        $session = $request->getSession();
        $token_id = $session->get('token_id');
        if ($token_id == null) {
            $request->getSession()->getFlashBag()->add('error', "Vous ne pouvez pas vous enregistrer sans clé d'inscription.");
            return $this->refuseRegistration($em, $user);
        }

        // Token valide ?
        $em = $this->getDoctrine()->getManager();
        $token = $em->getRepository('AssoMakerBaseBundle:RegistrationToken')->findOneBy(array('id' => $token_id));
        if ($token == null || $token->getCount() < 1){
            $request->getSession()->getFlashBag()->add('error', "La clé d'inscription n'est pas valide.");
            return $this->refuseRegistration($em, $user);
        }

        // L'utilisateur s'est enregistré via OAuth ?
        if ($user != "anon." && !$user->isEnabled() && $user->hasOAuthProvider()) {
            
            // Vérification de l'email du token
            if ($token->getEmail() != null && $token->getEmail() !== $user->getEmail()) {
                $request->getSession()->getFlashBag()->add('error', "La clé d'inscription n'est pas valide avec cette adresse mail.");
                return $this->refuseRegistration($em, $user);
            }

            // Tout est ok, on complète l'inscription
            $user->setEnabled(true);
            $user->addRole('ROLE_ORGA');
            $user->setStatut(0);
            $user->setEquipe($token->getEquipe());

            // Utilisation du token
            if($token->getCount() <= 1) {
                $em->remove($token);
            } else {
                $token->oneUse();
                $em->persist($token);
            }

            $em->persist($user);
            $em->flush();

            // Inscription terminée. On invite l'utilisateur à se reconnecter.
            $this->container->get('security.context')->setToken(NULL);
            $request->getSession()->getFlashBag()->add('notice', "Inscription effectuée avec succès, vous pouvez maintenant vous connecter.");
            return $this->redirect($this->generateUrl('base_publichome'));

        } 
        
        // L'utilisateur s'est-il enregistré via formulaire d'inscription ?
        if ($user == "anon.")
            $user = new BaseUser();

        $form = $this->createForm($this->get('form.type.quickRegistration'), $user);
        $form->handleRequest($request);

        if ($request->getMethod() == "POST") {
            if ($form->isValid()) {
                // On complète l'inscription
                $canonicalizer = $this->get('fos_user.util.email_canonicalizer');
                $user->setEmailCanonical($canonicalizer->canonicalize($user->getEmail()));
                $user->setEnabled(true);
                $user->addRole('ROLE_ORGA');
                $user->setStatut(0);
                $user->setEquipe($token->getEquipe());

                // Utilisation du token
                if($token->getCount() <= 1) {
                    $em->remove($token);
                } else {
                    $token->oneUse();
                    $em->persist($token);
                }

                $em->persist($user);
                $em->flush();

                // Inscription terminée. On invite l'utilisateur à se reconnecter.
                $this->container->get('security.context')->setToken(NULL);
                $request->getSession()->getFlashBag()->add('notice', "Inscription effectuée avec succès, vous pouvez maintenant vous connecter.");
                return $this->redirect($this->generateUrl('base_publichome'));
            }
        } 

        return array(
            'entity' => $user,
            'form' => $form->createView()
        );

    }

    /**
     * @param $entityManager
     * @param $user
     */
    private function refuseRegistration($entityManager, $user)
    {
        $entityManager->remove($user);
        $entityManager->flush();
        $this->securityContext->setToken(null);
        return $this->redirect($this->generateUrl('base_publichome'));
    }


}
