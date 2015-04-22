<?php

namespace AssoMaker\AnimBundle\Controller;

use AssoMaker\AnimBundle\Form\AnimationType;
use AssoMaker\AnimBundle\Entity\Animation;
use AssoMaker\AnimBundle\Entity\PhotoAnimation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        $em = $this->getDoctrine()->getManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();

        $animations = $em->createQuery("SELECT a,r,e FROM AssoMakerAnimBundle:Animation a JOIN a.responsable r JOIN a.equipe e ORDER BY a.statut DESC ")->getArrayResult();
        return array('animations' => json_encode($animations),
            'types' => json_encode(Animation::$animTypes)
        );
    }

    /**
     * @Route("/brochure", name="anim_animation_brochure")
     * @Template()
     */
    public function brochureAction() {
        $em = $this->getDoctrine()->getManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();

        $animations = $em->createQuery("SELECT a,r,e FROM AssoMakerAnimBundle:Animation a JOIN a.responsable r JOIN a.equipe e WHERE a.statut = 2 AND a.public = true ")->getArrayResult();
        return array('animations' => $animations
        );
    }

    /**
     * @Route("/map", name="anim_animation_map")
     * @Template()
     */
    public function mapAction() {
        $em = $this->getDoctrine()->getManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();

        $animations = $em->createQuery("SELECT a,r,e FROM AssoMakerAnimBundle:Animation a JOIN a.responsable r JOIN a.equipe e WHERE a.statut >= 0 ORDER BY a.statut DESC ")->getArrayResult();
        return array('animations' => json_encode($animations),
            'types' => json_encode(Animation::$animTypes)
        );
    }

    /**
     * @Route("/animations.json", name="anim_animation_index_json")
     */
    public function indexJsonAction() {
        $em = $this->getDoctrine()->getManager();

        $config = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();

        $animations = $em->createQuery("SELECT a FROM AssoMakerAnimBundle:Animation a WHERE a.statut =2 and a.public = TRUE")->getResult();
        $animationsArray = array();

        foreach ($animations as $animation) {
            $animArray = array();
            $animArray['id'] = $animation->getId();
            $animArray['nom'] = $animation->getNom();
            $animArray['lieu'] = $animation->getLieu();
            $animArray['type'] = $animation->getType();
            $animArray['locX'] = $animation->getLocX();
            $animArray['locY'] = $animation->getLocY();
            $animArray['horaires'] = $animation->getHoraires();
            $animArray['gosses'] = $animation->getAnimGosses();
            $animArray['phare'] = $animation->getAnimPhare();
            $animArray['description'] = $animation->getDescription();
            $animArray['pictureExtension'] = $animation->getPictureExtension();


            $animationsArray[$animation->getType()][] = $animArray;
        }

        $responseArray = array('animations' => $animationsArray, 'types' => Animation::$animTypes);

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
        $em = $this->getDoctrine()->getManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');

        $entity = new Animation();
        $entity->setStatut(0);
        $defaultValues = array('entity' => $entity);
        $editForm = $this->createForm(new AnimationType($admin, $config, true), $defaultValues);

        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->handleRequest($request);

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
        $em = $this->getDoctrine()->getManager();
        $config = $e = $this->get('config.extension');
        $entity = $em->getRepository('AssoMakerAnimBundle:Animation')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $humain = $this->get('security.context')->isGranted('ROLE_HUMAIN');
        $log = $this->get('security.context')->isGranted('ROLE_LOG');
        $secu = $this->get('security.context')->isGranted('ROLE_SECU');
        $request = $this->getRequest();
        $param = $request->request->all();


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Animation entity.');
        }


        $readOnly = array("h" => (!$humain) && ( $entity->getValidHumain() || ($entity->getStatut() >= 1) ), "s" => (!$secu) && ( $entity->getValidSecu() || ($entity->getStatut() >= 1) ), "l" => (!$log) && ( $entity->getValidLog() || ($entity->getStatut() >= 1) ));

        $defaultValues = array('entity' => $entity, "commentaire" => '');
        $editForm = $this->createForm(new AnimationType($log, $config, false, $readOnly), $defaultValues);

        $rawListeLieux = $em->createQuery("SELECT a.lieu FROM AssoMakerAnimBundle:Animation a WHERE a.lieu IS NOT NULL GROUP BY a.lieu")->getScalarResult();
        $listeLieux = array();
        foreach ($rawListeLieux as $l) {
            $listeLieux[] = $l['lieu'];
        }


        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->handleRequest($request);



            if ($editForm->isValid()) {


                $entity->uploadPubPicture();
                $data = $editForm->getData();
                $typeCommentaire = 0;


                if ($param['action'] == 'submit_validation') {
                    $entity->setStatut(1);
                    $typeCommentaire = 1;
                }

                if ($humain && $param['action'] == 'validate') {
                    $entity->setStatut(2);
                    $typeCommentaire = 2;
                }



                if ($param['action'] == 'rejectLog' && ($log)) {
                    $entity->setStatut(0);
                    $typeCommentaire = 8;
                    $entity->setValidLog(false);
                }

                if ($param['action'] == 'rejectSecu' && ($secu)) {
                    $entity->setStatut(0);
                    $typeCommentaire = 9;
                    $entity->setValidSecu(false);
                }

                if ($param['action'] == 'rejectHumain' && ($humain)) {
                    $entity->setStatut(0);
                    $typeCommentaire = 10;
                    $entity->setValidHumain(false);
                }

                if ($param['action'] == 'devalidate') {
                    $entity->setStatut(0);
                    $typeCommentaire = 11;
                }



                if (($log) && $param['action'] == 'validateLog') {
                    $entity->setValidLog(true);
                    $typeCommentaire = 5;
                }


                if (($secu) && $param['action'] == 'validateSecu') {
                    $entity->setValidSecu(true);
                    $typeCommentaire = 6;
                }

                if (($humain) && $param['action'] == 'validateHumain') {
                    $entity->setValidHumain(true);
                    $typeCommentaire = 7;
                }

                if (($entity->getStatut() <= 1 || $humain) && $param['action'] == 'delete') {
                    $entity->setStatut(-1);
                    $typeCommentaire = -1;
                }
                if ($entity->getStatut() == -1 && $param['action'] == 'restore') {
                    $entity->setStatut(0);
                    $typeCommentaire = 4;
                }

                if ($entity->getValidHumain() && $entity->getValidLog() && $entity->getValidSecu()) {
                    $entity->setStatut(2);
                }



                if ($data['commentaire'] != '' || $typeCommentaire != 0) {
                    $entity->addCommentaire($user, $data['commentaire'], $typeCommentaire);
                }

                if ($editForm['photoMob']->getData())
                {
                    $photoData = $editForm['photoMob']->getData();
                    $photoMob = new PhotoAnimation();
                    $photoMob->setAnimation($entity);
                    $photoMob->setNom(microtime() . '-' . $photoData->getClientOriginalName());
                    $photoData->move($this->get('kernel')->getRootDir() . '/../web/up/animPicturesMobile', $photoMob->getNom());
                    $em->persist($photoMob);
                }

                $em->persist($entity);
                $em->flush();

                if ($entity->getBesoinPass() && $entity->getPassAssocies()->count() == 0) {
                    return $this->redirect($this->generateUrl('pass_pass_new', array('animId' => $entity->getId())));
                }
                if ($param['action'] == 'devalidate') {
                    return $this->redirect($this->generateUrl('anim_animation_edit', array('id' => $entity->getId())));
                }

                if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
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

    /**
     *
     * @Route("/deletePhotoMobile/{id}/{token}", name="anim_animation_delete_photo_mobile")
     * @Template
     */
    public function deletePhotoMobileAction($id, $token)
    {

        if (!$this->get('security.csrf.token_manager')->isTokenValid(new CsrfToken('anim_animation_delete_photo_mobile', $token)))
        {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $config = $e = $this->get('config.extension');
        $entity = $em->getRepository('AssoMakerAnimBundle:PhotoAnimation')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $humain = $this->get('security.context')->isGranted('ROLE_HUMAIN');


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PhotoAnimation entity.');
        }

        $idAnim = $entity->getAnimation()->getId();
        unlink($this->get('kernel')->getRootDir() . '/../web/up/animPicturesMobile/' . $entity->getNom());
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('anim_animation_edit', array('id' => $idAnim)));

    }
}
