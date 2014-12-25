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
        $enabled = $this->token->getUser()->isEnabled();
        if(!$enabled)
            return $this->redirect($this->generateUrl("register_oauth"));
        else
            return $this->redirect($this->generateUrl("base_accueil"));
    }


    /**
     * @Route("/register", name="register_oauth")
     * @Template("AssoMakerBaseBundle:Orga:registration.html.twig")
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

        $form = $this->createForm($this->get('form.type.registration'), $entity, array());

        $orga = $entity;
        $config = $e = $this->get('config.extension');
        $confianceOrga = $registrationToken->getEquipe()->getConfiance()->getValeur();
        $groupesDIresult = $this->getDoctrine()->getEntityManager()->createQuery("SELECT g FROM AssoMakerPHPMBundle:Mission g WHERE g.confianceMin <= $confianceOrga ORDER BY g.ordre")->getResult();
        $groupesDI = array();

        foreach ($groupesDIresult as $entity) {
            $groupesDI[$entity->getId()] = $entity;
        }

        $queryResult = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM AssoMakerPHPMBundle:DisponibiliteInscription d JOIN d.mission m WHERE m.confianceMin <= $confianceOrga ORDER BY d.debut")->getResult();
        $DIs = array();

        foreach ($queryResult as $entity) {
            $fmt = new \IntlDateFormatter(null, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, 'Europe/Paris', null, 'EEEE d MMMM');
            $DIs[$entity->getMission()->getId()][$fmt->format($entity->getDebut()->getTimestamp())][$entity->getId()] = $entity;
        }

        $formDispos = $this->createForm(new InputDisposType());

        if ($request->getMethod() == 'POST') {
            $formDispos->handleRequest($request);
            $form->handleRequest($request);
            $data = $formDispos->getData();
            $submittedDI = $data['disponibiliteInscriptionItems'];

            if($form->isValid()){
                exit;
            }

            if ($form->isValid()&&$formDispos->isValid()) {
                $entity->setEnabled(true);
                $entity->addRole('ROLE_ORGA');
                $entity->setStatut(0);
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
                $this->container->get('security.context')->setToken(NULL);

                $allDI = $this->getDoctrine()->getEntityManager()->createQuery("SELECT d FROM AssoMakerPHPMBundle:DisponibiliteInscription d")->getResult();

                $minCharisme = $config->getValue('manifestation_charisme_minimum');

                $totalCharisme = 0;

                foreach ($submittedDI as $di) {
                    $totalCharisme+=$di->getPointsCharisme();
                }
                $totalCharisme+=$orga->getCharisme();

                if ($totalCharisme < $minCharisme) {
                    return array('form' => $form->createView(),
                        'entities' => $DIs,
                        'missions' => $groupesDI,
                        'orga' => $orga,
                        'charismeInsuffisant' => true,
                        'now' => new \DateTime(),
                        'messagesCharisme' => $config->getValue('phpm_messages_charisme')
                    );
                }

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

                $em->flush();

                return $this->redirect($this->generateUrl('orga_thankyou'));
            }
        }


        return array(
            'entity' => $orga,
            'form' => $form->createView(),
            'formDispo' => $formDispos->createView(),
            'entities' => $DIs,
            'missions' => $groupesDI,
            'orga' => $orga,
            'now' => new \DateTime(),
            'messagesCharisme' => $config->getValue('phpm_messages_charisme')
        );
    }

    public function generateAction($equipe_id){
        $em = $this->getDoctrine()->getManager();
        $token = new RegistrationToken();
        $token->setEquipe($em->getRepository('AssoMakerBaseBundle:Equipe')->findOneById($equipe_id));
        $em->persist($token);
        $em->flush();
        return $this->render($token->getToken());
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