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
		pmAffectation.models.orga.getData(pmAffectation.controllers.orga.callbackOrgas);
	},
	
	/*
	 * Callbacks
	 */
	callbackOrgas: function() {
		pmAffectation.data.orga = pmAffectation.models.orga.getOrgas();
		
		pmAffectation.views.orga.setOrgas(pmAffectation.current.orga);
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga});
	},
	
	/*
	 * Handlers
	 */
	// clic sur un orga
	clickHandler: function(obj) {
		$("#orga_"+pmAffectation.current.orga).removeClass('current');
		$("#orga_"+obj.data.id).addClass('current');

		pmAffectation.current.orga = obj.data.id;
		pmUtils.setUrlParam(); // maj de l'url
		
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga});
	},
	// clic sur le filtre niveau de confiance
	clickFilterConfiance: function(idNiveau) {
		pmAffectation.current.confiance = idNiveau;
		
		pmUtils.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.orga.getData();
	},
	// click sur le filtre permis
	clickFilterPermis: function(idPermis) {
		pmAffectation.current.permis = idPermis;
		
		pmUtils.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.orga.getData();
	},	

}
