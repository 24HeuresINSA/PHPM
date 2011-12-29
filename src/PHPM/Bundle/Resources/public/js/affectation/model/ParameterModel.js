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
		//  TODO lance les requêtes Ajax
		console.log('lance les requêtes...');
		
		// Mock code
		pmAffectation.controllers.parameter.callbackNiveaux();
		pmAffectation.controllers.parameter.callbackCategories();
		pmAffectation.controllers.parameter.callbackPlages();
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère les niveaux de confiance des orgas
	getNiveaux: function() {
		var tab = {
					'1': {nom: 'hard', couleur: '#333'},
					'2': {nom: 'soft', couleur: '#888'}
					};
				
		return tab;
	},
	// récupère les plages (de jours) définies
	getPlages: function() {
		var tab = {
					'1': {nom: 'pré-manif', jour_debut: new Date('2012-05-23'), jour_fin: new Date('2012-05-26')},
					'2': {nom: 'manif', jour_debut: new Date('2012-05-27'), jour_fin: new Date('2012-05-28')},
					'0': {nom: 'postmanif', jour_debut: new Date('2012-05-29'), jour_fin: new Date('2012-05-29')}
					};
				
		return tab;
	},
	// récupère les catégories des tâches
	getCategories: function() {
		var tab = {
					'12': {nom: 'securité'},
					'23': {nom: 'culture'}
					};
				
		return tab;
	}
}
