/*
 * Page Affectation
 * Controlleur pour les orgas
 */
function OrgaController() {
	// on lance juste le constructeur
	this.initialize();
}

OrgaController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.orga = {};
		pmAffectation.models.orga = new OrgaModel();
		pmAffectation.views.orga = new OrgaView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		pmAffectation.models.orga.getData(this.callbackOrgas);
	},
	
	/*
	 * Callbacks
	 */
	callbackOrgas: function() {
		pmAffectation.data.orga = pmAffectation.models.orga.getOrgas();
		
		//pmAffectation.views.orga.setPlage(pmAffectation.current.plage);
	},
	
	/*
	 * Handlers
	 */
	// clic sur un quart d'heure
	click: function(date_heure) {
		console.log(date_heure);

	},

}
