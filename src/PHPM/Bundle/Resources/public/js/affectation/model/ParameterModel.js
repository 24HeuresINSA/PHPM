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
	getData: function(callbackCategories, callbackNiveaux, callbackPermis) {
		pmAffectation.models.parameter.callbackCategories = callbackCategories;
		pmAffectation.models.parameter.callbackNiveaux = callbackNiveaux;
		pmAffectation.models.parameter.callbackPermis = callbackPermis;
		
		// pour les catégories
		$.ajax({
			url: pmAffectation.urls.categories,
			dataType: 'json',
			success: pmAffectation.models.parameter.requestSuccessCategories,
			error: pmAffectation.models.parameter.requestError,
			type: 'GET',
			async: false
		});
		
		// pour les niveaux de confiance
		$.ajax({
			url: pmAffectation.urls.niveaux,
			dataType: 'json',
			success: pmAffectation.models.parameter.requestSuccessNiveaux,
			error: pmAffectation.models.parameter.requestError,
			type: 'GET',
			async: false
		});
		
		// pour les niveaux de permis
		$.ajax({
			url: pmAffectation.urls.permis,
			dataType: 'json',
			success: pmAffectation.models.parameter.requestSuccessPermis,
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
	requestSuccessPermis: function(data) {
		pmAffectation.models.parameter.data.permis = data;
	
		pmAffectation.models.parameter.callbackPermis();
	},
	requestError: function(data, statusText) {
		pmMessage.alert("Impossible de récupérer les paramètres : " + statusText);
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère les catégories des tâches
	getCategories: function() {
		return pmAffectation.models.parameter.data.categories;
 	},
	// récupère les niveaux de confiance des orgas
	getNiveaux: function() {
		return pmAffectation.models.parameter.data.niveaux;
	},
	// récupère les permis
	getPermis: function() {
		return pmAffectation.models.parameter.data.permis;
	}
}
