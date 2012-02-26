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
		
		// construit les paramètres que l'on va envoyer, teste si déjà on a les filtres
		var _params = {
			plage_id: pmAffectation.current.plage, // on fournit toujours la plage, la base
		}; // -1 est le wildcart
		(pmAffectation.current.quart_heure != -1) && (_params.date_time = pmUtils.getDateBack(pmAffectation.current['quart_heure']));
		(($.isNumeric(pmAffectation.current.orga.id) === true) && (pmAffectation.current.orga.id != -1)) && (_params.orga_id = pmAffectation.current.orga.id);

		// filtre confiance
		// filtre durée
		// filtre catégorie
		// filtre age minimal
		// filtre permis
				
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
		message.error("Impossible de faire l'opération : " + statusText);
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
	},

	/*
	 * Réalise la (dés)affectation entre un créneau et un orga
	 * @param 'sens' : 'affecter' ou 'desaffecter'
	 */
	affecterCreneau: function(sens, idCreneau, idOrga, callBack) {
		pmAffectation.models.creneau.callBackAffectation = callBack;
		
		$.ajax({
			url: pmAffectation.url+'creneau/'+idCreneau+'/'+sens+'/'+idOrga,
			dataType: 'text',
			success: pmAffectation.models.creneau.affectationSuccess,
			error: pmAffectation.models.creneau.requestError,
			type: 'POST'
		});	
	},
	// les callbacks
	affectationSuccess: function(data) {
		// on test ce qui le serveur nous a retourné
		if (data == "OK") {
			pmAffectation.models.creneau.callBackAffectation();
		} else {
			message.error("Impossible de réaliser l'opération : "+data);
		}
	},
}
