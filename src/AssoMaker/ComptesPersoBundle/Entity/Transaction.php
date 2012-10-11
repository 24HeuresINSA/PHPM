<?php

namespace AssoMaker\ComptesPersoBundle\Entity;

use Symfony\Component\Validator\Constraints\DateTime;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssoMaker\ComptesPersoBundle\Entity\Transaction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\ComptesPersoBundle\Entity\TransactionRepository")
 */
class Transaction
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
     * @var float $amount
     *
     * @ORM\Column(name="amount", type="decimal", scale=2)
     */
    protected $amount;

    /**
     * @var \DateTime $commitDate
     *
     * @ORM\Column(name="commitDate", type="date")
     * @Assert\Date()
     */
    protected  $commitDate;

    /**
     * @var string $comment
     *
     * @ORM\Column(name="comment", type="string", length=255)
     */
    protected  $comment;

    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\BaseBundle\Entity\Orga",cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="orga_id", referencedColumnName="id")
     * @Assert\Valid
     */
    protected $orga;
    
    
    function __construct($orga, $amount, $comment) {
    	$this->commitDate = new \DateTime();
    	$this->orga=$orga;
    	$this->amount=$amount;
    	$this->comment=$comment;
    }
    
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
     * Set amount
     *
     * @param float $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get commitDate
     *
     * @return \DateTime 
     */
    public function getCommitDate()
    {
        return $this->commitDate;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Transaction
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set orga
     *
     * @param AssoMaker\BaseBundle\Orga $orga
     * @return Transaction
     */
    public function setOrga(\AssoMaker\BaseBundle\Orga $orga = null)
    {
        $this->orga = $orga;
    
        return $this;
    }

    /**
     * Get orga
     *
     * @return AssoMaker\BaseBundle\Orga 
     */
    public function getOrga()
    {
        return $this->orga;
    }

    /**
     * Set commitDate
     *
     * @param \DateTime $commitDate
     * @return Transaction
     */
    public function setCommitDate($commitDate)
    {
        $this->commitDate = $commitDate;
    
        return $this;
    }
}