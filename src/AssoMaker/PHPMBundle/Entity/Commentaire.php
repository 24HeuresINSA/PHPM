<?php

namespace AssoMaker\PHPMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;


/**
 * AssoMaker\PHPMBundle\Entity\Commentaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\CommentaireRepository")
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
    * @ORM\ManyToOne(targetEntity="Tache", inversedBy="commentaires")
    * @ORM\JoinColumn(name="tache_id", referencedColumnName="id",onDelete="CASCADE")
    * @Assert\Valid
    */
    protected $tache;

    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Orga", inversedBy="commentaires")
     * @ORM\JoinColumn(name="orga_id", referencedColumnName="id",onDelete="CASCADE")
     * @Assert\Valid
     */
    protected $auteur;
    
    /**
     * @var text $texte
     *
     * @ORM\Column(name="texte", type="text", nullable=true)
     */
    protected $texte;
    
    /**
     * @var smallint $type
     * @Assert\Choice(choices = {"0", "1", "2", "3", "-1"})
     * @ORM\Column(name="statut", type="smallint")
     */
    protected $type = 0;
    



    

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
     * @param AssoMaker\PHPMBundle\Entity\Tache $tache
     */
    public function setTache(\AssoMaker\PHPMBundle\Entity\Tache $tache)
    {
        $this->tache = $tache;
    }

    /**
     * Get tache
     *
     * @return AssoMaker\PHPMBundle\Entity\Tache 
     */
    public function getTache()
    {
        return $this->tache;
    }

    /**
     * Set auteur
     *
     * @param AssoMaker\BaseBundle\Entity\Orga $auteur
     */
    public function setAuteur(\AssoMaker\BaseBundle\Entity\Orga $auteur)
    {
        $this->auteur = $auteur;
    }

    /**
     * Get auteur
     *
     * @return AssoMaker\BaseBundle\Entity\Orga 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set texte
     *
     * @param text $texte
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
    }

    /**
     * Get texte
     *
     * @return text 
     */
    public function getTexte()
    {
        return $this->texte;
    }



    /**
     * Set type
     *
     * @param integer $type
     * @return Commentaire
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }
}