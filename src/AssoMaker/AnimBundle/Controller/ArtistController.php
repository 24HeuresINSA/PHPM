<?php

namespace AssoMaker\AnimBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\AnimBundle\Entity\Artist;
use AssoMaker\AnimBundle\Form\ArtistType;

/**
 * Artist controller.
 *
 * @Route("/artist")
 */
class ArtistController extends Controller
{

    /**
     * Lists all Artist entities.
     *
     * @Route("/", name="artist")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AssoMakerAnimBundle:Artist')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Artist entity.
     *
     * @Route("/", name="artist_create")
     * @Method("POST")
     * @Template("AssoMakerAnimBundle:Artist:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Artist();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('artist_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Artist entity.
     *
     * @param Artist $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Artist $entity)
    {
        $form = $this->createForm(new ArtistType(), $entity, array(
            'action' => $this->generateUrl('artist_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Artist entity.
     *
     * @Route("/new", name="artist_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = new Artist();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Artist entity.
     *
     * @Route("/{id}", name="artist_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AssoMakerAnimBundle:Artist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Artist entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Artist entity.
     *
     * @Route("/{id}/edit", name="artist_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AssoMakerAnimBundle:Artist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Artist entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Artist entity.
    *
    * @param Artist $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Artist $entity)
    {
        $form = $this->createForm(new ArtistType(), $entity, array(
            'action' => $this->generateUrl('artist_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Artist entity.
     *
     * @Route("/{id}", name="artist_update")
     * @Method("PUT")
     * @Template("AssoMakerAnimBundle:Artist:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AssoMakerAnimBundle:Artist')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Artist entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('artist_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Artist entity.
     *
     * @Route("/{id}", name="artist_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AssoMakerAnimBundle:Artist')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Artist entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('artist'));
    }

    /**
     * Creates a form to delete a Artist entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('artist_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
