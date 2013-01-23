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
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;
    
    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Orga", inversedBy="animsResponsable")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $responsable;
    
    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Orga")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $orgaManif;
    
    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0", "1", "2", "-1"})
     * @ORM\Column(name="statut", type="smallint")
     */
    protected $statut;
    
    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Equipe", inversedBy="animations")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $equipe;
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="extNom", type="string", length=255, nullable=true)
     */
    private $extNom;    
    
    
    /**
     * @Assert\Choice(choices = {"0", "1", "2", "3", "4"})
     * @ORM\Column(name="extType", type="smallint")
     */
    protected $extType = 0;
    
    
    /**
     * @ORM\Column(name="extTelephone", type="string", length=255, nullable=true)
     * @Assert\Regex(
     *     pattern="/^0[67][0-9]{8}$/",
     *     message="Veuillez renseigner un numéro de portable valide."
     * )
     */
    protected $extTelephone;
    
    /**
     *
     * @ORM\Column(name="extEmail", type="string", length=255, nullable=true)
     *
     * @Assert\Email(
     * 	   message = "L'email du prestataire doît être valide.",
     *     checkMX = true
     * )
     */
    protected $extEmail;
    
    /**
     *
     * @ORM\Column(name="extCommentaire", type="text", nullable=true)
     */
    protected $extCommentaire;
    
    /**
     *
     * @ORM\Column(name="extPresent", type="boolean", nullable=true)
     */
    protected $extPresent;
    
    /**
     *
     * @ORM\Column(name="extTboisson", type="integer", nullable=true)
     */
    protected $extTboisson;
    
    /**
     *
     * @ORM\Column(name="extTbouffe", type="integer", nullable=true)
     */
    protected $extTbouffe;
    
    /**
     *
     * @ORM\Column(name="extCatering", type="boolean", nullable=true)
     */
    protected $extCatering;
    
    /**
     * @var string $lieu
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=true)
     */
    protected $lieu;
    
    /**
     *
     * @ORM\Column(name="locX", type="float", nullable=true)
     */
    protected $locX;
    
    /**
     *
     * @ORM\Column(name="locY", type="float", nullable=true)
     */
    protected $locY;
    
    /**
     *
     * @ORM\Column(name="public", type="boolean", nullable=true)
     */
    protected $public;
    
    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=4096, nullable=true)
     */
    private $description;
    
    /**
     *
     * @ORM\Column(name="animPhare", type="boolean", nullable=true)
     */
    protected $animPhare;
    
    /**
     *
     * @ORM\Column(name="animGosses", type="boolean", nullable=true)
     */
    protected $animGosses;
    
    /**
     * @var string $lieuPublic
     *
     * @ORM\Column(name="lieuPublic", type="string", length=255, nullable=true)
     */
    protected $lieuPublic;
    
    
    
    
    



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
     * @param \bool $extPresent
     * @return Animation
     */
    public function setExtPresent(\bool $extPresent)
    {
        $this->extPresent = $extPresent;
    
        return $this;
    }

    /**
     * Get extPresent
     *
     * @return \bool 
     */
    public function getExtPresent()
    {
        return $this->extPresent;
    }

    /**
     * Set extTboisson
     *
     * @param \int $extTboisson
     * @return Animation
     */
    public function setExtTboisson(\int $extTboisson)
    {
        $this->extTboisson = $extTboisson;
    
        return $this;
    }

    /**
     * Get extTboisson
     *
     * @return \int 
     */
    public function getExtTboisson()
    {
        return $this->extTboisson;
    }

    /**
     * Set extTbouffe
     *
     * @param \int $extTbouffe
     * @return Animation
     */
    public function setExtTbouffe(\int $extTbouffe)
    {
        $this->extTbouffe = $extTbouffe;
    
        return $this;
    }

    /**
     * Get extTbouffe
     *
     * @return \int 
     */
    public function getExtTbouffe()
    {
        return $this->extTbouffe;
    }

    /**
     * Set extCatering
     *
     * @param \bool $extCatering
     * @return Animation
     */
    public function setExtCatering(\bool $extCatering)
    {
        $this->extCatering = $extCatering;
    
        return $this;
    }

    /**
     * Get extCatering
     *
     * @return \bool 
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
     * @param \bool $public
     * @return Animation
     */
    public function setPublic(\bool $public)
    {
        $this->public = $public;
    
        return $this;
    }

    /**
     * Get public
     *
     * @return \bool 
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
     * @param \bool $animPhare
     * @return Animation
     */
    public function setAnimPhare(\bool $animPhare)
    {
        $this->animPhare = $animPhare;
    
        return $this;
    }

    /**
     * Get animPhare
     *
     * @return \bool 
     */
    public function getAnimPhare()
    {
        return $this->animPhare;
    }

    /**
     * Set animGosses
     *
     * @param \bool $animGosses
     * @return Animation
     */
    public function setAnimGosses(\bool $animGosses)
    {
        $this->animGosses = $animGosses;
    
        return $this;
    }

    /**
     * Get animGosses
     *
     * @return \bool 
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
}