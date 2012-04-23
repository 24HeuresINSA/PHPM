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
		
		// construit les paramètres que l'on va envoyer, -1 est le wildcart
		var _params = {
			plage_id: pmAffectation.current.plage, // on fournit toujours la plage, la base
		};
		(typeof(pmAffectation.current.jour) !== 'undefined') && (pmAffectation.current.jour != -1) && (_params.jour = pmAffectation.current.jour);
		(typeof(pmAffectation.current.quart_heure) !== 'undefined') && (pmAffectation.current.quart_heure != -1) && (_params.date_time = pmUtils.getDateTimeBack(pmAffectation.current.quart_heure));
		($.isNumeric(pmAffectation.current.orga.id) === true) && (pmAffectation.current.orga.id != -1) && (_params.orga_id = pmAffectation.current.orga.id);
		
		// filtres
		($.isNumeric(pmAffectation.current.creneau.confiance) === true) && (pmAffectation.current.creneau.confiance != -1) && (_params.confiance_id = pmAffectation.current.creneau.confiance);
		($.isNumeric(pmAffectation.current.creneau.duree) === true) && (pmAffectation.current.creneau.duree != -1) && (_params.duree = pmAffectation.current.creneau.duree);
		($.isNumeric(pmAffectation.current.creneau.equipe) === true) && (pmAffectation.current.creneau.equipe != -1) && (_params.equipe_id = pmAffectation.current.creneau.equipe);
		($.isNumeric(pmAffectation.current.creneau.permis) === true) && (pmAffectation.current.creneau.permis != -1) && (_params.permis = pmAffectation.current.creneau.permis);
		
		$.ajax({
			url: pmAffectation.urls.creneaux,
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
		// attention, on reçoit un tableau (l'ordre compte)
		
		pmAffectation.models.creneau.data = data;;
	
		pmAffectation.models.creneau.callBack();
	},
	requestError: function(data, statusText) {
		pmMessage.alert("Impossible de faire l'opération : " + statusText);
	},
	
	/*
	 * Getters des résultats
	 */
	getCreneaux: function() {	
		var _creneaux = [];
		
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
			
			_creneaux.push(_creneau);
		}
		
		return _creneaux;
	},
}
