<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PHPM\Bundle\Validator\QuartHeure;
use PHPM\Bundle\Validator\Inclus;
use PHPM\Bundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
/**
 * PHPM\Bundle\Entity\Creneau
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\CreneauRepository")
 * @DebutAvantFin()
 */
class Creneau
{
	
	public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        /*
    	$metadata->addPropertyConstraint('debut', new QuartHeure()); // quart d'heure indivisible pour créneau
		$metadata->addPropertyConstraint('fin', new QuartHeure());
		$metadata->addPropertyConstraint('debut', new Inclus()); // inclusion du créneau dans une plage et dans une dispo orga
        $metadata->addPropertyConstraint('fin', new Inclus());
        $metadata->addPropertyConstraint('debut', new DebutAvantFin());	// le début est avant la fin		
        */	
		
    }
	
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var datetime $debut
     *
     * @ORM\Column(name="debut", type="datetime")
     * @Assert\DateTime()
	 * 
	 * 
     */
    private $debut;

    /**
     * @var datetime $fin
     *
     * @ORM\Column(name="fin", type="datetime")
     * 
	 * @QuartHeure()
	 * 
	 */
    private $fin;
    
    /**
    * @ORM\ManyToOne(targetEntity="Disponibilite", inversedBy="creneaux")
    * @ORM\JoinColumn(name="disponibilite_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
    * @Assert\Valid
    */
    protected $disponibilite;
    
    /**
    * @ORM\ManyToOne(targetEntity="PlageHoraire", inversedBy="creneaux")
    * @ORM\JoinColumn(name="plageHoraire_id", referencedColumnName="id",onDelete="CASCADE", onUpdate="CASCADE")
    * @Assert\Valid
    */
    protected $plageHoraire;


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
     * Set disponibilite
     *
     * @param PHPM\Bundle\Entity\Disponibilite $disponibilite
     */
    public function setDisponibilite(\PHPM\Bundle\Entity\Disponibilite $disponibilite)
    {
        $this->disponibilite = $disponibilite;
    }

    /**
     * Get disponibilite
     *
     * @return PHPM\Bundle\Entity\Disponibilite 
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * Set plageHoraire
     *
     * @param PHPM\Bundle\Entity\PlageHoraire $plageHoraire
     */
    public function setPlageHoraire(\PHPM\Bundle\Entity\PlageHoraire $plageHoraire)
    {
        $this->plageHoraire = $plageHoraire;
    }

    /**
     * Get plageHoraire
     *
     * @return PHPM\Bundle\Entity\PlageHoraire 
     */
    public function getPlageHoraire()
    {
        return $this->plageHoraire;
    }
    
    public function getDuree()
    {
    	return ($this->getFin()->getTimestamp()-$this->getDebut()->getTimestamp());
    }
    
    public function toArray()
    {
    	return array("id" => $this->getId(),"debut" => $this->getDebut(),"fin" => $this->getFin(), "duree" => $this->getDuree(), "plageHoraire" => $this->getPlageHoraire()->toArray(),"disponibilite" => $this->getDisponibilite()->toArray());
    }
    public function toArrayOrgaWebService()
    {
    	return array("debut" => $this->getDebut(),"fin" => $this->getFin(), "duree" => $this->getDuree(), "tache" => $this->getPlageHoraire()->getTache()->getNom());
    }
    public function toSimpleArray()
    {
    	return array("debut" => $this->getDebut(),"fin" => $this->getFin(), "duree" => $this->getDuree());
    }
    public function __toString()
    {
    return $this->getDebut()->format('D d H:i').' - '.$this->getFin()->format('D d H:i');
    }
        
}