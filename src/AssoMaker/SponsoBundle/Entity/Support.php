<?php

namespace AssoMaker\SponsoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssoMaker\SponsoBundle\Entity\Support
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\SponsoBundle\Entity\SupportRepository")
 */
class Support
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
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer $quantiteDisponible
     *
     * @ORM\Column(name="quantiteDisponible", type="smallint")
     */
    private $quantiteDisponible;


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
     * Set nom
     *
     * @param string $nom
     * @return Support
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

    /**
     * Set description
     *
     * @param string $description
     * @return Support
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set quantiteDisponible
     *
     * @param integer $quantiteDisponible
     * @return Support
     */
    public function setQuantiteDisponible($quantiteDisponible)
    {
        $this->quantiteDisponible = $quantiteDisponible;
    
        return $this;
    }

    /**
     * Get quantiteDisponible
     *
     * @return integer 
     */
    public function getQuantiteDisponible()
    {
        return $this->quantiteDisponible;
    }
}