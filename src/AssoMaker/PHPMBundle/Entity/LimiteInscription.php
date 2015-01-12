<?php

namespace AssoMaker\PHPMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LimiteInscription
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class LimiteInscription
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
     * @var integer
     *
     * @ORM\Column(name="max", type="integer")
     */
    private $max;

    /**
     * @var integer
     *
     * @ORM\Column(name="disponibilite_inscription_id", type="integer")
     */
    private $disponibiliteInscriptionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="confiance_id", type="integer")
     */
    private $confianceId;


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
     * Set max
     *
     * @param integer $max
     * @return LimiteInscription
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return integer 
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set disponibiliteInscriptionId
     *
     * @param integer $disponibiliteInscriptionId
     * @return LimiteInscription
     */
    public function setDisponibiliteInscriptionId($disponibiliteInscriptionId)
    {
        $this->disponibiliteInscriptionId = $disponibiliteInscriptionId;

        return $this;
    }

    /**
     * Get disponibiliteInscriptionId
     *
     * @return integer 
     */
    public function getDisponibiliteInscriptionId()
    {
        return $this->disponibiliteInscriptionId;
    }

    /**
     * Set confianceId
     *
     * @param integer $confianceId
     * @return LimiteInscription
     */
    public function setConfianceId($confianceId)
    {
        $this->confianceId = $confianceId;

        return $this;
    }

    /**
     * Get confianceId
     *
     * @return integer 
     */
    public function getConfianceId()
    {
        return $this->confianceId;
    }
}
