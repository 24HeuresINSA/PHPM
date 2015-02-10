<?php

namespace AssoMaker\AnimBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Artist
 *
 * @ORM\Entity
 * @ORM\Table
 */
class Category {

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
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="display_name")
     * @Assert\NotBlank()
     */
    private $displayName;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="icon_name")
     * @Assert\NotBlank()
     */
    private $iconName;





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
     * @return Category
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
     * Set displayName
     *
     * @param string $displayName
     * @return Category
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set iconName
     *
     * @param string $iconName
     * @return Category
     */
    public function setIconName($iconName)
    {
        $this->iconName = $iconName;

        return $this;
    }

    /**
     * Get iconName
     *
     * @return string 
     */
    public function getIconName()
    {
        return $this->iconName;
    }
}
