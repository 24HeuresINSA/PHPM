<?php
/**
* Import all Tache entities.
*
* @Route("/import", name="tache_import")
* @Template()
*/
function importAction()
{
	//recevoir le jason de TM
	$jason = "[{\"id\":1,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":1,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":2,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":3,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]},{\"id\":2,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":1,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":2,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":3,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]},{\"id\":3,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":1,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":2,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":3,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]}]";
	
	//jason à tester: 
	//$jason = "[{\"id\":1,"nom":"TENIR LE PUTAIN DE BAR","lieu":"DTC","confiance":{"nom":"Soft","couleur":"Bleu"},"categorie":{"nom":"Barres"},"permisNecessaire":0,"nbOrgasNecessaires":0,"plagesHoraire":{"1":{"debut":{"date":"2006-01-01 10:00:00","timezone_type":3,"timezone":"Europe\/Paris"},"fin":{"date":"2006-01-01 12:00:00","timezone_type":3,"timezone":"Europe\/Paris"},"creneaux":[]},"2":{"debut":{"date":"2006-01-01 13:00:00","timezone_type":3,"timezone":"Europe\/Paris"},"fin":{"date":"2006-01-01 14:00:00","timezone_type":3,"timezone":"Europe\/Paris"},"creneaux":[]}},{\"id\":2,"nom":"TENIR LE PUTAIN DE BAR","lieu":"DTC","confiance":{"nom":"Soft","couleur":"Bleu"},"categorie":{"nom":"Barres"},"permisNecessaire":0,"nbOrgasNecessaires":0,"plagesHoraire":{"3":{"debut":{"date":"2006-01-01 01:00:00","timezone_type":3,"timezone":"Europe\/Paris"},"fin":{"date":"2006-01-01 02:00:00","timezone_type":3,"timezone":"Europe\/Paris"},"creneaux":[]}},{\"id\":3,\"nom\":\"TENIR LE PUTAIN DE BAR\",\"categorie\":\"Barres\",\"description\":\"MAIS TU VA LE TENIR TON BAR, MERDE\",\"plages\":[{\"id\":1,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":2,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712},{\"id\":3,\"orgasNecessaires\":3,\"debut\":1234567,\"fin\":456712}]}]";
	
	$tabArray = json_decode($jason, TRUE);

	//On récupère les données de PM
	$em = $this->getDoctrine()->getEntityManager();
	$entities = $em->getRepository('PHPMBundle:Tache')->findAll();

	//Traitement des tâches
	print"Traitement des nouvelles taches et des taches modifiees<br />";
	
	foreach ($tabArray as $tache_en_traitement) {
		//*
		print $tache_en_traitement['id'];
		print"	";
		print $tache_en_traitement['nom'];
		print "<br />";
		$found = FALSE;
		$shiftLevel = 0;
		foreach ($entities as $elements){
			if ($elements->getId() == $tache_en_traitement['id']){
				$found = TRUE;
				break;
			}
		}
		
		
		if ($found){
// la tache existe déjà, on va donc comparer que les données n'ont pas été changées
			print "on a deja la tache <br />";

			foreach ($tache_en_traitement['plages'] as $timeTM){
				$found = FALSE;
				foreach ($elements->getPlagesHoraire() as $timePM){
					
					if ($timePM->getId() == $timeTM['id']){
						$found = TRUE;
						break;
					}
				}
				if ($found){
					//Le creneau existe, on vérifie qu'il est toujours bon
					print "hehe creneaux trouve<br/>";
					//TODO: virer le strtotime   
					if ($timePM->getDebut()->format('Y-m-d H:i:s') == strtotime($timeTM['debut']) ){
						if (strtotime($timePM->getFin())  == strtotime($timeTM['fin']) ){
							print "everything's fine";
						}
					}else{
						//changement de créneau, on modifie
						print "on modifie le creneau <br/>"; 
						$shiftLevel = 3;
						
					}
				}else{
					//Le creneau n'existe pas, on va donc l'ajouter à la DB
					print "on ajoute le creneau <br />";
					$shiftLevel = 1;
				}
			//TODO: gestion du nombre orga
				//if ($tache_en_traitement['orga'] != $elements->getNombreOrga()){
					//changer 
				//}
				
			}
		//TODO: changement mineurs: nom, description 
		}else{
//La tache n'existe pas, on va donc l'ajouter à la DB
			print "on ajoute la tache <br />";
			$shiftLevel = 1;
		}
		
		
	}//fin du foreach de chaque tache
	
	
		//Traitement des taches supprimées
		foreach ($entities as $tache_en_traitement) {
			$found = FALSE;
			foreach ($tabArray as $elements){
				if ($elements['id'] == $tache_en_traitement->getId()){
					$found = TRUE;
					break;
				}
				}
			
			if (!$found){
// La tache n'existe plus, on va donc la supprimer
				print "on supprime les creneaux <br />";
				print "on supprime la tache <br />";
			}
		
		}
		
	
	print "-------------------------------------------------------------";
	print "<br />";
	print "Les donnees";
	print "<br />";
	print "<pre>";
	foreach ($entities as $elements){
		print "tache numero ";
		print $elements->getId();
		print " <br />";
		print "encode <br/>";
		print_r (json_encode($elements->toArray()));
		
	}
	print "</pre>";
	
	print "<br />";
	print "<br />";	
	print "<br />";
	
	// on affiche le jason
	//*
	print "la c'est le jason!! <br />";
	print"<pre>";
	var_dump($tabArray);
	print"</pre>";
	//*/
	 
	exit(print($entities[0]->getId()));
	return array();
}
