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
	// bien mettre des valeurs par défaut aux paramètres
	pmAffectation.current.orga = 1;
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
	pmAffectation.paths.orgas = 'orga/basicquery.json';
	
/*
 * Lancement
 * Effectif que quand le document est prêt
 */
$(document).ready(function() {	
	// 0 : setter le layout et récupérer les paramètres dans l'Url
	pmUtils.setResizeableSidebars();
	pmUtils.parseUrlParam();
	
	// 1 : lancer les requêtes pour les paramètres
	// requêtes synchrones car nécessaire partout dans l'appli
	pmAffectation.controllers.parameter = new ParameterController();
	pmAffectation.controllers.parameter.getData();
	
	// 2 : setter le calendar
	// on récupère lesp lages via une requête synchrone
	// comme c'est les 1ères dates qu'on a, on check le fuseau horaire de l'utilisateur
	pmAffectation.controllers.calendar = new CalendarController();
	pmAffectation.controllers.calendar.getData();
	
	// 2,5 : deuxième partie du layout
	// il fallait attendre que le calendrier soit chargé pour
	pmUtils.setAppHeight();
	
	// 3 : on va chercher pour la colonne orgas
	pmAffectation.controllers.orga = new OrgaController();
	pmAffectation.controllers.orga.getData();
});