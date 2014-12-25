<?php

namespace AssoMaker\BaseBundle\Entity;

use AssoMaker\BaseBundle\AssoMakerBaseBundle;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use AssoMaker\PHPMBundle\Entity\Disponibilite;

/**
 * AssoMaker\BaseBundle\Entity\Orga
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\BaseBundle\Entity\OrgaRepository")
 * @UniqueEntity(fields={"email"}, message="Un orga possédant cet email est déjà inscrit.")
 * @UniqueEntity(fields={"telephone"}, message="Un orga possédant ce numéro de téléphone est déjà inscrit.")
 */
class Orga extends BaseUser implements UserInterface {

    public static $privilegesTypes = array('Orga', 'Orga Hard', 'Humain', 'Responsable Log', 'Responsable Sécurité', 'Super Admin');
    public static $privilegesRoles = array('ROLE_ORGA', 'ROLE_HARD', 'ROLE_HUMAIN', 'ROLE_LOG', 'ROLE_SECU', 'ROLE_SUPER_ADMIN');

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer $importId
     *
     * @ORM\Column(name="importid", type="integer", nullable=true)
     */
    protected $importId;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = "3")
     */
    protected $nom;

    /**
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min = "3")
     */
    protected $prenom;

    /**
     * @var string $surnom
     *
     * @ORM\Column(name="surnom", type="string", length=255, nullable=true)
     */
    protected $surnom;

    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", length=511, nullable=true)
     */
    protected $google_id;

    /**
     * @var string $role
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=true)
     */
    protected $role;

    /**
     *
     * @ORM\Column(name="membreBureau", type="boolean")
     */
    protected $membreBureau=false;

    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=255, unique=true, nullable=true)
     * @Assert\NotBlank()
     */
    protected $telephone;

    /*
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\Email(
     * 	   message = "L'email doît être valide.",
     *     checkMX = true
     * )
     */
    //protected $email;

    /**
     * @var string $publicEmail
     *
     * @ORM\Column(name="publicEmail", type="string", length=255, nullable=true)
     *
     * @Assert\Email(
     * 	   message = "L'email public doît être valide.",
     *     checkMX = true
     * )
     */
    protected $publicEmail;

    /**
     * @var date $dateDeNaissance
     *
     * @ORM\Column(name="dateDeNaissance", type="date", nullable=true)
     *
     * @Assert\Date(message="La date de naissance doît être valide")
     */
    protected $dateDeNaissance;

    /**
     * @Assert\Image(
     *     minWidth = 600,
     *     maxWidth = 600,
     *     minHeight = 800,
     *     maxHeight = 800,
     *     mimeTypes = {"image/jpeg"}
     * )
     */
    protected $profilePicture;

    /**
     * @var boolean $profilePictureSet
     *
     * @ORM\Column(name="profilePictureSet", type="boolean", nullable=true)
     */
    protected $profilePictureSet = false;

    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Fichier PDF uniquement"
     * )
     */
    protected $fichierPermis;

    /**
     *
     *
     * @ORM\Column(type="boolean")
     */
    protected $fichierPermisSet = false;

    /**
     * @var date $datePermis
     *
     * @ORM\Column(name="datePermis", type="date", nullable=true)
     *
     * @Assert\Date(message="La date de permis doît être valide")
     */
    protected $datePermis;

    /**
     * @ORM\Column(type="string", length=1, name="anneeEtudes",nullable=true)
     * @Assert\Choice(choices = {0,1,2,3,4,5,6,7,8} , message = "Année d'études invalide. ")
     */
    protected $anneeEtudes;

    /**
     * @ORM\Column(type="smallint", length=1, name="groupePC",nullable=true)
     */
    protected $groupePC;

    /**
     * @var string $departement
     *
     * @ORM\Column(name="departement", type="string", length=255, nullable=true)
     */
    protected $departement;

