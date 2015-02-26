<?php

namespace AssoMaker\PHPMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AssoMaker\PHPMBundle\Validator\QuartHeure;
use AssoMaker\PHPMBundle\Validator\Inclus;
use AssoMaker\PHPMBundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use AssoMaker\PHPMBundle\Validator\Recoupe;
/**
 * AssoMaker\PHPMBundle\Entity\Creneau
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\CreneauRepository")
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
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Equipe")
     * @ORM\JoinColumn(name="equipeHint_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $equipeHint;
    
    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Orga", inversedBy="creneauxHint")
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
     * @param \AssoMaker\PHPMBundle\Entity\Disponibilite $disponibilite
     */
    public function setDisponibilite(\AssoMaker\PHPMBundle\Entity\Disponibilite $disponibilite = null)
    {
        $this->disponibilite = $disponibilite;
    }

    /**
     * Get disponibilite
     *
     * @return \AssoMaker\PHPMBundle\Entity\Disponibilite
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * Set plageHoraire
     *
     * @param AssoMaker\PHPMBundle\Entity\PlageHoraire $plageHoraire
     */
    public function setPlageHoraire($plageHoraire)
    {
        $this->plageHoraire = $plageHoraire;
    }

    /**
     * Get plageHoraire
     *
     * @return AssoMaker\PHPMBundle\Entity\PlageHoraire 
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
     * @param AssoMaker\BaseBundle\Entity\Equipe $equipeHint
     */
    public function setEquipeHint($equipeHint)
    {
        $this->equipeHint = $equipeHint;
    }

    /**
     * Get equipeHint
     *
     * @return AssoMaker\BaseBundle\Entity\Equipe 
     */
    public function getEquipeHint()
    {
        return $this->equipeHint;
    }

    /**
     * Set orgaHint
     *
     * @param AssoMaker\BaseBundle\Entity\Orga $orgaHint
     */
    public function setOrgaHint($orgaHint)
    {
        $this->orgaHint = $orgaHint;
    }

    /**
     * Get orgaHint
     *
     * @return AssoMaker\BaseBundle\Entity\Orga 
     */
    public function getOrgaHint()
    {
        return $this->orgaHint;
    }
}