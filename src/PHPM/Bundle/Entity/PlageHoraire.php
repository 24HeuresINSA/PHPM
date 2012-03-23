<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PHPM\Bundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use PHPM\Bundle\Validator\QuartHeure;
use PHPM\Bundle\Validator\Recoupe;

/**
 * PHPM\Bundle\Entity\PlageHoraire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\PlageHoraireRepository")
 * @DebutAvantFin()
 * @Recoupe
 */
class PlageHoraire
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
     * @var datetime $debut
     *
     * @ORM\Column(name="debut", type="datetime")
     * @Assert\DateTime()
     * @QuartHeure()
     */
    protected $debut;

    /**
     * @var datetime $fin
     *
     * @ORM\Column(name="fin", type="datetime")
     * @Assert\DateTime()
     * @QuartHeure()
     */
    protected $fin;
    
    /**
    * @var smallint $dureeCreneau
    *
    * @ORM\Column(name="dureeCreneau", type="smallint")
    * @Assert\Min(limit = "0")
    * @QuartHeure()
    */
    protected $dureeCreneau;
    
    /**
     * @var smallint $recoupementCreneau
     *
     * @ORM\Column(name="recoupementCreneau", type="smallint")
     * @Assert\Min(limit = "0")
     * @QuartHeure()
     */
    protected $recoupementCreneau;
    
   
    
    /**
     * @var bool $respNecessaire
     *
     * @ORM\Column(name="respNecessaire", type="boolean", nullable=true)
     */
    protected $respNecessaire;
    
    /**
    * @ORM\OneToMany(targetEntity="Creneau", mappedBy="plageHoraire")
    */
    protected $creneaux;
    
    /**
     * @ORM\OneToMany(targetEntity="BesoinOrga", mappedBy="plageHoraire",orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $besoinsOrga;
    
    /**
    * @ORM\ManyToOne(targetEntity="Tache", inversedBy="PlagesHoraires")
    * @ORM\JoinColumn(name="tache_id", referencedColumnName="id",onDelete="CASCADE", onUpdate="CASCADE")
    * @Assert\Valid
    */
    protected $tache;



    public function getDuree()
    {
    	return ($this->getFin()->getTimestamp()-$this->getDebut()->getTimestamp());
    }
    
    	
    	public function __toString()
    {
    	return $this->getTache()->__toString()." - ".$this->getDebut()->format('D H:i')." - ".$this->getFin()->format('D H:i');
    }
    
    public function toArray($developCreneaux = NULL)
    {
    	
    	$a = array();
    	if(isset($developCreneaux))
    	foreach ($this->getCreneaux() as $entity){
    		$a[$entity->getId()] = $entity->toArray();
    		
    	}
    	
    	return array(
    	"id" => $this->getId(),
    	"debut" => $this->getDebut(),
    	"fin" => $this->getFin(),
    	"duree" => $this->getDuree(),
    	"nbOrgasNecessaires" => $this->getNbOrgasNecessaires(),
    	"tache" => $this->getTache()->toArray(),
    	"creneaux" => $a);
    }
    public function toSimpleArray()
    {
    	return array("debut" => $this->getDebut(),"fin" => $this->getFin(), "duree" => $this->getDuree());
    }

    public function __construct()
    {
        $this->creneaux = new \Doctrine\Common\Collections\ArrayCollection();
    $this->besoinsOrga = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set debut
     *
     * @param datetime $debut
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;
    }

    /**
     * Get debut
     *
     * @return datetime 
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param datetime $fin
     */
    public function setFin($fin)
    {
        $this->fin = $fin;
    }

    /**
     * Get fin
     *
     * @return datetime 
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set dureeCreneau
     *
     * @param smallint $dureeCreneau
     */
    public function setDureeCreneau($dureeCreneau)
    {
        $this->dureeCreneau = $dureeCreneau;
    }

    /**
     * Get dureeCreneau
     *
     * @return smallint 
     */
    public function getDureeCreneau()
    {
        return $this->dureeCreneau;
    }

    /**
     * Set recoupementCreneau
     *
     * @param smallint $recoupementCreneau
     */
    public function setRecoupementCreneau($recoupementCreneau)
    {
        $this->recoupementCreneau = $recoupementCreneau;
    }

    /**
     * Get recoupementCreneau
     *
     * @return smallint 
     */
    public function getRecoupementCreneau()
    {
        return $this->recoupementCreneau;
    }

    /**
     * Set nbOrgasNecessaires
     *
     * @param smallint $nbOrgasNecessaires
     */
    public function setNbOrgasNecessaires($nbOrgasNecessaires)
    {
        $this->nbOrgasNecessaires = $nbOrgasNecessaires;
    }

    /**
     * Get nbOrgasNecessaires
     *
     * @return smallint 
     */
    public function getNbOrgasNecessaires()
    {
        return $this->nbOrgasNecessaires;
    }

    /**
     * Set nbOrgasComNecessaires
     *
     * @param smallint $nbOrgasComNecessaires
     */
    public function setNbOrgasComNecessaires($nbOrgasComNecessaires)
    {
        $this->nbOrgasComNecessaires = $nbOrgasComNecessaires;
    }

    /**
     * Get nbOrgasComNecessaires
     *
     * @return smallint 
     */
    public function getNbOrgasComNecessaires()
    {
        return $this->nbOrgasComNecessaires;
    }

    /**
     * Set respNecessaire
     *
     * @param boolean $respNecessaire
     */
    public function setRespNecessaire($respNecessaire)
    {
        $this->respNecessaire = $respNecessaire;
    }

    /**
     * Get respNecessaire
     *
     * @return boolean 
     */
    public function getRespNecessaire()
    {
        return $this->respNecessaire;
    }

    /**
     * Add creneaux
     *
     * @param PHPM\Bundle\Entity\Creneau $creneaux
     */
    public function addCreneau(\PHPM\Bundle\Entity\Creneau $creneaux)
    {
        $this->creneaux[] = $creneaux;
    }

    /**
     * Get creneaux
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCreneaux()
    {
        return $this->creneaux;
    }

    /**
     * Add besoinsOrga
     *
     * @param PHPM\Bundle\Entity\BesoinOrga $besoinsOrga
     */
    public function addBesoinOrga(\PHPM\Bundle\Entity\BesoinOrga $besoinsOrga)
    {
        $this->besoinsOrga[] = $besoinsOrga;
    }

    /**
     * Get besoinsOrga
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBesoinsOrga()
    {
        return $this->besoinsOrga;
    }
    
    /**
     * Set besoinsOrga
     *
     * @param PHPM\Bundle\Entity\BesoinOrga $besoinsOrga
     */
    public function setBesoinsOrga($besoinsOrga)
    {
        foreach ($besoinsOrga as $bo)
            $bo->setPlageHoraire($this);
        $this->besoinsOrga = $besoinsOrga;
    }

    /**
     * Set tache
     *
     * @param PHPM\Bundle\Entity\Tache $tache
     */
    public function setTache(\PHPM\Bundle\Entity\Tache $tache)
    {
        $this->tache = $tache;
    }

    /**
     * Get tache
     *
     * @return PHPM\Bundle\Entity\Tache 
     */
    public function getTache()
    {
        return $this->tache;
    }
}