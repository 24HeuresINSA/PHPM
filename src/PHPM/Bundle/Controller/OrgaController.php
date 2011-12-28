<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Orga;
use PHPM\Bundle\Form\OrgaType;

/**
 * Orga controller.
 *
 * @Route("/orga")
 */
class OrgaController extends Controller
{
    /**
     * Lists all Orga entities.
     *
     * @Route("/", name="orga")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Orga')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Orga entity.
     *
     * @Route("/{id}/show", name="orga_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Orga entity.
     *
     * @Route("/new", name="orga_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Orga();
        $form   = $this->createForm(new OrgaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Orga entity.
     *
     * @Route("/create", name="orga_create")
     * @Method("post")
     * @Template("PHPMBundle:Orga:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Orga();
        $request = $this->getRequest();
        $form    = $this->createForm(new OrgaType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('orga_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Orga entity.
     *
     * @Route("/{id}/edit", name="orga_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        $editForm = $this->createForm(new OrgaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Orga entity.
     *
     * @Route("/{id}/update", name="orga_update")
     * @Method("post")
     * @Template("PHPMBundle:Orga:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Orga entity.');
        }

        $editForm   = $this->createForm(new OrgaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('orga_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Orga entity.
     *
     * @Route("/{id}/delete", name="orga_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Orga')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Orga entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('orga'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	
	
	 /**
     * Import Orgas from website.
     *
     * @Route("/import", name="orga_import")
     * 
     */
	public function importAction()	
	{
		
					
			$url = "inscriptionOrgas.json";			
			$json = file_get_contents($url);
			
			$listeOrgaArray = json_decode($json,TRUE);
			
			echo ("<pre>");
			var_dump($listeOrgaArray);
			echo("</pre>");
				
				
				
				
			
			/*	
			$plagehorraire = array ("1325083264", "1325083265");	
				
			$disponibilites = array ($plagehorraire,$plagehorraire, $plagehorraire);
							
        	$orga = array("id"=>1, "nom"=>"Bourgin", "prenom" => "Sylvain", "telephone" => "0685178329",
        	"email" => "patate@gmail.com", "dateDeNaissance" => "2012-01-01", "departement" => "IF", 
        	"commentaire" => "Le charisme c'est au BDE qu'on le trouve", "permisB" => "true", "permisB2ans" => "true",
        	"disponibilites" => $disponibilites);
			$orgas = array($orga,$orga,$orga,$orga);
			exit(print_r(json_encode($orgas)));
			*/
	
  
     	
     	
     	return array();
		
		
	}
	
}
