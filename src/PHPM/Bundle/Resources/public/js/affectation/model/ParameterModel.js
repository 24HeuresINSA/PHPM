/*
 * Page Affectation
 * Modèle Paramètres
 */

function ParameterModel() {
	// on lance juste le constructeur
	this.initialize();
}

ParameterModel.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		this.data = {};
	},
	
	/*
	 * Lance les requêtes
	 */
	getData: function(callbackCategories, callbackNiveaux) {
		pmAffectation.models.parameter.callbackCategories = callbackCategories;
		pmAffectation.models.parameter.callbackNiveaux = callbackNiveaux;
		
		// pour les catégories
		$.ajax({
			url: pmAffectation.url+pmAffectation.paths.categories,
			dataType: 'json',
			success: pmAffectation.models.parameter.requestSuccessCategories,
			error: pmAffectation.models.parameter.requestError,
			type: 'GET',
			async: false
		});
		
		// pour les niveaux de confiance
		$.ajax({
			url: pmAffectation.url+pmAffectation.paths.niveaux,
			dataType: 'json',
			success: pmAffectation.models.parameter.requestSuccessNiveaux,
			error: pmAffectation.models.parameter.requestError,
			type: 'GET',
			async: false
		});
	},
	
	/*
	 * Récup les résultats
	 */
	requestSuccessCategories: function(data) {
		pmAffectation.models.parameter.data.categories = data;
	
		pmAffectation.models.parameter.callbackCategories();
	},
	requestSuccessNiveaux: function(data) {
		pmAffectation.models.parameter.data.niveaux = data;
	
		pmAffectation.models.parameter.callbackNiveaux();
	},
	requestError: function(data, statusText) {
		message.error("Impossible de récupérer les paramètres : " + statusText);
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère les catégories des tâches
	getCategories: function() {
		var tab = {
					'12': {nom: 'securité'},
					'23': {nom: 'culture'}
					};
				
		return tab;
 	},
	// récupère les niveaux de confiance des orgas
	getNiveaux: function() {
		var tab = {
					'1': {nom: 'hard', couleur: '#333'},
					'2': {nom: 'soft', couleur: '#888'}
					};
				
		return tab;
	}
}
