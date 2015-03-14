<?php

namespace AssoMaker\AnimBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhotoArtiste
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\AnimBundle\Entity\PhotoArtisteRepository")
 */
class PhotoArtiste
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
     * @ORM\ManyToOne(targetEntity="AssoMaker\AnimBundle\Entity\Artiste", inversedBy="photos")
     * @ORM\JoinColumn(referencedColumnName="id",onDelete="SET NULL")
     */
    private $artiste;

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
     * Set artiste
     *
     * @param Artiste $artiste
     * @return PhotoArtiste
     */
    public function setArtiste(Artiste $artiste)
    {
        $this->artiste = $artiste;
    
        return $this;
    }

    /**
     * Get artiste
     *
     * @return Artiste
     */
    public function getArtiste()
    {
        return $this->artiste;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return PhotoArtiste
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
