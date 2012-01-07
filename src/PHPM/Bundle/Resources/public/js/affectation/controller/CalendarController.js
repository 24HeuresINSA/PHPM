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
	clickQuartHeure: function(obj) {
		console.log(obj.data.date);
		
		// on lance le bouzin, va chercher les creneaux
		// TODO : passer paramètre
		pmAffectation.controllers.tache.getData();
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
		pmAffectation.controllers.orga.getData(); // mise à jour de l'orga également
		pmAffectation.controllers.tache.empty(); // vide la colonne creneau
		
		pmUtils.setUrlParam(); // maj de l'url
	}
}
