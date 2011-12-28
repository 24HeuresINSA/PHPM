<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Confiance;
use PHPM\Bundle\Form\ConfianceType;

/**
 * Confiance controller.
 *
 * @Route("/confiance")
 */
class ConfianceController extends Controller
{
    /**
     * Lists all Confiance entities.
     *
     * @Route("/", name="confiance")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Confiance')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Confiance entity.
     *
     * @Route("/{id}/show", name="confiance_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Confiance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Confiance entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Confiance entity.
     *
     * @Route("/new", name="confiance_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Confiance();
        $form   = $this->createForm(new ConfianceType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Confiance entity.
     *
     * @Route("/create", name="confiance_create")
     * @Method("post")
     * @Template("PHPMBundle:Confiance:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Confiance();
        $request = $this->getRequest();
        $form    = $this->createForm(new ConfianceType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('confiance_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Confiance entity.
     *
     * @Route("/{id}/edit", name="confiance_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Confiance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Confiance entity.');
        }

        $editForm = $this->createForm(new ConfianceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Confiance entity.
     *
     * @Route("/{id}/update", name="confiance_update")
     * @Method("post")
     * @Template("PHPMBundle:Confiance:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Confiance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Confiance entity.');
        }

        $editForm   = $this->createForm(new ConfianceType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('confiance_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Confiance entity.
     *
     * @Route("/{id}/delete", name="confiance_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Confiance')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Confiance entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('confiance'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
