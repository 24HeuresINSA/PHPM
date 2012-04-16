<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PHPM\Bundle\Entity\Confiance
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\ConfianceRepository")
 */
class Confiance
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
     * @var smallint $valeur
     *
     * @ORM\Column(name="valeur", type="smallint")
     * @Assert\NotBlank()
     */
    protected $valeur;

    /**
     * @var string $couleur
     *
     * @ORM\Column(name="couleur", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $couleur;
    
    
    /**
    * @ORM\OneToMany(targetEntity="Equipe", mappedBy="confiance")
    */
    protected $equipes;
    


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
     * Set valeur
     *
     * @param smallint $valeur
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
    }

    /**
     * Get valeur
     *
     * @return smallint 
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    }

    /**
     * Get couleur
     *
     * @return string 
     */
    public function getCouleur()
    {
        return $this->couleur;
    }
    public function __construct()
    {
        $this->taches = new \Doctrine\Common\Collections\ArrayCollection();
    $this->orgas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add taches
     *
     * @param PHPM\Bundle\Entity\Tache $taches
     */
    public function addTache(\PHPM\Bundle\Entity\Tache $taches)
    {
        $this->taches[] = $taches;
    }

    /**
     * Get taches
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTaches()
    {
        return $this->taches;
    }

    /**
     * Add orgas
     *
     * @param PHPM\Bundle\Entity\Orga $orgas
     */
    public function addOrga(\PHPM\Bundle\Entity\Orga $orgas)
    {
        $this->orgas[] = $orgas;
    }

    /**
     * Get orgas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOrgas()
    {
        return $this->orgas;
    }
    
    public function __toString()
    {
	return $this->getValeur().' - '.$this->getNom() ;
    }
    
    public function toArray()
    {
    	return array("id" => $this->getId(),"nom" => $this->getNom(),"couleur" => $this->getCouleur());
    }
    public function toSimpleArray()
    {
        return array("nom" => $this->getNom(),"couleur" => $this->getCouleur());
    }

    /**
     * Add equipes
     *
     * @param PHPM\Bundle\Entity\Equipe $equipes
     */
    public function addEquipe(\PHPM\Bundle\Entity\Equipe $equipes)
    {
        $this->equipes[] = $equipes;
    }

    /**
     * Get equipes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEquipes()
    {
        return $this->equipes;
    }
}