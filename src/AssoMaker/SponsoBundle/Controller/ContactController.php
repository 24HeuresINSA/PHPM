<?php

namespace AssoMaker\SponsoBundle\Controller;

use Symfony\Component\Validator\Constraints\DateTime;

use AssoMaker\SponsoBundle\Entity\Note;

use AssoMaker\SponsoBundle\Form\NoteType;

use AssoMaker\SponsoBundle\Form\ContactType;

use AssoMaker\SponsoBundle\Entity\Contact;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Contact controller.
 *
 * @Route("/sponso/contact")
 */
class ContactController extends Controller
{
       
    /**
     * Lists all contacts  as JSON
     *
     * @Route("/index.json", name="sponso_contactsJSON")
     * @Method("get")
     */
    public function contactsDataAction(Request $request) {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
    
        
    
        $contacts=$em->createQuery("SELECT c FROM AssoMakerSponsoBundle:Contact c ")->getResult();
        
        
        
        $array = array();
        foreach ($contacts as $contact){
            $notes = array();
            foreach ($contact->getNotes() as $note){
                $notes[]=$note->toArray();
            }
            
            $array[]= array(  "id"=> $contact->getId(),
                        "nom"=> $contact->getNom(),
                        "telephone"=> $contact->getTelephone(),
                        "email"=> $contact->getEmail(),
                        "entreprise"=> $contact->getEntreprise(),
                        "poste"=> $contact->getPoste(),
                        "adresse"=> $contact->getAdresse(),
                        "notes"=>$notes
                );
        }
        
        
        $response = new Response();
        $response->setContent(json_encode($array));
        $response->headers->set('Content-Type', 'application/json');
         
        return $response;
    }
    
    /**
     * new Contact entity.
     *
     * @Route("/new", name="sponso_contact_new")
     * @Template()
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config  =$e=$this->get('config.extension');
        $entity  = new Contact();
        $editForm   = $this->createForm(new ContactType(), $entity);
        
        
        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);
        
            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();
        
                return $this->redirect($this->generateUrl('sponso_home'));
            }
        }
   
        return array(
                'entity'      => $entity,
                'form'   => $editForm->createView()
        );

    }
    
    
    
    /**
     * Edits an existing Contact entity.
     *
     * @Route("/{id}/edit", name="sponso_contact_edit")
     * @Template
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e=$this->get('config.extension');
        $entity = $em->getRepository('AssoMakerSponsoBundle:Contact')->find($id);
    
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contact entity.');
        }
    
        $editForm   = $this->createForm(new ContactType(), $entity);
    
    
        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);
    
            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();
    
                return $this->redirect($this->generateUrl('sponso_home'));
            }
        }
    
        return array(
                'entity'      => $entity,
                'form'   => $editForm->createView()
        );
    }
    
    /**
     * AddNote
     *
     * @Route("/{id}/addNote", name="sponso_contact_addNote")
     * @Template
     */
    public function addNoteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $contact = $em->getRepository('AssoMakerSponsoBundle:Contact')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();
        

        $entity  = new Note();
        
        $entity->setOrga($user);
        $entity->setContact($contact);
        $entity->setStatut("2");
    
        $editForm   = $this->createForm(new NoteType(),$entity);
        
        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);
            $entity->setDate(new \DateTime());
            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();
    
                return $this->redirect($this->generateUrl('sponso_home'));
            }
        }
    
        return array(
                'entity'      => $entity,
                'form'   => $editForm->createView()
        );
    }
}
