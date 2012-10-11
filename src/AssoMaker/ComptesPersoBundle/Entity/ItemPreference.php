<?php

namespace AssoMaker\ComptesPersoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssoMaker\ComptesPersoBundle\Entity\ItemPreference
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\ComptesPersoBundle\Entity\ItemPreferenceRepository")
 */
class ItemPreference
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
     * @var integer $rating
     *
     * @ORM\Column(name="rating", type="smallint")
     */
    protected  $rating;


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
     * Set rating
     *
     * @param integer $rating
     * @return ItemPreference
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return integer 
     */
    public function getRating()
    {
        return $this->rating;
    }
}