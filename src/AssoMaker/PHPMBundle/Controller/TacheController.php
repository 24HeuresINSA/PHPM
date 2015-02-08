<?php

namespace AssoMaker\PHPMBundle\Controller;

use AssoMaker\PHPMBundle\Entity\CreneauRepository;
use AssoMaker\PHPMBundle\Form\BesoinMaterielType;
use AssoMaker\PHPMBundle\Entity\BesoinMateriel;
use AssoMaker\PHPMBundle\Entity\PlageHoraire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AssoMaker\PHPMBundle\Entity\Tache;
use AssoMaker\PHPMBundle\Entity\Commentaire;
use AssoMaker\BaseBundle\Entity\Confiance;
// use AssoMaker\PHPMBundle\Entity\Categorie;
use AssoMaker\PHPMBundle\Form\TacheType;
use AssoMaker\PHPMBundle\Form\TacheBesoinsType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tache controller.
 *
 * @Security("has_role('ROLE_HARD')")
 * @Route("/tache")
 */
class TacheController extends Controller {

    /**
     * Lists all Tache entities.
     *
     * @Route("/index/{equipeid}/{statut}/{orgaid}", defaults={"equipeid"="all","statut"="all","orgaid"="all"}, name="tache")
     * @Template()
     */
    public function indexAction($equipeid, $statut, $orgaid) {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();

        $equipes = $em
                ->createQuery("SELECT e FROM AssoMakerBaseBundle:Equipe e")
                ->getResult();

        $tachesDQL = "SELECT t,l,r,g FROM AssoMakerPHPMBundle:Tache t LEFT JOIN t.groupeTache g JOIN g.equipe e JOIN t.responsable r LEFT JOIN t.commentaires l WHERE 1=1 ";

        if ($statut != 'all') {
            $tachesDQL .= " AND t.statut = $statut ";
        }

        if ($statut != -1) {
            $tachesDQL .= " AND t.statut <> -1 ";
        }

        if ($equipeid != 'all') {
            $tachesDQL .= " AND e.id = $equipeid ";
        }
        if ($orgaid != 'all') {
            $tachesDQL .= " AND r.id = $orgaid ";
        }

        $tachesDQL .= " ORDER BY e.id, g.id, t.statut";


        $taches = $em
                ->createQuery($tachesDQL)
                ->getResult();

        return array('equipes' => $equipes,
            'taches' => $taches
        );
    }

    /**
     * Lists all Tache entities as JSON.
     *
     * @Route("/index.json", name="tache_json")
     *
     */
    public function indexJsonAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('AssoMakerPHPMBundle:Tache')->findAll();

        $a = array();

