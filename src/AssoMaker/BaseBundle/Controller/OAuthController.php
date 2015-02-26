<?php
/**
 * Created by PhpStorm.
 * User: pvienne
 * Date: 10/11/14
 * Time: 14:02
 */

namespace AssoMaker\BaseBundle\Controller;


use AssoMaker\BaseBundle\Entity\Orga;
use AssoMaker\BaseBundle\Entity\RegistrationToken;
use AssoMaker\PHPMBundle\Form\InputDisposType;
use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class OAuthController
 * @Route("/oauth")
 * @package AssoMaker\BaseBundle\Controller
 */
class OAuthController extends Controller {

    /**
     * @var SecurityContext
     */
    private $securityContext;

    /**
     * @var TokenInterface
     */
    private $token;

    /**
     * @Route("/check", name="check_oauth")
     * Cette action vérifie que l'utilisateur a remplis son inscription et que le compte est actif.
     */
    public function checkAction(){
        $this->securityContext=$this->get('security.context');
        $this->token = $this->securityContext->getToken();
        if(!$this->securityContext->isGranted('ROLE_ORGA')) // Si l'utilisateur ne s'est pas enregistré
            return $this->redirect($this->generateUrl("register_oauth"));
        else
            return $this->redirect($this->generateUrl("base_accueil"));
    }

    /**
     * @Route("/register", name="register_oauth")
     * Cette action vérifie que l'utilisateur a remplis son inscription et que le compte est actif.
     */
    public function registerAction(Request $request){
        $this->securityContext=$this->get('security.context');
        $this->token = $this->securityContext->getToken();
        /**
         * @var AbstractManagerRegistry
         */
        $em = $this->getDoctrine()->getManager();

        $entity = $this->token->getUser();

        $token_id = $request->getSession()->get('token_id');
        if($token_id===null){
            $request->getSession()->getFlashBag()->add('error', "Vous n'avez pas de compte lié sur ce site, inscrivez-vous !");
            return $this->refuseRegistration($em, $entity);
        }
        /**
         * @var RegistrationToken
         */
        $registrationToken = $em->getRepository('AssoMakerBaseBundle:RegistrationToken')->findOneBy(array('id'=>$token_id));
        if($registrationToken==null){
            $request->getSession()->getFlashBag()->add('error', "La clef d'inscription n'est plus valide");
            return $this->refuseRegistration($em,$entity);
        }

        $entity->setEquipe($registrationToken->getEquipe());

        $config = $e = $this->get('config.extension');

        if($request->getSession()->get('registration_step')==null||$request->getSession()->get('registration_step')==0){
            $request->getSession()->set('registration_step',0);

            // User is on first step for registration and should enter personal information

            $form = $this->createForm($this->get('form.type.registration'), $entity, array());

            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $em->persist($entity);
                    $em->flush();
                    $request->getSession()->set('registration_step', 1);
                    return $this->redirect($this->generateUrl("register_oauth"));
                }
            }
        } else {
            $request->getSession()->set('registration_step',1);

            // User is on second step and should enter his availabilities

            $orga = $entity;
            $confianceOrga = $registrationToken->getEquipe()->getConfiance()->getValeur();
            $groupesDIresult = $this->getDoctrine()->getEntityManager()->createQuery("SELECT g FROM AssoMakerPHPMBundle:Mission g WHERE g.confianceMin <= $confianceOrga ORDER BY g.ordre")->getResult();
            $groupesDI = array();

            foreach ($groupesDIresult as $e) {
                $groupesDI[$e->getId()] = $e;
            }

            $queryResult = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM AssoMakerPHPMBundle:DisponibiliteInscription d JOIN d.mission m WHERE m.confianceMin <= $confianceOrga ORDER BY d.debut")->getResult();
            $DIs = array();

            foreach ($queryResult as $e) {
                $fmt = new \IntlDateFormatter(null, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, 'Europe/Paris', null, 'EEEE d MMMM');
                $DIs[$e->getMission()->getId()][$fmt->format($e->getDebut()->getTimestamp())][$e->getId()] = $e;
            }

            $form = $this->createForm(new InputDisposType());

            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                $data = $form->getData();
                $submittedDI = $data['disponibiliteInscriptionItems'];

                if ($form->isValid()) {
                    $entity->addRole('ROLE_ORGA');
                    $entity->setStatut(0);
                    $entity->setEnabled(true);
                    $entity->setEquipe($registrationToken->getEquipe());
                    if($em->getRepository('AssoMakerBaseBundle:Orga')->count()<=1)
                        $entity->addRole('ROLE_SUPER_ADMIN');
                    if($registrationToken->getCount()<=2) {
                        $em->remove($registrationToken);
                    }else{
                        $registrationToken->oneUse();
                        $em->persist($registrationToken);
                    }
                    $em->persist($entity);
                    $em->flush();

                    // Flush authentication to reload permissions
                    $this->container->get('security.context')->getToken()->setAuthenticated( false );

                    $allDI = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM AssoMakerPHPMBundle:DisponibiliteInscription d")->getResult();

                    foreach ($allDI as $di) {
                        if (!$submittedDI->contains($di)) {
                            if ($orga->getDisponibilitesInscription()->contains($di) && ($this->get('security.context')->isGranted('ROLE_HUMAIN')) && ($di->getDebut() > new \DateTime())) {
                                $orga->removeDisponibiliteInscription($di);
                            }
                        }
                    }

                    foreach ($allDI as $di) {

                        if ($submittedDI->contains($di)) {
                            if (!$orga->getDisponibilitesInscription()->contains($di) && ($this->get('security.context')->isGranted('ROLE_HUMAIN') || ($di->getStatut() > 0)) && ($di->getDebut() > new \DateTime())) {
                                $orga->addDisponibiliteInscription($di);
                                $di->addOrga($orga);
                            }
                        }
                    }

                    $orga->cleanDisponibilites();

                    if ($orga->getStatut() == 2) {
                        $orga->setStatut(1);
                    }

                    $em->persist($orga);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('info', "Votre inscription est validée mais vous devez vous reconnecter");

                    return $this->redirect($this->generateUrl('orga_thankyou'));
                }
            }

        }

        $template = $request->getSession()->get('registration_step')<=0?
            "AssoMakerBaseBundle:Orga:registration.html.twig":
            "AssoMakerBaseBundle:Orga:registrationInputDispos.html.twig";

        return $this->render($template, array(
            'form' => $form->createView(),
            'entities' => isset($DIs)?$DIs:null,
            'missions' => isset($groupesDI)?$groupesDI:null,
            'orga' => isset($orga)?$orga:null,
            'now' => new \DateTime(),
            'messagesCharisme' => $config->getValue('phpm_messages_charisme')
        ));
    }

    public function generateAction($equipe_id){
        $em = $this->getDoctrine()->getManager();
        $token = new RegistrationToken();
        $token->setEquipe($em->getRepository('AssoMakerBaseBundle:Equipe')->findOneById($equipe_id));
        $em->persist($token);
        $em->flush();
        return $this->render($token->getToken());
    }

} 