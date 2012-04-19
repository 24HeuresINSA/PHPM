<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PHPM\Bundle\Entity\GroupeDI
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\GroupeDIRepository")
 */
class GroupeDI
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $nom;
    
    /**
     * @var smallint $ordre
     * @ORM\Column(name="statut", type="smallint")
     */
    protected $ordre;
    
    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\OneToMany(targetEntity="DisponibiliteInscription", mappedBy="groupe")
     */
    protected $disponibilitesInscription;


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
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
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
     * Set ordre
     *
     * @param smallint $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * Get ordre
     *
     * @return smallint 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }
    public function __construct()
    {
        $this->disponibilitesInscription = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add disponibilitesInscription
     *
     * @param PHPM\Bundle\Entity\DisponibiliteInscription $disponibilitesInscription
     */
    public function addDisponibiliteInscription(\PHPM\Bundle\Entity\DisponibiliteInscription $disponibilitesInscription)
    {
        $this->disponibilitesInscription[] = $disponibilitesInscription;
    }

    /**
     * Get disponibilitesInscription
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDisponibilitesInscription()
    {
        return $this->disponibilitesInscription;
    }
    
    public function __toString()
    {
    	return $this->getNom() ;
    }
}