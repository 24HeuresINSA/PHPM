<?php

namespace AssoMaker\PassSecuBundle\Controller;

use AssoMaker\AnimBundle\Entity\Animation;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use AssoMaker\PassSecuBundle\Form\PassType;
use AssoMaker\PassSecuBundle\Entity\Pass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 *
 * @Route("/pass")
 * @Security("has_role('ROLE_HARD')")
 *
 */
class DefaultController extends Controller {

    /**
     * @Route("/",name="pass_pass_index")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();

        $passes = $em->createQuery("SELECT p FROM AssoMakerPassSecuBundle:Pass p")->getArrayResult();
        return array('passes' => json_encode($passes)
        );
    }

    /**
     *
     * @Route("/new/{animId}", defaults={"animId"=""},name="pass_pass_new")
     * @Template
     */
    public function newAction($animId) {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');

        $entity = new Pass();

        if ($animId != '') {
            $anim = $em->getRepository('AssoMakerAnimBundle:Animation')->find($animId);
            if (!$anim) {
                throw $this->createNotFoundException('Unable to find Animation entity.');
            }
            $entity->setAnimationLiee($anim);
            //$entity->setEmailDemandeur($anim->getExtEmail());
            $entity->setTelephoneDemandeur($anim->getExtTelephone());
            $entity->setEntite(Animation::$extTypes[$anim->getExtType()] . ' ' . $anim->getExtNom());
            $entity->setInfosSupplementaires('Pass demandé par ' . $user->getPrenom() . ' ' . $user->getNom());
        }

        $entity->setAccessCode(md5(uniqid(rand(), true)));

        $editForm = $this->createForm(new PassType(false, $config, true), $entity);

        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this
                                ->redirect($this->generateUrl('pass_pass_edit', array('id' => $entity->getId(), 'code' => $entity->getAccessCode())));
            }
        }

        return array('entity' => $entity, 'form' => $editForm->createView());
    }

    /**
     *
     * @Route("/{id}/{code}", name="pass_pass_edit")
     * @Template
     */
    public function editAction($id, $code) {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $entity = $em->getRepository('AssoMakerPassSecuBundle:Pass')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_SECU');
        $request = $this->getRequest();
        $param = $request->request->all();

        $guest = !$this->get('security.context')->isGranted('ROLE_USER');


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pass entity.');
        }

        if ($entity->getAccessCode() != $code) {
            throw new AccessDeniedException();
        }

        $editForm = $this->createForm(new PassType($guest, $config, false), $entity);


        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->handleRequest($request);
            $data = $editForm->getData();

            if ($editForm->isValid()) {


                $data = $editForm->getData();



                if ($param['action'] == 'submit') {
                    $entity->setStatut(1);
                }

                if ($admin && $param['action'] == 'validate') {
                    $entity->setStatut(2);
                }

                if ($admin && $param['action'] == 'sent') {
                    $entity->setStatut(3);
                }

                if ($admin && $param['action'] == 'devalidate') {
                    $entity->setStatut(0);
                }


                $em->persist($entity);
                $em->flush();

                if ($param['action'] == 'sent') {
                    return $this->redirect($this->generateUrl('pass_pass_index'));
                }
                return $this->redirect($this->generateUrl('pass_pass_edit', array('id' => $entity->getId(), 'code' => $entity->getAccessCode())));
            }
        }


        if (!$admin && $entity->getStatut() >= 2) {
            return $this->redirect($this->generateUrl('pass_pass_print', array('id' => $entity->getId(), 'code' => $entity->getAccessCode())));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'points' => Pass::$points
        );
    }

    /**
     *
     * @Route("/print/{id}/{code}", name="pass_pass_print")
     * @Template
     */
    public function printAction($id, $code) {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $entity = $em->getRepository('AssoMakerPassSecuBundle:Pass')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_SECU');
        $request = $this->getRequest();
        $param = $request->request->all();

        $guest = !$this->get('security.context')->isGranted('ROLE_USER');

        if (!$admin && $entity->getStatut() < 2) {
            throw new AccessDeniedException();
        }


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pass entity.');
        }

        if ($entity->getAccessCode() != $code) {
            throw new AccessDeniedException();
        }


        return array(
            'entity' => $entity,
            'points' => Pass::$points,
            'validites' => Pass::$validiteChoices
        );
    }

}
