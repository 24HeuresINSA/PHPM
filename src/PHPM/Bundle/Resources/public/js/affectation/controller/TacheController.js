/*
 * Page Affectation
 * Controlleur pour les tâches
 */
function TacheController() {
	// on lance juste le constructeur
	this.initialize();
}

TacheController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.tache = {};
		//pmAffectation.models.tache = new TacheModel(); // TODO : finir
		pmAffectation.models.creneau = new CreneauModel();
		pmAffectation.views.tache = new TacheView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		$('#liste_taches').empty();
		$('#liste_taches').addClass('spinner_medium');
		
		pmAffectation.models.creneau.getData(pmAffectation.controllers.tache.callbackCreneaux);
	},
	
	/*
	 * Callbacks
	 */
	// pour les créneaux
	callbackCreneaux: function() {
		pmAffectation.data.creneaux = pmAffectation.models.creneau.getCreneaux();
		
		pmAffectation.views.tache.setCreneaux();
	},
	// pour les taches
	callbackTaches: function() {
		/*pmAffectation.data.taches = pmAffectation.models.orga.getOrgas();
		
		pmAffectation.views.orga.setOrgas(pmAffectation.current.orga);
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga});*/
	},
	
	/*
	 * Handlers
	 */
	// clic sur une tache
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
