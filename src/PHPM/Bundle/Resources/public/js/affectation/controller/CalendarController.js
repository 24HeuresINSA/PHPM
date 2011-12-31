/*
 * Page Affectation
 * Controlleur du calendier central
 */
function CalendarController() {
	// on lance juste le constructeur
	this.initialize();
}

CalendarController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.calendar = {};
		pmAffectation.models.calendar = new CalendarModel();
		pmAffectation.views.calendar = new CalendarView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		pmAffectation.models.calendar.getData(pmAffectation.controllers.calendar.callbackPlages);
	},
	
	/*
	 * Callbacks
	 */
	callbackPlages: function() {
		pmAffectation.data.calendar.plage = pmAffectation.models.calendar.getPlages();
		
		pmAffectation.views.calendar.setPlage(pmAffectation.current.plage);
	},
	
	/*
	 * Handlers
	 */
	// clic sur un quart d'heure
	clickHandler: function(obj) {
		console.log(obj.data.date);
		
		// TODO : passe la demande au controlleur tâche qu'il se mette à jour
	},
	// clic sur un créneau
	clickCreneau: function(obj) {
		console.log(obj.data.creneauId);
		
		// afficher le détail ?
	},
	// clic sur les boutons pour changer de plage
	changePlage: function(plageId) {
		pmAffectation.current.plage = plageId;
		
		pmAffectation.views.calendar.setPlage(plageId);
		
		pmUtils.setUrlParam(); // maj de l'url
	}
}
