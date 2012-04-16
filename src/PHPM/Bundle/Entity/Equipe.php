<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PHPM\Bundle\Entity\Equipe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\EquipeRepository")
 */
class Equipe
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $nom;
    

    
    /**
     * @ORM\ManyToOne(targetEntity="Orga", inversedBy="equipesResponsable",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE", nullable="true")
     * @Assert\Valid
     */
    protected $responsable;
    
    /**
     * @ORM\OneToMany(targetEntity="GroupeTache", mappedBy="equipe")
     */
    protected $groupesTache;
    

    /**
     * @ORM\OneToMany(targetEntity="Orga", mappedBy="equipe")
     */
    protected $orgas;
    
    /**
     * @var string $couleur
     *
     * @ORM\Column(name="couleur", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $couleur;
    
    /**
     * @ORM\ManyToOne(targetEntity="Confiance", inversedBy="equipes")
     * @ORM\JoinColumn(name="confiance_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
     * @Assert\Valid
     */
    protected $confiance;
    
    
    public function __toString()
    {
        return $this->getNom() ;
    }
    

    public function __construct()
    {
        $this->groupesTache = new \Doctrine\Common\Collections\ArrayCollection();
    $this->orgas = new \Doctrine\Common\Collections\ArrayCollection();
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
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
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
     * Set responsable
     *
     * @param PHPM\Bundle\Entity\Orga $responsable
     */
    public function setResponsable(\PHPM\Bundle\Entity\Orga $responsable)
    {
        $this->responsable = $responsable;
    }

    /**
     * Get responsable
     *
     * @return PHPM\Bundle\Entity\Orga 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Add groupesTache
     *
     * @param PHPM\Bundle\Entity\GroupeTache $groupesTache
     */
    public function addGroupeTache(\PHPM\Bundle\Entity\GroupeTache $groupesTache)
    {
        $this->groupesTache[] = $groupesTache;
    }

    /**
     * Get groupesTache
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGroupesTache()
    {
        return $this->groupesTache;
    }

    /**
     * Add orgas
     *
     * @param PHPM\Bundle\Entity\Orga $orgas
     */
    public function addOrga(\PHPM\Bundle\Entity\Orga $orgas)
    {
        $this->orgas[] = $orgas;
    }

    /**
     * Get orgas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrgas()
    {
        return $this->orgas;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    }

    /**
     * Get couleur
     *
     * @return string 
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set confiance
     *
     * @param PHPM\Bundle\Entity\Confiance $confiance
     */
    public function setConfiance(\PHPM\Bundle\Entity\Confiance $confiance)
    {
        $this->confiance = $confiance;
    }

    /**
     * Get confiance
     *
     * @return PHPM\Bundle\Entity\Confiance 
     */
    public function getConfiance()
    {
        return $this->confiance;
    }
}