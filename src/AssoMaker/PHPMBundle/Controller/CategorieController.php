<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Categorie;
use AssoMaker\PHPMBundle\Form\CategorieType;

/**
 * Categorie controller.
 *
 * @Route("/categorie")
 */
class CategorieController extends Controller
{
    /**
     * Lists all Categorie entities.
     *
     * @Route("/index.{_format}", defaults={"_format"="html"}, requirements={"_format"="html|json"}, name="categorie")
     * 
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('AssoMakerPHPMBundle:Categorie')->findAll();
        $format = $this->get('request')->getRequestFormat();
        if($format=='html'){
            
            return array('entities' => $entities);
        }
        
        if($format=='json'){
            $a = array();
            foreach ($entities as $entity){
                $a[$entity->getId()] = $entity->toSimpleArray();
            
            }
            return array('response'=>$a);
        }
        
        
    }
    

    /**
     * Finds and displays a Categorie entity.
     *
     * @Route("/{id}/show", name="categorie_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Categorie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categorie entity.');
        }

        

        return array(
            'entity'      => $entity,
                    );
    }

    /**
     * Displays a form to create a new Categorie entity.
     *
     * @Route("/new", name="categorie_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Categorie();
        $form   = $this->createForm(new CategorieType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Categorie entity.
     *
     * @Route("/create", name="categorie_create")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:Categorie:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Categorie();
        $request = $this->getRequest();
        $form    = $this->createForm(new CategorieType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('categorie_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Categorie entity.
     *
     * @Route("/{id}/edit", name="categorie_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Categorie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categorie entity.');
        }

        $editForm = $this->createForm(new CategorieType(), $entity);
        

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Edits an existing Categorie entity.
     *
     * @Route("/{id}/update", name="categorie_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:Categorie:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:Categorie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categorie entity.');
        }

        $editForm   = $this->createForm(new CategorieType(), $entity);
        

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('categorie_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Deletes a Categorie entity.
     *
     * @Route("/{id}/delete", name="categorie_delete")
     * 
     */
    public function deleteAction($id)
    {
        
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AssoMakerPHPMBundle:Categorie')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Categorie entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('categorie'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
