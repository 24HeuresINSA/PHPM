<?php

namespace AssoMaker\AnimBundle\Entity;

use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Artist
 *
 * @ORM\Entity
 * @ORM\Table
 */
class Artist {

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string $stage
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    protected $stage;


    /**
     * @ORM\Column(type="array")
     *
     */
    protected $horaires;// = array(array('jour' => 'Samedi', 'debut' => '10h00', 'fin' => '18h00'), array('jour' => 'Dimanche', 'debut' => '10h00', 'fin' => '18h00'));


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="main_picture_url")
     */
    protected $mainPictureUrl;

//    TODO pictures and category



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
     * Set name
     *
     * @param string $name
     * @return Artist
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set stage
     *
     * @param string $stage
     * @return Artist
     */
    public function setStage($stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * Get stage
     *
     * @return string 
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * Set horaires
     *
     * @param array $horaires
     * @return Artist
     */
    public function setHoraires($horaires)
    {
        $this->horaires = $horaires;

        return $this;
    }

    /**
     * Get horaires
     *
     * @return array 
     */
    public function getHoraires()
    {
        return $this->horaires;
    }

    /**
     * Set mainPictureUrl
     *
     * @param string $mainPictureUrl
     * @return Artist
     */
    public function setMainPictureUrl($mainPictureUrl)
    {
        $this->mainPictureUrl = $mainPictureUrl;

        return $this;
    }

    /**
     * Get mainPictureUrl
     *
     * @return string 
     */
    public function getMainPictureUrl()
    {
        return $this->mainPictureUrl;
    }
}
