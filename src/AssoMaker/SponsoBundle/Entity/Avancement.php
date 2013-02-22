<?php

namespace AssoMaker\SponsoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * AssoMaker\SponsoBundle\Entity\Avancement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\SponsoBundle\Entity\AvancementRepository")
 */
class Avancement {

    public static $messagesStatut = array(
        -1 => 'Projet annulé / en sommeil',
        0 => 'Dossier en cours de rédaction',
        1 => 'Dossier en attente de validation',
        2 => 'Dossier prêt à être envoyé',
        3 => 'En attente de réponse du contact',
        4 => 'Rendez-vous pris',
        5 => 'Partenariat négocié',
        6 => 'Transmission des infos à l\'Orga',
        7 => 'Préparation de la prestation en attente de validation',
        8 => 'Prestation prête à être exécutée',
        9 => 'Documentation pour l\'an prochain',
        10 => 'Projet terminé');

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255,nullable=true)
     */
    private $nom;

    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=true)
     *
     * @Assert\Email(
     * 	   message = "L'email doît être valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var string $poste
     *
     * @ORM\Column(name="poste", type="string", length=255,nullable=true)
     */
    private $poste;

    /**
     * @var string $entreprise
     *
     * @ORM\Column(name="entreprise", type="string", length=255)
     */
    private $entreprise;

    /**
     * @var string $adresse
     *
     * @ORM\Column(name="adresse", type="string", length=1024, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="avancements")
     * @ORM\JoinColumn(name="projet_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $projet;

    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\BaseBundle\Entity\Orga", inversedBy="equipesResponsable",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id",onDelete="SET NULL", nullable=true)
     * @Assert\Valid
     */
    protected $responsable;

    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="avancement")
     */
    protected $notes;

    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "-1"})
     * @ORM\Column(name="statut", type="smallint")
     */
    protected $statut;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Avancement
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
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
     * Set telephone
     *
     * @param string $telephone
     * @return Avancement
     */
    public function setTelephone($telephone) {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone() {
        return $this->telephone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Avancement
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set poste
     *
     * @param string $poste
     * @return Avancement
     */
    public function setPoste($poste) {
        $this->poste = $poste;

        return $this;
    }

    /**
     * Get poste
     *
     * @return string
     */
    public function getPoste() {
        return $this->poste;
    }

    public function __toString() {
        return $this->getEntreprise();
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Avancement
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();

        $this->statut = 0;
    }

    /**
     * Add notes
     *
     * @param AssoMaker\SponsoBundle\Entity\Note $notes
     * @return Avancement
     */
    public function addNote(\AssoMaker\SponsoBundle\Entity\Note $notes) {
        $this->notes[] = $notes;

        return $this;
    }

    /**
     * Remove notes
     *
     * @param AssoMaker\SponsoBundle\Entity\Note $notes
     */
    public function removeNote(\AssoMaker\SponsoBundle\Entity\Note $notes) {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * Set entreprise
     *
     * @param string $entreprise
     * @return Avancement
     */
    public function setEntreprise($entreprise) {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get entreprise
     *
     * @return string
     */
    public function getEntreprise() {
        return $this->entreprise;
    }

    /**
     * Set projet
     *
     * @param AssoMaker\SponsoBundle\Entity\Projet $projet
     * @return Avancement
     */
    public function setProjet(\AssoMaker\SponsoBundle\Entity\Projet $projet = null) {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet
     *
     * @return AssoMaker\SponsoBundle\Entity\Projet
     */
    public function getProjet() {
        return $this->projet;
    }

    /**
     * Set responsable
     *
     * @param AssoMaker\BaseBundle\Entity\Orga $responsable
     * @return Avancement
     */
    public function setResponsable(\AssoMaker\BaseBundle\Entity\Orga $responsable = null) {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return AssoMaker\BaseBundle\Entity\Orga
     */
    public function getResponsable() {
        return $this->responsable;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     * @return Avancement
     */
    public function setStatut($statut) {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut() {
        return $this->statut;
    }

    public function getMessageStatut() {
        return $this::$messagesStatut[$this->getStatut()];
    }

    public function hasDossier() {
        $fs = new Filesystem();
        return $fs->exists($this->getDossierPath());
    }

    public function getDossierDir() {
        return __DIR__ . '/../../../../web/up/dossiersSponso/';
    }

    public function getDossierFileName() {
        return $this->getId() . $this->getEntreprise() . '.docx';
    }

    public function getDossierPath() {
        return $this->getDossierDir() . $this->getDossierFileName();
    }

}

