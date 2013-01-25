<?php

namespace AssoMaker\AnimBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Animation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\AnimBundle\Entity\AnimationRepository")
 */
class Animation


{
    
    public static $extTypes = array(
            0=>'Aucun',
            1=>'Asso INSA',
            2=>'Association',
            3=>'Entreprise',
            4=>'Particulier');
    
    
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
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Equipe", inversedBy="animations")
     * @ORM\JoinColumn( referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $equipe;
    
    
    
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
     *     pattern="/^0[67][0-9]{8}$/",
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
    protected $extBoisson=0;
    
    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $extBouffe=0;
    
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
    protected $horaires = array(array('jour'=>'Samedi','debut'=>'10h00','fin'=>'18h00'),array('jour'=>'Dimanche','debut'=>'10h00','fin'=>'18h00'));
    
    
    
    

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
     * Set nom
     *
     * @param string $nom
     * @return Animation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     * @return Animation
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return integer 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set extNom
     *
     * @param string $extNom
     * @return Animation
     */
    public function setExtNom($extNom)
    {
        $this->extNom = $extNom;
    
        return $this;
    }

    /**
     * Get extNom
     *
     * @return string 
     */
    public function getExtNom()
    {
        return $this->extNom;
    }

    /**
     * Set extType
     *
     * @param integer $extType
     * @return Animation
     */
    public function setExtType($extType)
    {
        $this->extType = $extType;
    
        return $this;
    }

    /**
     * Get extType
     *
     * @return integer 
     */
    public function getExtType()
    {
        return $this->extType;
    }

    /**
     * Set extTelephone
     *
     * @param string $extTelephone
     * @return Animation
     */
    public function setExtTelephone($extTelephone)
    {
        $this->extTelephone = $extTelephone;
    
        return $this;
    }

    /**
     * Get extTelephone
     *
     * @return string 
     */
    public function getExtTelephone()
    {
        return $this->extTelephone;
    }

    /**
     * Set extEmail
     *
     * @param string $extEmail
     * @return Animation
     */
    public function setExtEmail($extEmail)
    {
        $this->extEmail = $extEmail;
    
        return $this;
    }

    /**
     * Get extEmail
     *
     * @return string 
     */
    public function getExtEmail()
    {
        return $this->extEmail;
    }

    /**
     * Set extCommentaire
     *
     * @param string $extCommentaire
     * @return Animation
     */
    public function setExtCommentaire($extCommentaire)
    {
        $this->extCommentaire = $extCommentaire;
    
        return $this;
    }

    /**
     * Get extCommentaire
     *
     * @return string 
     */
    public function getExtCommentaire()
    {
        return $this->extCommentaire;
    }

    /**
     * Set extPresent
     *
     * @param boolean $extPresent
     * @return Animation
     */
    public function setExtPresent($extPresent)
    {
        $this->extPresent = $extPresent;
    
        return $this;
    }

    /**
     * Get extPresent
     *
     * @return boolean 
     */
    public function getExtPresent()
    {
        return $this->extPresent;
    }

    /**
     * Set extBoisson
     *
     * @param integer $extBoisson
     * @return Animation
     */
    public function setExtBoisson($extBoisson)
    {
        $this->extBoisson = $extBoisson;
    
        return $this;
    }

    /**
     * Get extBoisson
     *
     * @return integer 
     */
    public function getExtBoisson()
    {
        return $this->extBoisson;
    }

    /**
     * Set extBouffe
     *
     * @param integer $extBouffe
     * @return Animation
     */
    public function setExtBouffe($extBouffe)
    {
        $this->extBouffe = $extBouffe;
    
        return $this;
    }

    /**
     * Get extBouffe
     *
     * @return integer 
     */
    public function getExtBouffe()
    {
        return $this->extBouffe;
    }

    /**
     * Set extCatering
     *
     * @param boolean $extCatering
     * @return Animation
     */
    public function setExtCatering($extCatering)
    {
        $this->extCatering = $extCatering;
    
        return $this;
    }

    /**
     * Get extCatering
     *
     * @return boolean 
     */
    public function getExtCatering()
    {
        return $this->extCatering;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Animation
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    
        return $this;
    }

    /**
     * Get lieu
     *
     * @return string 
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set locX
     *
     * @param float $locX
     * @return Animation
     */
    public function setLocX($locX)
    {
        $this->locX = $locX;
    
        return $this;
    }

    /**
     * Get locX
     *
     * @return float 
     */
    public function getLocX()
    {
        return $this->locX;
    }

    /**
     * Set locY
     *
     * @param float $locY
     * @return Animation
     */
    public function setLocY($locY)
    {
        $this->locY = $locY;
    
        return $this;
    }

    /**
     * Get locY
     *
     * @return float 
     */
    public function getLocY()
    {
        return $this->locY;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Animation
     */
    public function setPublic($public)
    {
        $this->public = $public;
    
        return $this;
    }

    /**
     * Get public
     *
     * @return boolean 
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Animation
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set animPhare
     *
     * @param boolean $animPhare
     * @return Animation
     */
    public function setAnimPhare($animPhare)
    {
        $this->animPhare = $animPhare;
    
        return $this;
    }

    /**
     * Get animPhare
     *
     * @return boolean 
     */
    public function getAnimPhare()
    {
        return $this->animPhare;
    }

    /**
     * Set animGosses
     *
     * @param boolean $animGosses
     * @return Animation
     */
    public function setAnimGosses($animGosses)
    {
        $this->animGosses = $animGosses;
    
        return $this;
    }

    /**
     * Get animGosses
     *
     * @return boolean 
     */
    public function getAnimGosses()
    {
        return $this->animGosses;
    }

    /**
     * Set lieuPublic
     *
     * @param string $lieuPublic
     * @return Animation
     */
    public function setLieuPublic($lieuPublic)
    {
        $this->lieuPublic = $lieuPublic;
    
        return $this;
    }

    /**
     * Get lieuPublic
     *
     * @return string 
     */
    public function getLieuPublic()
    {
        return $this->lieuPublic;
    }

    /**
     * Set responsable
     *
     * @param \AssoMaker\BaseBundle\Entity\Orga $responsable
     * @return Animation
     */
    public function setResponsable(\AssoMaker\BaseBundle\Entity\Orga $responsable = null)
    {
        $this->responsable = $responsable;
    
        return $this;
    }

    /**
     * Get responsable
     *
     * @return \AssoMaker\BaseBundle\Entity\Orga 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set orgaManif
     *
     * @param \AssoMaker\BaseBundle\Entity\Orga $orgaManif
     * @return Animation
     */
    public function setOrgaManif(\AssoMaker\BaseBundle\Entity\Orga $orgaManif = null)
    {
        $this->orgaManif = $orgaManif;
    
        return $this;
    }

    /**
     * Get orgaManif
     *
     * @return \AssoMaker\BaseBundle\Entity\Orga 
     */
    public function getOrgaManif()
    {
        return $this->orgaManif;
    }

    /**
     * Set equipe
     *
     * @param \AssoMaker\BaseBundle\Entity\Equipe $equipe
     * @return Animation
     */
    public function setEquipe(\AssoMaker\BaseBundle\Entity\Equipe $equipe = null)
    {
        $this->equipe = $equipe;
    
        return $this;
    }

    /**
     * Get equipe
     *
     * @return \AssoMaker\BaseBundle\Entity\Equipe 
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * Set competences
     *
     * @param array $competences
     * @return Animation
     */
    public function setCompetences($competences)
    {
        $this->competences = $competences;
    
        return $this;
    }

    /**
     * Get competences
     *
     * @return array 
     */
    public function getCompetences()
    {
        return $this->competences;
    }

    /**
     * Set horaires
     *
     * @param array $horaires
     * @return Animation
     */
    public function setHoraires($horaires)
    {
        $this->horaires = $horaires;
    
        return $this;
    }

    /**
     * Get horaires
     *
     *  
     */
    public function getHoraires()
    {
        return json_encode($this->horaires,true);
    }
}