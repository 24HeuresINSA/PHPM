<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\EntityRepository;
use PHPM\Bundle\Entity\Orga;
use PHPM\Bundle\Entity\Config;

/**
 * OrgaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrgaRepository extends EntityRepository
{
	public function getOrgasWithCriteria($permis, $maxDateNaissance, $plage_id, $niveau_confiance, $creneau, $equipe_id)
	{
		// la requête ci-dessous plante (plus exactement le JOIN) : à corriger
		//$dql = "Select Distinct o From PHPMBundle:Orga as o JOIN o.disponibilites d JOIN d.creneaux c Where o.statut=1";
		//le distinct est là à cause du creneau_id, en attendant qu'il soit mieux codé ^ ^
		
		// (le JOIN plante)ancienne) requête (148b2848650e5c3af0bff2685054605d5ee10944)
		$dql = "SELECT o, SUM(di.pointsCharisme) charisme FROM PHPMBundle:Orga AS o JOIN o.disponibilitesInscription di JOIN o.disponibilites d JOIN o.equipe e WHERE o.statut=1 AND d.orga != 0";
		
// 		if ($permis != '') {
// 			$dql.=" AND o.permis = '$permis'";
// 		}
		
			$dql .= " AND o.dateDeNaissance <= '$maxDateNaissance'";
		
		if ($niveau_confiance !== '') {
			$dql .= " AND e.confiance = '$niveau_confiance'";
		}
		
		if ($plage_id !== '') {
			$pref = json_decode($this->getEntityManager()->getRepository('PHPMBundle:Config')->findOneByField('manifestation_plages')->getValue(),TRUE);
			$plage = $pref[$plage_id];
			$fin = $plage["fin"];
			$debut = $plage["debut"];
			$dql .= " AND d.debut < '$fin' AND d.fin > '$debut'";
		}
		
		if ($creneau !== '') {
			//test sur l'overlap des créneaux
			$dql .=" AND (o.id NOT IN (SELECT oi.id FROM PHPMBundle:Orga as oi JOIN oi.disponibilites di JOIN di.creneaux ci, PHPMBundle:Creneau cref where cref.id = '$creneau' AND (ci.debut < cref.fin) AND (ci.fin > cref.debut ) ))";
			//test sur la dispo qui est pas nul
			$dql .=" AND (c.disponibilite IS NULL) ";
		}
		
		if ($equipe_id !== '') {
			$dql .= " AND e.id = $equipe_id ";
		}
		
		// on trie par nombre de points de charisme
		$dql .= "GROUP BY o.id ORDER BY charisme DESC";
		
		$q = $this->getEntityManager()->createQuery($dql);
		return $q->execute();
	}
	
	public function getOrgasToValidate()
	{
	
		return $this->getEntityManager()
		->createQuery("SELECT o ,SUM(d.fin-d.debut)/3600 AS nbHeures FROM PHPMBundle:Orga o, PHPMBundle:Disponibilite d WHERE (d.orga = o AND o.statut=0)")
		
		->getResult();	
	
	
	}
	
	public function getStats(\PHPM\Bundle\Entity\Orga $orga)
	{
	
		
		$pcmax = $this->getEntityManager()
		->createQuery("SELECT sum(d.pointsCharisme)FROM PHPMBundle:DisponibiliteInscription d")
		
		->getSingleScalarResult();
		
		$totalpc = $this->getEntityManager()
		->createQuery("SELECT sum(d.pointsCharisme)FROM PHPMBundle:Orga o JOIN o.disponibilitesInscription d WHERE o.statut >=0")
		
		->getSingleScalarResult();
		
		
		$DIs = $this->getEntityManager()
		->createQuery("SELECT o  , sum(d.pointsCharisme) as pc FROM PHPMBundle:Orga o JOIN o.disponibilitesInscription d WHERE o.statut >=0 GROUP BY o.id ORDER BY pc DESC")
	
		->getResult();
		
		$nonValidated = $this->getEntityManager()
		->createQuery("SELECT count(o) FROM PHPMBundle:Orga o WHERE  o.statut =0")
		
		->getSingleScalarResult();
		
		$rang=1;
		$nbOrgas = count($DIs);
		
		foreach ($DIs as $row){
			$orgaDI=$row[0];
			$pc=$row['pc'];
			if ($orgaDI==$orga){
				return array('rangCharisme'=>$rang,'nbOrgas'=>$nbOrgas,'PCOrga'=>$pc,'PCTotal'=>$totalpc, 'PCMax'=>$pcmax,'nonValidated'=>$nonValidated)
				;
			}
			$rang++;
		}
		return(array('rangCharisme'=>-1,'nbOrgas'=>$nbOrgas,'PCOrga'=>0,'PCTotal'=>$totalpc,'PCMax'=>$pcmax,'nonValidated'=>$nonValidated));
	
	
	}
	
    public function getOrgasFromRegistration()
    {
    
        return $this->getEntityManager()
        ->createQuery("SELECT o FROM PHPMBundle:Orga o WHERE (o.statut=0)")
        
        ->getResult();  
    
    
    }
    
    public function getNombreOrgas() 
    {
        return $this->getEntityManager()
        ->createQuery("SELECT COUNT (o.id) AS nbOrgas FROM PHPMBundle:Orga o WHERE (o.statut=1)")   
        ->getResult();
    }
    
    public function getNombreHeureDesCreneauNonAffecte() 
    {
        return $this->getEntityManager()
        ->createQuery("SELECT SUM(c.fin-c.debut)/3600 AS nbHeures FROM PHPMBundle:Creneau c  WHERE (c.disponibilite is NULL)")   
        ->getResult();
    }
    
    public function getNombreHeureInscription($oid)
    {
        return number_format($this->getEntityManager()
        ->createQuery("SELECT SUM(d.fin-d.debut)/3600 AS nbHeures FROM PHPMBundle:Orga o JOIN o.disponibilitesInscription d")
        ->getSingleScalarResult(),1);
    }
   
   
    /*    Voir comment on peut récupérer le résultat d'une requête SQL en natif
           public function getTacheSansCreneau() 
    {
        
        /*
        return $this->getEntityManager()
        ->createQuery("SELECT t FROM PHPMBundle:Tache t WHERE (t.id = (SELECT p.tache FROM PHPMBundle:PlageHoraire WHERE (p.id NOT IN (SELECT c.plageHoraire FROM PHPMBundle:Creneau))")   
        ->getResult();
    
         
         
         $conn = $em->getConnection();
         
         $sql = "SELECT t FROM PHPMBundle:Tache t WHERE (t.id = (SELECT p.tache FROM PHPMBundle:PlageHoraire WHERE (p.id NOT IN (SELECT c.plageHoraire FROM PHPMBundle:Creneau))";
         
         $conn->query($sql);

         $conn->close();
    }
    
    */
    
	public function search($s)
	{
		return $this->getEntityManager()
		->createQuery("SELECT o FROM PHPMBundle:Orga o WHERE (o.nom LIKE :s OR o.prenom LIKE :s OR o.surnom LIKE :s OR o.telephone LIKE :s OR o.email LIKE :s)")
		->setParameter('s', "%".$s."%")
		->getResult();	
	}
	
