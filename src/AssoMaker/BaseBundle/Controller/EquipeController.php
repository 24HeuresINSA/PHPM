<?php

namespace AssoMaker\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\BaseBundle\Entity\Equipe;
use AssoMaker\PHPMBundle\Form\EquipeType;
use Symfony\Component\HttpFoundation\Response;

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
    	if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
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
     * Print planning for a team.
     * @Route("/{id}/print", name="equipe_print")
     */
    public function printAction($id){

        $config = $this->get('config.extension');

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }

        $debut = new \DateTime();
        $fin = new \DateTime($config->getValue('phpm_planning_fin'));

        $em = $this->getDoctrine()->getEntityManager();
        $pdfGenerator = $this->get('spraed.pdf.generator');
        {
            $taches = $em->createQueryBuilder()->
            select("t,g,r")->from("AssoMakerPHPMBundle:Tache","t")->
            join("t.groupeTache",'g')->join("t.responsable",'r')
                ->where("t.statut>=2")->andWhere("g.equipe=?0")->orderBy("t.id")->getQuery()->setParameter(0,$id)->getArrayResult();
            $max = count($taches);
            $request = "SELECT o0_.id AS oid, o0_.nom AS nom, o0_.prenom AS prenom, o0_.telephone AS telephone, c1_.debut AS debut, c1_.fin AS fin
FROM Creneau c1_
INNER JOIN Disponibilite d2_ ON ( d2_.id = c1_.disponibilite_id )
INNER JOIN PlageHoraire p3_ ON ( c1_.plageHoraire_id = p3_.id )
INNER JOIN Tache t4_ ON ( t4_.id = p3_.tache_id )
INNER JOIN Orga o0_ ON ( o0_.id = d2_.orga_id )
WHERE t4_.id = ? ORDER BY debut";
            for($i = 0; $i < $max; $i++){
                $result = $em->getConnection()->fetchAll($request,array('0'=>$taches[$i]['id']));
                $taches[$i]["creneaux"]=$result;
            }

            $html = $this->renderView('AssoMakerBaseBundle:Equipe:printPlanning.html.twig',array(
                'equipe'=>$em->getRepository('AssoMakerBaseBundle:Equipe')->find($id),
                'taches'=>$taches,
                'debut'=>$debut
            ));

            return new Response($pdfGenerator->generatePDF($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="out.pdf"'
                )
            );
        }
    }

    /**
     * Print a custom planning for a team.
     * @Route("/print/tasks/{task}/title/{title}", name="planning_print")
     */
    public function printCustomAction($task,$title){

        $config = $this->get('config.extension');

        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }

        $tasks = explode(",",$task);

        $debut = new \DateTime();
        $fin = new \DateTime($config->getValue('phpm_planning_fin'));

        $em = $this->getDoctrine()->getEntityManager();
        $pdfGenerator = $this->get('spraed.pdf.generator');
        {
            $where = "";

            $queryBuilder = $em->createQueryBuilder()->
            select("t,g,r")->from("AssoMakerPHPMBundle:Tache", "t")->
            join("t.groupeTache", 'g')->join("t.responsable", 'r')
                ->where("t.id=" . intval($tasks[0]))->orderBy("t.id");
            $x = count($tasks);
            for ($i = 1; $i < $x; $i++) {
                $queryBuilder->orWhere("t.id=".intval($tasks[$i]));
            }
            $taches = $queryBuilder->getQuery()->getArrayResult();
            $max = count($taches);
            $request = "SELECT o0_.id AS oid, o0_.nom AS nom, o0_.prenom AS prenom, o0_.telephone AS telephone, c1_.debut AS debut, c1_.fin AS fin
FROM Creneau c1_
INNER JOIN Disponibilite d2_ ON ( d2_.id = c1_.disponibilite_id )
INNER JOIN PlageHoraire p3_ ON ( c1_.plageHoraire_id = p3_.id )
INNER JOIN Tache t4_ ON ( t4_.id = p3_.tache_id )
INNER JOIN Orga o0_ ON ( o0_.id = d2_.orga_id )
WHERE t4_.id = ? ORDER BY debut";
            for($i = 0; $i < $max; $i++){
                $result = $em->getConnection()->fetchAll($request,array('0'=>$taches[$i]['id']));
                $taches[$i]["creneaux"]=$result;
            }

            $html = $this->renderView('AssoMakerBaseBundle:Equipe:printPlanning.html.twig',array(
                'title'=>$title,
                'taches'=>$taches,
                'debut'=>$debut
            ));

            return new Response($pdfGenerator->generatePDF($html),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="out.pdf"'
                )
            );
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

        $editForm->handleRequest($request);

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
