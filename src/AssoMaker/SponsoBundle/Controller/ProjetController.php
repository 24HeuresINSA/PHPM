<?php

namespace AssoMaker\SponsoBundle\Controller;

use AssoMaker\SponsoBundle\Form\NoteType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AssoMaker\SponsoBundle\Entity\Contact;
use AssoMaker\SponsoBundle\Entity\Note;
use AssoMaker\SponsoBundle\Form\ProjetType;


/**
 * Projet controller.
 *
 * @Route("/sponso/projet")
 */
class ProjetController extends Controller
{
    /**
     * @Route("/", name="sponso_projet_home")
     * @Template()
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getEntityManager();

        $projets = $em->createQuery("SELECT p FROM AssoMakerSponsoBundle:Projet p")->getResult();
        
        return array('projets' => $projets);
    }
    
    /**
     * Edits an existing Projet entity.
     *
     * @Route("/{id}/edit", name="sponso_projet_edit")
     * @Template
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e=$this->get('config.extension');
        $entity = $em->getRepository('AssoMakerSponsoBundle:Projet')->find($id);
    
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }
    
        $editForm   = $this->createForm(new ProjetType, $entity);
        $noteForm   = $this->createForm(new NoteType);
    
    
        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);
    
            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();
    
                return $this->redirect($this->generateUrl('sponso_projet_home'));
            }
        }
    
        return array(
                'entity'      => $entity,
                'form'   => $editForm->createView(),
                'formNote' => $noteForm->createView()
        );
    }
    
    /**
     * AddNote
     *
     * @Route("/{id}/addNote", name="sponso_projet_addNote")
     */
    public function addNoteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $projet = $em->getRepository('AssoMakerSponsoBundle:Projet')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
    
    
        $entity  = new Note();
    
        $entity->setOrga($user);
        $entity->setProjet($projet);
    
        $editForm   = $this->createForm(new NoteType(),$entity);
    
     
        $request = $this->getRequest();
        $editForm->bindRequest($request);
        $entity->setDate(new \DateTime());
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sponso_projet_edit',array('id'=>$projet->getId())));
        }

    }
    
}
