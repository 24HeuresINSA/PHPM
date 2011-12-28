<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Categorie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\CategorieRepository")
 */
class Categorie
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
    * @ORM\OneToMany(targetEntity="Tache", mappedBy="categorie")
    */
    protected $taches;


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
    public function __construct()
    {
        $this->taches = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function __toString()
    {
	return getNom();
    }
}
