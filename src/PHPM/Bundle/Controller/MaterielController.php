<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Materiel;
use PHPM\Bundle\Form\MaterielType;

/**
 * Materiel controller.
 *
 * @Route("/materiel")
 */
class MaterielController extends Controller
{
    /**
     * Lists all Materiel entities.
     *
     * @Route("/", name="materiel")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Materiel')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Materiel entity.
     *
     * @Route("/{id}/show", name="materiel_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Materiel entity.
     *
     * @Route("/new", name="materiel_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Materiel();
        $form   = $this->createForm(new MaterielType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Materiel entity.
     *
     * @Route("/create", name="materiel_create")
     * @Method("post")
     * @Template("PHPMBundle:Materiel:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Materiel();
        $request = $this->getRequest();
        $form    = $this->createForm(new MaterielType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('config_manif'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Materiel entity.
     *
     * @Route("/{id}/edit", name="materiel_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        $editForm = $this->createForm(new MaterielType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Materiel entity.
     *
     * @Route("/{id}/update", name="materiel_update")
     * @Method("post")
     * @Template("PHPMBundle:Materiel:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Materiel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Materiel entity.');
        }

        $editForm   = $this->createForm(new MaterielType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('materiel_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Materiel entity.
     *
     * @Route("/{id}/delete", name="materiel_delete")
     * 
     */
    public function deleteAction($id)
    {
        
        $request = $this->getRequest();

            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Materiel')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Materiel entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('config_manif'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}