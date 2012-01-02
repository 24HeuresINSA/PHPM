<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\PlageHoraire;
use PHPM\Bundle\Form\PlageHoraireType;
use PHPM\Bundle\Entity\Creneau;

/**
 * PlageHoraire controller.
 *
 * @Route("/plagehoraire")
 */
class PlageHoraireController extends Controller
{
    /**
     * Lists all PlageHoraire entities.
     *
     * @Route("/", name="plagehoraire")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:PlageHoraire')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a PlageHoraire entity.
     *
     * @Route("/{id}/show", name="plagehoraire_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:PlageHoraire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PlageHoraire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new PlageHoraire entity.
     *
     * @Route("/new", name="plagehoraire_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new PlageHoraire();
        $form   = $this->createForm(new PlageHoraireType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new PlageHoraire entity.
     *
     * @Route("/create", name="plagehoraire_create")
     * @Method("post")
     * @Template("PHPMBundle:PlageHoraire:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new PlageHoraire();
        $request = $this->getRequest();
        $form    = $this->createForm(new PlageHoraireType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('plagehoraire_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing PlageHoraire entity.
     *
     * @Route("/{id}/edit", name="plagehoraire_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:PlageHoraire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PlageHoraire entity.');
        }

        $editForm = $this->createForm(new PlageHoraireType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing PlageHoraire entity.
     *
     * @Route("/{id}/update", name="plagehoraire_update")
     * @Method("post")
     * @Template("PHPMBundle:PlageHoraire:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:PlageHoraire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PlageHoraire entity.');
        }

        $editForm   = $this->createForm(new PlageHoraireType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('plagehoraire_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a PlageHoraire entity.
     *
     * @Route("/{id}/delete", name="plagehoraire_delete")
     * 
     */
    public function deleteAction($id)
    {
       
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:PlageHoraire')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PlageHoraire entity.');
            }

            $em->remove($entity);
            $em->flush();
        

        return $this->redirect($this->generateUrl('plagehoraire'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	
    /**
     * Create a creneau entity.
     *
     * @Route("/{id}/creationCreneau", name="plagehoraire_creationCreneau")
     * @Template
     */	
		
	public function creationCreneauAction($id)   // creation de créneau à partir de la duréer de la plage horaire et du recoupement
	{
		
		$em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:PlageHoraire')->find($id);
		$nobody = $em->getRepository('PHPMBundle:Orga')->find(0);
		$dispoNobody = $em->getRepository('PHPMBundle:Disponibilite')->findOneByOrga($nobody);

		$arrayCreneauCree = array();
		
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PlageHoraire entity.');
        }
		
		else
		{	
			
			
			
			
			if (( $entity->getdureeCreneau() +  $entity->getrecoupementCreneau()) > ( $entity->getfin()->getTimestamp() -  $entity->getdebut()->getTimestamp()) )	
			{
				$nouveauCreneau = new Creneau();
				
				$nouveauCreneau->setPlageHoraire($entity);			
				$nouveauCreneau->setDisponibilite($nobody);
				
				$debutDesCreneauxDate = date ('y-m-d H:i:s', ($entity->getdebut()->getTimestamp()) ); // permet d'avoir le bon format pour le stocker dans la BDD				
				$finDesCreneauxDate = date ('y-m-d H:i:s', ($entity->getfin()->getTimestamp()) ); // permet d'avoir le bon format pour le stocker dans la BDD				
					
				$nouveauCreneau->setDebut(new \DateTime("20$debutDesCreneauxDate"));
				$nouveauCreneau->setFin(new \DateTime("20$finDesCreneaux"));
				
				$em->persist($nouveauCreneau);
				$em->flush();
				array_push($arrayCreneauCree, $nouveauCreneau);
			}
			
			else 
			{
				$tempsRestantAAffecter = ($entity->getfin()->getTimestamp() - $entity->getdebut()->getTimestamp() );
				$debutDesCreneaux = $entity->getdebut()->getTimestamp();
				while ( ($entity->getdureeCreneau() + $entity->getrecoupementCreneau()) < $tempsRestantAAffecter)
					{
						$nouveauCreneau = new Creneau();
						$nouveauCreneau->setPlageHoraire($entity);			
						$nouveauCreneau->setDisponibilite($dispoNobody);
						
						$debutDesCreneauxDate = date ('y-m-d H:i:s', $debutDesCreneaux); // permet d'avoir le bon format pour le stocker dans la BDD
						$finDuCreneauDate = ($debutDesCreneaux + $entity->getdureeCreneau() + $entity->getrecoupementCreneau() );
						$finDuCreneauDate = date ('y-m-d H:i:s', $finDuCreneauDate);
					
						
						$nouveauCreneau->setDebut(new \DateTime("20$debutDesCreneauxDate"));					
						$nouveauCreneau->setFin(new \DateTime("20$finDuCreneauDate"));
						
						
						$tempsRestantAAffecter = ($tempsRestantAAffecter- ($entity->getdureeCreneau()));
						$debutDesCreneaux += ($entity->getdureeCreneau() + $entity->getrecoupementCreneau());					
						
						$em->persist($nouveauCreneau);
						$em->flush();						
						array_push($arrayCreneauCree, $nouveauCreneau);			
					}
				if ($tempsRestantAAffecter > 0)	
					{
						$nouveauCreneau = new Creneau();
				
						$nouveauCreneau->setPlageHoraire($entity);			
						$nouveauCreneau->setDisponibilite($dispoNobody);
						
						$debutDesCreneauxDate = date ('y-m-d H:i:s', ($entity->getfin()->getTimestamp() - $tempsRestantAAffecter) ); // permet d'avoir le bon format pour le stocker dans la BDD				
						$finDesCreneauxDate = date ('y-m-d H:i:s', ($entity->getfin()->getTimestamp()) ); // permet d'avoir le bon format pour le stocker dans la BDD									
				
						$nouveauCreneau->setDebut(new \DateTime("20$debutDesCreneauxDate"));
						$nouveauCreneau->setFin(new \DateTime("20$finDesCreneauxDate"));
						
						$em->persist($nouveauCreneau);
						$em->flush();
						array_push($arrayCreneauCree, $nouveauCreneau);
					}
			}
			
		}
		
		return array('entities' => $arrayCreneauCree);
		
		
		
	}
}
