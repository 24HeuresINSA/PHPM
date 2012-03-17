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
		
		// si aucun orga n'est sélectionné, on choisit le 1er
		if (pmAffectation.current.orga.id == -1 && Object.keys(pmAffectation.data.orga)[0]  !== undefined) {
			pmAffectation.current.orga.id = Object.keys(pmAffectation.data.orga)[0];
			
			pmHistory.setUrlParam(); // maj de l'url
		}
		
		pmAffectation.views.orga.setOrgas();
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga.id});
		
		// force la mise à jour des créneaux - tous ceux pouvant aller à cet orga
		pmAffectation.controllers.creneau.getData();
	},
	
	/*
	 * Handlers
	 */
	// clic sur un orga
	clickHandler: function(obj) {
		$("#orga_"+pmAffectation.current.orga.id).removeClass('current');
		$("#orga_"+obj.id).addClass('current');
		
		pmAffectation.controllers.creneau.empty(); // vide la colonne creneau
		
		// on de-set le quart d'heure et le jour
		pmAffectation.controllers.calendar.resetDateHeure();

		pmAffectation.current.orga.id = obj.id;
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga.id});
	},
	// changement de la valeur d'un filtre
	clickFilter: function(nomFiltre, valeurFiltre) {
		pmAffectation.current.orga[nomFiltre] = valeurFiltre;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.orga.getData();
	},

}
