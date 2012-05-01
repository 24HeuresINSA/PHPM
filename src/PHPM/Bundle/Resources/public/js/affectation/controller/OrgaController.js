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
		pmAffectation.data.orgas = {};
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
		pmAffectation.data.orgas = pmAffectation.models.orga.getOrgas();
		
		// si aucun orga n'est sélectionné, on choisit le 1er
		if (pmAffectation.current.mode === 'orga' && pmAffectation.current.orga.id == -1 && pmAffectation.data.orgas[0]  !== undefined) {
			pmAffectation.current.orga.id = pmAffectation.data.orgas[0]['id'];
			
			pmHistory.setUrlParam(); // maj de l'url
		}
		
		pmAffectation.views.orga.setOrgas();
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga.id});
		
		// force la mise à jour des créneaux - tous ceux pouvant aller à cet orga
		(pmAffectation.current.mode === 'orga') && (pmAffectation.controllers.creneau.getData());
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
		pmAffectation.controllers.calendar.resetDateHeure(true);

		pmAffectation.current.orga.id = obj.id;
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.creneau.getData(); // récupère les taches à jour
		
		pmAffectation.views.calendar.setFrees({type: 'orga', id: pmAffectation.current.orga.id});
	},
	// changement de la valeur d'un filtre
	clickFilter: function(nomFiltre, valeurFiltre) {
		pmAffectation.current.orga[nomFiltre] = valeurFiltre;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.orga.getData();
	},
	// handler du champ de recherche, filtre la liste des orgas avec la str passée
	filterList: function(str) {
		str = pmUtils.removeDiacritics(str); // retire les accents
		
		var _orgas = pmUtils.filter(pmAffectation.data.orgas, function(key, val) {
			// on recherche sur les champs nom, prénom, et surnom (test leur existence)
			return (
				(val.nom && pmUtils.removeDiacritics(val.nom.substr(0, str.length).toLowerCase()) == str) || 
				(val.prenom && pmUtils.removeDiacritics(val.prenom.substr(0, str.length).toLowerCase()) == str) || 
				(val.surnom && pmUtils.removeDiacritics(val.surnom.substr(0, str.length).toLowerCase()) == str)
			);
		});
		
		if ($.isEmptyObject(_orgas) === true) {
			$('#liste_orgas').html('<div class="alert">Aucun orga correspondant !</div>');
		} else {
			pmAffectation.views.orga.setOrgas(_orgas);
		}
	},
	
	/*
	 * Vide la colonne
	 */
	empty: function() {
		pmAffectation.data.orgas = {};
		
		$('#liste_orgas').empty();
	},

}
