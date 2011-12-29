/*
 * Script de la page Affectation orga -> tâches
 * Script principal, lancé en 1er
 */

// rend resizeable les blocs sur le côté
/* faire fonction de calcul des tailles
$(function() {
	$("#sidebar_orga").resizable({
							minWidth: 150,
							maxWidth: 400,
							alsoResize: '#calendar',
							autoHide: true,
							handles: 'e'
							});
	
	$("#sidebar_tache").resizable({
							minWidth: 150,
							maxWidth: 400,
							alsoResize: '#calendar',
							autoHide: true,
							handles: 'w'
							});
});
*/

/*
 * Globals
 */
	pmAffectation = {}; // namespace
	
	// stockage des données
	pmAffectation.data = {};
	
	// infos courantes
	pmAffectation.current = {};
	pmAffectation.current.plage = 1; // par défaut on est sur la plage 0
	
	// les MVC
	pmAffectation.models = {};
	pmAffectation.views = {};
	pmAffectation.controllers = {};
	
/*
 * Lancement
 * Effectif que quand le document est prêt
 */
$(document).ready(function() {
	// 1 : lancer les requêtes pour les paramètres
	pmAffectation.controllers.parameter = new ParameterController();
	pmAffectation.controllers.parameter.getData();
	
	// 2 : setter le calendar
	pmAffectation.controllers.calendar = new CalendarController();
	pmAffectation.controllers.calendar.getData();
	
	// 3 : mettre en place les éléments
});
	