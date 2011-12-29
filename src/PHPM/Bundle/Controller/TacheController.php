<?php

namespace PHPM\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PHPM\Bundle\Entity\Tache;
use PHPM\Bundle\Form\TacheType;

/**
 * Tache controller.
 *
 * @Route("/tache")
 */
class TacheController extends Controller
{
    /**
     * Lists all Tache entities.
     *
     * @Route("/", name="tache")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('PHPMBundle:Tache')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Tache entity.
     *
     * @Route("/{id}/show", name="tache_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Tache entity.
     *
     * @Route("/new", name="tache_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tache();
        $form   = $this->createForm(new TacheType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Tache entity.
     *
     * @Route("/create", name="tache_create")
     * @Method("post")
     * @Template("PHPMBundle:Tache:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Tache();
        $request = $this->getRequest();
        $form    = $this->createForm(new TacheType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tache_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Tache entity.
     *
     * @Route("/{id}/edit", name="tache_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $editForm = $this->createForm(new TacheType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tache entity.
     *
     * @Route("/{id}/update", name="tache_update")
     * @Method("post")
     * @Template("PHPMBundle:Tache:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $editForm   = $this->createForm(new TacheType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tache_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tache entity.
     *
     * @Route("/{id}/delete", name="tache_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('PHPMBundle:Tache')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tache entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tache'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }


/**
* Import all Tache entities.
*
* @Route("/import", name="tache_import")
* @Template()
*/
public function importAction()
{
	//recevoir le jason de TM
	$jason = "[{\"id\":1,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":11,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":12,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":13,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]},{\"id\":2,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":21,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":22,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":23,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]},{\"id\":3,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":31,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":32,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":33,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]}]";
	//$jason = fopen("taches.json", "r");
	
	// on affiche le jason
	print"<pre>";
	$tabArray = json_decode($jason, TRUE);
	var_dump($tabArray);
	print"</pre>";
	
	
	$em = $this->getDoctrine()->getEntityManager();
	
	$entities = $em->getRepository('PHPMBundle:Tache')->findAll();

	foreach ($tabArray as $tache_en_traitement) {
		//*
		print $tache_en_traitement['id'];
		print"	";
		print $tache_en_traitement['nom'];
		print "<br/>";
		if (isset($entities[$tache_en_traitement['id']-1])){
			print $entities[$tache_en_traitement['id']-1];
		}else{
			print "tache not found";
		}
		print "<br/>";
		foreach ($tache_en_traitement['plages'] as $creneau_en_traitement){
			print $creneau_en_traitement['id'];
			print"	";
			print "<br/";
		}
		//*/
	}
	print "<br/>";
	/*
	print"<pre>";
	var_dump($tache_en_traitement['plages']);
	print"</pre>";
	*/
	//$entities = $em->getRepository('PHPMBundle:Tache')->findById($tache_en_traitement['id']);
	//$entities = $em->getRepository('PHPMBundle:Tache')->findById(1);

	print "<br/>";
	
	
	print "<br/>";
	print "<br/>";

	exit(print($entities[0]->getId()));
	return array();
}

}
