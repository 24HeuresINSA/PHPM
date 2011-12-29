<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Orga;
use PHPM\Bundle\Entity\Disponibilite;
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
     * @Route("/affectation", name="orga_affectation")
     * @Template()
     */
    public function affectationAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Orga')->findAll();

        return array('entities' => $entities);
      
    }
	
	
	
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
     * @Template()
     */
	public function importAction()	
	{
	
        $em = $this->getDoctrine()->getEntityManager();

        $entitiesOrga = $em->getRepository('PHPMBundle:Orga')->findAll();	
	
			$url = "inscriptionOrgas.json";			
			$json = file_get_contents($url);
			
			$listeOrgaArray = json_decode($json,TRUE);
			
			

			
			
			foreach($listeOrgaArray as $case => $inscriptionOrga)
				{
					$inscriptionOrga['nom']=strtoupper($inscriptionOrga['nom']);
					$inscriptionOrga['prenom']=strtoupper($inscriptionOrga['prenom']);	
					
					$orgaNonTrouve=TRUE;
					$i = 0;
					foreach ($entitiesOrga as $key) 
					{
						$nomOrgaBDD = $entitiesOrga[$i]->getnom();    
						$prenomOrgaBDD = $entitiesOrga[$i]->getprenom();
						$telephoneOrgaBDD = $entitiesOrga[$i]->gettelephone();					
						$emailOrgaBDD = $entitiesOrga[$i]->getemail();
						$surnomOrgaBDD = $entitiesOrga[$i]->getsurnom();
						$departementOrgaBDD = $entitiesOrga[$i]->getdepartement();
						$commentaireOrgaBDD = $entitiesOrga[$i]->getcommentaire();
						$permisOrgaBDD = $entitiesOrga[$i]->getpermis();
						$dateDeNaissanceOrgaBDD = $entitiesOrga[$i]->getDateDeNaissance();
						
						
						
						if ($inscriptionOrga['nom'] == $nomOrgaBDD AND $inscriptionOrga['prenom'] == $prenomOrgaBDD
							AND $inscriptionOrga['telephone'] == $telephoneOrgaBDD)
						{
							$orgaNonTrouve=FALSE;
						}
						
						if ($inscriptionOrga['nom'] == $nomOrgaBDD AND $inscriptionOrga['prenom'] == $prenomOrgaBDD
						AND $inscriptionOrga['telephone'] == $telephoneOrgaBDD AND $inscriptionOrga['email'] == $emailOrgaBDD
						AND $inscriptionOrga['surnom'] == $surnomOrgaBDD AND $inscriptionOrga['departement'] == $departementOrgaBDD
						AND $inscriptionOrga['commentaire'] == $commentaireOrgaBDD AND $inscriptionOrga['permis'] == $permisOrgaBDD)
							{
					//			echo $dateDeNaissanceOrgaBDD;
								echo 'orga deja present a lidentique dans la bdd';
							}

						// rajouter les permis et date de naissance
						
						
						$i++;
					}



					if ($orgaNonTrouve)    // si l'orga nexiste pas on lajoute
						{
						echo $inscriptionOrga['prenom'];
						echo 'orga a rajouter';
						echo "<p>";		
						
						$confiance = $entitiesOrga = $em->getRepository('PHPMBundle:Confiance')->findOneById(1);  // pour récupérer confiance
						
						$entity  = new orga();
						$entity->setNom($inscriptionOrga['nom']);
						$entity->setPrenom($inscriptionOrga['prenom']);
						$entity->setConfiance($confiance);
						$entity->settelephone($inscriptionOrga['telephone']);
						$entity->setemail($inscriptionOrga['email']);
						$entity->setdepartement($inscriptionOrga['departement']);
						$entity->setcommentaire($inscriptionOrga['commentaire']);
						$entity->setpermis($inscriptionOrga['permis']);
						$entity->setDateDeNaissance(new \DateTime($inscriptionOrga['dateDeNaissance']));
						$entity->setSurnom($inscriptionOrga['surnom']);				
						$em->persist($entity);
	            		
	            		
						// ajout des disponibilite
						

						$em->flush();	
						$idOrgaAjoute= $em->getRepository('PHPMBundle:Orga')->findOneByTelephone($inscriptionOrga['telephone']);	

						
						
						foreach($inscriptionOrga['disponibilites'] as $dispoAAjoute)
							{
								$entitydisponibilite = new disponibilite();						
								$entitydisponibilite->setOrga($idOrgaAjoute);
								$debutdispo = date ('y-m-d', $dispoAAjoute[0]);
								$entitydisponibilite->setDebut(new \DateTime("20$debutdispo"));
								$findispo = date ('y-m-d', $dispoAAjoute[1]);
								$entitydisponibilite->setFin(new \DateTime("20$findispo"));
								$em->persist($entitydisponibilite);
								$em->flush();							
							}

						
						
						
						
	            		
	            	//	$em->flush();
							
						
																
						}
	


					
					
					
	
	
	
	
					}
						
					/*
					echo ("<pre>");
					print_r($entitiesOrga);
					echo("</pre>");
					
					//echo $Nom;
					echo ("<pre>");
					print_r($inscriptionOrga);
					echo("</pre>");
					 */ 
					
		//		}
			


	
			
			
			echo "<p>";
			echo "plouf";
			/*
			echo ("<pre>");
			var_dump($listeOrgaArray);
			echo("</pre>");
				*/
				
				
				
			
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