//	getOrgasWithCriteriaTache numéro 2 pour gérer le tache id
/*
public function getOrgasWithCompatibleTache($tache_id)
	{
		
		$qb = $this->getEntityManager()->createQueryBuilder();
		$expr = $qb->expr();
	
		$andx = $expr->andx(
		
		
		
		//$expr->eq('t.id',$tache_id),
		$expr->neq('t.id',$tache_id),
		
		$expr->eq('d.orga','o'),
		$expr->neq('d.orga','0'),
		$expr->eq('co.disponibilite','d'),
		$expr->eq('ct.plageHoraire','p'),
		$expr->eq('p.tache','t')
		
		
		$expr->eq('ct.disponibilite','0'),
		$expr->lte('ct.debut','d.fin'),
		$expr->gte('ct.fin','d.debut'),
		
				'ct.id NOT IN (SELECT ci.id FROM PHPMBundle:Creneau ci 
				WHERE 
				
				( (ci.debut < co.fin) AND (ci.fin > co.debut ) )
				OR
				(((ci.debut<p.debut)OR(ci.fin > p.fin))OR((ci.debut >= p.fin)OR(ci.fin <= p.debut)))
				
				)'
		
		
		);
		
		$qb
		->select('o,ct')
		
		->from('PHPMBundle:Orga','o')
		
		
		
		->from('PHPMBundle:Disponibilite', 'd')
		->from('PHPMBundle:Creneau', 'co')
		->from('PHPMBundle:PlageHoraire', 'p')
		->from('PHPMBundle:Tache', 't')
		->from('PHPMBundle:Creneau', 'ct')
		
		
		->where($andx);
		
		//exit(var_dump($qb->getQuery()->getDQL()));
		
		
		
		return $qb->getQuery()->getResult();
		
		
	}
//*/	
}