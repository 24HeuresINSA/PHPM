/*
 * Page Affectation
 * Modèle Créneau
 */

function CreneauModel() {
	// on lance juste le constructeur
	this.initialize();
}

CreneauModel.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
	},
	
	/*
	 * Lance les requêtes
	 */
	getData: function(callBack) {
		pmAffectation.models.creneau.callBack = callBack;
		
		// TODO : compléter
		// construit les paramètres que l'on va envoyer, teste si déjà on a les filtres
		var _params = {
			plage_id: pmAffectation.current.plage, // on fournit toujours la plage, la base
		}; // -1 est le wildcart
		//(($.isNumeric(pmAffectation.current.confiance) === true) && (pmAffectation.current.confiance != -1)) && (_params.confiance_id = pmAffectation.current.confiance);
		//(($.isNumeric(pmAffectation.current.permis) === true) && (pmAffectation.current.permis != -1)) && (_params.permis = pmAffectation.current.permis);
		
		$.ajax({
			url: pmAffectation.url+pmAffectation.paths.creneaux,
			dataType: 'json',
			data: _params,
			success: pmAffectation.models.creneau.requestSuccess,
			error: pmAffectation.models.creneau.requestError,
			type: 'POST'
		});
	},
	
	/*
	 * Récup les résultats
	 */
	requestSuccess: function(data) {
		pmAffectation.models.creneau.data = data;;
	
		pmAffectation.models.creneau.callBack();
	},
	requestError: function(data, statusText) {
		message.error("Impossible de récupérer les créneaux : " + statusText);
	},
	
	/*
	 * Getters des résultats
	 */
	getCreneaux: function() {	
		var _creneaux = {};
		
		// traitement des orgas
		for (var _iCreneau in this.data) {
			var _creneau = {};
			
			// récupère toutes les données
			for (var _iChamp in this.data[_iCreneau]) {
				_creneau[_iChamp] = this.data[_iCreneau][_iChamp];
			}
			
			// re-traitement des dates
			_creneau['debut'] = new Date(_creneau['debut']['date']);
			_creneau['fin'] = new Date(this.data[_iCreneau]['fin']['date']);
			
			_creneaux[_iCreneau] = _creneau;
		}
		
		return _creneaux;
	}
}
