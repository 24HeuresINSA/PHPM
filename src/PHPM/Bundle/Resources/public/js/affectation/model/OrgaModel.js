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
				
		// construit les paramètres que l'on va envoyer, -1 est le wildcart
		var _params = {
			plage_id: pmAffectation.current.plage, // on fournit toujours la plage, la base
		};
		
		// filtres
		($.isNumeric(pmAffectation.current.orga.confiance) === true) && (pmAffectation.current.orga.confiance != -1) && (_params.confiance_id = pmAffectation.current.orga.confiance);
		($.isNumeric(pmAffectation.current.orga.permis) === true) && (pmAffectation.current.orga.permis != -1) && (_params.annee_permis = pmAffectation.current.orga.permis);
		($.isNumeric(pmAffectation.current.orga.age) === true) && (pmAffectation.current.orga.age != -1) && (_params.age = pmAffectation.current.orga.age);
		($.isNumeric(pmAffectation.current.orga.equipe) === true) && (pmAffectation.current.orga.equipe != -1) && (_params.equipe_id = pmAffectation.current.orga.equipe);
		
		// filtres pour le sens tâches ->
		($.isNumeric(pmAffectation.current.tache.id) === true) && (pmAffectation.current.tache.id != -1) && (_params.equipe_id = pmAffectation.current.orga.equipe);

		$.ajax({
			url: pmAffectation.urls.orgas,
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
		// attention, on reçoit un tableau (l'ordre compte)
		
		pmAffectation.models.orga.data = data;
	
		pmAffectation.models.orga.callBack();
	},
	requestError: function(data, statusText) {
		pmMessage.alert("Impossible de récupérer les orgas : " + statusText);
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
			
			// de la date de naissance
			_orga['dateDeNaissance'] = new Date(this.data[_iOrga]['dateDeNaissance']);
			
			// disponibilités, cela devient physique
			for (var _iDispo in this.data[_iOrga]['disponibilites']) {
				// créneaux, encore un niveau
				for (var _iCreneau in this.data[_iOrga]['disponibilites'][_iDispo]['creneaux']) {
					_orga['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut']);
					_orga['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin']);
				
					_orga['disponibilites'][_iDispo]['creneaux'][_iCreneau]['couleur'] = pmUtils.hexToRgba(this.data[_iOrga]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['couleur'], 0.6);
				}
				
				_orga['disponibilites'][_iDispo]['debut'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['debut']);
				_orga['disponibilites'][_iDispo]['fin'] = new Date(this.data[_iOrga]['disponibilites'][_iDispo]['fin']);
			}
		
			_orgas[_iOrga] = _orga;
		}
		
		return _orgas;
	}
}
