<?php

namespace AssoMaker\SponsoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * AssoMaker\SponsoBundle\Entity\Projet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\SponsoBundle\Entity\ProjetRepository")
 * @UniqueEntity(fields="nom", message="Un projet du même nom existe déjà")
 */
class Projet {

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
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var \DateTime $dateCreation
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     * @Assert\NotBlank()
     */
    private $dateCreation;

    /**
     * @ORM\OneToMany(targetEntity="Avancement", mappedBy="projet")
     */
    protected $avancements;

    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\BaseBundle\Entity\Equipe", inversedBy="orgas")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $equipe;

    /**
     * @ORM\ManyToMany(targetEntity="Support")
     * @ORM\JoinTable(name="Projet_Support",
     *      joinColumns={@ORM\JoinColumn(name="Projet_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="Support_id", referencedColumnName="id")}
     *      )
     */
    protected $supports;

    /**
     * Constructor
     */
    public function __construct() {
        $this->supports = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

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
     * @return Projet
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Projet
     */
    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation() {
        return $this->dateCreation;
    }

    /**
     * Set equipe
     *
     * @param AssoMaker\BaseBundle\Entity\Equipe $equipe
     * @return Projet
     */
    public function setEquipe(\AssoMaker\BaseBundle\Entity\Equipe $equipe = null) {
        $this->equipe = $equipe;

        return $this;
    }

    /**
     * Get equipe
     *
     * @return AssoMaker\BaseBundle\Entity\Equipe
     */
    public function getEquipe() {
        return $this->equipe;
    }

    /**
     * Add supports
     *
     * @param AssoMaker\SponsoBundle\Entity\Support $supports
     * @return Projet
     */
    public function addSupport(\AssoMaker\SponsoBundle\Entity\Support $supports) {
        $this->supports[] = $supports;

        return $this;
    }

    /**
     * Remove supports
     *
     * @param AssoMaker\SponsoBundle\Entity\Support $supports
     */
    public function removeSupport(\AssoMaker\SponsoBundle\Entity\Support $supports) {
        $this->supports->removeElement($supports);
    }

    /**
     * Get supports
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSupports() {
        return $this->supports;
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
     * Get avancements
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAvancements() {
        return $this->avancements;
    }

    /**
     * Add avancements
     *
     * @param AssoMaker\SponsoBundle\Entity\Avancement $avancements
     * @return Projet
     */
    public function addAvancement(\AssoMaker\SponsoBundle\Entity\Avancement $avancements) {
        $this->avancements[] = $avancements;

        return $this;
    }

    /**
     * Remove avancements
     *
     * @param AssoMaker\SponsoBundle\Entity\Avancement $avancements
     */
    public function removeAvancement(\AssoMaker\SponsoBundle\Entity\Avancement $avancements) {
        $this->avancements->removeElement($avancements);
    }

    public function __toString() {
        return $this->getNom();
    }

}

