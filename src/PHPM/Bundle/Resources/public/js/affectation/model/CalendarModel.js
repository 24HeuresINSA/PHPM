/*
 * Page Affectation
 * Modèle Calendar
 */

function CalendarModel() {
	// on lance juste le constructeur
	this.initialize();
}

CalendarModel.prototype = {
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
		console.log('lance les requêtes... 2');
		
		// Mock code
		pmAffectation.controllers.calendar.callbackPlages();
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère les plages (de jours) définies
	getPlages: function() {
		var tab = {
					'1': {nom: 'pré-manif', jour_debut: new Date('2012-05-23'), jour_fin: new Date('2012-05-26')},
					'2': {nom: 'manif', jour_debut: new Date('2012-05-27'), jour_fin: new Date('2012-05-28')},
					'0': {nom: 'postmanif', jour_debut: new Date('2012-05-29'), jour_fin: new Date('2012-05-29')}
					};
				
		return tab;
	}
}
