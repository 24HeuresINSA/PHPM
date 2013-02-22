<?php

namespace AssoMaker\SponsoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

$fs = new Filesystem();

/**
 * AssoMaker\SponsoBundle\Entity\Note
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\SponsoBundle\Entity\NoteRepository")
 */
class Note {

    public static $textesTypes = array('Infos', 'Rencontre', 'Appel téléphonique', 'Mail', 'Autre');

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    protected $date;

    /**
     * @var string $texte
     *
     * @ORM\Column(name="texte", type="string", length=4096, nullable=true)
     */
    private $texte;

    /**
     * @ORM\ManyToOne(targetEntity="Avancement",inversedBy="notes",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="avancement_id", referencedColumnName="id")
     * @Assert\Valid
     */
    protected $avancement;

    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\BaseBundle\Entity\Orga",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="orga_id", referencedColumnName="id")
     * @Assert\Valid
     */
    protected $orga;

    /**
     * @Assert\File(
     *     maxSize = "20M",
     *     mimeTypes = {"application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/zip"},
     *     mimeTypesMessage = "Fichier .docx uniquement"
     * )
     */
    protected $dossierSponso;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Note
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Note
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set orga
     *
     * @param AssoMaker\BaseBundle\Entity\Orga $orga
     * @return Note
     */
    public function setOrga(\AssoMaker\BaseBundle\Entity\Orga $orga = null) {
        $this->orga = $orga;

        return $this;
    }

    /**
     * Get orga
     *
     * @return AssoMaker\BaseBundle\Entity\Orga
     */
    public function getOrga() {
        return $this->orga;
    }

    /**
     * Get type Texte
     *
     *
     */
    public function getTypeTexte() {
        return Note::$textesTypes[$this->type];
    }

    public function toArray() {
        return array("id" => $this->getId(),
            "type" => $this->getTypeTexte(),
            "statut" => $this->getStatut(),
            "date" => $this->getDate(),
            "texte" => $this->getTexte());
    }

    /**
     * Set texte
     *
     * @param string $texte
     * @return Note
     */
    public function setTexte($texte) {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte() {
        return $this->texte;
    }

    /**
     * Set avancement
     *
     * @param AssoMaker\SponsoBundle\Entity\Avancement $avancement
     * @return Note
     */
    public function setAvancement(\AssoMaker\SponsoBundle\Entity\Avancement $avancement = null) {
        $this->avancement = $avancement;

        return $this;
    }

    /**
     * Get avancement
     *
     * @return AssoMaker\SponsoBundle\Entity\Avancement
     */
    public function getAvancement() {
        return $this->avancement;
    }

    public function uploadDossierSponso() {

        if (null === $this->dossierSponso) {
            return;
        }

        $this->dossierSponso->move($this->getAvancement()->getDossierDir(), $this->getAvancement()->getDossierFileName());
        $this->dossierSponso = null;
    }

    public function getDossierSponso() {
        return $this->dossierSponso;
    }

    public function setDossierSponso($dossierSponso) {
        $this->dossierSponso = $dossierSponso;

        return $this;
    }

}

