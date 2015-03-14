<?php

namespace AssoMaker\AnimBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhotoAnimation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\AnimBundle\Entity\PhotoAnimationRepository")
 */
class PhotoAnimation
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
     * @ORM\ManyToOne(targetEntity="AssoMaker\AnimBundle\Entity\Animation", inversedBy="photosMobile")
     * @ORM\JoinColumn(referencedColumnName="id",onDelete="SET NULL")
     */
    private $animation;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

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
     * Set animation
     *
     * @param Animation $animation
     * @return PhotoAnimation
     */
    public function setAnimation(Animation $animation)
    {
        $this->animation = $animation;
    
        return $this;
    }

    /**
     * Get animation
     *
     * @return Animation
     */
    public function getAnimation()
    {
        return $this->animation;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return PhotoAnimation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
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
