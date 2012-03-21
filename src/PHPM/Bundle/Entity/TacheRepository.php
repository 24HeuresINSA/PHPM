<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TacheRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TacheRepository extends EntityRepository
{
	
	public function getTacheWithCriteria($duree, $permis, $niveau_confiance, $plage, $bloc)
	{
	
		$qb = $this->getEntityManager()->createQueryBuilder();
		$expr = $qb->expr();
		
		$andx = $expr->andx(
		
		$expr->eq('ct.plageHoraire', 'p'),
		$expr->eq('p.tache','t')
		
		);
		
		$offset = $bloc*50;
		$limit = $offset+49;
	
		if($duree!='')
		{
			$andx->add('(ct.fin - ct.debut < '.$duree.' )');
		}
// 		if($categorie !='')
// 		{
// 			$andx->add($qb->expr()->eq('t.categorie_id',$categorie));
// 		}
		if($permis!='')
		{
			$andx->add($qb->expr()->gte('t.permisNecessaire',$permis));
		}
// 		if($age !='')
// 		{
// 			$andx->add($qb->expr()->gte('t.ageNecessaire',$age));
// 		}
		if($niveau_confiance !='')
		{
			$andx->add($qb->expr()->gte('t.confiance_id',$niveau_confiance));
		}
		if($plage !='')
		{
			$andx->add($qb->expr()->eq('p.id',$plage));
			$andx->add($qb->expr()->neq('ct.disponibilite','O'));
		}
		
		$qb
		->select('t')
		
		->from('PHPMBundle:PlageHoraire', 'p')
		->from('PHPMBundle:Tache', 't')
		->from('PHPMBundle:Creneau', 'ct')
		
		->where($andx)
		->setFirstResult($offset)
		->setMaxResults($limit);;
		
		
		
		//exit(var_dump($qb->getQuery()->getDQL()));
		
		
		return $qb->getQuery()->getResult();
	
	}
	
	public function search($s)
	{
		return $this->getEntityManager()
		->createQuery("SELECT t FROM PHPMBundle:Tache t WHERE (t.nom LIKE :s OR t.consignes LIKE :s OR t.materielNecessaire LIKE :s OR t.lieu LIKE :s )")
		->setParameter('s', "%".$s."%")
		->getResult();
	}
	

	
	
}