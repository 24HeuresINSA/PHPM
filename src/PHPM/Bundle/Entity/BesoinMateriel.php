<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Sonata\IntlBundle\Templating\Helper;


/**
 * PHPM\Bundle\Entity\BesoinMateriel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\BesoinMaterielRepository")
 * 
 */
class BesoinMateriel
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
    * @ORM\ManyToOne(targetEntity="Tache", inversedBy="besoinsMateriel")
    * @ORM\JoinColumn(name="tache_id", referencedColumnName="id",onDelete="CASCADE", onUpdate="CASCADE")
    * @Assert\Valid
    */
    protected $tache;
    
    /**
    * @ORM\ManyToOne(targetEntity="Materiel", inversedBy="besoinsMateriel")
    * @ORM\JoinColumn(name="materiel_id", referencedColumnName="id",onDelete="CASCADE", onUpdate="CASCADE")
    * @Assert\Valid
    */
    
    protected $materiel;
    
    /**
     * @var integer $quantite
     *
     * @ORM\Column(name="quantite", type="integer")
     * 
     */
    protected $quantite;

    

    
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
     * Set quantite
     *
     * @param integer $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
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

    /**
     * Set materiel
     *
     * @param PHPM\Bundle\Entity\Materiel $materiel
     */
    public function setMateriel(\PHPM\Bundle\Entity\Materiel $materiel)
    {
        $this->materiel = $materiel;
    }

    /**
     * Get materiel
     *
     * @return PHPM\Bundle\Entity\Materiel 
     */
    public function getMateriel()
    {
        return $this->materiel;
    }
}