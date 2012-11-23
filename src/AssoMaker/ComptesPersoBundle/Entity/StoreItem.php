<?php

namespace AssoMaker\ComptesPersoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssoMaker\ComptesPersoBundle\Entity\StoreItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\ComptesPersoBundle\Entity\StoreItemRepository")
 */
class StoreItem
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected  $id;

    /**
     * @var string $category
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    protected  $category;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected  $name;


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
     * Set category
     *
     * @param string $category
     * @return StoreItem
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return StoreItem
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
}