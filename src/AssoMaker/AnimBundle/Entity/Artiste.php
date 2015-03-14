<?php

namespace AssoMaker\AnimBundle\Entity;

use Symfony\Component\Validator\Constraints\DateTime;
use AssoMaker\BaseBundle\Entity\Orga;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Artiste
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\AnimBundle\Entity\ArtisteRepository")
 */
class Artiste {

    public static $stages = array('SMALL' => 'Petite scène', 'BIG' => 'Grande scène');

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0", "1", "2", "-1"})
     * @ORM\Column(type="smallint")
     */
    protected $statut;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $stage;

    /**
     * @var string $description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="array")
     *
     */
    protected $horaires = array();

    /**
     * @ORM\Column(type="array")
     *
     */
    protected $commentaires = array();

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\AnimBundle\Entity\PhotoArtiste", mappedBy="artiste")
     */
    private $photos;

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
     * @return Artiste
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
     * Set statut
     *
     * @param integer $statut
     * @return Artiste
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

    /**
     * Set stage
     *
     * @param string $stage
     * @return Artiste
     */
    public function setStage($stage) {
        $this->stage = $stage;

        return $this;
    }

    /**
     * Get stage
     *
     * @return string
     */
    public function getStage() {
        return $this->stage;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Artiste
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set horaires
     *
     * @param array $horaires
     * @return Artiste
     */
    public function setHoraires($horaires) {
        $this->horaires = $horaires;

        return $this;
    }

    /**
     * Get horaires
     *
     *
     */
    public function getHoraires() {
        return $this->horaires;
    }

    /**
     * Set commentaires
     *
     * @param array $commentaires
     * @return Artiste
     */
    public function setCommentaires($commentaires) {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * Get commentaires
     *
     * @return array
     */
    public function getCommentaires() {
        return $this->commentaires;
    }

    public function addCommentaire(Orga $auteur, $texte, $type = 0) {

        $this->commentaires[] = array('auteur' => $auteur->getFullName(),
            'type' => $type,
            'texte' => $texte,
            'date' => (new \DateTime()));
    }

    public function __toString() {
        return $this->nom;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add PhotoArtiste
     *
     * @param \AssoMaker\AnimBundle\Entity\PhotoArtiste $photo
     * @return Artiste
     */
    public function addPhoto(\AssoMaker\AnimBundle\Entity\PhotoArtiste $photo) {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove PhotoArtiste
     *
     * @param \AssoMaker\AnimBundle\Entity\PhotoArtiste $photo
     */
    public function removePhotoMobile(\AssoMaker\AnimBundle\Entity\PhotoArtiste $photo) {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos() {
        return $this->photos;
    }

}

