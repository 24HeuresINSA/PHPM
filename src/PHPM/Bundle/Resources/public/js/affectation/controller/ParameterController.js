/*
 * Page Affectation
 * Controlleur Paramètres
 */

function ParameterController() {
	// on lance juste le constructeur
	this.initialize();
}

ParameterController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.parameter = {};
		pmAffectation.models.parameter = new ParameterModel(); // création du modèle
		pmAffectation.views.calendar = new CalendarView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		pmAffectation.models.parameter.getData();
	},
	
	/*
	 * Callbacks
	 */
	callbackNiveaux: function() {
		pmAffectation.data.parameter.niveau = pmAffectation.models.parameter.getNiveaux();
	},
	callbackCategories: function() {
		pmAffectation.data.parameter.categorie = pmAffectation.models.parameter.getCategories();
	},
	callbackPlages: function() {
		pmAffectation.data.parameter.plage = pmAffectation.models.parameter.getPlages();
		
		pmAffectation.views.calendar.setPlage(pmAffectation.current.plage);
	}
}
