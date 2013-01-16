<?php

namespace AssoMaker\SponsoBundle\Controller;
use AssoMaker\SponsoBundle\Entity\Projet;

use AssoMaker\SponsoBundle\Form\NoteType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $user = $this->get('security.context')->getToken()->getUser();

        $p = $em
                ->createQuery("SELECT p FROM AssoMakerSponsoBundle:Projet p JOIN p.equipe e  WHERE e.id != :tid ORDER BY e.id")
                ->setParameter("tid",$user->getEquipe()->getId())
                ->getResult();
        
        
        $tp = $em
        ->createQuery("SELECT p FROM AssoMakerSponsoBundle:Projet p JOIN p.equipe e WHERE e.id = :tid")
        ->setParameter("tid",$user->getEquipe()->getId())
        ->getResult();

        return array('projets' => $p,'teamProjets' => $tp);
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
        $config = $e = $this->get('config.extension');
        $entity = $em->getRepository('AssoMakerSponsoBundle:Projet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createForm(new ProjetType, $entity);
        $noteForm = $this->createForm(new NoteType);

        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);

            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this
                        ->redirect($this->generateUrl('sponso_projet_edit',array('id'=>$entity->getId())));
            }
        }

        return array('entity' => $entity, 'form' => $editForm->createView(),
                'formNote' => $noteForm->createView());
    }

    /**
     * Edits an existing Projet entity.
     *
     * @Route("/new", name="sponso_projet_new")
     * @Template
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $config = $e = $this->get('config.extension');
        $user = $this->get('security.context')->getToken()->getUser();

        $entity = new Projet();
        $entity->setEquipe($user->getEquipe());
        $editForm = $this->createForm(new ProjetType, $entity);
        
        if ($this->get('request')->getMethod() == 'POST') {
            $request = $this->getRequest();
            $editForm->bindRequest($request);
        
            if ($editForm->isValid()) {
                var_dump($editForm->getData());
                exit;
                $em->persist($entity);
                $em->flush();
        
                return $this
                ->redirect($this->generateUrl('sponso_projet_edit',array('id'=>$entity->getId())));
            }
        }
        
        return array('entity' => $entity, 'form' => $editForm->createView());

    }

    

}
