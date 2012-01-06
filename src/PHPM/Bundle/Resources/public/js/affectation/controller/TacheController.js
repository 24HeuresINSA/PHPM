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
		pmAffectation.models.tache = new TacheModel();
		pmAffectation.views.tache = new TacheView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		$('#liste_taches').empty();
		$('#liste_taches').addClass('spinner_medium');
		
		pmAffectation.models.tache.getData(pmAffectation.controllers.tache.callbackTaches);
	},
	
	/*
	 * Callbacks
	 */
	callbackTaches: function() {
		/*pmAffectation.data.orga = pmAffectation.models.orga.getOrgas();
		
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
		pmUtils.setUrlParam(); // maj de l'url
		
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga});*/
	},

}
