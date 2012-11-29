<?php

namespace AssoMaker\SponsoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * AssoMaker\SponsoBundle\Entity\Projet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\SponsoBundle\Entity\ProjetRepository")
 */
class Projet
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
     * @var \DateTime $dateCreation
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0", "1", "2", "-1"})
     * @ORM\Column(name="statut", type="smallint")
     */
    protected $statut;
    
    /**
     * @ORM\ManyToOne(targetEntity="Contact",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     * @Assert\Valid
     */
    protected $contact;
    
    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\BaseBundle\Entity\Equipe", inversedBy="orgas")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $equipe;
    
    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\BaseBundle\Entity\Orga", inversedBy="equipesResponsable",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id",onDelete="SET NULL", nullable=true)
     * @Assert\Valid
     */
    protected $responsable;
      
    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\NotBlank()
     */
    protected $description;
    
    /**
     * @ORM\ManyToMany(targetEntity="Support")
     * @ORM\JoinTable(name="Projet_Support",
     *      joinColumns={@ORM\JoinColumn(name="Projet_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="Support_id", referencedColumnName="id")}
     *      )
     */
    protected $supports;
    
    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="projet")
     */
    protected $notes;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supports = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->statut = 0;
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
     * Set nom
     *
     * @param string $nom
     * @return Projet
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Projet
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     * @return Projet
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
     * Set description
     *
     * @param string $description
     * @return Projet
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
     * Set contact
     *
     * @param AssoMaker\SponsoBundle\Entity\Contact $contact
     * @return Projet
     */
    public function setContact(\AssoMaker\SponsoBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;
    
        return $this;
    }

    /**
     * Get contact
     *
     * @return AssoMaker\SponsoBundle\Entity\Contact 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set equipe
     *
     * @param AssoMaker\BaseBundle\Entity\Equipe $equipe
     * @return Projet
     */
    public function setEquipe(\AssoMaker\BaseBundle\Entity\Equipe $equipe = null)
    {
        $this->equipe = $equipe;
    
        return $this;
    }

    /**
     * Get equipe
     *
     * @return AssoMaker\BaseBundle\Entity\Equipe 
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * Set responsable
     *
     * @param AssoMaker\BaseBundle\Entity\Orga $responsable
     * @return Projet
     */
    public function setResponsable(\AssoMaker\BaseBundle\Entity\Orga $responsable = null)
    {
        $this->responsable = $responsable;
    
        return $this;
    }

    /**
     * Get responsable
     *
     * @return AssoMaker\BaseBundle\Entity\Orga 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Add supports
     *
     * @param AssoMaker\SponsoBundle\Entity\Support $supports
     * @return Projet
     */
    public function addSupport(\AssoMaker\SponsoBundle\Entity\Support $supports)
    {
        $this->supports[] = $supports;
    
        return $this;
    }

    /**
     * Remove supports
     *
     * @param AssoMaker\SponsoBundle\Entity\Support $supports
     */
    public function removeSupport(\AssoMaker\SponsoBundle\Entity\Support $supports)
    {
        $this->supports->removeElement($supports);
    }

    /**
     * Get supports
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSupports()
    {
        return $this->supports;
    }

    /**
     * Add notes
     *
     * @param AssoMaker\SponsoBundle\Entity\Note $notes
     * @return Projet
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