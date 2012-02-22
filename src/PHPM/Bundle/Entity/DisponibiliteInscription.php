<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PHPM\Bundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use PHPM\Bundle\Validator\QuartHeure;
use PHPM\Bundle\Validator\Recoupe;


/**
 * PHPM\Bundle\Entity\DisponibiliteInscription
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\DisponibiliteInscriptionRepository")
 * @DebutAvantFin()
 * 
 */
class DisponibiliteInscription
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
     * @var datetime $debut
     *
     * @ORM\Column(name="debut", type="datetime")
     * @Assert\DateTime()
     * @QuartHeure()
     * 
     */
    private $debut;

    /**
     * @var datetime $fin
     *
     * @ORM\Column(name="fin", type="datetime")
     * @Assert\DateTime()
     * @QuartHeure()
     */
    private $fin;
    

    

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