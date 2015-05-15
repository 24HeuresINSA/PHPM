<?php

namespace AssoMaker\PHPMBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * MobileClient
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class MobileClient
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
     * @var text $gcm_regid
     *
     * @ORM\Column(name="gcm_regid", type="text", nullable=true, unique=true)
     */
    protected $gcm_regid;
    
    
    public function getId() {
        return $this->id;
    }
    
    public function setGcm_regid($gcm_regid) {
        $this->gcm_regid = $gcm_regid;
        return $this;
    }

    
    public function getGcm_regid() {
        return $this->gcm_regid;
    }    
    
}
