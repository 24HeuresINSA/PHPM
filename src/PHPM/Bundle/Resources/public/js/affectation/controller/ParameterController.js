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
			pmAffectation.controllers.parameter.callbackEquipes,
			pmAffectation.controllers.parameter.callbackNiveaux
			);
	},
	
	/*
	 * Callbacks
	 */
	callbackNiveaux: function() {
		pmAffectation.data.parameter.confiance = pmAffectation.models.parameter.getNiveaux();
		
		// ajoute les options correspondantes dans les menus déroulants
		for (_iNiveau in pmAffectation.data.parameter.confiance) {
			$('#filtre_orga_confiance').append('<option value="'+_iNiveau+'">Orga '+pmAffectation.data.parameter.confiance[_iNiveau]['nom']+'</option>');
			$('#filtre_tache_confiance').append('<option value="'+_iNiveau+'">Orga '+pmAffectation.data.parameter.confiance[_iNiveau]['nom']+'</option>');
		}
	},
	callbackEquipes: function() {
		pmAffectation.data.parameter.equipes = pmAffectation.models.parameter.getEquipes();
		
		// ajoute les options correspondantes dans le menu déroulant
		for (_iEquipe in pmAffectation.data.parameter.equipes) {
			$('#filtre_orga_equipe').append('<option value="'+_iEquipe+'">'+pmAffectation.data.parameter.equipes[_iEquipe]['nom']+'</option>');
			$('#filtre_tache_equipe').append('<option value="'+_iEquipe+'">'+pmAffectation.data.parameter.equipes[_iEquipe]['nom']+'</option>');
		}
	},
	
	/*
	 * Récupère d'éventuels paramètres de localStorage et les applique
	 */
	getLocalStorageParams: function() {
		// taille des sidebars
		var _ssOrga = pmUtils.getLocalStorage('SizeSidebarOrga');
		if (_ssOrga !== undefined) {
			$('#sidebar_orga').width(_ssOrga);
		}
		
		var _ssTache = pmUtils.getLocalStorage('SizeSidebarTache');
		if (_ssTache !== undefined) {			
			$('#sidebar_tache').width(_ssTache);
		}
		
		pmLayout.resizeCalendar();
	}
	
}
