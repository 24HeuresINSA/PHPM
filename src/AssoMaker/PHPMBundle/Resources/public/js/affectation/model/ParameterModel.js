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
	getData: function(callbackEquipes, callbackNiveaux) {
		pmAffectation.models.parameter.callbackEquipes = callbackEquipes;
		pmAffectation.models.parameter.callbackNiveaux = callbackNiveaux;
		
		// pour les équipes
		$.ajax({
			url: pmAffectation.urls.equipes,
			dataType: 'json',
			success: pmAffectation.models.parameter.requestSuccessEquipes,
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
	},
	
	/*
	 * Récup les résultats
	 */
	requestSuccessEquipes: function(data) {
		pmAffectation.models.parameter.data.equipes = data;
	
		pmAffectation.models.parameter.callbackEquipes();
	},
	requestSuccessNiveaux: function(data) {
		pmAffectation.models.parameter.data.niveaux = data;
	
		pmAffectation.models.parameter.callbackNiveaux();
	},
	requestError: function(data, statusText) {
		pmMessage.alert("Impossible de récupérer les paramètres : " + statusText);
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère les catégories des tâches
	getEquipes: function() {
		return pmAffectation.models.parameter.data.equipes;
 	},
	// récupère les niveaux de confiance des orgas
	getNiveaux: function() {
		return pmAffectation.models.parameter.data.niveaux;
	}
}
