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
		$('#liste_orgas').empty();
		$('#liste_orgas').addClass('spinner_medium');
		
		pmAffectation.models.orga.getData(pmAffectation.controllers.orga.callbackOrgas);
	},
	
	/*
	 * Callbacks
	 */
	callbackOrgas: function() {
		pmAffectation.data.orga = pmAffectation.models.orga.getOrgas();
		
		pmAffectation.views.orga.setOrgas();
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga.id});
	},
	
	/*
	 * Handlers
	 */
	// clic sur un orga
	clickHandler: function(obj) {
		$("#orga_"+pmAffectation.current.orga.id).removeClass('current');
		$("#orga_"+obj.data.id).addClass('current');
		
		pmAffectation.controllers.tache.empty(); // vide la colonne creneau

		pmAffectation.current.orga.id = obj.data.id;
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga.id});
	},
	// clic sur le filtre niveau de confiance
	clickFilterConfiance: function(idNiveau) {
		pmAffectation.current.orga.confiance = idNiveau;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.orga.getData();
	},
	// click sur le filtre permis
	clickFilterPermis: function(idPermis) {
		pmAffectation.current.orga.permis = idPermis;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.orga.getData();
	},
	// click sur le filtre age
	clickFilterAge: function(idAge) {
		pmAffectation.current.orga.age = idAge;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.orga.getData();
	},	

}
