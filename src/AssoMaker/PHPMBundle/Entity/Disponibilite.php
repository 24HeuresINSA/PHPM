<?php

namespace AssoMaker\PHPMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AssoMaker\PHPMBundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use AssoMaker\PHPMBundle\Validator\QuartHeure;
use AssoMaker\PHPMBundle\Validator\Recoupe;


/**
 * AssoMaker\PHPMBundle\Entity\Disponibilite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\DisponibiliteRepository")
 * @DebutAvantFin()

 */
class Disponibilite
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
     * 
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
    * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Orga", inversedBy="disponibilites")
    * @ORM\JoinColumn(name="orga_id", referencedColumnName="id",onDelete="CASCADE")
    * @Assert\Valid
    */
    protected $orga;

    /**
    * @ORM\OneToMany(targetEntity="Creneau", mappedBy="disponibilite", indexBy="id", cascade={"persist"})
    */
    protected $creneaux;

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
     * Set orga
     *
     * @param AssoMaker\BaseBundle\Entity\Orga $orga
     */
    public function setOrga($orga)
    {
        $this->orga = $orga;
    }

    /**
     * Get orga
     *
     * @return AssoMaker\BaseBundle\Entity\Orga 
     */
    public function getOrga()
    {
        return $this->orga;
    }

    /**
     * Add creneaux
     *
     * @param AssoMaker\PHPMBundle\Entity\Creneau $creneaux
     */
    public function addCreneau(\AssoMaker\PHPMBundle\Entity\Creneau $creneaux)
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
    
    public function __toString()
    {
    	return $this->getDebut()->format('D H:i')." - ".$this->getFin()->format('D H:i');
    }
    
    
    
    public function getDuree()
    {
    	return ($this->getFin()->getTimestamp()-$this->getDebut()->getTimestamp());
    }
       
    
    
    public function toArray($developCreneaux = NULL)
    {
    	$a = array(
    	    "id" => $this->getId(),
        	"debut" => $this->getDebut(),
        	"fin" => $this->getFin(),
        	"duree" => $this->getDuree()
        	);
    	
    	
    	if($developCreneaux == true)
    	foreach ($this->getCreneaux() as $entity){
    		$a["creneaux"][$entity->getId()] = $entity->toArray();
    
    	}
    	 
    	return $a;
    }
    
    
    
    public function toSimpleArray()
    {
    	return array(
            	"debut" => $this->getDebut()->format('Y-m-d H:i:s'),
            	"fin" => $this->getFin()->format('Y-m-d H:i:s')
    	);
    }
    
    public function toArrayOrgaWebService()
    {
    	
    	$disponibilite = array(
							"debut" => $this->getDebut()->format('Y-m-d H:i:s'),
							"fin" => $this->getFin()->format('Y-m-d H:i:s'),
    					);
    	
    	foreach ($this->getCreneaux() as $entity) {
    		$disponibilite["creneaux"][$entity->getId()] = $entity->toArrayOrgaWebService();
		}
    	
    	return $disponibilite;
    }
    
    

    /**
     * Add creneaux
     *
     * @param \AssoMaker\PHPMBundle\Entity\Creneau $creneaux
     * @return Disponibilite
     */
    public function addCreneaux(\AssoMaker\PHPMBundle\Entity\Creneau $creneaux)
    {
        $this->creneaux[] = $creneaux;
    
        return $this;
    }

    /**
     * Remove creneaux
     *
     * @param \AssoMaker\PHPMBundle\Entity\Creneau $creneaux
     */
    public function removeCreneaux(\AssoMaker\PHPMBundle\Entity\Creneau $creneaux)
    {
        $this->creneaux->removeElement($creneaux);
    }
}