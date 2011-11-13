<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Timespan
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\TimespanRepository")
 */
class Timespan
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
     * @var smallint $orgasneeded
     *
     * @ORM\Column(name="orgasneeded", type="smallint")
     */
    private $orgasneeded;

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
     * Set orgasneeded
     *
     * @param smallint $orgasneeded
     */
    public function setOrgasneeded($orgasneeded)
    {
        $this->orgasneeded = $orgasneeded;
    }

    /**
     * Get orgasneeded
     *
     * @return smallint 
     */
    public function getOrgasneeded()
    {
        return $this->orgasneeded;
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