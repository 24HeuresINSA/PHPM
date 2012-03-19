<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PHPM\Bundle\Entity\GroupeTache
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\GroupeTacheRepository")
 */
class GroupeTache
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
    * @ORM\OneToMany(targetEntity="Tache", mappedBy="groupeTache")
    */
    protected $taches;
    
    /**
     * @ORM\ManyToOne(targetEntity="Orga", inversedBy="groupesTachesResponsable")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
     * @Assert\Valid
     */
    protected $responsable;
    

    
    /**
     * @ORM\ManyToOne(targetEntity="Equipe", inversedBy="groupesTache")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
     * @Assert\Valid
     */
    protected $equipe;

    
    /**
     * @ORM\ManyToOne(targetEntity="Lieu", inversedBy="groupestache")
     * @ORM\JoinColumn(name="lieu_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
     * @Assert\Valid
     */
    protected $lieu;
    
    /**
     * @var integer $animLiee
     *
     * @ORM\Column(name="animLiee", type="integer")
     */
    protected $animLiee;

    
    public function __toString()
    {
        return $this->getId()."- ".$this->getNom();
    }
    public function __construct()
    {
        $this->taches = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add taches
     *
     * @param PHPM\Bundle\Entity\Tache $taches
     */
    public function addTache(\PHPM\Bundle\Entity\Tache $taches)
    {
        $this->taches[] = $taches;
    }

    /**
     * Get taches
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTaches()
    {
        return $this->taches;
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
     * Set equipe
     *
     * @param PHPM\Bundle\Entity\Equipe $equipe
     */
    public function setEquipe(\PHPM\Bundle\Entity\Equipe $equipe)
    {
        $this->equipe = $equipe;
    }

    /**
     * Get equipe
     *
     * @return PHPM\Bundle\Entity\Equipe 
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * Set lieu
     *
     * @param PHPM\Bundle\Entity\Lieu $lieu
     */
    public function setLieu(\PHPM\Bundle\Entity\Lieu $lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * Get lieu
     *
     * @return PHPM\Bundle\Entity\Lieu 
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set animLiee
     *
     * @param integer $animLiee
     */
    public function setAnimLiee($animLiee)
    {
        $this->animLiee = $animLiee;
    }

    /**
     * Get animLiee
     *
     * @return integer 
     */
    public function getAnimLiee()
    {
        return $this->animLiee;
    }
}