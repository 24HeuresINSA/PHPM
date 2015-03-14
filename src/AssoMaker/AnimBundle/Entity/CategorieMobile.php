<?php

namespace AssoMaker\AnimBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * CategorieMobile
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\AnimBundle\Entity\CategorieMobileRepository")
 */
class CategorieMobile
{
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
     * @ORM\Column(name="identifiant", type="string", length=255)
     */
    private $identifiant;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="icone", type="string", length=255)
     */
    private $icone;

    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\AnimBundle\Entity\Animation", mappedBy="categorieMobile")
     */
    private $animations;


    public function __construct()
    {
        $this->animations = new ArrayCollection();
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
     * Set identifiant
     *
     * @param string $identifiant
     * @return CategorieMobile
     */
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;
    
        return $this;
    }

    /**
     * Get identifiant
     *
     * @return string 
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return CategorieMobile
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
     * Set icone
     *
     * @param string $icone
     * @return CategorieMobile
     */
    public function setIcone($icone)
    {
        $this->icone = $icone;
    
        return $this;
    }

    /**
     * Get icone
     *
     * @return string 
     */
    public function getIcone()
    {
        return $this->icone;
    }

    /**
     * Get animations
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAnimations() {
        return $this->animations;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
