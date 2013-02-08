<?php

namespace AssoMaker\AnimBundle\Controller;

use AssoMaker\AnimBundle\Form\AnimationType;
use AssoMaker\AnimBundle\Entity\Animation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 *
 * @Route("/anim")
 *
 *
 */
class AnimationController extends Controller {

    /**
     * @Route("/", name="anim_animation_index")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();

        $animations = $em->createQuery("SELECT a,r,e FROM AssoMakerAnimBundle:Animation a JOIN a.responsable r JOIN a.equipe e ORDER BY a.statut DESC ")->getArrayResult();
        return array('animations' => json_encode($animations),
            'types' => json_encode(Animation::$animTypes)
        );
    }

    /**
     * @Route("/animations.json", name="anim_animation_index_json")
     */
    public function indexJsonAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $config = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();

        $animations = $em->createQuery("SELECT a FROM AssoMakerAnimBundle:Animation a ")->getResult();
        $responseArray = array();
        foreach ($animations as $animation) {
            $animArray = array();
            $animArray['nom'] = $animation->getNom();
            $animArray['lieu'] = $animation->getLieu();
            if ($this->get('security.context')->isGranted('ROLE_USER')) {

            }
            $responseArray[] = $animArray;
        }

        $response = new Response(json_encode($responseArray));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     *
     * @Route("/new", name="anim_animation_new")
     * @Template
     */
    public function newAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');

        $entity = new Animation();
        $entity->setStatut(0);
        $defaultValues = array('entity' => $entity);
        $editForm = $this->createForm(new AnimationType($admin, $config, true), $defaultValues);

        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);

            if ($editForm->isValid()) {
                $entity->addCommentaire($user, "Création de la fiche");
                $em->persist($entity);
                $em->flush();

                return $this
                                ->redirect($this->generateUrl('anim_animation_edit', array('id' => $entity->getId())));
            }
        }

        return array('entity' => $entity, 'form' => $editForm->createView());
    }

    /**
     *
     * @Route("/{id}/edit", name="anim_animation_edit")
     * @Template
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $entity = $em->getRepository('AssoMakerAnimBundle:Animation')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');
        $log = $this->get('security.context')->isGranted('ROLE_LOG');
        $secu = $this->get('security.context')->isGranted('ROLE_SECU');
        $request = $this->getRequest();
        $param = $request->request->all();


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Animation entity.');
        }

        $readOnly = (!($admin || $secu || $log) && ($entity->getStatut() >= 1));

        $defaultValues = array('entity' => $entity, "commentaire" => '');
        $editForm = $this->createForm(new AnimationType($admin, $config, false, $readOnly), $defaultValues);

        $rawListeLieux = $em->createQuery("SELECT a.lieu FROM AssoMakerAnimBundle:Animation a WHERE a.lieu IS NOT NULL GROUP BY a.lieu")->getScalarResult();
        $listeLieux = array();
        foreach ($rawListeLieux as $l) {
            $listeLieux[] = $l['lieu'];
        }


        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);



            if ($editForm->isValid()) {



                $data = $editForm->getData();

                $typeCommentaire = 0;


                if ($param['action'] == 'submit_validation') {
                    $entity->setStatut(1);
                    $typeCommentaire = 1;
                }

                if ($admin && $param['action'] == 'validate') {
                    $entity->setStatut(2);
                    $typeCommentaire = 2;
                }

                if (($admin || $log || $secu) && $param['action'] == 'reject') {
                    $entity->setStatut(0);
                    $typeCommentaire = 3;
                    $entity->setValidLog(false);
                    $entity->setValidSecu(false);
                }

                if ($entity->getStatut() <= 1 && $param['action'] == 'delete') {
                    $entity->setStatut(-1);
                    $typeCommentaire = -1;
                }
                if ($entity->getStatut() == -1 && $param['action'] == 'restore') {
                    $entity->setStatut(0);
                    $typeCommentaire = 4;
                }

                if ($entity->getStatut() >= 1 && ($log) && $param['action'] == 'validateLog') {
                    $entity->setValidLog(true);
                    $typeCommentaire = 5;
                }


                if ($entity->getStatut() >= 1 && ($secu) && $param['action'] == 'validateSecu') {
                    $entity->setValidSecu(true);
                    $typeCommentaire = 6;
                }



                if ($data['commentaire'] != '' || $typeCommentaire != 0) {
                    $entity->addCommentaire($user, $data['commentaire'], $typeCommentaire);
                }

                $em->persist($entity);
                $em->flush();

                if ($entity->getBesoinPass() && $entity->getPassAssocies()->count() == 0) {
                    return $this->redirect($this->generateUrl('pass_pass_new', array('animId' => $entity->getId())));
                }
                if ($this->get('security.context')->isGranted('ROLE_USER')) {
                    return $this->redirect($this->generateUrl('anim_animation_index'));
                }
                return $this->redirect($this->generateUrl('anim_animation_edit', array('id' => $entity->getId())));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'listeLieux' => json_encode($listeLieux),
            'readOnly' => $readOnly
        );
    }

}
