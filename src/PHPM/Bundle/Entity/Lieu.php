<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PHPM\Bundle\Entity\Lieu
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\LieuRepository")
 */
class Lieu
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
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @var integer $latitude
     *
     * @ORM\Column(name="latitude", type="integer")
     * 
     */
    protected $latitude;   
     
    /**
     * @var integer $longitude
     *
     * @ORM\Column(name="longitude", type="integer")
     */
    protected $longitude;
    
    /**
    * @ORM\OneToMany(targetEntity="Tache", mappedBy="lieu")
    */
    protected $taches;
    
    /**
     * @ORM\OneToMany(targetEntity="GroupeTache", mappedBy="lieu")
     */
    protected $groupesTaches;
    
    
    
    
    public function __toString()
    {
        return $this->getNom() ;
    }

    public function __construct()
    {
        $this->taches = new \Doctrine\Common\Collections\ArrayCollection();
    $this->groupesTaches = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set latitude
     *
     * @param integer $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get latitude
     *
     * @return integer 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param integer $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get longitude
     *
     * @return integer 
     */
    public function getLongitude()
    {
        return $this->longitude;
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
     * Add groupesTaches
     *
     * @param PHPM\Bundle\Entity\GroupeTache $groupesTaches
     */
    public function addGroupeTache(\PHPM\Bundle\Entity\GroupeTache $groupesTaches)
    {
        $this->groupesTaches[] = $groupesTaches;
    }

    /**
     * Get groupesTaches
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGroupesTaches()
    {
        return $this->groupesTaches;
    }
}