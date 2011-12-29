<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\PlageHoraire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\PlageHoraireRepository")
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
    private $id;

    /**
     * @var datetime $debut
     *
     * @ORM\Column(name="debut", type="datetime")
     */
    private $debut;

    /**
     * @var datetime $fin
     *
     * @ORM\Column(name="fin", type="datetime")
     */
    private $fin;
    
    /**
    * @ORM\OneToMany(targetEntity="Creneau", mappedBy="plageHoraire")
    */
    protected $creneaux;
    
    /**
    * @ORM\ManyToOne(targetEntity="Tache", inversedBy="PlagesHoraires")
    * @ORM\JoinColumn(name="tache_id", referencedColumnName="id")
    */
    protected $tache;


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
    public function __construct()
    {
        $this->creneaux = new \Doctrine\Common\Collections\ArrayCollection();
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
    
	public function __toString()
    {
    	return $this->getDebut()->format('D H:i')." - ".$this->getFin()->format('D H:i');
    }
    
    public function toArray()
    {
    	
    	$a = array();
    	foreach ($this->getCreneaux() as $entity){
    		$a[$entity->getId()] = $entity->toArray();
    		
    	}
    	
    	return array(
    	"debut" => $this->getDebut(),
    	"fin" => $this->getFin(),
    	"creneaux" => $a);
    }
    
    
}