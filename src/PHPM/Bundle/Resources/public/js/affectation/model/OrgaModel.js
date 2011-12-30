/*
 * Page Affectation
 * Modèle Orga
 */

function OrgaModel() {
	// on lance juste le constructeur
	this.initialize();
}

OrgaModel.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
	},
	
	/*
	 * Lance les requêtes
	 */
	getData: function(callBack) {
		pmAffectation.models.orga.callBack = callBack;
		
		$.post(pmAffectation.url+pmAffectation.paths.orgas, pmAffectation.models.orga.requestSuccess, 'json');
	},
	
	/*
	 * Récup les résultats
	 */
	requestSuccess: function(data, statusText) {
		if (statusText == "success") {
			pmAffectation.models.orga.data = data;
		
			pmAffectation.models.orga.callBack();
		} else {
			console.error("Impossible de récupérer les plages : ", statusText);
		}
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère la liste des orgas
	getOrgas: function() {	
		var _orgas = {};
		
		// traitement des orgas
		for (var _iOrga in this.data) {
			var _orga = {};
			
			// récupère toutes les données
			for (var _iChamp in this.data[_iOrga]) {
				_orga[_iChamp] = this.data[_iOrga][_iChamp];
			}
			
			// re-traitement du niveau de confiance derrière
			_orga['confiance'] = this.data[_iOrga]['confiance']['id'];
			
			// de la date de naissance
			_orga['dateDeNaissance'] = new Date(this.data[_iOrga]['dateDeNaissance']['date']);
			
			// disponibilités, cela devient physique
			for (var _iDispo in this.data[_iOrga]['disponibilites']) {
				// créneaux, encore un niveau
				for (var _iCreneau in this.data[_iOrga]['disponibilites'][_iDispo]['creneaux']) {
					this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut']['date']);
					this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin']['date']);
				}
				
				this.data[_iOrga]['disponibilites'][_iDispo]['debut'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['debut']['date']);
				this.data[_iOrga]['disponibilites'][_iDispo]['fin'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['fin']['date']);
			}
		
			// sauvegarde (on ne reçoit que les orgas importés validés)
			_orgas[_iOrga] = _orga;
		}
		
		return _orgas;
	}
}
