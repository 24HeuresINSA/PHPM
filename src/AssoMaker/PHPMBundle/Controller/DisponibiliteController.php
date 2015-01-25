<?php

namespace AssoMaker\PHPMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Disponibilite;
use AssoMaker\PHPMBundle\Form\DisponibiliteType;
use AssoMaker\PHPMBundle\Validator\QuartHeure;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;


/**
 * Disponibilite controller.
 *
 * @Route("/disponibilite")
 */
class DisponibiliteController extends Controller
{
//     /**
//      * Lists all Disponibilite entities.
//      *
//      * @Route("/", name="disponibilite")
//      * @Template()
//      */
//     public function indexAction()
//     {
//         $em = $this->getDoctrine()->getEntityManager();

//         $entities = $em->getRepository('AssoMakerPHPMBundle:Disponibilite')->findAll();

//         return array('entities' => $entities);
//     }

//     /**
//      * Finds and displays a Disponibilite entity.
//      *
//      * @Route("/{id}/show", name="disponibilite_show")
//      * @Template()
//      */
//     public function showAction($id)
//     {
//         $em = $this->getDoctrine()->getEntityManager();

//         $entity = $em->getRepository('AssoMakerPHPMBundle:Disponibilite')->find($id);

//         if (!$entity) {
//             throw $this->createNotFoundException('Unable to find Disponibilite entity.');
//         }

//         

//         return array(
//             'entity'      => $entity,
//                     );
//     }

//     /**
//      * Displays a form to create a new Disponibilite entity.
//      *
//      * @Route("/new", name="disponibilite_new")
//      * @Template()
//      */
//     public function newAction()
//     {
//         $entity = new Disponibilite();
//         $form   = $this->createForm(new DisponibiliteType(), $entity);

//         return array(
//             'entity' => $entity,
//             'form'   => $form->createView()
//         );
//     }

//     /**
//      * Creates a new Disponibilite entity.
//      *
//      * @Route("/create", name="disponibilite_create")
//      * @Method("post")
//      * @Template("AssoMakerPHPMBundle:Disponibilite:new.html.twig")
//      */
//     public function createAction()
//     {
//         $entity  = new Disponibilite();
//         $request = $this->getRequest();
//         $form    = $this->createForm(new DisponibiliteType(), $entity);
//         $form->handleRequest($request);

//         if ($form->isValid()) {
//             $em = $this->getDoctrine()->getEntityManager();
//             $em->persist($entity);
//             $em->flush();

//             return $this->redirect($this->generateUrl('disponibilite_show', array('id' => $entity->getId())));
            
//         }

//         return array(
//             'entity' => $entity,
//             'form'   => $form->createView()
//         );
//     }

//     /**
//      * Displays a form to edit an existing Disponibilite entity.
//      *
//      * @Route("/{id}/edit", name="disponibilite_edit")
//      * @Template()
//      */
//     public function editAction($id)
//     {
//         $em = $this->getDoctrine()->getEntityManager();

//         $entity = $em->getRepository('AssoMakerPHPMBundle:Disponibilite')->find($id);

//         if (!$entity) {
//             throw $this->createNotFoundException('Unable to find Disponibilite entity.');
//         }

//         $editForm = $this->createForm(new DisponibiliteType(), $entity);
//         

//         return array(
//             'entity'      => $entity,
//             'edit_form'   => $editForm->createView(),
//             
//         );
//     }

//     /**
//      * Edits an existing Disponibilite entity.
//      *
//      * @Route("/{id}/update", name="disponibilite_update")
//      * @Method("post")
//      * @Template("AssoMakerPHPMBundle:Disponibilite:edit.html.twig")
//      */
//     public function updateAction($id)
//     {
//         $em = $this->getDoctrine()->getEntityManager();

//         $entity = $em->getRepository('AssoMakerPHPMBundle:Disponibilite')->find($id);

//         if (!$entity) {
//             throw $this->createNotFoundException('Unable to find Disponibilite entity.');
//         }

//         $editForm   = $this->createForm(new DisponibiliteType(), $entity);
//         

//         $request = $this->getRequest();

//         $editForm->handleRequest($request);

//         if ($editForm->isValid()) {
//             $em->persist($entity);
//             $em->flush();

//             return $this->redirect($this->generateUrl('disponibilite_edit', array('id' => $id)));
//         }

//         return array(
//             'entity'      => $entity,
//             'edit_form'   => $editForm->createView(),
//             
//         );
//     }

//     /**
//      * Deletes a Disponibilite entity.
//      *
//      * @Route("/{id}/delete", name="disponibilite_delete")
//      * 
//      */
//     public function deleteAction($id)
//     {
        
//             $em = $this->getDoctrine()->getEntityManager();
//             $entity = $em->getRepository('AssoMakerPHPMBundle:Disponibilite')->find($id);

//             if (!$entity) {
//                 throw $this->createNotFoundException('Unable to find Disponibilite entity.');
//             }

//             $em->remove($entity);
//             $em->flush();
        

//         return $this->redirect($this->generateUrl('disponibilite'));
//     }

	
// 	/**
//      * Displays a form to create a new Disponibilite entity, already assigned to an Orga.
//      *
//      * @Route("/neworga/{orgaid}", name="disponibilite_orga_new")
//      * @Template("AssoMakerPHPMBundle:Disponibilite:new.html.twig")
//      */
//     public function newOrgaAction($orgaid)
//     {
//         $em = $this->getDoctrine()->getEntityManager();

//         $orga = $em->getRepository('AssoMakerBaseBundle:Orga')->find($orgaid);	
			
//         $entity = new Disponibilite();
// 		$entity->setOrga($orga);
// 		$dispoform = new DisponibiliteType($orga);
// 		$dispoform->disableOrga();
//         $form   = $this->createForm($dispoform, $entity );

//         return array(
//             'entity' => $entity,
//             'form'   => $form->createView()
//         );
//     }
	
	/**
	* Lists all dispo of an Orga entity
	*
	* @Route("/query.json", name="dispo_query_json")
	* @Method("post")
	*/
	public function queryJsonAction()
	{
		$request = $this->getRequest();
		
		// on recupère les paramètres passés en post
		$orga_id = $request->request->get('orga_id', '');
		$plage_id = $request->request->get('plage_id', '');

		$em = $this->getDoctrine()->getEntityManager();
		// on appelle la fonction qui va faire la requête SQL et nous renvoyer le resultat
		$entities = $em->getRepository('AssoMakerPHPMBundle:Disponibilite')->getOrgaDispo($orga_id, $plage_id);
		
		//var_dump($entities);
		// magie, on a rien à faire comme mise en forme !
    	
    	$response = new Response();
    	$response->setContent(json_encode($entities));
		$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
	}
}
