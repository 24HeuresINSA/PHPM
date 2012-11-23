<?php

namespace AssoMaker\SponsoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssoMaker\SponsoBundle\Entity\Contact
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\SponsoBundle\Entity\ContactRepository")
 */
class Contact
{
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=10, nullable=true)
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
     * @ORM\Column(name="poste", type="string", length=255)
     */
    private $poste;
    
    /**
     * @ORM\ManyToOne(targetEntity="Entreprise",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="entreprise_id", referencedColumnName="id")
     * @Assert\Valid
     */
    private $entreprise;
    
    /**
     * @var string $adresse
     *
     * @ORM\Column(name="adresse", type="string", length=1024, nullable=true)
     */
    private $adresse;
    
    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="contact")
     */
    protected $notes;


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
     * @return Contact
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
     * Set telephone
     *
     * @param string $telephone
     * @return Contact
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    
        return $this;
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
     * Set email
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set poste
     *
     * @param string $poste
     * @return Contact
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;
    
        return $this;
    }

    /**
     * Get poste
     *
     * @return string 
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * Set entreprise
     *
     * @param AssoMaker\SponsoBundle\Entity\Entreprise $entreprise
     * @return Contact
     */
    public function setEntreprise(\AssoMaker\SponsoBundle\Entity\Entreprise $entreprise = null)
    {
        $this->entreprise = $entreprise;
    
        return $this;
    }

    /**
     * Get entreprise
     *
     * @return AssoMaker\SponsoBundle\Entity\Entreprise 
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }
    
    public function __toString(){
        return $this->getNom();
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Contact
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add notes
     *
     * @param AssoMaker\SponsoBundle\Entity\Note $notes
     * @return Contact
     */
    public function addNote(\AssoMaker\SponsoBundle\Entity\Note $notes)
    {
        $this->notes[] = $notes;
    
        return $this;
    }

    /**
     * Remove notes
     *
     * @param AssoMaker\SponsoBundle\Entity\Note $notes
     */
    public function removeNote(\AssoMaker\SponsoBundle\Entity\Note $notes)
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getNotes()
    {
        return $this->notes;
    }
}