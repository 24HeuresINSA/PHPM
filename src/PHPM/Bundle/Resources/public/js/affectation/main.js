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
	pmHistory = new PmHistory();
	pmMessage = new PmMessage();
	
	// stockage des données
	pmAffectation.data = {};
	
	// infos courantes
	pmAffectation.current = {}; // sera stocké dans l'url
	// bien mettre des valeurs par défaut aux paramètres
	pmAffectation.current.orga = {id: -1};
	pmAffectation.current.plage = 1; // par défaut on est sur la plage 0
	pmAffectation.current.quart_heure = -1; // wildcart
	pmAffectation.current.creneau = {};
	pmAffectation.current.tache = {id: -1};
	pmAffectation.current.mode = 'orga';
	
	// log de toutes les affectations/désaffectations réalisées
	pmAffectation.journal = [];
	
	// les MVC
	pmAffectation.models = {};
	pmAffectation.views = {};
	pmAffectation.controllers = {};
	
/*
 * CONSTANTES
 */
	// les urls sur lesquelles ont fait les requêtes
	// sont passées par la page sur laquelle on est
	// (dans un <script>)
	
/*
 * Lancement
 * Effectif que quand le document est prêt
 */
$(document).ready(function() {
	$('#client').addClass('spinner_large'); // au cas où le chargement soit vraiment très très long
	
	// 0 : setter le layout et récupérer les paramètres dans l'Url
	pmUtils.setResizeableSidebars();
	// 0.5 : travail sur l'historique
	pmHistory.initHistoryListener(); 
	pmHistory.parseUrlParam(); // un peu bizarre : parfois faut le faire, parfois pas
	
	// 1 : lancer les requêtes pour les paramètres
	// requêtes synchrones car nécessaire partout dans l'appli
	pmAffectation.controllers.parameter = new ParameterController();
	pmAffectation.controllers.parameter.getData();
	
	// 2 : setter le calendar
	// on récupère les plages via une requête synchrone
	pmAffectation.controllers.calendar = new CalendarController();
	pmAffectation.controllers.calendar.getData();
	// lorsque la vue calendrier est settée, il va redimensionner l'appli et retirer le spinner
	
	// 3 : on set le mode
	// et cela va appeler les bons contrôlleurs, orga & créneau ou tache & orga
	pmAffectation.views.calendar.initMode();
	pmUtils.setMode(pmAffectation.current.mode);
	
	// last step : à partir de maintenant, les modifs du hash provoquent la mise à jour des données
	pmHistory.refreshData = true;
	pmHistory.parseUrlParam(); // il faut le relancer, juste pour le quart d'heure
});