<?php

namespace AssoMaker\PHPMBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssoMaker\PHPMBundle\Entity\GroupeTache
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\GroupeTacheRepository")
 */
class GroupeTache
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
     * @ORM\OneToMany(targetEntity="Tache", mappedBy="groupeTache")
     */
    protected $taches;

    /**
     * @ORM\ManyToOne(targetEntity="Orga", inversedBy="groupesTacheResponsable")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $responsable;

    /**
     * @ORM\ManyToOne(targetEntity="Equipe", inversedBy="groupesTache")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $equipe;

    //     /**
    //      * @ORM\ManyToOne(targetEntity="Lieu", inversedBy="groupestache")
    //      * @ORM\JoinColumn(name="lieu_id", referencedColumnName="id",onDelete="SET NULL")
    //      * @Assert\Valid
    //      */
    //     protected $lieu;

    /**
     * @var string $lieu
     *
     * @ORM\Column(name="lieu", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $lieu;

    /**
     * @var integer $animLiee
     *
     * @ORM\Column(name="animLiee", type="integer", nullable=true)
     */
    protected $animLiee;

    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0","-1"})
     * @ORM\Column(name="statut", type="smallint")
     */
    protected $statut;

    public function __toString()
    {
        return $this->getId() . "- " . $this->getNom();
    }
    public function __construct()
    {
        $this->taches = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add taches
     *
     * @param AssoMaker\PHPMBundle\Entity\Tache $taches
     */
    public function addTache(\AssoMaker\PHPMBundle\Entity\Tache $taches)
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
     * Get non-deleted taches
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getNonDeletedTaches()
    {
        return $this->taches;
    }

    /**
     * Set responsable
     *
     * @param AssoMaker\PHPMBundle\Entity\Orga $responsable
     */
    public function setResponsable(\AssoMaker\PHPMBundle\Entity\Orga $responsable)
    {
        $this->responsable = $responsable;
    }

    /**
     * Get responsable
     *
     * @return AssoMaker\PHPMBundle\Entity\Orga
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set equipe
     *
     * @param AssoMaker\PHPMBundle\Entity\Equipe $equipe
     */
    public function setEquipe(\AssoMaker\PHPMBundle\Entity\Equipe $equipe)
    {
        $this->equipe = $equipe;
    }

    /**
     * Get equipe
     *
     * @return AssoMaker\PHPMBundle\Entity\Equipe
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * Set animLiee
     *
     * @param integer $animLiee
     */
    public function setAnimLiee($animLiee)
    {
        $this->animLiee = $animLiee;
    }

    /**
     * Get animLiee
     *
     * @return integer
     */
    public function getAnimLiee()
    {
        return $this->animLiee;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set statut
     *
     * @param smallint $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * Get statut
     *
     * @return smallint
     */
    public function getStatut()
    {
    	return $this->statut;
    }
    
    /**
     * Get tachesStatut
     *
     * @return smallint
     */
    public function getTachesStatut()
    {
    
    	if($this->getStatut() ==-1){
    		return -1;
    	}
    	
	    if(count($this->getTaches())==0){
	    	$statut= 0;
	    }else{
	    	$statut=3;
	    }
    
	    foreach ($this->getTaches() as $tache){
	    	if($tache->getStatut()>=0){
	    		$statut = min($statut,$tache->getStatut());
	    	}
    	 
    	}
    return $statut;
    
    }
    
   
     

    /**
     * Is deletable ?
     *
     * @param smallint $statut
     */
    public function isDeletable()
    {

        foreach ($this->taches as $tache) {
            if ($tache->getStatut() != -1) {
                return false;
            }
        }
        return true;

    }

	/*
	 * Recherche
	 */
    public function toSearchArray()
    {
    	return array(
    		"type" => "groupe_tache",
	    	"id" => $this->getId(),
	    	"nom" => $this->getNom());
    }
}