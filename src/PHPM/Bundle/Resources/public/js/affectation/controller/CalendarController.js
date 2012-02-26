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
		// on met une petite classe current, qui indique où on se trouve
		(pmAffectation.current.quart_heure != -1) && ($('#'+pmAffectation.current.quart_heure).removeClass('current')); // si existe bien
		$('#'+obj.currentTarget.id).addClass('current');
		pmAffectation.current.quart_heure = obj.currentTarget.id;
		pmHistory.setUrlParam(); // maj de l'url
		
		// on va chercher les creneaux
		pmAffectation.controllers.creneau.getData();
	},
	// clic sur un créneau
	clickCreneau: function(obj) {		
		// on demande à la vue un joli popup avec les détails
		pmAffectation.views.calendar.showDetails(obj);
	},
	// clic sur les boutons pour changer de plage
	changePlage: function(plageId) {
		pmAffectation.current.plage = plageId;
		
		pmAffectation.views.calendar.setPlage(plageId);
		pmAffectation.controllers.orga.getData(); // mise à jour de l'orga également
		pmAffectation.controllers.creneau.empty(); // vide la colonne creneau - deviendra peut-être inutile si on prend un orga par défaut
		
		pmHistory.setUrlParam(); // maj de l'url
	},
}
