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
		
		// construit les paramètres que l'on va envoyer, teste si déjà on a les filtres
		var _params = {
			plage_id: pmAffectation.current.plage, // on fournit toujours la plage, la base
		}; // -1 est le wildcart
		(($.isNumeric(pmAffectation.current.confiance) === true) && (pmAffectation.current.confiance != -1)) && (_params.confiance_id = pmAffectation.current.confiance);
		(($.isNumeric(pmAffectation.current.permis) === true) && (pmAffectation.current.permis != -1)) && (_params.permis = pmAffectation.current.permis);
		// TODO : age ou date de naissance
		
		$.ajax({
			url: pmAffectation.url+pmAffectation.paths.orgas,
			dataType: 'json',
			data: _params,
			success: pmAffectation.models.orga.requestSuccess,
			error: pmAffectation.models.orga.requestError,
			type: 'POST'
		});
	},
	
	/*
	 * Récup les résultats
	 */
	requestSuccess: function(data) {
		pmAffectation.models.orga.data = data;
	
		pmAffectation.models.orga.callBack();
	},
	requestError: function(data, statusText) {
		message.error("Impossible de récupérer les orgas : " + statusText);
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
			_orga['confiance'] = this.data[_iOrga]['confiance'];
			
			// de la date de naissance
			_orga['dateDeNaissance'] = new Date(this.data[_iOrga]['dateDeNaissance']);
			
			console.log(this.data);
			
			// disponibilités, cela devient physique
			for (var _iDispo in this.data[_iOrga]['disponibilites']) {
				// TODO : hack à virer
				if (this.data[_iOrga]['disponibilites'][_iDispo] === null) {
					console.error('On a vu un null !');
					break;	
				}
				
				// créneaux, encore un niveau
				for (var _iCreneau in this.data[_iOrga]['disponibilites'][_iDispo]['creneaux']) {
					this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut']);
					this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin']);
				}
				
				this.data[_iOrga]['disponibilites'][_iDispo]['debut'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['debut']);
				this.data[_iOrga]['disponibilites'][_iDispo]['fin'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['fin']);
			}
		
			_orgas[_iOrga] = _orga;
		}
		
		return _orgas;
	}
}
