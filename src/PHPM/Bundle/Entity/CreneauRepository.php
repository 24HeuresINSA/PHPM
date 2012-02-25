<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * CreneauRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CreneauRepository extends EntityRepository
{
	
	public function getCreneauxParJour($orga)
	{
	
		$entities= $this->getEntityManager()
		->createQuery("
		SELECT c FROM PHPMBundle:Creneau c
		JOIN c.disponibilite d
		JOIN d.orga o
		WHERE d.orga = :orga_id
		 ")
		 ->setParameter('orga_id', $orga->getId())
		->getResult();
		 
		
		
		$result=array();
		foreach ($entities as $entity){
			$dow = $entity->getDebut()->format('w');
			
			$result[$dow][$entity->getId()] = $entity;
		
		}
		
		
		return $result;
		
	}
	
	
	public function getCreneauxParJour2($orga_id){
		$conn = $this->_em->getConnection();
		//$orga_id=$orga->getId();
	
		$sql = "SELECT c.id, WEEKDAY(c.debut) d FROM Creneau c LEFT JOIN Disponibilite d ON c.disponibilite_id=d.id LEFT JOIN Orga o ON d.orga_id=o.id ";
	
		$rows = $conn->fetchAll($sql);
		
		foreach ($rows as $row){
			var_dump($c);
			$co=$c[0];
				
			$a[$c['w']][$co->getId()]=$co;
			//$a[$c['w'][($c[0])->getId()]]=$c;
				
		}
		
		//$rows = $conn->prepare($sql)->execute();
	
	
		return $rows;
	}
	
	public function getCreneauxParJourNative($orga_id){
		
		
		$rsm = new ResultSetMapping;
		$rsm->addEntityResult('PHPM\Bundle\Entity\Creneau', 'c');
		$rsm->addFieldResult('c', 'id', 'id');
		$rsm->addFieldResult('c', 'debut', 'debut');
		$rsm->addFieldResult('c', 'fin', 'fin');
		$rsm->addFieldResult('c', 'id', 'id');
		$rsm->addJoinedEntityResult("PHPM\Bundle\Entity\PlageHoraire", "p", "c", "plageHoraire");
		
		
		$rsm->addScalarResult('d', 'd');
		
		
		$query = $this->_em->createNativeQuery(
		'SELECT c.*, p.id as p_id, WEEKDAY(c.debut) d 
		FROM Creneau c 
		LEFT JOIN PlageHoraire p ON c.plageHoraire_id=p.id
		ORDER BY d', $rsm);
		
		
		$creneaux = $query->getResult();
		var_dump($creneaux);
		exit();
		
		foreach ($creneaux as $c){
			var_dump($c);
			$co=$c[0];
			
			$a[$c['w']][$co->getId()]=$co;
			//$a[$c['w'][($c[0])->getId()]]=$c;
			
		}
		var_dump($a);
		exit();
		
		return $a;
		
		
		
		
	}
	
	
	public function getCreneauxCompatibleWithCriteria($niveau_confiance, $categorie, $age, $permis, $duree, $orga, $plage, $date_time, $bloc)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$expr = $qb->expr();
		
		$andx = $expr->andx(

		$expr->eq('ct.plageHoraire', 'p'),
		$expr->eq('p.tache','t'),
		$expr->isNull('ct.disponibilite')

		);

		//$offset = $bloc*50;
		//$limit = 50;
		
		if($permis!='')
		{
			$andx->add($expr->gte('t.permisNecessaire',$permis));
		}
		if($age !='')
		{
			$andx->add($expr->gte('t.ageNecessaire','\''.$age.'\''));
		}
		if($niveau_confiance !='')
		{
			$andx->add($expr->gte('t.confiance',$niveau_confiance));
		}
		
		if($categorie !='')
		{
			$andx->add($expr->gte('t.categorie',$categorie));
		}
		if($duree !='')
		{
			
			$andx->add('(ct.fin - ct.debut < '.$duree.' )');
		}		
		if($orga !='')
		{
			$andx->add(" ct.id NOT IN (SELECT ci.id FROM PHPMBundle:Creneau ci , PHPMBundle:Creneau co, PHPMBundle:Disponibilite do
			WHERE (co.disponibilite= do.id AND do.orga = $orga ) AND ( (ci.debut < co.fin) AND (ci.fin > co.debut ) )
			OR	(((ci.debut<p.debut)OR(ci.fin > p.fin))OR((ci.debut >= p.fin)OR(ci.fin <= p.debut)))
			
		)");
		}
		if($plage !='')
		{
			$pref = json_decode($this->getEntityManager()->getRepository('PHPMBundle:Config')->findOneByField('manifestation.plages')->getValue(),TRUE);
			$plage= $pref[$plage];
			$andx->add('(ct.debut < \''.$plage["fin"].'\' ) AND (ct.fin >\''.$plage["debut"].'\' )');
		}
		if($date_time!='')
		{
				
			$andx->add(($expr->gte('ct.debut',$date_time)));
		}
		
		
		
		$qb
		->select('ct')
		
		->from('PHPMBundle:PlageHoraire', 'p')
		->from('PHPMBundle:Tache', 't')
		->from('PHPMBundle:Creneau', 'ct')
		
		->where($andx);
		
		
		
		//exit(var_dump($qb->getQuery()->getDQL()));
		
		
		
		return $qb->getQuery()->getResult();
		
	}
		
	
}