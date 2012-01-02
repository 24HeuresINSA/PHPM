<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Creneau;
use PHPM\Bundle\Form\CreneauType;
use PHPM\Bundle\Validator\QuartHeure;
/**
 * Creneau controller.
 *
 * @Route("/creneau")
 */
class CreneauController extends Controller
{
    /**
     * Lists all Creneau entities.
     *
     * @Route("/", name="creneau")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Creneau')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Creneau entity.
     *
     * @Route("/{id}/show", name="creneau_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Creneau')->find($id);
		


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creneau entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Creneau entity.
     *
     * @Route("/new", name="creneau_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Creneau();
        $form   = $this->createForm(new CreneauType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Creneau entity.
     *
     * @Route("/create", name="creneau_create")
     * @Method("post")
     * @Template("PHPMBundle:Creneau:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Creneau();
        $request = $this->getRequest();
        $form    = $this->createForm(new CreneauType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('creneau_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Creneau entity.
     *
     * @Route("/{id}/edit", name="creneau_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Creneau')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creneau entity.');
        }

        $editForm = $this->createForm(new CreneauType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Creneau entity.
     *
     * @Route("/{id}/update", name="creneau_update")
     * @Method("post")
     * @Template("PHPMBundle:Creneau:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Creneau')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Creneau entity.');
        }

        $editForm   = $this->createForm(new CreneauType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('creneau_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Creneau entity.
     *
     * @Route("/{id}/delete", name="creneau_delete")
     * 
     */
    public function deleteAction($id)
    {
       
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Creneau')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Creneau entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('creneau'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
    * Lists all Creneaux entities according to post criteria.
    *
    * @Route("/query.json", name="creneau_query_json")
    * @Method("post")
    */
    public function queryJsonAction()
    {
    
    	$request = $this->getRequest();
    
    	$permis= $request->request->get('permisNecessaire', '');
    	$age= $request->request->get('age', '0');
    	$plage_id= $request->request->get('plage_id', '');
    	$niveau_confiance= $request->request->get('confiance_id', '');
    	$categorie = $request->request->get('categorie_id', '');
    	$duree = $request->request->get('duree', '');
    	$orga = $request->request->get('orga_id', '');
    	$plage = $request->request->get('plage_id', '');
    
    
    
    	$em = $this->getDoctrine()->getEntityManager();
    	$entities = $em->getRepository('PHPMBundle:Creneau')->getCreneauxCompatibleWithCriteria($niveau_confiance, $categorie, $age, $permis, $duree, $orga, $plage);
    
    	$response = new Response();
    
    
    	$creneauArray = array();
    	foreach ($entities as $creneau){
	
    			
    		$creneauArray[$creneau->getId()]= array(
    		 
    
    		 
    			        	"nom" => $creneau->getPlageHoraire()->getTache()->getNom(),
    						"lieu" => $creneau->getPlageHoraire()->getTache()->getLieu(),
    						"confiance" => $creneau->getPlageHoraire()->getTache()->getConfiance()->getId(),
    						"categorie" => $creneau->getPlageHoraire()->getTache()->getCategorie()->getId(),
				    		"debut" => $creneau->getDebut,
				    		"fin" => $creneau->getFin,
    			        	"duree" => $creneau->getDuree(),
    			    		"permis"=> $creneau->getPlageHoraire()->getTache()->getPermisNecessaire(),
    			        	);
    			
    			
    			
    			
    			
    	}
    	 
    	exit(var_dump($orgaArray));
    	 
    	$response = new Response();
    	$response->setContent(json_encode($orgaArray));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    
    	return $response;
    }

}
