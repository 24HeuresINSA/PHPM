<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PHPM\Bundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use PHPM\Bundle\Validator\QuartHeure;
use PHPM\Bundle\Validator\Recoupe;


/**
 * PHPM\Bundle\Entity\DisponibiliteInscription
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\DisponibiliteInscriptionRepository")
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
     * 
     * @ORM\ManyToMany(targetEntity="Orga", mappedBy="disponibilitesInscription")
     */
    private $orgas;

    

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
     * @param PHPM\Bundle\Entity\Orga $orgas
     */
    public function addOrga(\PHPM\Bundle\Entity\Orga $orgas)
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
        
        return $this->getDebut()->format("G:i").' '.$this->getFin()->format("G:i");
    }
    
    
    public function getnbOrga()
    {
        $ArrayOrga = $this->getOrgas();
        $compteur = 0;
        foreach ($ArrayOrga as $orga )
        {
            $compteur++ ;
        }       
        return $compteur;
    }
    
        public function getnbOrgaAvecPermisLongueDuree()
    {
        $ArrayOrga = $this->getOrgas();
        $compteur = 0;
        foreach ($ArrayOrga as $orga )
        {
            if ($orga->getPermis() == 2)
            {
            $compteur++ ;
            }
        }       
        return $compteur;
    }
    
    
}