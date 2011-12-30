/*
 * Script de la page Affectation orga -> tâches
 * Script principal, lancé en 1er
 */

/*
 * Globals
 */
	pmAffectation = {}; // namespace
	
	// utilitaires
	pmUtils = new PmUtils();
	
	// stockage des données
	pmAffectation.data = {};
	
	// les URLS
	pmAffectation.paths = {};
	
	// infos courantes
	pmAffectation.current = {};
	pmAffectation.current.plage = 1; // par défaut on est sur la plage 0
	
	// les MVC
	pmAffectation.models = {};
	pmAffectation.views = {};
	pmAffectation.controllers = {};
	
/*
 * CONSTANTES
 */
	// les urls sur lesquelles on fera les requêtes
	pmAffectation.paths.plages = 'config/get/manifestation.plages';
	
/*
 * Lancement
 * Effectif que quand le document est prêt
 */
$(document).ready(function() {	
	// 0 : setter le layout
	pmUtils.setResizeableSidebars();
	
	// 1 : lancer les requêtes pour les paramètres
	pmAffectation.controllers.parameter = new ParameterController();
	pmAffectation.controllers.parameter.getData();
	
	// 2 : setter le calendar
	pmAffectation.controllers.calendar = new CalendarController();
	pmAffectation.controllers.calendar.getData();
	
	// 3 : mettre en place les éléments
});