<?php

namespace AssoMaker\PHPMBundle\Entity;

use AssoMaker\BaseBundle\Entity\Confiance;
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
     * @var DisponibiliteInscription
     *
     * @ORM\ManyToOne(targetEntity="DisponibiliteInscription", inversedBy="limitesInscriptions")
     * @ORM\JoinColumn(name="disponibilite_inscription_id", referencedColumnName="id",onDelete="CASCADE")
     */
    private $disponibiliteInscription;

    /**
     * @var \AssoMaker\BaseBundle\Entity\Confiance
     *
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Confiance")
     * @ORM\JoinColumn(name="confiance_id", referencedColumnName="id",onDelete="CASCADE")
     */
    private $confiance;


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
     * @return DisponibiliteInscription
     */
    public function getDisponibiliteInscription()
    {
        return $this->disponibiliteInscription;
    }

    /**
     * @param DisponibiliteInscription $disponibiliteInscription
     */
    public function setDisponibiliteInscription($disponibiliteInscription)
    {
        $this->disponibiliteInscription = $disponibiliteInscription;
    }

    /**
     * @return Confiance
     */
    public function getConfiance()
    {
        return $this->confiance;
    }

    /**
     * @param Confiance $confiance
     */
    public function setConfiance($confiance)
    {
        $this->confiance = $confiance;
    }
}
