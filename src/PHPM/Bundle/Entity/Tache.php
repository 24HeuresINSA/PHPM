<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Tache
 */
class Tache
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
     * @var text $description
     */
    private $description;

    /**
     * @var string $lieu
     */
    private $lieu;


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
     * Set lieu
     *
     * @param string $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
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
}