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
            return $this->redirect($this->generateUrl("register"));
        else
            return $this->redirect($this->generateUrl("base_accueil"));
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