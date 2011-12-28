<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Creneau
 */
class Creneau
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var datetime $debut
     */
    private $debut;

    /**
     * @var datetime $fin
     */
    private $fin;
    
    /**
    * @ORM\ManyToOne(targetEntity="PlageHoraire", inversedBy="creneaux")
    * @ORM\JoinColumn(name="PlageHoraire_id", referencedColumnName="id")
    */
    protected $plageHoraire;
    
    /**
    * @ORM\ManyToOne(targetEntity="Disponibilite", inversedBy="creneaux")
    * @ORM\JoinColumn(name="Disponiblite_id", referencedColumnName="id")
    */
    protected $disponibilite;


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
     * Set debut
     *
     * @param datetime $debut
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;
    }

    /**
     * Get debut
     *
     * @return datetime 
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param datetime $fin
     */
    public function setFin($fin)
    {
        $this->fin = $fin;
    }

    /**
     * Get fin
     *
     * @return datetime 
     */
    public function getFin()
    {
        return $this->fin;
    }
}