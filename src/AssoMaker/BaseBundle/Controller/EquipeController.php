<?php

namespace AssoMaker\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\BaseBundle\Entity\Equipe;
use AssoMaker\PHPMBundle\Form\EquipeType;

/**
 * Equipe controller.
 *
 * @Route("/equipe")
 */
class EquipeController extends Controller
{
    /**
     * Lists all Equipe entities.
     * @Route("/index.{_format}", defaults={"_format"="html"}, requirements={"_format"="html|json"}, name="equipe")
     * 
     * @Template()
     */
    public function indexAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('AssoMakerBaseBundle:Equipe')->findAll();
        $format = $this->get('request')->getRequestFormat();
       

  		if($format=='html'){
            
            return array('entities' => $entities);
        }
        
        if ($format === 'json') {
            $a = array();
            foreach ($entities as $entity) {
                $a[$entity->getId()] = array(
                							'id' => $entity->getId(),
                							'nom' => $entity->getNom(),
                							'couleur' => $entity->getCouleur()
										);
            
            }
			
            return array('response'=>$a);
        }
    }

    /**
     * Finds and displays a Equipe entity.
     *
     * @Route("/{id}/show", name="equipe_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerBaseBundle:Equipe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipe entity.');
        }

        

        return array(
            'entity'      => $entity,
                    );
    }

    /**
     * Displays a form to create a new Equipe entity.
     *
     * @Route("/new", name="equipe_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Equipe();
        $form   = $this->createForm(new EquipeType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Equipe entity.
     *
     * @Route("/create", name="equipe_create")
     * @Method("post")
     * @Template("AssoMakerBaseBundle:Equipe:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Equipe();
        $request = $this->getRequest();
        $form    = $this->createForm(new EquipeType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('config'));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Equipe entity.
     *
     * @Route("/{id}/edit", name="equipe_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerBaseBundle:Equipe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipe entity.');
        }

        $editForm = $this->createForm(new EquipeType(), $entity);
        

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Edits an existing Equipe entity.
     *
     * @Route("/{id}/update", name="equipe_update")
     * @Method("post")
     * @Template("AssoMakerBaseBundle:Equipe:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerBaseBundle:Equipe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Equipe entity.');
        }

        $editForm   = $this->createForm(new EquipeType(), $entity);
        

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('equipe_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Deletes a Equipe entity.
     *
     * @Route("/{id}/delete", name="equipe_delete")
     * 
     */
    public function deleteAction($id)
    {
        
        $request = $this->getRequest();

        

        
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AssoMakerBaseBundle:Equipe')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Equipe entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('config'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