        foreach ($entities as $entity) {
            $a[$entity->getId()] = $entity->toArray();
        }
        $response = new Response();
        $response->setContent(json_encode($a));
        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }

    /**
     * Creates a new Tache entity.
     *
     * @Route("/create/{gid}", name="tache_create")
     *
     *
     */
    public function createAction($gid) {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $entity = new Tache();
        $entity->setStatut(0);
        $request = $this->getRequest();
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');
        $user = $this->get('security.context')->getToken()->getUser();

        if ($gid != "") {
            $groupe = $em->getRepository('AssoMakerPHPMBundle:GroupeTache')->find($gid);

            if (!$groupe) {
                throw $this->createNotFoundException('Pas de groupe correspondant.');
            }

            $entity->setGroupeTache($groupe);
            $entity->setLieu($groupe->getLieu());
            $entity->setResponsable($groupe->getResponsable());
            $entity->setStatut(0);
            $entity->setNom('Tâche sans nom');
            $entity->setPermisNecessaire(0);
        }
        $em->persist($entity);
        $em->flush();


        return $this->redirect($this->generateUrl('tache_edit', array('id' => $entity->getId())));
    }

    /**
     * Displays a form to edit an existing Tache entity.
     *
     * @Route("/{id}/edit", name="tache_edit")
     * @Template()
     */
    public function editAction($id) {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');

        $entity = $em->getRepository('AssoMakerPHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        if ($config->getValue('phpm_tache_heure_limite_validation')) {
            $heureLimite = $config->getValue('phpm_tache_heure_limite_validation');
            $deadlinePassed = ((new \DateTime($heureLimite)) < (new \DateTime()));
        } else {
            $deadlinePassed = false;
        }


        $defaultValues = array('entity' => $entity, 'Materiel' => $entity->getMateriel(), "commentaire" => '');
        $rOnly = (($entity->getStatut() >= 1 ) && (!$admin)) || ($entity->getStatut() == 3 );

        $editForm = $this->createForm(new TacheType($admin, $em, $config, $rOnly), $defaultValues);
//         $besoinsForm = $this->createForm(new TacheBesoinsType(false,$em,$config,$entity));


        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'admin' => $admin,
            'rOnly' => $rOnly,
            'deadlinePassed' => $deadlinePassed
        );
    }

    /**
     * Edits an existing Tache entity.
     *
     * @Route("/{id}/update", name="tache_update")
     * @Method("post")
     * @Template("AssoMakerPHPMBundle:Tache:edit.html.twig")
     */
    public function updateAction($id) {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $request = $this->getRequest();
        $param = $request->request->all();
        $entity = $em->getRepository('AssoMakerPHPMBundle:Tache')->find($id);
        $prevStatut = $entity->getStatut();
        $user = $this->get('security.context')->getToken()->getUser();
        $rOnly = (($entity->getStatut() >= 1 ) && (!$admin)) || ($entity->getStatut() == 3 );
        if ($config->getValue('phpm_tache_heure_limite_validation')) {
            $heureLimite = $config->getValue('phpm_tache_heure_limite_validation');
            $deadlinePassed = ((new \DateTime($heureLimite)) < (new \DateTime()));
        } else {
            $deadlinePassed = false;
        }



        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }
        $defaultValues = array('entity' => $entity, 'Materiel' => $entity->getMateriel());
        $editForm = $this->createForm(new TacheType($admin, $em, $config, $rOnly), $defaultValues);


        $editForm->handleRequest($request);

        $data = $editForm->getData();

        $typeCommentaire = 0;

        $valid = $editForm->isValid();

        if ($valid) {

            $tmpm = $data['Materiel'];


            if ($param['action'] == 'submit_validation') {
                $entity->setStatut(1);
                $typeCommentaire = 1;
            }

            if ($param['action'] == 'validate') {
                $entity->setStatut(2);
                $typeCommentaire = 2;
            }

            if ($param['action'] == 'reject') {
                $entity->setStatut(0);
                $typeCommentaire = 3;
            }

            if ($param['action'] == 'delete') {
                $entity->setStatut(-1);
                $entity->removeAllCreneaux();
                $typeCommentaire = -1;
            }
            if ($param['action'] == 'restore') {
                $entity->setStatut(0);
                $typeCommentaire = 4;
            }

            if ($param['action'] == 'devalidate') {
                $entity->setStatut(0);
                $typeCommentaire = 11;
            }


            if ($tmpm) {

                foreach ($tmpm as $group) {
                    foreach ($group as $key => $value) {



                        $bms = $em->createQuery("SELECT b FROM AssoMakerPHPMBundle:BesoinMateriel b JOIN b.materiel m JOIN b.tache t WHERE t.id = :tid AND m.id=:mid")
                                ->setParameter('tid', $id)
                                ->setParameter('mid', $key)
                                ->getResult();

                        if (($value * 1) != 0) {
                            if (!array_key_exists(0, $bms)) { //Le bm liant m et t n'existe pas
                                $bm = new BesoinMateriel();
                                $bm->setTache($entity);
                                $m = $em->createQuery("SELECT m FROM AssoMakerPHPMBundle:Materiel  m  WHERE  m.id=:mid")
                                        ->setParameter('mid', $key)
                                        ->getSingleResult();
                                $bm->setMateriel($m);
                                $em->persist($bm);
                            } else {
                                $bm = $bms[0];
                            }

                            $bm->setQuantite($value * 1);
                            $entity->addBesoinMateriel($bm);
                        } else {
                            if (array_key_exists(0, $bms)) { //Le bm liant m et t n'existe pas
                                $bm = $bms[0];
                                $entity->getBesoinsMateriel()->removeElement($bm);
                                $m = $em->createQuery("SELECT m FROM AssoMakerPHPMBundle:Materiel  m  WHERE  m.id=:mid")
                                        ->setParameter('mid', $key)
                                        ->getSingleResult();
                                $m->getBesoinsMateriel()->removeElement($bm);
                                $em->remove($bm);
                            }
                        }
                    }
                }
            }
            if ($data['commentaire'] != "" || $typeCommentaire != 0) {
                $commentaire = new Commentaire();
                $commentaire->setAuteur($user);
                $commentaire->setHeure(new \DateTime());
                $commentaire->setTache($entity);
                $commentaire->setTexte($data['commentaire']);
                $commentaire->setType($typeCommentaire);
                $em->persist($commentaire);
            }

            $em->persist($entity);
            $em->flush();

            $defaultValues = array('entity' => $entity, 'Materiel' => $entity->getMateriel());
            $rOnly = (($entity->getStatut() >= 1 ) && (!$admin)) || ($entity->getStatut() == 3 );
            $editForm = $this->createForm(new TacheType($admin, $em, $config, $rOnly), $defaultValues);


//             return $this->redirect($this->generateUrl('tache_edit', array('id' => $id)));
            if ($param['action'] == 'save_return') {
                return $this->redirect($this->generateUrl('groupetache_edit', array('id' => $entity->getGroupeTache()->getId())));
            }
            if ($param['action'] == 'creneaumaker') {
                return $this->redirect($this->generateUrl('creneaumaker_tache', array('id' => $entity->getId())));
            }
            if ($param['action'] == 'affectation') {
                return $this->redirect($this->generateUrl('tache_okaffectation', array('id' => $entity->getId())));
            }
        }

        if ($param['action'] == 'add_plage') {
            return $this->redirect($this->generateUrl('plagehoraire_new', array('id' => $entity->getId())));
        }


        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'valid' => $valid,
            'rOnly' => $rOnly,
            'deadlinePassed' => $deadlinePassed
        );
    }

    /**
     * Sets a tache ok for affectation
     *
     * @Route("/{id}/ok", name="tache_okaffectation")
     */
    public function okaffectationAction($id) {
        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }
        $admin = $this->get('security.context')->isGranted('ROLE_HUMAIN');
        $em = $this->getDoctrine()->getEntityManager();
        $config = $this->get('config.extension');
        $request = $this->getRequest();
        $entity = $em->getRepository('AssoMakerPHPMBundle:Tache')->find($id);
        $user = $this->get('security.context')->getToken()->getUser();


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        if ($entity->getStatut() < 2) {
            throw new \Exception("La tâche doit être validée");
        }

        // On génère les créneaux
        /** @var CreneauRepository $creneauRepository */
        $creneauRepository = $this->getDoctrine()->getRepository('AssoMakerPHPMBundle:Creneau');
        $creneauRepository->generateCreneauForTache($id);

        $commentaire = new Commentaire();
        $commentaire->setAuteur($user);
        $commentaire->setHeure(new \DateTime());
        $commentaire->setTache($entity);
        $commentaire->setTexte('<b>&rarr;Tache ok pour affectation.</b>');
        $em->persist($commentaire);

        $entity->setStatut(3);

        $em->flush();


        return $this->redirect($this->generateUrl('creneaumaker_tache', array('id' => $entity->getId())));
    }

    /**
     * Deletes a Tache entity.
     *
     * @Route("/{id}/delete", name="tache_delete")
     *
     */
    public function deleteAction($id) {
        if (false === $this->get('security.context')->isGranted('ROLE_HUMAIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AssoMakerPHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $em->remove($entity);
        $em->flush();


        return $this->redirect($this->generateUrl('tache'));
    }

    /**
     * Moves a Tache entity to trash.
     *
     * @Route("/{id}/trash", name="tache_trash")
     *
     */
    public function trashAction($id) {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('AssoMakerPHPMBundle:Tache')->find($id);

        $user = $this->get('security.context')->getToken()->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tache entity.');
        }

        $entity->removeAllCreneaux();

        $entity->setStatut(-1);
        $commentaire = new Commentaire();
        $commentaire->setAuteur($user);
        $commentaire->setHeure(new \DateTime());
        $commentaire->setTache($entity);
        $commentaire->setTexte("<b>&rarr;Fiche supprimée.</b>");
        $em->persist($commentaire);


        $em->flush();


        return $this->redirect($this->generateUrl('tache'));
    }

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /**
     * Fiches Resp
     *
     * @Route("/fichesresp/{sdebut}/{sfin}",  name="phpm_tache_fiches_resp")
     * @Template()
     */
    public function fichesRespAction($sdebut, $sfin) {

        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $debut = new \DateTime($sdebut);
        $fin = new \DateTime($sfin);


        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->createQuery("SELECT o,t,p,c,d,oa,g FROM AssoMakerBaseBundle:Orga o JOIN o.tachesResponsable t JOIN t.plagesHoraire p JOIN p.creneaux c JOIN c.disponibilite d JOIN d.orga oa JOIN t.groupeTache g WHERE o.statut >=0 AND p.debut > :debut  AND p.fin < :fin ORDER BY o.nom, c.debut")
                ->setParameter('debut', $debut)
                ->setParameter('fin', $fin)
                ->getResult();

        return array('orgas' => $entities);
    }

    /**
     * Lists all Tache entities.
     *
     * @Route("/query.json", name="tache_query_json")
     * @Method("post")
     */
    public function queryJsonAction() {
        // fonction qui permet de selectionner une liste de tache en fonction de certains critères
        $request = $this->getRequest();

        //on recupere les paramètres passés en post
        $equipe = $request->request->get('equipe_id', '');
        $permis = $request->request->get('permisNecessaire', '');
        $plage = $request->request->get('plage_id', '');

        $em = $this->getDoctrine()->getEntityManager();
        // création de la requête SQL et récupération de son retour
        $entities = $em->getRepository('AssoMakerPHPMBundle:Tache')->getTacheWithCriteria($plage, $permis, $equipe);

        // creation du json de retour
        $taches = array();
        foreach ($entities as $entity) {
            $taches[] = array(
                "id" => $entity->getId(),
                "nom" => $entity->getNom(),
                "lieu" => $entity->getLieu(),
                "permisNecessaire" => $entity->getPermisNecessaire()
            );
        }

        $response = new Response();
        $response->setContent(json_encode($taches));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     *
     *
     * @Route("/changeCom", name="phpm_tache_changecom")
     * @Secure("ROLE_LOG")
     * @Method("post")
     *
     */
    public function changeComAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();


        $data = json_decode($request->getContent(), true);

        $id = $data['id'];

        $entity = $em->getRepository('AssoMakerPHPMBundle:Tache')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find T entity.');
        }

        $com = $data['com'];

        $entity->setCommentaireLog($com);

        $em->persist($entity);
        $em->flush();


        return new Response();
    }

}
