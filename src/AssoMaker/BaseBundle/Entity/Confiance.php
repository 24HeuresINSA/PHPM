<?php

namespace AssoMaker\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssoMaker\BaseBundle\Entity\Confiance
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\BaseBundle\Entity\ConfianceRepository")
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
     * @var smallint $privileges
     *
     * @ORM\Column(name="privileges", type="smallint")
     * @Assert\NotBlank()
     */
    protected $privileges;

    /**
     * @var string $couleur
     *
     * @ORM\Column(name="couleur", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $couleur;
    
    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    protected $code;
    
    
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
     * @param \AssoMaker\PHPMBundle\Entity\Tache $taches
     */
    public function addTache(\AssoMaker\PHPMBundle\Entity\Tache $taches)
    {
        $this->taches[] = $taches;
    }

    /**
     * Get taches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaches()
    {
        return $this->taches;
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
     * @param \AssoMaker\BaseBundle\Entity\Equipe $equipes
     */
    public function addEquipe(\AssoMaker\BaseBundle\Entity\Equipe $equipes)
    {
        $this->equipes[] = $equipes;
    }

    /**
     * Get equipes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipes()
    {
        return $this->equipes;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set privileges
     *
     * @param smallint $privileges
     */
    public function setPrivileges($privileges)
    {
        $this->privileges = $privileges;
    }

    /**
     * Get privileges
     *
     * @return smallint 
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * Remove equipes
     *
     * @param \AssoMaker\BaseBundle\Entity\Equipe $equipes
     */
    public function removeEquipe(\AssoMaker\BaseBundle\Entity\Equipe $equipes)
    {
        $this->equipes->removeElement($equipes);
    }
}