<?php

namespace AssoMaker\SponsoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssoMaker\SponsoBundle\Entity\Entreprise
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\SponsoBundle\Entity\EntrepriseRepository")
 */
class Entreprise
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
     * @return Entreprise
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
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="entreprise")
     */
    protected $contacts;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add contacts
     *
     * @param AssoMaker\SponsoBundle\Entity\Contact $contacts
     * @return Entreprise
     */
    public function addContact(\AssoMaker\SponsoBundle\Entity\Contact $contacts)
    {
        $this->contacts[] = $contacts;
    
        return $this;
    }

    /**
     * Remove contacts
     *
     * @param AssoMaker\SponsoBundle\Entity\Contact $contacts
     */
    public function removeContact(\AssoMaker\SponsoBundle\Entity\Contact $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContacts()
    {
        return $this->contacts;
    }
    
    public function __toString(){
        return $this->getNom();
    }
}