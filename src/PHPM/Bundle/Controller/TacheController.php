<?php

namespace PHPM\Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
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
    * Lists all Tache entities as JSON.
    *
    * @Route("/index.json", name="tache_json")
    *
    */
    public function indexJsonAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$entities = $em->getRepository('PHPMBundle:Tache')->findAll();
    	 
    	$a = array();
    	 
    	foreach ($entities as $entity){
    		$a[$entity->getId()] = $entity->toArray();
    
    	}
    	$response = new Response();
    	$response->setContent(json_encode($a));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    
    	return $response;
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
		
		$jason = "[{\"id\":1,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":1,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":2,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":3,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]},{\"id\":2,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":1,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":2,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":3,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]},{\"id\":3,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":1,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":2,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":3,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]}]";
		
		$em = $this->getDoctrine()->getEntityManager();
		$entities = $em->getRepository('PHPMBundle:Tache')->findAll();
		
		$tabArray = json_decode($jason, TRUE);
		print"<pre>";
		var_dump($tabArray);
		print"</pre>";

		print"<pre>";
		foreach ($entities as $elements){
			print $elements->getId();
		var_dump($elements->toArray());
		}
		print"</pre>";
		
		foreach ($tabArray as $tache_en_traitement) {
			$found = FALSE;
			foreach ($entities as $elements){
				if ($elements->getId() == $tache_en_traitement['id']){	
					$found = TRUE;
					break;
				}
				
			}
			if (!$found){
				//On ajoute la tache
				print "ajout de la tache ";
				print $tache_en_traitement['id'];
				print "<br />";
			}
		}	
			
		
	exit(print($tabArray[0]));
	return array();
	}
	
	}
