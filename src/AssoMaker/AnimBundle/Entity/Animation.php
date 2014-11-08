<?php

namespace AssoMaker\AnimBundle\Entity;

use Symfony\Component\Validator\Constraints\DateTime;
use AssoMaker\BaseBundle\Entity\Orga;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Animation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\AnimBundle\Entity\AnimationRepository")
 */
class Animation {

    public static $extTypes = array('Aucun', 'Asso INSA', 'Association', 'Entreprise', 'Particulier');
    public static $animTypes = array('Divertissement', 'Spectacle', 'Initiation', 'Démo sportive', 'Match de Gala', 'Tournoi', 'Course', 'Concert', 'Prévention', 'Vente', 'Autre');
    public static $lieuxDepotLog = array('Dépôt AIP', 'Dépôt Humanités', 'QG Orga', 'QG Courses', 'QG Culture', 'Local 24h', 'Autre');

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Orga", inversedBy="animsResponsable")
     * @ORM\JoinColumn(referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $responsable;

    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Orga")
     * @ORM\JoinColumn(referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $orgaManif;

    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0", "1", "2", "-1"})
     * @ORM\Column(type="smallint")
     */
    protected $statut;

    /**
     *
     * @ORM\Column(type="boolean")
     */
    protected $validLog = false;

    /**
     *
     * @ORM\Column(type="boolean")
     */
    protected $validSecu = false;

    /**
     *
     * @ORM\Column(type="boolean")
     */
    protected $validHumain = false;

    /**
     * @var smallint $statut
     * @ORM\Column(type="smallint",nullable=true)
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Equipe", inversedBy="animations")
     * @ORM\JoinColumn( referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $equipe;

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\PassSecuBundle\Entity\Pass", mappedBy="animationLiee")
     */
    protected $passAssocies;

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\PHPMBundle\Entity\GroupeTache", mappedBy="animLiee")
     */
    protected $groupesTacheLies;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $extNom;

    /**
     * @Assert\Choice(choices = {"0", "1", "2", "3", "4"})
     * @ORM\Column(type="smallint")
     */
    protected $extType = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex(
     *     pattern="/^0[0-9]{9}$/",
     *     message="Veuillez renseigner un numéro de portable valide."
     * )
     */
    protected $extTelephone;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Email(
     * 	   message = "L'email du prestataire doît être valide.",
     *     checkMX = true
     * )
     */
    protected $extEmail;

    /**
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $extCommentaire;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $extPresent;

    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $extBoisson = 0;

    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $extBouffe = 0;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $extCatering;

    /**
     * @var string $lieu
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $lieu;

    /**
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $locX = '45.783562';

    /**
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $locY = '4.87623899999994';

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $public;

    /**
     * @var string $description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $animPhare;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $animGosses;

    /**
     * @ORM\Column(type="array")
     *
     */
    protected $horaires = array(array('jour' => 'Samedi', 'debut' => '10h00', 'fin' => '18h00'), array('jour' => 'Dimanche', 'debut' => '10h00', 'fin' => '18h00'));

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $besoinSecu;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $besoinPass;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $elec = false;

    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $elecAmperes = 0;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $elecTri = false;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $interieur = false;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $besoinEau = false;

    /**
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $detailSecu;

    /**
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $besoinSigna;

    /**
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $detailSigna;

    /**
     * @ORM\Column(type="array")
     *
     */
    protected $commentaires = array();

    /**
     * @ORM\Column(type="array")
     *
     */
    protected $materiel = array();

    /**
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $lieuDepotLog;

    /**
     * @Assert\Image(
     *     maxWidth = 400,
     *     maxHeight = 400,
     *     mimeTypes = {"image/jpeg"}
     * )
     */
    protected $pubPicture;

    /**
     *      *
     * @ORM\Column( type="boolean")
     */
    protected $pubPictureSet = false;

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
     * @return Animation
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
     * @return Animation
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
     * Set extNom
     *
     * @param string $extNom
     * @return Animation
     */
    public function setExtNom($extNom) {
        $this->extNom = $extNom;

        return $this;
    }

    /**
     * Get extNom
     *
     * @return string
     */
    public function getExtNom() {
        return $this->extNom;
    }

    /**
     * Set extType
     *
     * @param integer $extType
     * @return Animation
     */
    public function setExtType($extType) {
        $this->extType = $extType;

        return $this;
    }

    /**
     * Get extType
     *
     * @return integer
     */
    public function getExtType() {
        return $this->extType;
    }

    /**
     * Set extTelephone
     *
     * @param string $extTelephone
     * @return Animation
     */
    public function setExtTelephone($extTelephone) {
        $this->extTelephone = $extTelephone;

        return $this;
    }

    /**
     * Get extTelephone
     *
     * @return string
     */
    public function getExtTelephone() {
        return $this->extTelephone;
    }

    /**
     * Set extEmail
     *
     * @param string $extEmail
     * @return Animation
     */
    public function setExtEmail($extEmail) {
        $this->extEmail = $extEmail;

        return $this;
    }

    /**
     * Get extEmail
     *
     * @return string
     */
    public function getExtEmail() {
        return $this->extEmail;
    }

    /**
     * Set extCommentaire
     *
     * @param string $extCommentaire
     * @return Animation
     */
    public function setExtCommentaire($extCommentaire) {
        $this->extCommentaire = $extCommentaire;

        return $this;
    }

    /**
     * Get extCommentaire
     *
     * @return string
     */
    public function getExtCommentaire() {
        return $this->extCommentaire;
    }

    /**
     * Set extPresent
     *
     * @param boolean $extPresent
     * @return Animation
     */
    public function setExtPresent($extPresent) {
        $this->extPresent = $extPresent;

        return $this;
    }

    /**
     * Get extPresent
     *
     * @return boolean
     */
    public function getExtPresent() {
        return $this->extPresent;
    }

    /**
     * Set extBoisson
     *
     * @param integer $extBoisson
     * @return Animation
     */
    public function setExtBoisson($extBoisson) {
        $this->extBoisson = $extBoisson;

        return $this;
    }

    /**
     * Get extBoisson
     *
     * @return integer
     */
    public function getExtBoisson() {
        return $this->extBoisson;
    }

    /**
     * Set extBouffe
     *
     * @param integer $extBouffe
     * @return Animation
     */
    public function setExtBouffe($extBouffe) {
        $this->extBouffe = $extBouffe;

        return $this;
    }

    /**
     * Get extBouffe
     *
     * @return integer
     */
    public function getExtBouffe() {
        return $this->extBouffe;
    }

    /**
     * Set extCatering
     *
     * @param boolean $extCatering
     * @return Animation
     */
    public function setExtCatering($extCatering) {
        $this->extCatering = $extCatering;

        return $this;
    }

    /**
     * Get extCatering
     *
     * @return boolean
     */
    public function getExtCatering() {
        return $this->extCatering;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Animation
     */
    public function setLieu($lieu) {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu() {
        return $this->lieu;
    }

    /**
     * Set locX
     *
     * @param float $locX
     * @return Animation
     */
    public function setLocX($locX) {
        $this->locX = $locX;

        return $this;
    }

    /**
     * Get locX
     *
     * @return float
     */
    public function getLocX() {
        return $this->locX;
    }

    /**
     * Set locY
     *
     * @param float $locY
     * @return Animation
     */
    public function setLocY($locY) {
        $this->locY = $locY;

        return $this;
    }

    /**
     * Get locY
     *
     * @return float
     */
    public function getLocY() {
        return $this->locY;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Animation
     */
    public function setPublic($public) {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic() {
        return $this->public;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Animation
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
     * Set animPhare
     *
     * @param boolean $animPhare
     * @return Animation
     */
    public function setAnimPhare($animPhare) {
        $this->animPhare = $animPhare;

        return $this;
    }

    /**
     * Get animPhare
     *
     * @return boolean
     */
    public function getAnimPhare() {
        return $this->animPhare;
    }

    /**
     * Set animGosses
     *
     * @param boolean $animGosses
     * @return Animation
     */
    public function setAnimGosses($animGosses) {
        $this->animGosses = $animGosses;

        return $this;
    }

    /**
     * Get animGosses
     *
     * @return boolean
     */
    public function getAnimGosses() {
        return $this->animGosses;
    }

    /**
     * Set lieuPublic
     *
     * @param string $lieuPublic
     * @return Animation
     */
    public function setLieuPublic($lieuPublic) {
        $this->lieuPublic = $lieuPublic;

        return $this;
    }

    /**
     * Get lieuPublic
     *
     * @return string
     */
    public function getLieuPublic() {
        return $this->lieuPublic;
    }

    /**
     * Set responsable
     *
     * @param \AssoMaker\BaseBundle\Entity\Orga $responsable
     * @return Animation
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
     * Set orgaManif
     *
     * @param \AssoMaker\BaseBundle\Entity\Orga $orgaManif
     * @return Animation
     */
    public function setOrgaManif(\AssoMaker\BaseBundle\Entity\Orga $orgaManif = null) {
        $this->orgaManif = $orgaManif;

        return $this;
    }

    /**
     * Get orgaManif
     *
     * @return \AssoMaker\BaseBundle\Entity\Orga
     */
    public function getOrgaManif() {
        return $this->orgaManif;
    }

    /**
     * Set equipe
     *
     * @param \AssoMaker\BaseBundle\Entity\Equipe $equipe
     * @return Animation
     */
    public function setEquipe(\AssoMaker\BaseBundle\Entity\Equipe $equipe = null) {
        $this->equipe = $equipe;

        return $this;
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
     * Set competences
     *
     * @param array $competences
     * @return Animation
     */
    public function setCompetences($competences) {
        $this->competences = $competences;

        return $this;
    }

    /**
     * Get competences
     *
     * @return array
     */
    public function getCompetences() {
        return $this->competences;
    }

    /**
     * Set horaires
     *
     * @param array $horaires
     * @return Animation
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
     * Set type
     *
     * @param integer $type
     * @return Animation
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set commentaires
     *
     * @param array $commentaires
     * @return Animation
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

        $this->commentaires[] = array('auteur' => $auteur->__toString(),
            'type' => $type,
            'texte' => $texte,
            'date' => (new \DateTime()));
    }

    /**
     * Set besoinSecu
     *
     * @param boolean $besoinSecu
     * @return Animation
     */
    public function setBesoinSecu($besoinSecu) {
        $this->besoinSecu = $besoinSecu;

        return $this;
    }

    /**
     * Get besoinSecu
     *
     * @return boolean
     */
    public function getBesoinSecu() {
        return $this->besoinSecu;
    }

    /**
     * Set detailSecu
     *
     * @param string $detailSecu
     * @return Animation
     */
    public function setDetailSecu($detailSecu) {
        $this->detailSecu = $detailSecu;

        return $this;
    }

    /**
     * Get detailSecu
     *
     * @return string
     */
    public function getDetailSecu() {
        return $this->detailSecu;
    }

    /**
     * Set besoinSigna
     *
     * @param boolean $besoinSigna
     * @return Animation
     */
    public function setBesoinSigna($besoinSigna) {
        $this->besoinSigna = $besoinSigna;

        return $this;
    }

    /**
     * Get besoinSigna
     *
     * @return boolean
     */
    public function getBesoinSigna() {
        return $this->besoinSigna;
    }

    /**
     * Set detailSigna
     *
     * @param string $detailSigna
     * @return Animation
     */
    public function setDetailSigna($detailSigna) {
        $this->detailSigna = $detailSigna;

        return $this;
    }

    /**
     * Get detailSigna
     *
     * @return string
     */
    public function getDetailSigna() {
        return $this->detailSigna;
    }

    /**
     * Set materiel
     *
     * @param array $materiel
     * @return Animation
     */
    public function setMateriel($materiel) {
        $this->materiel = $materiel;

        return $this;
    }

    /**
     * Get materiel
     *
     * @return array
     */
    public function getMateriel() {
        return $this->materiel;
    }

    /**
     * Set besoinPass
     *
     * @param boolean $besoinPass
     * @return Animation
     */
    public function setBesoinPass($besoinPass) {
        $this->besoinPass = $besoinPass;

        return $this;
    }

    /**
     * Get besoinPass
     *
     * @return boolean
     */
    public function getBesoinPass() {
        return $this->besoinPass;
    }

    /**
     * Set validLog
     *
     * @param boolean $validLog
     * @return Animation
     */
    public function setValidLog($validLog) {
        $this->validLog = $validLog;

        return $this;
    }

    /**
     * Get validLog
     *
     * @return boolean
     */
    public function getValidLog() {
        return $this->validLog;
    }

    /**
     * Set elec
     *
     * @param boolean $elec
     * @return Animation
     */
    public function setElec($elec) {
        $this->elec = $elec;

        return $this;
    }

    /**
     * Get elec
     *
     * @return boolean
     */
    public function getElec() {
        return $this->elec;
    }

    /**
     * Set elecAmperes
     *
     * @param integer $elecAmperes
     * @return Animation
     */
    public function setElecAmperes($elecAmperes) {
        $this->elecAmperes = $elecAmperes;

        return $this;
    }

    /**
     * Get elecAmperes
     *
     * @return integer
     */
    public function getElecAmperes() {
        return $this->elecAmperes;
    }

    /**
     * Set elecTri
     *
     * @param boolean $elecTri
     * @return Animation
     */
    public function setElecTri($elecTri) {
        $this->elecTri = $elecTri;

        return $this;
    }

    /**
     * Get elecTri
     *
     * @return boolean
     */
    public function getElecTri() {
        return $this->elecTri;
    }

    /**
     * Set interieur
     *
     * @param boolean $interieur
     * @return Animation
     */
    public function setInterieur($interieur) {
        $this->interieur = $interieur;

        return $this;
    }

    /**
     * Get interieur
     *
     * @return boolean
     */
    public function getInterieur() {
        return $this->interieur;
    }

    /**
     * Set besoinEau
     *
     * @param boolean $besoinEau
     * @return Animation
     */
    public function setBesoinEau($besoinEau) {
        $this->besoinEau = $besoinEau;

        return $this;
    }

    /**
     * Get besoinEau
     *
     * @return boolean
     */
    public function getBesoinEau() {
        return $this->besoinEau;
    }

    /**
     * Set lieuDepotLog
     *
     * @param integer $lieuDepotLog
     * @return Animation
     */
    public function setLieuDepotLog($lieuDepotLog) {
        $this->lieuDepotLog = $lieuDepotLog;

        return $this;
    }

    /**
     * Get lieuDepotLog
     *
     * @return integer
     */
    public function getLieuDepotLog() {
        return $this->lieuDepotLog;
    }

    /**
     * Set validSecu
     *
     * @param boolean $validSecu
     * @return Animation
     */
    public function setValidSecu($validSecu) {
        $this->validSecu = $validSecu;

        return $this;
    }

    /**
     * Get validSecu
     *
     * @return boolean
     */
    public function getValidSecu() {
        return $this->validSecu;
    }

    public function __toString() {
        return $this->nom;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->passAssocies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add passAssocies
     *
     * @param \AssoMaker\PassSecuBundle\Entity\Pass $passAssocies
     * @return Animation
     */
    public function addPassAssocie(\AssoMaker\PassSecuBundle\Entity\Pass $passAssocies) {
        $this->passAssocies[] = $passAssocies;

        return $this;
    }

    /**
     * Remove passAssocies
     *
     * @param \AssoMaker\PassSecuBundle\Entity\Pass $passAssocies
     */
    public function removePassAssocie(\AssoMaker\PassSecuBundle\Entity\Pass $passAssocies) {
        $this->passAssocies->removeElement($passAssocies);
    }

    /**
     * Get passAssocies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPassAssocies() {
        return $this->passAssocies;
    }

    /**
     * Add groupesTacheLies
     *
     * @param \AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheLies
     * @return Animation
     */
    public function addGroupesTacheLie(\AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheLies) {
        $this->groupesTacheLies[] = $groupesTacheLies;

        return $this;
    }

    /**
     * Remove groupesTacheLies
     *
     * @param \AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheLies
     */
    public function removeGroupesTacheLie(\AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTacheLies) {
        $this->groupesTacheLies->removeElement($groupesTacheLies);
    }

    /**
     * Get groupesTacheLies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupesTacheLies() {
        return $this->groupesTacheLies;
    }

    public function uploadPubPicture() {

        if (null === $this->pubPicture) {
            return;
        }


        $this->pubPicture->move(__DIR__ . '/../../../../web/up/animPictures', $this->getId() . '.jpg');
        $this->pubPicture = null;
        $this->setPubPictureSet(true);
    }

    /**
     * Set pubPictureSet
     *
     * @param boolean $pubPictureSet
     * @return Animation
     */
    public function setPubPictureSet($pubPictureSet) {
        $this->pubPictureSet = $pubPictureSet;

        return $this;
    }

    /**
     * Get pubPictureSet
     *
     * @return boolean
     */
    public function getPubPictureSet() {
        return $this->pubPictureSet;
    }

    public function getPubPicture() {
        return $this->pubPicture;
    }

    public function setPubPicture($pubPicture) {
        $this->pubPicture = $pubPicture;

        return $this;
    }

    /**
     * Set validHumain
     *
     * @param boolean $validHumain
     * @return Animation
     */
    public function setValidHumain($validHumain) {
        $this->validHumain = $validHumain;

        return $this;
    }

    /**
     * Get validHumain
     *
     * @return boolean
     */
    public function getValidHumain() {
        return $this->validHumain;
    }

}

