<?php

namespace AssoMaker\PHPMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AssoMaker\PHPMBundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use AssoMaker\PHPMBundle\Validator\QuartHeure;
use AssoMaker\PHPMBundle\Validator\Recoupe;
use Sonata\IntlBundle\Templating\Helper;


/**
 * AssoMaker\PHPMBundle\Entity\DisponibiliteInscription
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\DisponibiliteInscriptionRepository")
 * @DebutAvantFin()
 * 
 */
class DisponibiliteInscription
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
     * @Assert\DateTime()
     * @QuartHeure()
     * 
     */
    private $debut;

    /**
     * @var datetime $fin
     *
     * @ORM\Column(name="fin", type="datetime")
     * @Assert\DateTime()
     * @QuartHeure()
     */
    private $fin;
    
    
    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0", "1", "2"})
     * 0 : locked, 1 : allow check only, 2 : unlocked
     * @ORM\Column(name="statut", type="smallint")
     */
    protected $statut;
    
    /**
     * @var smallint $pointsCharisme
     * @ORM\Column(name="pointsCharisme", type="smallint")
     */
    protected $pointsCharisme;
    

    /**
     * 
     * @ORM\ManyToMany(targetEntity="Orga", mappedBy="disponibilitesInscription")
     */
    private $orgas;
    
    /**
     * @ORM\ManyToOne(targetEntity="Mission", inversedBy="disponibilitesInscription")
     * @ORM\JoinColumn(name="mission_id", referencedColumnName="id",onDelete="CASCADE")
     * @Assert\Valid
     */
    protected $mission;

    

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
        $this->orgas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add orgas
     *
     * @param AssoMaker\PHPMBundle\Entity\Orga $orgas
     */
    public function addOrga(\AssoMaker\PHPMBundle\Entity\Orga $orgas)
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
    
    public function getDebutDay()
    {
        return $this->getDebut()->format('z');
    }
    
    public function __toString()
    {
        
        return 
        \IntlDateFormatter::create(null, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT,null,null,'HH:mm')->format($this->getDebut())
        .'-'.
        \IntlDateFormatter::create(null, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT,null,null,'HH:mm')->format($this->getFin())
        
        ;
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
     * Set pointsCharisme
     *
     * @param smallint $pointsCharisme
     */
    public function setPointsCharisme($pointsCharisme)
    {
        $this->pointsCharisme = $pointsCharisme;
    }

    /**
     * Get pointsCharisme
     *
     * @return smallint 
     */
    public function getPointsCharisme()
    {
        return $this->pointsCharisme;
    }


    /**
     * Set mission
     *
     * @param AssoMaker\PHPMBundle\Entity\Mission $mission
     */
    public function setMission(\AssoMaker\PHPMBundle\Entity\Mission $mission)
    {
        $this->mission = $mission;
    }

    /**
     * Get mission
     *
     * @return AssoMaker\PHPMBundle\Entity\Mission 
     */
    public function getMission()
    {
        return $this->mission;
    }
}