<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PHPM\Bundle\Validator\QuartHeure;
use PHPM\Bundle\Validator\Inclus;
use PHPM\Bundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use PHPM\Bundle\Validator\Recoupe;
/**
 * PHPM\Bundle\Entity\Creneau
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\CreneauRepository")
 * @DebutAvantFin()
 * @Inclus()
 * @Recoupe()

 */
class Creneau
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
	 * 
	 * 
     */
    protected $debut;

    /**
     * @var datetime $fin
     *
     * @ORM\Column(name="fin", type="datetime")
     * 
	 * @QuartHeure()
	 * 
	 */
    protected $fin;
    
    /**
    * @ORM\ManyToOne(targetEntity="Disponibilite", inversedBy="creneaux")
    * @ORM\JoinColumn(name="disponibilite_id", referencedColumnName="id",onDelete="SET NULL")
    * @Assert\Valid
    */
    protected $disponibilite;
    
    /**
    * @ORM\ManyToOne(targetEntity="PlageHoraire", inversedBy="creneaux")
    * @ORM\JoinColumn(name="plageHoraire_id", referencedColumnName="id",onDelete="CASCADE")
    * @Assert\Valid
    */
    protected $plageHoraire;

    /**
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumn(name="equipeHint_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $equipeHint;
    
    /**
     * @ORM\ManyToOne(targetEntity="Orga", inversedBy="creneauxHint")
     * @ORM\JoinColumn(name="orgaHint_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $orgaHint;

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
    public function setDisponibilite(\PHPM\Bundle\Entity\Disponibilite $disponibilite = null)
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
    public function setPlageHoraire($plageHoraire)
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
    	$tache = $this->getPlageHoraire()->getTache();
		
    	$creneauArray = array(
    					"debut" => $this->getDebut()->format('Y-m-d H:i:s'),
    					"fin" => $this->getFin()->format('Y-m-d H:i:s'), 
    					"duree" => $this->getDuree(), 
    					"tache" => $tache->toArrayOrgaWebService(),
						"couleur" => $tache->getGroupeTache()->getEquipe()->getCouleur()
    					);
    	return $creneauArray;
    
    }
    public function toSimpleArray()
    {
    	return array("debut" => $this->getDebut(),"fin" => $this->getFin(), "duree" => $this->getDuree());
    }
    public function __toString()
    {
    return $this->getDebut()->format('D d H:i').' - '.$this->getFin()->format('D d H:i');
    }
        

    /**
     * Set equipeHint
     *
     * @param PHPM\Bundle\Entity\Equipe $equipeHint
     */
    public function setEquipeHint(\PHPM\Bundle\Entity\Equipe $equipeHint)
    {
        $this->equipeHint = $equipeHint;
    }

    /**
     * Get equipeHint
     *
     * @return PHPM\Bundle\Entity\Equipe 
     */
    public function getEquipeHint()
    {
        return $this->equipeHint;
    }

    /**
     * Set orgaHint
     *
     * @param PHPM\Bundle\Entity\Orga $orgaHint
     */
    public function setOrgaHint(\PHPM\Bundle\Entity\Orga $orgaHint)
    {
        $this->orgaHint = $orgaHint;
    }

    /**
     * Get orgaHint
     *
     * @return PHPM\Bundle\Entity\Orga 
     */
    public function getOrgaHint()
    {
        return $this->orgaHint;
    }
}