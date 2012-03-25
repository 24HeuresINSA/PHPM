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
		pmAffectation.data.taches = pmAffectation.models.tache.getTaches();
		
		pmAffectation.views.tache.setTaches();
	},
	
	/*
	 * Handlers
	 */
	// clic sur une tache : affiche son planning
	// TODO
	// changement de la valeur d'un filtre
	clickFilter: function(nomFiltre, valeurFiltre) {
		pmAffectation.current.tache[nomFiltre] = valeurFiltre;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.tache.getData();
	},
	
	/*
	 * Vide la colonne
	 */
	empty: function() {
		pmAffectation.data.taches = {};
		
		$('#liste_taches').empty();
	},

}
