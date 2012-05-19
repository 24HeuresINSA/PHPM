/*
 * Page Affectation
 * Controlleur pour les créneaux
 * 
 * Une chose à savoir : on bosse dans un colonne qui s'appelle "liste_taches"
 */
function CreneauController() {
	// on lance juste le constructeur
	this.initialize();
}

CreneauController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.creneaux = {};

		pmAffectation.models.creneau = new CreneauModel();
		pmAffectation.views.creneau = new CreneauView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		$('#liste_taches').empty();
		$('#liste_taches').addClass('spinner_medium');
		
		pmAffectation.models.creneau.getData(pmAffectation.controllers.creneau.callbackCreneaux);
	},
	
	/*
	 * Callbacks
	 */
	// pour les créneaux
	callbackCreneaux: function() {
		pmAffectation.data.creneaux = pmAffectation.models.creneau.getCreneaux();
		
		pmAffectation.views.creneau.setCreneaux();
		
		// s'il y avait un filtre de recherche, on le re-set
		if ($('#champ_tache_rechercher').val() != '') {
			$('#bouton_tache_rechercher').click();
			pmAffectation.controllers.creneau.filterList($('#champ_tache_rechercher').val());
		}
	},
	
	/*
	 * Handlers
	 */
	// clic sur un creneau : on fait l'affectation
	clickHandler: function(obj) {
		// on log l'action
		pmUtils.logAction('affectation', obj.idCreneau, obj.idOrga);
		
		// on appelle le webservice
		pmAffectation.models.calendar.affecterCreneau('affecter', obj.idCreneau, obj.idOrga, pmAffectation.controllers.creneau.callbackAffectation);
	},
	// callback
	callbackAffectation: function() {
		pmMessage.success('<strong>Affectation réalisée !</strong>');
		
		// on recharge le planning de cet orga (et du coup la liste des tous les orgas)
		pmAffectation.controllers.orga.getDispos();
		pmAffectation.controllers.creneau.getData(); // et aussi les créneaux dispos
	},
	// changement de la valeur d'un filtre
	clickFilter: function(nomFiltre, valeurFiltre) {
		pmAffectation.current.creneau[nomFiltre] = valeurFiltre;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.creneau.getData();
	},
	// handler du champ de recherche, filtre la liste des orgas avec la str passée
	filterList: function(str) {
		str = pmUtils.removeDiacritics(str); // retire les accents
		
		var _creneaux = pmUtils.filter(pmAffectation.data.creneaux, function(key, val) {
			// on recherche sur les champs nom et lieu (test leur existence)
			return (
				(val.nom && pmUtils.removeDiacritics(val.nom.substr(0, str.length).toLowerCase()) == str) || 
				(val.lieu && pmUtils.removeDiacritics(val.lieu.substr(0, str.length).toLowerCase()) == str) 
			);
		});
		
		if ($.isEmptyObject(_creneaux) === true) {
			$('#liste_taches').html('<div class="alert">Aucun créneau correspondant !</div>');
		} else {
			pmAffectation.views.creneau.setCreneaux(_creneaux);
		}
	},
	
	/*
	 * Gère la désaffectation
	 * (clic sur le bouton correspondant)
	 */
	desAffectation: function(idCreneau, idOrga) {
		// on log l'action
		pmUtils.logAction('desaffectation', idCreneau, idOrga);
		
		// on appelle le webservice
		pmAffectation.models.calendar.affecterCreneau('desaffecter', idCreneau, idOrga, pmAffectation.controllers.creneau.callbackDesaffectation);
	},
	// callback
	callbackDesaffectation: function() {
		pmMessage.success('<strong>Désaffectation réalisée !</strong>');

		// on recharge le planning de cet orga (et du coup la liste des tous les orgas)
		pmAffectation.controllers.orga.getDispos();
		pmAffectation.controllers.creneau.getData(); // et aussi les créneaux dispos
		
		// on dé-star l'orga
		$('#bouton_orga_statut > i').removeClass().addClass('icon-star-empty');
		$("#orga_"+pmAffectation.current.orga.id).removeClass('star');
	},
	
	/*
	 * Vide la colonne
	 */
	empty: function() {
		pmAffectation.data.creneaux = {};
		
		$('#liste_taches').empty();
	},
}
