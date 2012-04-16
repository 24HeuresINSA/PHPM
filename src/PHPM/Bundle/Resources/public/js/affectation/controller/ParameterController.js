/*
 * Page Affectation
 * Controlleur Paramètres
 */

function ParameterController() {
	// on lance juste le constructeur
	this.initialize();
}

ParameterController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.parameter = {};
		pmAffectation.models.parameter = new ParameterModel(); // création du modèle
		
		// récupère d'éventuels paramètres de localStorage et les applique
		this.getLocalStorageParams();		
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		pmAffectation.models.parameter.getData(
			pmAffectation.controllers.parameter.callbackCategories,
			pmAffectation.controllers.parameter.callbackNiveaux,
			pmAffectation.controllers.parameter.callbackPermis
			);
	},
	
	/*
	 * Callbacks
	 */
	callbackNiveaux: function() {
		pmAffectation.data.parameter.niveau = pmAffectation.models.parameter.getNiveaux();
		
		// ajoute les options correspondantes dans les menus déroulants
		for (_iNiveau in pmAffectation.data.parameter.niveau) {
			$('#filtre_orga_confiance').append('<option value="'+_iNiveau+'">Orga '+pmAffectation.data.parameter.niveau[_iNiveau]['nom']+'</option>');
			$('#filtre_tache_confiance').append('<option value="'+_iNiveau+'">Orga '+pmAffectation.data.parameter.niveau[_iNiveau]['nom']+'</option>');
		}
	},
	callbackPermis: function() {
		pmAffectation.data.parameter.permis = pmAffectation.models.parameter.getPermis();
		
		// ajoute les options correspondantes dans les menu déroulants
		for (_iPermis in pmAffectation.data.parameter.permis) {
			$('#filtre_orga_permis').append('<option value="'+_iPermis+'">'+pmAffectation.data.parameter.permis[_iPermis]+'</option>');
			$('#filtre_tache_permis').append('<option value="'+_iPermis+'">'+pmAffectation.data.parameter.permis[_iPermis]+'</option>');
		}
	},
	callbackCategories: function() {
		pmAffectation.data.parameter.categorie = pmAffectation.models.parameter.getCategories();
		
		// ajoute les options correspondantes dans le menu déroulant
		for (_iCategorie in pmAffectation.data.parameter.categorie) {
			$('#filtre_tache_categorie').append('<option value="'+_iCategorie+'">'+pmAffectation.data.parameter.categorie[_iCategorie].nom+'</option>');
		}
	},
	
	/*
	 * Récupère d'éventuels paramètres de localStorage et les applique
	 */
	getLocalStorageParams: function() {
		// taille des sidebars
		var _ssOrga = pmUtils.getLocalStorage('SizeSidebarOrga');
		if (_ssOrga !== undefined) {
			pmUtils.resizeCalendar($('#sidebar_orga').width()-_ssOrga);
			
			$('#sidebar_orga').width(_ssOrga);
			pmUtils.setPourcentWidth('#sidebar_orga');
		}
		
		var _ssTache = pmUtils.getLocalStorage('SizeSidebarTache');
		if (_ssTache !== undefined) {
			pmUtils.resizeCalendar($('#sidebar_tache').width()-_ssTache);
			
			$('#sidebar_tache').width(_ssTache);
			pmUtils.setPourcentWidth('#sidebar_tache');
		}
	}
	
}
