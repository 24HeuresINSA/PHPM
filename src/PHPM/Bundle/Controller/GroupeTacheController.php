<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\GroupeTache;
use PHPM\Bundle\Form\GroupeTacheType;

/**
 * GroupeTache controller.
 *
 * @Route("/groupetache")
 */
class GroupeTacheController extends Controller
{
    /**
     * Lists all GroupeTache entities.
     *
     * @Route("/", name="groupetache")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities =$em
        ->createQuery("SELECT g FROM PHPMBundle:GroupeTache g JOIN g.equipe e ORDER BY e.id ")
        ->getResult();
        

        

        return array('entities'=>$entities);
    }

    /**
     * Finds and displays a GroupeTache entity.
     *
     * @Route("/{id}/show", name="groupetache_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:GroupeTache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupeTache entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new GroupeTache entity.
     *
     * @Route("/new", name="groupetache_new")
     * @Template()
     */
    public function newAction()
    {
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();
        $entity = new GroupeTache();
        $entity->setResponsable($user);
        $form   = $this->createForm(new GroupeTacheType($admin,$config), $entity);
        
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new GroupeTache entity.
     *
     * @Route("/create", name="groupetache_create")
     * @Method("post")
     * @Template("PHPMBundle:GroupeTache:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new GroupeTache();
        $entity->setStatut(0);
        $request = $this->getRequest();
        $form    = $this->createForm(new GroupeTacheType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupetache_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing GroupeTache entity.
     *
     * @Route("/{id}/edit", name="groupetache_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $admin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$this->get('config.extension');
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:GroupeTache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupeTache entity.');
        }

        $editForm = $this->createForm(new GroupeTacheType($admin,$config), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing GroupeTache entity.
     *
     * @Route("/{id}/update", name="groupetache_update")
     * @Method("post")
     * @Template("PHPMBundle:GroupeTache:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:GroupeTache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupeTache entity.');
        }

        $editForm   = $this->createForm(new GroupeTacheType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupetache_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a GroupeTache entity.
     *
     * @Route("/{id}/delete", name="groupetache_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:GroupeTache')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GroupeTache entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('groupetache'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
