<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;


/**
 * PHPM\Bundle\Entity\Commentaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\Commentaire")
 */
class Commentaire
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
     * @var datetime $heure
     *
     * @ORM\Column(name="debut", type="datetime")
     * @Assert\DateTime()
     */
    protected $heure;

    /**
    * @ORM\ManyToOne(targetEntity="Tache", inversedBy="Commentaires")
    * @ORM\JoinColumn(name="tache_id", referencedColumnName="id",onDelete="CASCADE", onUpdate="CASCADE")
    * @Assert\Valid
    */
    protected $tache;

    /**
     * @ORM\ManyToOne(targetEntity="Orga", inversedBy="Commentaires")
     * @ORM\JoinColumn(name="orga_id", referencedColumnName="id",onDelete="CASCADE", onUpdate="CASCADE")
     * @Assert\Valid
     */
    protected $auteur;
    
    



    

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
     * Set heure
     *
     * @param datetime $heure
     */
    public function setHeure($heure)
    {
        $this->heure = $heure;
    }

    /**
     * Get heure
     *
     * @return datetime 
     */
    public function getHeure()
    {
        return $this->heure;
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
     * Set auteur
     *
     * @param PHPM\Bundle\Entity\Orga $auteur
     */
    public function setAuteur(\PHPM\Bundle\Entity\Orga $auteur)
    {
        $this->auteur = $auteur;
    }

    /**
     * Get auteur
     *
     * @return PHPM\Bundle\Entity\Orga 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }
}