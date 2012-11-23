<?php

namespace AssoMaker\SponsoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssoMaker\SponsoBundle\Entity\Note
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\SponsoBundle\Entity\NoteRepository")
 */
class Note
{
    
    public $textesType = array('Infos','Rencontre','Appel téléphonique','Mail','Autre');
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    /**
    * @var smallint $statut
    * @Assert\Choice(choices = {"-1", "0", "1", "2"})
    * @ORM\Column(name="statut", type="smallint")
    */
    private $statut;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    protected  $date;

    /**
     * @var string $texte
     *
     * @ORM\Column(name="texte", type="string", length=4096)
     */
    private $texte;
    
    /**
     * @ORM\ManyToOne(targetEntity="Contact",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     * @Assert\Valid
     */
    protected $contact;
    
    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\BaseBundle\Entity\Orga",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="orga_id", referencedColumnName="id")
     * @Assert\Valid
     */
    protected $orga;


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
     * Set type
     *
     * @param string $type
     * @return Note
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Note
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Set contact
     *
     * @param AssoMaker\SponsoBundle\Entity\Contact $contact
     * @return Note
     */
    public function setContact(\AssoMaker\SponsoBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;
    
        return $this;
    }

    /**
     * Get contact
     *
     * @return AssoMaker\SponsoBundle\Entity\Contact 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set orga
     *
     * @param AssoMaker\BaseBundle\Entity\Orga $orga
     * @return Note
     */
    public function setOrga(\AssoMaker\BaseBundle\Entity\Orga $orga = null)
    {
        $this->orga = $orga;
    
        return $this;
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
     * Set statut
     *
     * @param integer $statut
     * @return Note
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return integer 
     */
    public function getStatut()
    {
        return $this->statut;
    }
    
    /**
     * Get type Texte
     *
     * 
     */
    public function getTypeTexte()
    {
        return $this->textesType[$this->type];
    }
    
    public function toArray(){
        return  array(  "id"=> $this->getId(),
                "type"=> $this->getTypeTexte(),
                "statut"=> $this->getStatut(),
                "date"=> $this->getDate(),
                "texte"=> $this->getTexte());
    }

    /**
     * Set texte
     *
     * @param string $texte
     * @return Note
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
    
        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }
}