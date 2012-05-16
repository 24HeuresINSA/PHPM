/*
 * Page Affectation
 * Controlleur du calendier central
 */
function CalendarController() {
	// on lance juste le constructeur
	this.initialize();
}

CalendarController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.calendar = {};
		pmAffectation.models.calendar = new CalendarModel();
		pmAffectation.views.calendar = new CalendarView();
		
		// on s'occupe de lier le petit bouton en haut
		$('#bouton_reset').bind('click', this.resetDateHeure)
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		pmAffectation.models.calendar.getData(pmAffectation.controllers.calendar.callbackPlages);
	},
	
	/*
	 * Callbacks
	 */
	callbackPlages: function() {
		pmAffectation.data.calendar.plage = pmAffectation.models.calendar.getPlages();
		
		pmAffectation.views.calendar.setPlage(pmAffectation.current.plage);
	},
	
	/*
	 * Handlers
	 */
	// clic sur un jour (le titre en haut)
	clickJour: function(obj) {
		pmAffectation.current.jour = obj.data.date;
		
		// on de-set l'éventuel quart d'heure sélectionné
		(pmAffectation.current.quart_heure != -1) && ($('#'+pmAffectation.current.quart_heure).removeClass('current'));
		pmAffectation.current.quart_heure = -1;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		// on va chercher les creneaux
		pmAffectation.controllers.creneau.getData();
	},
	// clic sur un quart d'heure
	clickQuartHeure: function(obj) {
		// on met une petite classe current, qui indique où on se trouve
		(pmAffectation.current.quart_heure != -1) && ($('#'+pmAffectation.current.quart_heure).removeClass('current')); // si existe bien
		$('#'+obj.currentTarget.id).addClass('current');
		pmAffectation.current.quart_heure = obj.currentTarget.id;
		pmAffectation.current.jour = -1; // de-set le jout
		pmHistory.setUrlParam(); // maj de l'url
		
		// on va chercher les creneaux
		pmAffectation.controllers.creneau.getData();
	},
	// clic sur un créneau (mode Orga)
	clickCreneauOrga: function(obj) {		
		// on demande à la vue un joli popup avec les détails
		pmAffectation.views.calendar.showDetails(obj);
	},
	// clic sur un créneau (mode Tâche)
	clickCreneauTache: function(obj, e) {
		pmAffectation.controllers.orga.empty(); // vide la colonne orga

		if (obj.affecte == true || e.altKey) {
			// on demande à la vue un joli popup avec les détails
			pmAffectation.views.calendar.showDetails({data: obj});
		} else {
			pmAffectation.current.creneau.id = obj.idCreneau;
			pmHistory.setUrlParam(); // maj de l'url
			pmAffectation.controllers.orga.getData();
		}
	},
	// clic sur les boutons pour changer de plage
	changePlage: function(plageId) {
		pmAffectation.current.plage = plageId;
		
		this.resetDateHeure(true); // on ce paramètre
		pmAffectation.views.calendar.setPlage(plageId);

		pmMode.master.update();
		
		pmHistory.setUrlParam(); // maj de l'url
	},
	// reset les paramètres quart d'heure et jour
	// fetchData : si non on met à jour la liste des créneaux, sinon s'occupe juste de current
	resetDateHeure: function(notFetchData) {
		// le quart d'heure, check s'il n'y avait pas un current
		(pmAffectation.current.quart_heure != -1) && ($('#'+pmAffectation.current.quart_heure).removeClass('current'));
		pmAffectation.current.quart_heure = -1;
		
		// le jour
		pmAffectation.current.jour = -1;
		
		if (notFetchData !== true) {
			pmHistory.setUrlParam(); // maj de l'url
			
			// on re-get les créneaux
			pmAffectation.controllers.creneau.getData();
		}
	},
	// clic sur un bouton de mode ou init
	changeMode: function(obj) {
		pmMode.setMode(obj.data.mode, true);
	},
}
