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
            $this->refuseRegistration($em, $entity);
        }
        /**
         * @var RegistrationToken
         */
        $registrationToken = $em->getRepository('AssoMakerBaseBundle:RegistrationToken')->findOneBy(array('id'=>$token_id));
        if($registrationToken==null){
            $this->refuseRegistration($em,$entity);
        }

        $form = $this->createForm($this->get('form.type.registration'), $entity, array());

        $form->handleRequest($request);

        if ($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $entity->setEnabled(true);
                $entity->addRole('ROLE_ORGA');
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
                return $this->redirect($this->generateUrl('hwi_oauth_service_redirect',array('service'=>'google')));
            }
        }


        return array(
            'entity' => $entity,
            'form' => $form->createView()
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
        throw new AccessDeniedException("Vous avez essayé de vous inscrire sans clef valide");
    }

} 