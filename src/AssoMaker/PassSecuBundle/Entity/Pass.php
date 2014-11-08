<?php

namespace AssoMaker\PassSecuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pass
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PassSecuBundle\Entity\PassRepository")
 */
class Pass {

    public static $validiteChoices = array(
        "Vendredi 18h00",
        "Samedi 8h00", "Samedi 10h00", "Samedi 12h00", "Samedi 16h00", "Samedi 20h00", "Dimanche 00h00",
        "Dimanche 8h00", "Dimanche 10h00", "Dimanche 12h00", "Dimanche 16h00", "Dimanche 20h00", "Lundi 0h00");
    public static $points = array("PS1 Laurent Bonnevay", "PS2 Dermscan", "PS3 Croix Luizet", "PS4 Einstein", "PS5 Double Mixte", "PS6 UCBL");

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="entite", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $entite;

    /**
     * @var string
     *
     * @ORM\Column( type="string", length=255)
     */
    private $validiteDebut;

    /**
     * @var string
     *
     * @ORM\Column( type="string", length=255)
     */
    private $validiteFin;

    /**
     * @var string
     *
     * @ORM\Column(type="text",nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column( type="text",nullable=true)
     */
    private $infosSupplementaires;

    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\AnimBundle\Entity\Animation")
     * @ORM\JoinColumn(referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $animationLiee;

    /**
     * @ORM\Column(type="array")
     */
    protected $personnes;

    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0", "1", "2", "3"})
     * @ORM\Column(type="smallint")
     */
    protected $statut = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $accessCode;

    /**
     * @ORM\Column(type="array")
     */
    protected $pointsPassage = array("1" => false, "2" => false, "3" => false, "4" => false, "5" => false, "6" => false);

    /**
     *
     * @ORM\Column(type="smallint")
     */
    protected $maxPersonnes = 2;

    /**
     *
     * @ORM\Column( type="string", length=255, nullable=true)
     *
     * @Assert\Email(
     * 	   message = "L'email doît être valide.",
     *     checkMX = true
     * )
     */
    protected $emailDemandeur;

    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    protected $telephoneDemandeur;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set entite
     *
     * @param string $entite
     * @return Pass
     */
    public function setEntite($entite) {
        $this->entite = $entite;

        return $this;
    }

    /**
     * Get entite
     *
     * @return string
     */
    public function getEntite() {
        return $this->entite;
    }

    /**
     * Set debut
     *
     * @param string $debut
     * @return Pass
     */
    public function setDebut($debut) {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut
     *
     * @return string
     */
    public function getDebut() {
        return $this->debut;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Pass
     */
    public function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire() {
        return $this->commentaire;
    }

    /**
     * Set validite
     *
     * @param string $validite
     * @return Pass
     */
    public function setValidite($validite) {
        $this->validite = $validite;

        return $this;
    }

    /**
     * Get validite
     *
     * @return string
     */
    public function getValidite() {
        return $this->validite;
    }

    /**
     * Set personnes
     *
     * @param array $personnes
     * @return Pass
     */
    public function setPersonnes($personnes) {
        $this->personnes = $personnes;

        return $this;
    }

    /**
     * Get personnes
     *
     * @return array
     */
    public function getPersonnes() {
        return $this->personnes;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     * @return Pass
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
     * Set responsable
     *
     * @param \AssoMaker\BaseBundle\Entity\Orga $responsable
     * @return Pass
     */
    public function setResponsable(\AssoMaker\BaseBundle\Entity\Orga $responsable = null) {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \AssoMaker\BaseBundle\Entity\Orga
     */
    public function getResponsable() {
        return $this->responsable;
    }

    /**
     * Set animationLiee
     *
     * @param \AssoMaker\AnimBundle\Entity\Animation $animationLiee
     * @return Pass
     */
    public function setAnimationLiee(\AssoMaker\AnimBundle\Entity\Animation $animationLiee = null) {
        $this->animationLiee = $animationLiee;

        return $this;
    }

    /**
     * Get animationLiee
     *
     * @return \AssoMaker\AnimBundle\Entity\Animation
     */
    public function getAnimationLiee() {
        return $this->animationLiee;
    }

    /**
     * Set delivrePar
     *
     * @param \AssoMaker\BaseBundle\Entity\Orga $delivrePar
     * @return Pass
     */
    public function setDelivrePar(\AssoMaker\BaseBundle\Entity\Orga $delivrePar = null) {
        $this->delivrePar = $delivrePar;

        return $this;
    }

    /**
     * Get delivrePar
     *
     * @return \AssoMaker\BaseBundle\Entity\Orga
     */
    public function getDelivrePar() {
        return $this->delivrePar;
    }

    /**
     * Set accessCode
     *
     * @param string $accessCode
     * @return Pass
     */
    public function setAccessCode($accessCode) {
        $this->accessCode = $accessCode;

        return $this;
    }

    /**
     * Get accessCode
     *
     * @return string
     */
    public function getAccessCode() {
        return $this->accessCode;
    }

    /**
     * Set validiteDebut
     *
     * @param string $validiteDebut
     * @return Pass
     */
    public function setValiditeDebut($validiteDebut) {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }

    /**
     * Get validiteDebut
     *
     * @return string
     */
    public function getValiditeDebut() {
        return $this->validiteDebut;
    }

    /**
     * Set validiteFin
     *
     * @param string $validiteFin
     * @return Pass
     */
    public function setValiditeFin($validiteFin) {
        $this->validiteFin = $validiteFin;

        return $this;
    }

    /**
     * Get validiteFin
     *
     * @return string
     */
    public function getValiditeFin() {
        return $this->validiteFin;
    }

    /**
     * Set pointsPassage
     *
     * @param array $pointsPassage
     * @return Pass
     */
    public function setPointsPassage($pointsPassage) {
        $this->pointsPassage = $pointsPassage;

        return $this;
    }

    /**
     * Get pointsPassage
     *
     * @return array
     */
    public function getPointsPassage() {
        return $this->pointsPassage;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Pass
     */
    public function setMessage($message) {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Set infosSupplementaires
     *
     * @param string $infosSupplementaires
     * @return Pass
     */
    public function setInfosSupplementaires($infosSupplementaires) {
        $this->infosSupplementaires = $infosSupplementaires;

        return $this;
    }

    /**
     * Get infosSupplementaires
     *
     * @return string
     */
    public function getInfosSupplementaires() {
        return $this->infosSupplementaires;
    }

    /**
     * Set maxPersonnes
     *
     * @param integer $maxPersonnes
     * @return Pass
     */
    public function setMaxPersonnes($maxPersonnes) {
        $this->maxPersonnes = $maxPersonnes;

        return $this;
    }

    /**
     * Get maxPersonnes
     *
     * @return integer
     */
    public function getMaxPersonnes() {
        return $this->maxPersonnes;
    }

    /**
     * Set emailDemandeur
     *
     * @param string $emailDemandeur
     * @return Pass
     */
    public function setEmailDemandeur($emailDemandeur) {
        $this->emailDemandeur = $emailDemandeur;

        return $this;
    }

    /**
     * Get emailDemandeur
     *
     * @return string
     */
    public function getEmailDemandeur() {
        return $this->emailDemandeur;
    }

    /**
     * Set telephoneDemandeur
     *
     * @param string $telephoneDemandeur
     * @return Pass
     */
    public function setTelephoneDemandeur($telephoneDemandeur) {
        $this->telephoneDemandeur = $telephoneDemandeur;

        return $this;
    }

    /**
     * Get telephoneDemandeur
     *
     * @return string
     */
    public function getTelephoneDemandeur() {
        return $this->telephoneDemandeur;
    }

}

