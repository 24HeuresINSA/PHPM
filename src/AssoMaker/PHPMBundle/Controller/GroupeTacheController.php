<?php

namespace AssoMaker\PHPMBundle\Controller;

use AssoMaker\PHPMBundle\Entity\Tache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\GroupeTache;
use AssoMaker\PHPMBundle\Form\GroupeTacheType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * GroupeTache controller.
 *
 * @Security("has_role('ROLE_HARD')")
 * @Route("/groupetache")
 */
class GroupeTacheController extends Controller {

    /**
     * Lists all GroupeTache entities.
     *
     * @Route("/index/{equipeid}/{statut}/{orgaid}", defaults={"equipeid"="all","statut"="all","orgaid"="all"}, name="groupetache")
     * @Template()
     */
    public function indexAction($equipeid, $statut, $orgaid) {
        if (false === $this->get('security.context')->isGranted('ROLE_HARD')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();

        $equipes = $em
                ->createQuery("SELECT e FROM AssoMakerBaseBundle:Equipe e")
                ->getResult();

        $groupesDQL = "SELECT g,r,t FROM AssoMakerPHPMBundle:GroupeTache g LEFT JOIN g.equipe e JOIN g.responsable r JOIN g.taches t WHERE 1=1 ";

        if ($statut != 'all') {
            $groupesDQL .= " AND g.statut = $statut ";
        }

        if ($statut != -1) {
            $groupesDQL .= " AND g.statut <> -1 ";
        }

        if ($equipeid != 'all') {
            $groupesDQL .= " AND e.id = $equipeid ";
        }
        if ($orgaid != 'all') {
            $groupesDQL .= " AND r.id = $orgaid ";
        }

        $groupesDQL .= " AND t.statut <> -1 ORDER BY e.id, g.id, g.statut";

        $groupes = $em
                ->createQuery($groupesDQL)
                ->getResult();

        return array('equipes' => $equipes,
            'groupes' => $groupes
        );
    }

    /**
     * Creates a new GroupeTache entity.
     *
     * @Route("/create", name="groupetache_create")
     *
     * @Template("AssoMakerPHPMBundle:GroupeTache:new.html.twig")
     */
    public function createAction() {
        if (false === $this->get('security.context')->isGranted('ROLE_HARD')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $entity = new GroupeTache();
        $request = $this->getRequest();
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');
        $user = $this->get('security.context')->getToken()->getUser();

        $entity->setResponsable($user);
        $entity->setEquipe($user->getEquipe());
        $entity->setNom('Groupe sans nom');
        $entity->setLieu(' ');
        $entity->setStatut(0);


        $em->persist($entity);
        $em->flush();


        return $this->redirect($this->generateUrl('groupetache_edit', array('id' => $entity->getId())));
    }

    /**
     * Displays a form to edit an existing GroupeTache entity.
     *
     * @Route("/{id}/edit", name="groupetache_edit")
     * @Template()
     */
    public function editAction($id) {
        if (false === $this->get('security.context')->isGranted('ROLE_HARD')) {
            throw new AccessDeniedException();
        }
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AssoMakerPHPMBundle:GroupeTache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupeTache entity.');
        }
        $taches = $em->getRepository('AssoMakerPHPMBundle:Tache')->getNonDeletedTaches($id);

        $editForm = $this->createForm(new GroupeTacheType($admin, $config), $entity);

        $animations = $em->createQuery('SELECT a.id,a.nom,a.lieu FROM AssoMakerAnimBundle:Animation a INDEX BY a.id')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'taches' => $taches,
            'animations' => $animations
        );
    }

    /**
     * Edits an existing GroupeTache entity.
     *
     * @Route("/{id}/update", name="groupetache_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:GroupeTache:edit.html.twig")
     */
    public function updateAction($id) {

        if (false === $this->get('security.context')->isGranted('ROLE_HARD')) {
            throw new AccessDeniedException();
        }
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $config = $this->get('config.extension');
        $param = $request->request->all();


        $entity = $em->getRepository('AssoMakerPHPMBundle:GroupeTache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GroupeTache entity.');
        }

        $editForm = $this->createForm(new GroupeTacheType($admin, $config), $entity);


        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($param['action'] == 'delete' && $entity->isDeletable()) {
                $entity->setStatut(-1);
            }

            if ($param['action'] == 'restore') {
                $entity->setStatut(0);
            }


            $em->persist($entity);
            $em->flush();

            if ($param['action'] == 'add_tache') {
                $tache = new Tache();
                $tache->setGroupeTache($entity);
                $tache->setNom("Tâche sans nom");
                $tache->setStatut(0);
                $tache->setResponsable($entity->getResponsable());
                $tache->setPermisNecessaire(-1);
                $tache->setLieu($entity->getLieu());
                $em->persist($tache);
                $em->flush();
                return $this->redirect($this->generateUrl('tache_edit', array('id' => $tache->getId())));
            }



            return $this->redirect($this->generateUrl('groupetache_edit', array('id' => $id)));
        }

        $taches = $em->getRepository('AssoMakerPHPMBundle:Tache')->getNonDeletedTaches($id);
        $animations = $em->createQuery('SELECT a.id,a.nom,a.lieu FROM AssoMakerAnimBundle:Animation a INDEX BY a.id')->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'animations' => $animations,
            'taches' => $taches
        );
    }

    /**
     * Deletes a GroupeTache entity.
     *
     * @Route("/{id}/delete", name="groupetache_delete")
     * @Method("post")
     */
    public function deleteAction($id) {
        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('AssoMakerPHPMBundle:GroupeTache')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GroupeTache entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('groupetache'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

}
