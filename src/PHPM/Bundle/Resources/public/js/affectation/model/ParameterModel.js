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
	},
	
	/*
	 * Lance les requêtes
	 */
	getData: function() {
		//  TODO lance les requêtes Ajax - en synchrone
		console.log('lance les requêtes...');
		
		// Mock code
		pmAffectation.controllers.parameter.callbackNiveaux();
		pmAffectation.controllers.parameter.callbackCategories();
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
