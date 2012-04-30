/*
 * Page Affectation
 * Modèle Tache
 */

function TacheModel() {
	// on lance juste le constructeur
	this.initialize();
}

TacheModel.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
	},
	
	/*
	 * Lance les requêtes
	 */
	getData: function(callBack) {
		pmAffectation.models.tache.callBack = callBack;
		
		// construit les paramètres que l'on va envoyer, -1 est le wildcart
		var _params = {
			plage_id: pmAffectation.current.plage, // on fournit toujours la plage, la base
		};
		
		// filtres
		// TODOs
		//($.isNumeric(pmAffectation.current.tache.confiance) === true) && (pmAffectation.current.tache.confiance != -1) && (_params.confiance_id = pmAffectation.current.tache.confiance);
		($.isNumeric(pmAffectation.current.tache.duree) === true) && (pmAffectation.current.tache.duree != -1) && (_params.duree = pmAffectation.current.tache.duree);
		//($.isNumeric(pmAffectation.current.tache.categorie) === true) && (pmAffectation.current.tache.categorie != -1) && (_params.categorie_id = pmAffectation.current.tache.categorie);
		($.isNumeric(pmAffectation.current.tache.permis) === true) && (pmAffectation.current.tache.permis != -1) && (_params.permis = pmAffectation.current.tache.permis);
		
		$.ajax({
			url: pmAffectation.urls.taches,
			dataType: 'json',
			data: _params,
			success: pmAffectation.models.tache.requestSuccess,
			error: pmAffectation.models.tache.requestError,
			type: 'POST'
		});
	},
	
	/*
	 * Récup les résultats
	 */
	requestSuccess: function(data) {
		// attention, on reçoit un tableau (l'ordre compte)
		
		pmAffectation.models.tache.data = data;
	
		pmAffectation.models.tache.callBack();
	},
	requestError: function(data, statusText) {
		pmMessage.alert("Impossible de récupérer les tâches : " + statusText);
	},
	
	/*
	 * Getters des résultats
	 */
	getTaches: function() {	
		var _taches = {};
		
		// traitement des taches
		for (var _iTache in this.data) {
			var _tache = {};
			
			// récupère toutes les données
			for (var _iChamp in this.data[_iTache]) {
				_tache[_iChamp] = this.data[_iTache][_iChamp];
			}
			
			// re-traitement des dates
			for (var _iCreneau in _tache['creneaux']) {
				_tache['creneaux'][_iCreneau]['debut'] = new Date(_tache['creneaux'][_iCreneau]['debut']['date']);
				_tache['creneaux'][_iCreneau]['fin'] = new Date(_tache['creneaux'][_iCreneau]['fin']['date']);
				
				// TODO : donner des couleurs aux créneaux
			}
			
			_taches[_iTache] = _tache;
		}
		
		return _taches;
	},

}
