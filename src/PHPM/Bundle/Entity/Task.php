<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Task
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Task
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var datetime $begintime
     *
     * @ORM\Column(name="begintime", type="datetime")
     */
    private $begintime;

    /**
     * @var datetime $endtime
     *
     * @ORM\Column(name="endtime", type="datetime")
     */
    private $endtime;

    /**
     * @var smallint $duration
     *
     * @ORM\Column(name="duration", type="smallint")
     */
    private $duration;

    /**
     * @var smallint $overlap
     *
     * @ORM\Column(name="overlap", type="smallint")
     */
    private $overlap;

    /**
     * @ORM\OneToMany(targetEntity="Timeslot", mappedBy="task")
     */
    protected $timeslots;
    
    
    

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set begintime
     *
     * @param datetime $begintime
     */
    public function setBegintime($begintime)
    {
        $this->begintime = $begintime;
    }

    /**
     * Get begintime
     *
     * @return datetime 
     */
    public function getBegintime()
    {
        return $this->begintime;
    }

    /**
     * Set endtime
     *
     * @param datetime $endtime
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;
    }

    /**
     * Get endtime
     *
     * @return datetime 
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Set duration
     *
     * @param smallint $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Get duration
     *
     * @return smallint 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set overlap
     *
     * @param smallint $overlap
     */
    public function setOverlap($overlap)
    {
        $this->overlap = $overlap;
    }

    /**
     * Get overlap
     *
     * @return smallint 
     */
    public function getOverlap()
    {
        return $this->overlap;
    }
    
    public function __toString()
    {
        return $this->name;
    }
    public function __construct()
    {
        $this->timeslots = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add timeslots
     *
     * @param PHPM\Bundle\Entity\Timeslot $timeslots
     */
    public function addTimeslot(\PHPM\Bundle\Entity\Timeslot $timeslots)
    {
        $this->timeslots[] = $timeslots;
    }

    /**
     * Get timeslots
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTimeslots()
    {
        return $this->timeslots;
    }
    
    public function isPopulated(){
    	
    	
    	$ok= false;
    	$times=array();
    	 foreach ( $this->timeslots as $ts){
    	 	$dt=$ts->getBegintime();
    	 	array_push($times, $dt->getTimestamp());
    	 	
    	 }
    	 
    	var_dump(array_count_values($times));
    	 
    	 
    	 
    	 
    	 
    	
    	
    	
    	return $ok;
    }
    
}