//     /**
//      * @var string $equipe
//      *
//      * @ORM\Column(name="equipe", type="string", length=255, nullable=true)
//      */
//     protected $equipe;

    /**
     * @var text $commentaire
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    protected $commentaire;

    /** @ORM\Column(type="array") */
    private $competences = array();

    public function setCompetences($value) {
        $this->competences = $value;
    }

    public function getCompetences() {
        return $this->competences;
    }

    /**
     * @var text $amis
     *
     * @ORM\Column(name="amis", type="text", nullable=true)
     */
    protected $amis;

//     /**
//      * @var smallint $permis
//      * @Assert\Choice(choices = {"0", "1", "2"})
//      * @ORM\Column(name="permis", type="smallint")
//      */
//     protected $permis;

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\PHPMBundle\Entity\GroupeTache", mappedBy="responsable")
     */
    protected $groupesTacheResponsable;

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\PHPMBundle\Entity\Tache", mappedBy="responsable")
     */
    protected $tachesResponsable;

    /**
     * @ORM\OneToMany(targetEntity="Equipe", mappedBy="responsable")
     */
    protected $equipesResponsable;

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\PHPMBundle\Entity\Disponibilite", mappedBy="orga",indexBy="id", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $disponibilites;

    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"-1", "0", "1", "2"})
     * @ORM\Column(name="statut", type="smallint", nullable=true)
     */
    protected $statut;

    /**
     * @ORM\ManyToMany(targetEntity="AssoMaker\PHPMBundle\Entity\DisponibiliteInscription", inversedBy="orgas")
     * @ORM\JoinTable(name="orga_disponibiliteinscription",
     *      joinColumns={@ORM\JoinColumn(name="orga_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="disponibiliteinscription_id", referencedColumnName="id")}
     *      )
     */
    protected $disponibilitesInscription;

    /**
     * @ORM\Column(type="smallint", name="privileges", nullable=true)
     * @Assert\Choice(choices = {"0", "1","2","3","4","5"})
     */
    protected $privileges;

    /**
     * @ORM\ManyToOne(targetEntity="Equipe", inversedBy="orgas")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $equipe;

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\PHPMBundle\Entity\Commentaire", mappedBy="auteur")
     */
    protected $commentaires;

    /**
     * @var datetime $lastActivity
     *
     * @ORM\Column(name="lastActivity", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    protected $lastActivity;

    /**
     * @var smallint $celibataire
     * @Assert\Choice(choices = {"0", "1"})
     * @ORM\Column(name="celibataire", type="smallint", nullable=true)
     */
    protected $celibataire;

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\PHPMBundle\Entity\Creneau", mappedBy="orgaHint")
     */
    protected $creneauxHint;

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\PHPMBundle\Entity\BesoinOrga", mappedBy="orgaHint")
     */
    protected $besoinsOrgaHint;

    public function __sleep() {
        return array('id', 'email');
    }

    public function toArray($developCreneaux = NULL) {
        $a = array();
        if (isset($developCreneaux))
            foreach ($this->getDisponibilites() as $entity) {
                $a[$entity->getId()] = $entity->toArray(TRUE);
            }


        return array(
            "id" => $this->getId(),
            "importid" => $this->getImportId(),
            "statut" => $this->getStatut(),
            "nom" => $this->getNom(),
            "prenom" => $this->getPrenom(),
            "surnom" => $this->getSurnom(),
            "telephone" => $this->getTelephone(),
            "email" => $this->getEmail(),
            "dateDeNaissance" => $this->getDateDeNaissance(),
            "departement" => $this->getDepartement(),
            "commentaire" => $this->getCommentaire(),
//     		"permis"=>$this->getPermis(),
            "confiance" => $this->getConfiance()->toArray(),
            "disponibilites" => $a);
    }

    public function toSearchArray() {
        return array(
            "type" => "orga",
            "id" => $this->getId(),
            "nom" => $this->getNom(),
            "prenom" => $this->getPrenom(),
            "surnom" => $this->getSurnom(),
            "telephone" => $this->getTelephone(),
            "email" => $this->getEmail());
    }

    public function toExportArray() {
        $a = array();

        foreach ($this->getDisponibilites() as $entity) {
            $a[$entity->getId()] = $entity->toSimpleArray();
        }



        return array(
            "id" => $this->getId(),
            "statut" => $this->getStatut(),
            "nom" => $this->getNom(),
            "prenom" => $this->getPrenom(),
            "surnom" => $this->getSurnom(),
            "telephone" => $this->getTelephone(),
            "email" => $this->getEmail(),
            "dateDeNaissance" => $this->getDateDeNaissance()->format('Y-m-d'),
            "departement" => $this->getDepartement(),
            "commentaire" => $this->getCommentaire(),
//     			"permis"=>$this->getPermis(),
            "confiance" => $this->getConfiance()->getId(),
            "disponibilites" => $a);
    }

    public function addDIstoDisponibilites() {
        foreach ($this->getDisponibilitesInscription() as $di) {
            $absorbed = false;
            foreach ($this->getDisponibilites()as $dispo) {
                if (($di->getDebut() <= $dispo->getFin()) && ($di->getFin() >= $dispo->getFin())) {
                    $dispo->setFin($di->getFin());
                    $absorbed = true;
                }
                if (($di->getDebut() <= $dispo->getDebut()) && ($di->getFin() >= $dispo->getDebut())) {
                    $dispo->setDebut($di->getDebut());
                    $absorbed = true;
                }
            }
            if (!$absorbed) {
                $dispo = new Disponibilite();
                $dispo->setDebut($di->getDebut());
                $dispo->setFin($di->getFin());
                $dispo->setOrga($this);
                $this->addDisponibilite($dispo);
            }
        }
    }

    public function addDIToDisponibilites(\AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $di) {
        $absorbed = false;
        foreach ($this->getDisponibilites()as $dispo) {
            if (($di->getDebut() <= $dispo->getFin()) && ($di->getFin() >= $dispo->getFin())) {
                $dispo->setFin($di->getFin());
                $absorbed = true;
            }
            if (($di->getDebut() <= $dispo->getDebut()) && ($di->getFin() >= $dispo->getDebut())) {
                $dispo->setDebut($di->getDebut());
                $absorbed = true;
            }
        }
        if (!$absorbed) {
            $dispo = new Disponibilite();
            $dispo->setDebut($di->getDebut());
            $dispo->setFin($di->getFin());
            $dispo->setOrga($this);
            $this->addDisponibilite($dispo);
        }
    }

    public function removeDIFromDisponibilites(\AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $di) {
        foreach ($this->getDisponibilites()as $dispo) {

            if ($di->getFin() <= $dispo->getDebut()) {

            } elseif ($di->getDebut() >= $dispo->getFin()) {

            } elseif (($di->getDebut() <= $dispo->getDebut()) && ($di->getFin() >= $dispo->getFin())) {
                $this->removeDisponibilite($dispo);
            } elseif (($di->getDebut() <= $dispo->getDebut()) && ($di->getFin() > $dispo->getDebut())) {

                foreach ($dispo->getCreneaux() as $creneau) {
                    if (($creneau->getDebut() < $di->getFin()) && ($creneau->getFin() > $di->getDebut())) {
                        var_dump('deja affecté a ' . $creneau->getId());
                        return false;
                    }
                }

                $dispo->setDebut($di->getFin());
            } elseif (($di->getDebut() < $dispo->getFin()) && ($di->getFin() >= $dispo->getFin())) {

                foreach ($dispo->getCreneaux() as $creneau) {
                    if (($creneau->getDebut() < $di->getFin()) && ($creneau->getFin() > $di->getDebut())) {
                        var_dump('deja affecté a ' . $creneau->getId());
                        return false;
                    }
                }

                $dispo->setFin($di->getDebut());
            } elseif (($di->getDebut() > $dispo->getDebut()) && ($di->getFin() < $dispo->getFin())) {

                foreach ($dispo->getCreneaux() as $creneau) {
                    if (($creneau->getDebut() < $di->getFin()) && ($creneau->getFin() > $di->getDebut())) {
                        var_dump('deja affecté a ' . $creneau->getId());
                        return false;
                    }
                }
                print($dispo->getId());
                print(" ");
                print($di->getId());
                print("scinder");

                $nd = new Disponibilite();
                $nd->setDebut($di->getFin());
                $nd->setFin($dispo->getFin());
                $dispo->setFin($di->getDebut());

                foreach ($dispo->getCreneaux() as $creneau) {
                    if ($creneau->getDebut() >= $di->getFin()) {
                        $creneau->setDisponibilite($nd);
                    }
                }


                $nd->setOrga($this);
                $this->addDisponibilite($nd);
            } else {
                print("merde...");

                var_dump($dispo->getDebut());
                var_dump($dispo->getFin());
                var_dump($di->getDebut());
                var_dump($di->getFin());
            }
            print("\n");
        }
        return true;
    }

    public function cleanDisponibilites() {

        foreach ($this->getDisponibilites()as $d1) {
            foreach ($this->getDisponibilites()as $d2) {
                if ($d1 == $d2) {
                    continue;
                }

                if (($d1->getDebut() <= $d2->getFin()) && ($d1->getFin() >= $d2->getFin())) {
                    $d2->setFin($d1->getFin());
                    if ($d1->getDebut() <= $d2->getDebut()) {
                        $d2->setDebut($d1->getDebut());
                    }
                    foreach ($d1->getCreneaux() as $c) {
                        $c->setDisponibilite($d2);
                    }
                    $this->removeDisponibilite($d1);
                    $d1->setOrga(null);
                }
            }
        }
    }

    public function generateUserToken() {

        if ($this->getPrivileges() == 5) {
            $options = array('ROLE_SUPER_ADMIN');
        } elseif ($this->getPrivileges() == 4) {
            $options = array('ROLE_SECU');
        } elseif ($this->getPrivileges() == 3) {
            $options = array('ROLE_LOG');
        } elseif ($this->getPrivileges() == 2) {
            $options = array('ROLE_HUMAIN');
        } elseif ($this->getPrivileges() == 1) {
            $options = array('ROLE_USER');
        } elseif ($this->getPrivileges() == 0) {
            $options = array('ROLE_VISITOR');
        }
        
        if ($this->getMembreBureau()) {
            $options[] = 'ROLE_BUREAU';
        }


        return new UsernamePasswordToken($this, null, 'main', $options);
    }

    public function isEqualTo(UserInterface $user)
    {
        return $user->getEmail() === $this->email;
    }

    public function __construct() {
        parent::__construct();
        $this->tachesResponsable = new \Doctrine\Common\Collections\ArrayCollection();
        $this->equipesResponsable = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disponibilites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disponibilitesInscription = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set importId
     *
     * @param integer $importId
     */
    public function setImportId($importId) {
        $this->importId = $importId;
    }

    /**
     * Get importId
     *
     * @return integer
     */
    public function getImportId() {
        return $this->importId;
    }

    /**
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom) {
        $this->nom = $nom;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * Set surnom
     *
     * @param string $surnom
     */
    public function setSurnom($surnom) {
        $this->surnom = $surnom;
    }

    /**
     * Get surnom
     *
     * @return string
     */
    public function getSurnom() {
        return $this->surnom;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     */
    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set dateDeNaissance
     *
     * @param date $dateDeNaissance
     */
    public function setDateDeNaissance($dateDeNaissance) {
        $this->dateDeNaissance = $dateDeNaissance;
    }

    /**
     * Get dateDeNaissance
     *
     * @return date
     */
    public function getDateDeNaissance() {
        return $this->dateDeNaissance;
    }

    /**
     * Set departement
     *
     * @param string $departement
     */
    public function setDepartement($departement) {
        $this->departement = $departement;
    }

    /**
     * Get departement
     *
     * @return string
     */
    public function getDepartement() {
        return $this->departement;
    }

    /**
     * Set commentaire
     *
     * @param text $commentaire
     */
    public function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    /**
     * Get commentaire
     *
     * @return text
     */
    public function getCommentaire() {
        return $this->commentaire;
    }

    /**
     * Set statut
     *
     * @param smallint $statut
     */
    public function setStatut($statut) {
        $this->statut = $statut;
    }

    /**
     * Get statut
     *
     * @return smallint
     */
    public function getStatut() {
        return $this->statut;
    }

    /**
     * Add tachesResponsable
     *
     * @param \AssoMaker\PHPMBundle\Entity\Tache $tachesResponsable
     */
    public function addTache(\AssoMaker\PHPMBundle\Entity\Tache $tachesResponsable) {
        $this->tachesResponsable[] = $tachesResponsable;
    }

    /**
     * Get tachesResponsable
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTachesResponsable() {
        return $this->tachesResponsable;
    }

    /**
     * Add equipesResponsable
     *
     * @param \AssoMaker\BaseBundle\Entity\Equipe $equipesResponsable
     */
    public function addEquipe(\AssoMaker\BaseBundle\Entity\Equipe $equipesResponsable) {
        $this->equipesResponsable[] = $equipesResponsable;
    }

    /**
     * Get equipesResponsable
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipesResponsable() {
        return $this->equipesResponsable;
    }

    /**
     * Add disponibilites
     *
     * @param \AssoMaker\PHPMBundle\Entity\Disponibilite $disponibilites
     */
    public function addDisponibilite(\AssoMaker\PHPMBundle\Entity\Disponibilite $disponibilites) {
        $this->disponibilites[] = $disponibilites;
    }

    /**
     * remove disponibilite
     *
     * @param \AssoMaker\PHPMBundle\Entity\Disponibilite $disponibilite
     */
    public function removeDisponibilite(\AssoMaker\PHPMBundle\Entity\Disponibilite $disponibilite) {
//     	foreach ($disponibilite->getCreneaux() as $creneau){
// 				$disponibilite->getCreneaux()->removeElement($creneau);
// 				$creneau->setDisponibilite(null);
//     			}

        $this->getDisponibilites()->removeElement($disponibilite);
    }

    /**
     * Get disponibilites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisponibilites() {
        return $this->disponibilites;
    }

    /**
     * Add disponibilitesInscription
     *
     * @param \AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $disponibilitesInscription
     */
    public function addDisponibiliteInscription(\AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $disponibilitesInscription) {

        $this->disponibilitesInscription[] = $disponibilitesInscription;
        $this->addDIToDisponibilites($disponibilitesInscription);
    }

    /**
     * Remove disponibilitesInscription
     *
     * @param \AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $disponibilitesInscription
     */
    public function removeDisponibiliteInscription(\AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $disponibiliteInscription) {
        if ($this->removeDIFromDisponibilites($disponibiliteInscription)) {
            $this->getDisponibilitesInscription()->removeElement($disponibiliteInscription);
        }
    }

    /**
     * Get disponibilitesInscription
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisponibilitesInscription() {
        return $this->disponibilitesInscription;
    }

    /**
     * Get charisme
     *
     *
     */
    public function getCharisme() {
        $charisme = 0;
        foreach ($this->disponibilitesInscription as $di) {
            $charisme+=$di->getPointsCharisme();
        }
        return $charisme;
    }

    /**
     * Set equipe
     *
     * @param \AssoMaker\BaseBundle\Entity\Equipe $equipe
     */
    public function setEquipe(\AssoMaker\BaseBundle\Entity\Equipe $equipe) {
        $this->equipe = $equipe;
    }

    /**
     * Get equipe
     *
     * @return \AssoMaker\BaseBundle\Entity\Equipe
     */
    public function getEquipe() {
        return $this->equipe;
    }

    /**
     * Add commentaires
     *
     * @param \AssoMaker\PHPMBundle\Entity\Commentaire $commentaires
     */
    public function addCommentaire(\AssoMaker\PHPMBundle\Entity\Commentaire $commentaires) {
        $this->commentaires[] = $commentaires;
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires() {
        return $this->commentaires;
    }

    /**
     * Set lastActivity
     *
     * @param datetime $lastActivity
     */
    public function setLastActivity($lastActivity) {
        $this->lastActivity = $lastActivity;
    }

    /**
     * Get lastActivity
     *
     * @return datetime
     */
    public function getLastActivity() {
        return $this->lastActivity;
    }

    /**
     * Set datePermis
     *
     * @param date $datePermis
     */
    public function setDatePermis($datePermis) {
        $this->datePermis = $datePermis;
    }

    /**
     * Get datePermis
     *
     * @return date
     */
    public function getDatePermis() {
        return $this->datePermis;
    }

    /**
     * Set privileges
     *
     * @param smallint $privileges
     */
    public function setPrivileges($privileges) {
        $this->privileges = $privileges;
    }

    /**
     * Get privileges
     *
     * @return smallint
     */
    public function getPrivileges() {
        return $this->privileges;
    }

    /**
     * Add creneauxHint
     *
     * @param \AssoMaker\PHPMBundle\Entity\Creneau $creneauxHint
     */
    public function addCreneau(\AssoMaker\PHPMBundle\Entity\Creneau $creneauxHint) {
        $this->creneauxHint[] = $creneauxHint;
    }

    /**
     * Get creneauxHint
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreneauxHint() {
        return $this->creneauxHint;
    }

    /**
     * Add besoinsOrgaHint
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrgaHint
     */
    public function addBesoinOrga(\AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrgaHint) {
        $this->besoinsOrgaHint[] = $besoinsOrgaHint;
    }

    /**
     * Get besoinsOrgaHint
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBesoinsOrgaHint() {
        return $this->besoinsOrgaHint;
    }

    /**
     * Add groupesTacheResponsable
     *
     * @param \AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheResponsable
     */
    public function addGroupeTache(\AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheResponsable) {
        $this->groupesTacheResponsable[] = $groupesTacheResponsable;
    }

    /**
     * Get groupesTacheResponsable
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupesTacheResponsable() {
        return $this->groupesTacheResponsable;
    }

    /**
     * Set celibataire
     *
     * @param smallint $celibataire
     */
    public function setCelibataire($celibataire) {
        $this->celibataire = $celibataire;
    }

    /**
     * Get celibataire
     *
     * @return smallint
     */
    public function getCelibataire() {
        return $this->celibataire;
    }

    /**
     * Set amis
     *
     * @param text $amis
     */
    public function setAmis($amis) {
        $this->amis = $amis;
    }

    /**
     * Get amis
     *
     * @return text
     */
    public function getAmis() {
        return $this->amis;
    }

    /**
     * Set anneeEtudes
     *
     * @param string $anneeEtudes
     */
    public function setAnneeEtudes($anneeEtudes) {
        $this->anneeEtudes = $anneeEtudes;
    }

    /**
     * Get anneeEtudes
     *
     * @return string
     */
    public function getAnneeEtudes() {
        return $this->anneeEtudes;
    }

    /**
     * Set groupePC
     *
     * @param smallint $groupePC
     */
    public function setGroupePC($groupePC) {
        $this->groupePC = $groupePC;
    }

    /**
     * Get groupePC
     *
     * @return smallint
     */
    public function getGroupePC() {
        return $this->groupePC;
    }

    public function uploadProfilePicture() {

        if (null === $this->profilePicture) {
            return;
        }


        $this->profilePicture->move(__DIR__ . '/../../../../web/up/profilePictures', $this->getId() . '.jpg');
        $this->profilePicture = null;
        $this->setProfilePictureSet(true);
    }

    public function uploadFichierPermis() {

        if (null === $this->fichierPermis) {
            return;
        }


        $this->fichierPermis->move(__DIR__ . '/../../../../web/up/fichiersPermis', $this->getId() . '.pdf');
        $this->fichierPermis = null;
        $this->setFichierPermisSet(true);
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Orga
     */
    public function setRole($role) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * Set publicEmail
     *
     *
     * @return Orga
     */
    public function setPublicEmail($publicEmail) {
        $this->publicEmail = $publicEmail;

        return $this;
    }

    /**
     * Get publicEmail
     *
     * @return string
     */
    public function getPublicEmail() {
        if ($this->publicEmail != null) {
            return $this->publicEmail;
        } else {
            return $this->email;
        }
    }

    /**
     * Set profilePictureSet
     *
     * @param boolean $profilePictureSet
     * @return Orga
     */
    public function setProfilePictureSet($profilePictureSet) {
        $this->profilePictureSet = $profilePictureSet;

        return $this;
    }

    /**
     * Get profilePictureSet
     *
     * @return boolean
     */
    public function getProfilePictureSet() {
        return $this->profilePictureSet;
    }

    public function getProfilePicture() {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture) {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function setFichierPermis($fichierPermis) {
        $this->fichierPermis = $fichierPermis;

        return $this;
    }

    public function getFichierPermis() {
        return $this->fichierPermis;
    }

    /**
     * Add groupesTacheResponsable
     *
     * @param \AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheResponsable
     * @return Orga
     */
    public function addGroupesTacheResponsable(\AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheResponsable) {
        $this->groupesTacheResponsable[] = $groupesTacheResponsable;

        return $this;
    }

    /**
     * Remove groupesTacheResponsable
     *
     * @param \AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheResponsable
     */
    public function removeGroupesTacheResponsable(\AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheResponsable) {
        $this->groupesTacheResponsable->removeElement($groupesTacheResponsable);
    }

    /**
     * Add tachesResponsable
     *
     * @param \AssoMaker\PHPMBundle\Entity\Tache $tachesResponsable
     * @return Orga
     */
    public function addTachesResponsable(\AssoMaker\PHPMBundle\Entity\Tache $tachesResponsable) {
        $this->tachesResponsable[] = $tachesResponsable;

        return $this;
    }

    /**
     * Remove tachesResponsable
     *
     * @param \AssoMaker\PHPMBundle\Entity\Tache $tachesResponsable
     */
    public function removeTachesResponsable(\AssoMaker\PHPMBundle\Entity\Tache $tachesResponsable) {
        $this->tachesResponsable->removeElement($tachesResponsable);
    }

    /**
     * Add equipesResponsable
     *
     * @param \AssoMaker\BaseBundle\Entity\Equipe $equipesResponsable
     * @return Orga
     */
    public function addEquipesResponsable(\AssoMaker\BaseBundle\Entity\Equipe $equipesResponsable) {
        $this->equipesResponsable[] = $equipesResponsable;

        return $this;
    }

    /**
     * Remove equipesResponsable
     *
     * @param \AssoMaker\BaseBundle\Entity\Equipe $equipesResponsable
     */
    public function removeEquipesResponsable(\AssoMaker\BaseBundle\Entity\Equipe $equipesResponsable) {
        $this->equipesResponsable->removeElement($equipesResponsable);
    }

    /**
     * Add disponibilitesInscription
     *
     * @param \AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $disponibilitesInscription
     * @return Orga
     */
    public function addDisponibilitesInscription(\AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $disponibilitesInscription) {
        $this->disponibilitesInscription[] = $disponibilitesInscription;

        return $this;
    }

    /**
     * Remove disponibilitesInscription
     *
     * @param \AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $disponibilitesInscription
     */
    public function removeDisponibilitesInscription(\AssoMaker\PHPMBundle\Entity\DisponibiliteInscription $disponibilitesInscription) {
        $this->disponibilitesInscription->removeElement($disponibilitesInscription);
    }

    /**
     * Remove commentaires
     *
     * @param \AssoMaker\PHPMBundle\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\AssoMaker\PHPMBundle\Entity\Commentaire $commentaires) {
        $this->commentaires->removeElement($commentaires);
    }

    /**
     * Add creneauxHint
     *
     * @param \AssoMaker\PHPMBundle\Entity\Creneau $creneauxHint
     * @return Orga
     */
    public function addCreneauxHint(\AssoMaker\PHPMBundle\Entity\Creneau $creneauxHint) {
        $this->creneauxHint[] = $creneauxHint;

        return $this;
    }

    /**
     * Remove creneauxHint
     *
     * @param \AssoMaker\PHPMBundle\Entity\Creneau $creneauxHint
     */
    public function removeCreneauxHint(\AssoMaker\PHPMBundle\Entity\Creneau $creneauxHint) {
        $this->creneauxHint->removeElement($creneauxHint);
    }

    /**
     * Add besoinsOrgaHint
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrgaHint
     * @return Orga
     */
    public function addBesoinsOrgaHint(\AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrgaHint) {
        $this->besoinsOrgaHint[] = $besoinsOrgaHint;

        return $this;
    }

    /**
     * Remove besoinsOrgaHint
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrgaHint
     */
    public function removeBesoinsOrgaHint(\AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrgaHint) {
        $this->besoinsOrgaHint->removeElement($besoinsOrgaHint);
    }

    public function isBirthDayValid(ExecutionContext $context) {
        echo 'a';die;
        if ($this->dateDeNaissance >= new \DateTime()) {
            $context->addViolation('dateDeNaissance', 'Cette date doit être dans le passé.');
        }
    }

    public function isLicenceDateValid(ExecutionContext $context) {
        if ($this->datePermis != null && $this->datePermis >= new \DateTime()) {
            $context->addViolation('datePermis', 'Cette date doit être dans le passé.');
        }
    }

    /**
     * Set fichierPermisSet
     *
     * @param boolean $fichierPermisSet
     * @return Orga
     */
    public function setFichierPermisSet($fichierPermisSet) {
        $this->fichierPermisSet = $fichierPermisSet;

        return $this;
    }

    /**
     * Get fichierPermisSet
     *
     * @return boolean
     */
    public function getFichierPermisSet() {
        return $this->fichierPermisSet;
    }


    /**
     * Set membreBureau
     *
     * @param boolean $membreBureau
     * @return Orga
     */
    public function setMembreBureau($membreBureau)
    {
        $this->membreBureau = $membreBureau;
    
        return $this;
    }

    /**
     * Get membreBureau
     *
     * @return boolean 
     */
    public function getMembreBureau()
    {
        return $this->membreBureau;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     * @return Orga
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string 
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Generate roles for the user depending on status and privileges.
     * This function update current roles, you haven't to do it.
     *
     * @return array The roles that this user should have.
     */
    public function updateRoles(){
        $roles = array();
        if($this->isEnabled()){ // If account is not disabled
            $roles[] = "ROLE_ORGA";
            if($this->getPrivileges()!=null){ // Add role for this privilege. TODO: Privileges should be SQL saved
                if (isset(Orga::$privilegesRoles[intval($this->getPrivileges())])) {
                    if (!empty(Orga::$privilegesRoles[intval($this->getPrivileges())])) {
                        $roles[] = Orga::$privilegesRoles[intval($this->getPrivileges())];
                    }
                }
            }
        } else { // A user with a disabled account
            $roles[] = "ROLE_USER";
        }
        foreach($this->roles as $role)
            $this->removeRole($role);
        foreach($roles as $role)
            $this->addRole($role);
    }
}
