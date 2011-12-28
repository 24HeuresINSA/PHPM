<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Categorie
 */
class Categorie
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $nom
     */
    private $nom;
    
    /**
    * @ORM\OneToMany(targetEntity="Tache", mappedBy="confiance")
    */
    protected $taches;
    
    /**
    * @ORM\OneToMany(targetEntity="Orga", mappedBy="confiance")
    */
    protected $orgas;
    


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
}