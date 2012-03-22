<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Sonata\IntlBundle\Templating\Helper;


/**
 * PHPM\Bundle\Entity\Materiel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\MaterielRepository")
 * 
 */
class Materiel
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
     * @Assert\NotBlank()
     */
    protected $nom;
    
    /**
     * @var string $categorie
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $categorie;
    
    /**
     * @var smallint $type
     * @Assert\Choice(choices = {"0", "1"})
     * @ORM\Column(name="type", type="smallint")
     */
    protected $type;
    
    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
    * @ORM\OneToMany(targetEntity="BesoinMateriel", mappedBy="materiel")
    */
    protected $besoinsMateriel;

    
    
    
    public function __construct()
    {
        $this->besoinsMateriel = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    public function __toString()
    {
        return $this->getNom() ;
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
     * Set categorie
     *
     * @param string $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * Get categorie
     *
     * @return string 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set type
     *
     * @param smallint $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return smallint 
     */
    public function getType()
    {
        return $this->type;
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
     * Add besoinsMateriel
     *
     * @param PHPM\Bundle\Entity\BesoinMateriel $besoinsMateriel
     */
    public function addBesoinMateriel(\PHPM\Bundle\Entity\BesoinMateriel $besoinsMateriel)
    {
        $this->besoinsMateriel[] = $besoinsMateriel;
    }

    /**
     * Get besoinsMateriel
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBesoinsMateriel()
    {
        return $this->besoinsMateriel;
    }
}