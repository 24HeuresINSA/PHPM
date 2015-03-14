<?php

namespace AssoMaker\AnimBundle\Controller;

use AssoMaker\AnimBundle\Form\ArtisteType;
use AssoMaker\AnimBundle\Entity\Artiste;
use AssoMaker\AnimBundle\Entity\PhotoArtiste;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 *
 * @Route("/artiste")
 * @Security("has_role('ROLE_CONCERT')")
 *
 */
class ArtisteController extends Controller {

    /**
     * @Route("/", name="artiste_index")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $config = $e = $this->get('config.extension');

        $artistes = $em->createQuery("SELECT a FROM AssoMakerAnimBundle:Artiste a ORDER BY a.statut DESC")->getArrayResult();
        return array('artistes' => json_encode($artistes));
    }

    /**
     *
     * @Route("/new", name="artiste_new")
     * @Template
     */
    public function newAction() {
        $em = $this->getDoctrine()->getManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();
        $admin = $this->get('security.context')->isGranted('ROLE_CONCERT');

        $entity = new Artiste();
        $entity->setStatut(0);
        $defaultValues = array('entity' => $entity);
        $editForm = $this->createForm(new ArtisteType($admin, $config, true), $defaultValues);

        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                $entity->addCommentaire($user, "Création de la fiche artiste");
                $em->persist($entity);
                $em->flush();

                return $this
                                ->redirect($this->generateUrl('artiste_edit', array('id' => $entity->getId())));
            }
        }

        return array('entity' => $entity, 'form' => $editForm->createView());
    }

    /**
     *
     * @Route("/{id}/edit", name="artiste_edit")
     * @Template
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $config = $e = $this->get('config.extension');
        $entity = $em->getRepository('AssoMakerAnimBundle:Artiste')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        $request = $this->getRequest();
        $param = $request->request->all();


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Artiste entity.');
        }

        $defaultValues = array('entity' => $entity, "commentaire" => '');
        $editForm = $this->createForm(new ArtisteType($config, false), $defaultValues);

        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {

                $data = $editForm->getData();
                $typeCommentaire = 0;

                if ($param['action'] == 'submit_publish') {
                    $entity->setStatut(2);
                    $typeCommentaire = 2;
                }


                if ($param['action'] == 'unpublish') {
                    $entity->setStatut(0);
                    $typeCommentaire = 3;
                }

                if ($data['commentaire'] != '' || $typeCommentaire != 0) {
                    $entity->addCommentaire($user, $data['commentaire'], $typeCommentaire);
                }

                if ($editForm['photo']->getData())
                {
                    $photoData = $editForm['photo']->getData();
                    $photo = new PhotoArtiste();
                    $photo->setArtiste($entity);
                    $photo->setNom(microtime() . '-' . $photoData->getClientOriginalName());
                    $photoData->move($this->get('kernel')->getRootDir() . '/../web/up/artistsPicturesMobile', $photo->getNom());
                    $em->persist($photo);
                }

                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('artiste_edit', array('id' => $entity->getId())));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView()
        );
    }

    /**
     *
     * @Route("/deletePhoto/{id}/{token}", name="artiste_delete_photo")
     * @Template
     */
    public function deletePhotoAction($id, $token)
    {

        if (!$this->get('security.csrf.token_manager')->isTokenValid(new CsrfToken('artiste_delete_photo', $token)))
        {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AssoMakerAnimBundle:PhotoArtiste')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PhotoArtiste entity.');
        }

        $idArtiste = $entity->getArtiste()->getId();
        unlink($this->get('kernel')->getRootDir() . '/../web/up/artistsPicturesMobile/' . $entity->getNom());
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('artiste_edit', array('id' => $idArtiste)));

    }
}
