<?php

namespace AssoMaker\PHPMBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Sonata\IntlBundle\Templating\Helper;

/**
 * AssoMaker\PHPMBundle\Entity\Materiel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\MaterielRepository")
 *
 */
class Materiel
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
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $nom;

    /**
     * @var string $categorie
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $categorie;

    /**
     * @var smallint $type
     * @Assert\Choice(choices = {"0", "1"})
     * @ORM\Column(name="type", type="smallint")
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="BesoinMateriel", mappedBy="materiel")
     */
    protected $besoinsMateriel;

    public function __construct()
    {
        $this->besoinsMateriel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNom();
    }

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
     * Set categorie
     *
     * @param string $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set type
     *
     * @param smallint $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return smallint
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add besoinsMateriel
     *
     * @param AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel
     */
    public function addBesoinMateriel(
            \AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel)
    {
        $this->besoinsMateriel[] = $besoinsMateriel;
    }

    /**
     * Get besoinsMateriel
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBesoinsMateriel()
    {
        return $this->besoinsMateriel;
    }

    /**
     * Add besoinsMateriel
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel
     * @return Materiel
     */
    public function addBesoinsMateriel(\AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel)
    {
        $this->besoinsMateriel[] = $besoinsMateriel;
    
        return $this;
    }

    /**
     * Remove besoinsMateriel
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel
     */
    public function removeBesoinsMateriel(\AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel)
    {
        $this->besoinsMateriel->removeElement($besoinsMateriel);
    }
}