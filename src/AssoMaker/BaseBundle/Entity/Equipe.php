<?php

namespace AssoMaker\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssoMaker\BaseBundle\Entity\Equipe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\BaseBundle\Entity\EquipeRepository")
 */
class Equipe
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
     * @ORM\ManyToOne(targetEntity="Orga", inversedBy="equipesResponsable")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id", nullable=true)
     * @Assert\Valid
     */
    protected $responsable;
    
    /**
     * @ORM\OneToMany(targetEntity="AssoMaker\PHPMBundle\Entity\GroupeTache", mappedBy="equipe")
     */
    protected $groupesTache;
    

    /**
     * @ORM\OneToMany(targetEntity="Orga", mappedBy="equipe")
     */
    protected $orgas;

    /**
     * @ORM\OneToMany(targetEntity="RegistrationToken", mappedBy="equipe")
     */
    protected $tokens;

    /**
     * @var string $couleur
     *
     * @ORM\Column(name="couleur", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $couleur;
    
    /**
     * @ORM\ManyToOne(targetEntity="Confiance", inversedBy="equipes")
     * @ORM\JoinColumn(name="confiance_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $confiance;
    
    
    public function __toString()
    {
        return $this->getNom() ;
    }
    

    public function __construct()
    {
        $this->groupesTache = new \Doctrine\Common\Collections\ArrayCollection();
    $this->orgas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @var boolean $comptesPersoEnabled
     *
     * @ORM\Column(name="comptesPersoEnabled", type="boolean")
     */
    protected $comptesPersoEnabled = false;
    
    /**
     * @var boolean $showOnTrombi
     *
     * @ORM\Column(name="showOnTrombi", type="boolean")
     */
    protected $showOnTrombi = false;
    
    
    
    
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
     * Set responsable
     *
     * @param \AssoMaker\BaseBundle\Entity\Orga $responsable
     */
    public function setResponsable(\AssoMaker\BaseBundle\Entity\Orga $responsable)
    {
        $this->responsable = $responsable;
    }

    /**
     * Get responsable
     *
     * @return \AssoMaker\BaseBundle\Entity\Orga
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Add groupesTache
     *
     * @param \AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTache
     */
    public function addGroupeTache(\AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTache)
    {
        $this->groupesTache[] = $groupesTache;
    }

    /**
     * Get groupesTache
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupesTache()
    {
        return $this->groupesTache;
    }

    /**
     * Add orgas
     *
     * @param \AssoMaker\BaseBundle\Entity\Orga $orgas
     */
    public function addOrga(\AssoMaker\BaseBundle\Entity\Orga $orgas)
    {
        $this->orgas[] = $orgas;
    }

    /**
     * Get orgas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrgas()
    {
        return $this->orgas;
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

    /**
     * Set confiance
     *
     * @param \AssoMaker\BaseBundle\Entity\Confiance $confiance
     */
    public function setConfiance(\AssoMaker\BaseBundle\Entity\Confiance $confiance)
    {
        $this->confiance = $confiance;
    }

    /**
     * Get confiance
     *
     * @return \AssoMaker\BaseBundle\Entity\Confiance
     */
    public function getConfiance()
    {
        return $this->confiance;
    }

    /**
     * Set comptesPersoEnabled
     *
     * @param boolean $comptesPersoEnabled
     * @return Equipe
     */
    public function setComptesPersoEnabled($comptesPersoEnabled)
    {
        $this->comptesPersoEnabled = $comptesPersoEnabled;
    
        return $this;
    }

    /**
     * Get comptesPersoEnabled
     *
     * @return boolean 
     */
    public function getComptesPersoEnabled()
    {
        return $this->comptesPersoEnabled;
    }

    /**
     * Set showOnTrombi
     *
     * @param boolean $showOnTrombi
     * @return Equipe
     */
    public function setShowOnTrombi($showOnTrombi)
    {
        $this->showOnTrombi = $showOnTrombi;
    
        return $this;
    }

    /**
     * Get showOnTrombi
     *
     * @return boolean 
     */
    public function getShowOnTrombi()
    {
        return $this->showOnTrombi;
    }

    /**
     * Add groupesTache
     *
     * @param \AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTache
     * @return Equipe
     */
    public function addGroupesTache(\AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTache)
    {
        $this->groupesTache[] = $groupesTache;
    
        return $this;
    }

    /**
     * Remove groupesTache
     *
     * @param \AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTache
     */
    public function removeGroupesTache(\AssoMaker\PHPMBundle\Entity\GroupeTache $groupesTache)
    {
        $this->groupesTache->removeElement($groupesTache);
    }

    /**
     * Remove orgas
     *
     * @param \AssoMaker\BaseBundle\Entity\Orga $orgas
     */
    public function removeOrga(\AssoMaker\BaseBundle\Entity\Orga $orgas)
    {
        $this->orgas->removeElement($orgas);
    }

    /**
     * Add tokens
     *
     * @param \AssoMaker\BaseBundle\Entity\RegistrationToken $tokens
     * @return Equipe
     */
    public function addToken(\AssoMaker\BaseBundle\Entity\RegistrationToken $tokens)
    {
        $this->tokens[] = $tokens;

        return $this;
    }

    /**
     * Remove tokens
     *
     * @param \AssoMaker\BaseBundle\Entity\RegistrationToken $tokens
     */
    public function removeToken(\AssoMaker\BaseBundle\Entity\RegistrationToken $tokens)
    {
        $this->tokens->removeElement($tokens);
    }

    /**
     * Get tokens
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}
