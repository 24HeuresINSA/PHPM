<?php

namespace AssoMaker\BaseBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\BaseBundle\Entity\Confiance;
use AssoMaker\PHPMBundle\Form\ConfianceType;

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
     * 
     * @Route("/index.{_format}", defaults={"_format"="html"}, requirements={"_format"="html|json"}, name="confiance")
     * 
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('AssoMakerBaseBundle:Confiance')->findAll();
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
     * Finds and displays a Confiance entity.
     *
     * @Route("/{id}/show", name="confiance_show")
     * @Template()
     */
    public function showAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerBaseBundle:Confiance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Confiance entity.');
        }

        

        return array(
            'entity'      => $entity,
                    );
    }

    /**
     * Displays a form to create a new Confiance entity.
     *
     * @Route("/new", name="confiance_new")
     * @Template()
     */
    public function newAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
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
     * @Template("AssoMakerBaseBundle:Confiance:new.html.twig")
     */
    public function createAction()
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $entity  = new Confiance();
        $request = $this->getRequest();
        $form    = $this->createForm(new ConfianceType(), $entity);
        $form->handleRequest($request);

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
     * Displays a form to edit an existing Confiance entity.
     *
     * @Route("/{id}/edit", name="confiance_edit")
     * @Template()
     */
    public function editAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerBaseBundle:Confiance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Confiance entity.');
        }

        $editForm = $this->createForm(new ConfianceType(), $entity);
        

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Edits an existing Confiance entity.
     *
     * @Route("/{id}/update", name="confiance_update")
     * @Method("post")
     * @Template("AssoMakerBaseBundle:Confiance:edit.html.twig")
     */
    public function updateAction($id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
    		throw new AccessDeniedException();
    	}
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerBaseBundle:Confiance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Confiance entity.');
        }

        $editForm   = $this->createForm(new ConfianceType(), $entity);
        

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('config'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            
        );
    }

    /**
     * Deletes a Confiance entity.
     *
     * @Route("/{id}/delete", name="confiance_delete")
     * 
     */
    public function deleteAction($id)
    {
       
       $em = $this->getDoctrine()->getEntityManager();
       $entity = $em->getRepository('AssoMakerBaseBundle:Confiance')->find($id);

       if (!$entity) {
           throw $this->createNotFoundException('Unable to find Confiance entity.');
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
