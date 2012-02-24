/*
 * Page Affectation
 * Controlleur pour les créneaux
 * 
 * Une chsoe à savoir : on bosse dans un colonne qui s'appelle "liste_taches"
 */
function CreneauController() {
	// on lance juste le constructeur
	this.initialize();
}

CreneauController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.creneau = {};

		pmAffectation.models.creneau = new CreneauModel();
		pmAffectation.views.creneau = new CreneauView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		$('#liste_taches').empty();
		$('#liste_taches').addClass('spinner_medium');
		
		pmAffectation.models.creneau.getData(pmAffectation.controllers.creneau.callbackCreneaux);
	},
	
	/*
	 * Callbacks
	 */
	// pour les créneaux
	callbackCreneaux: function() {
		pmAffectation.data.creneaux = pmAffectation.models.creneau.getCreneaux();
		
		pmAffectation.views.creneau.setCreneaux();
	},
	
	/*
	 * Handlers
	 */
	// clic sur un creneau
	clickHandler: function(obj) {
		/*$("#orga_"+pmAffectation.current.orga).removeClass('current');
		$("#orga_"+obj.data.id).addClass('current');

		pmAffectation.current.orga = obj.data.id;
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga});*/
	},
	
	/*
	 * Vide la colonne
	 */
	empty: function() {
		pmAffectation.data.creneaux = {};
		
		$('#liste_taches').empty();
	},

}
