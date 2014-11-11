<?php
/**
 * Created by PhpStorm.
 * User: pvienne
 * Date: 10/11/14
 * Time: 14:02
 */

namespace AssoMaker\BaseBundle\Controller;


use AssoMaker\BaseBundle\Entity\Orga;
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
        $em = $this->getDoctrine()->getManager();
        $email = $request->getSession()->get('email');

        $entity = $this->token->getUser();

        $form = $this->createForm($this->get('form.type.registration'), $entity, array("confianceCode" => null));

        $form->handleRequest($request);

        if ($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $entity->setEnabled(true);
                $entity->addRole('ROLE_ADMIN');
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('check_oauth'));
            }
        }


        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

} 