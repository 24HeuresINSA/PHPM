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
		
		pmAffectation.views.orga.setOrgas(pmAffectation.current.orga);
		//pmAffectation.views.calendar.setOrga(pmAffectation.current.orga);
	},
	
	/*
	 * Handlers
	 */
	// clic sur un orga
	click: function(obj) {
		console.log(obj);

		pmAffectation.current.orga = Object.data.id;
		pmUtils.setUrlParam(); // maj de l'url
	},

}
