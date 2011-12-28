<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Timeslot
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\TimeslotRepository")
 * 
 * 
 *  */
class Timeslot
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
     * @ORM\ManyToOne(targetEntity="Orga", inversedBy="timeslots")
     * @ORM\JoinColumn(name="orga_id", referencedColumnName="id")
     */
    protected $orga;
    
    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="timeslots")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id")
     */
    protected $task;

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
     * Set orga
     *
     * @param PHPM\Bundle\Entity\Orga $orga
     */
    public function setOrga(\PHPM\Bundle\Entity\Orga $orga)
    {
        $this->orga = $orga;
    }

    /**
     * Get orga
     *
     * @return PHPM\Bundle\Entity\Orga 
     */
    public function getOrga()
    {
        return $this->orga;
    }

    /**
     * Set task
     *
     * @param PHPM\Bundle\Entity\Task $task
     */
    public function setTask(\PHPM\Bundle\Entity\Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get task
     *
     * @return PHPM\Bundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }
}