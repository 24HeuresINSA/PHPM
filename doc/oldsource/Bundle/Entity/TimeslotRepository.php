<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TimeslotRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TimeslotRepository extends EntityRepository
{
	
	public function getAffectable($id)
	{
		$em = $this->getEntityManager();
	
	
	
		$dql = "SELECT t FROM PHPMBundle:Timeslot t WHERE
	            t.id NOT IN 
	                (
	                SELECT t2.id FROM
	                PHPMBundle:Timeslot t1,
	                PHPMBundle:Timeslot t2
	                WHERE 
	                ((t1.orga = $id) AND (t1.endtime > t2.begintime AND t1.begintime <t2.endtime) )
	                OR
	                (t2.orga != 1 ) 
	                
	                
	                
	                )
	            ";
	
		
	
		$query = $em->createQuery($dql);
	
		$entity = $query->getResult();
					
		return  $entity;
	}
	
	public function getAffectedAtTime($taskid,$time)
	{
		$em = $this->getEntityManager();
	
	
	
		$dql = "SELECT t FROM PHPMBundle:Timeslot t WHERE
		WHERE t.task =$id
		AND (
		NOT(t1.endtime <= t2.begintime OR t1.begintime >=t2.endtime)
		OR t2.orga != 1 )
		)
		";
	
	
		$query = $em->createQuery($dql);
	
		$entity = $query->getResult();
	
		return  $entity;
	}
	
	public function getFromTimespan($taskid,$timespanid)
	{
		$em = $this->getEntityManager();
	
	
	
		$dql = "SELECT t FROM PHPMBundle:Timeslot t , PHPMBundle:Timespan tsp
		WHERE tsp.id= $timespanid AND t.task = $taskid
		AND 
		(t.begintime <= tsp.endtime AND t.endtime >=tsp.begintime)
		
		";
	
	
		$query = $em->createQuery($dql);
	
		$entity = $query->getResult();
	
		return  $entity;
	}
	
	
	
	public function getHours($tsp){
		$conn = $this->_em->getConnection();
		$tid=$tsp->getId();
		
		$sql = "CREATE TEMPORARY TABLE te SELECT  t.endtime AS t FROM Timeslot t, Timespan tsp WHERE tsp.id=$tid AND (t.begintime <= tsp.endtime AND t.endtime >= tsp.begintime)  ;
		
		CREATE TEMPORARY TABLE tb SELECT  t.begintime AS t FROM Timeslot t, Timespan tsp WHERE tsp.id=$tid AND (t.begintime <= tsp.endtime AND t.endtime >= tsp.begintime)  ;
		
		CREATE TEMPORARY TABLE t1 SELECT * FROM tb UNION SELECT * FROM te UNION SELECT begintime from Timespan WHERE tsp.id=$tid UNION SELECT endtime from Timespan WHERE tsp.id=$tid;
		
		CREATE TEMPORARY TABLE t2 SELECT  t.begintime, t.endtime FROM Timeslot t, Timespan tsp  WHERE  (t.begintime <= tsp.endtime AND t.endtime >= tsp.begintime) AND orga_id !=1 ;
		
		CREATE TEMPORARY TABLE hours SELECT t , SUM(IF(begintime<= t AND endtime >t,1,0)) AS o from t1, t2  GROUP BY t ;
		
		
		
		
		";
		
		
		$conn->query($sql)->closeCursor();

$sql = "SELECT t, o  FROM hours  ";


$st = $conn->prepare($sql)->execute();







		return $st;
	}
	
	
}