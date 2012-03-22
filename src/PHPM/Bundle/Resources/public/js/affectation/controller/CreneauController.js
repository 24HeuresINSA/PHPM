/*
 * Page Affectation
 * Controlleur pour les créneaux
 * 
 * Une chsoe à savoir : on bosse dans un colonne qui s'appelle "liste_taches"
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
		pmAffectation.data.creneau = {};

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
	},
	
	/*
	 * Handlers
	 */
	// clic sur un creneau : on fait l'affectation
	clickHandler: function(obj) {
		// on log l'action
		pmUtils.logAction('affectation', obj.idCreneau, obj.idOrga);
		
		// on appelle le webservice
		pmAffectation.models.creneau.affecterCreneau('affecter', obj.idCreneau, obj.idOrga, pmAffectation.controllers.creneau.callbackAffectation);
	},
	// callback
	callbackAffectation: function() {
		pmMessage.success("Affectation réalisée");
		
		// on recharge le planning de cet orga (et du coup la liste des tous les orgas)
		pmAffectation.controllers.orga.getData();
		pmAffectation.controllers.creneau.getData(); // et aussi les créneaux dispos
	},
	// changement de la valeur d'un filtre
	clickFilter: function(nomFiltre, valeurFiltre) {
		pmAffectation.current.creneau[nomFiltre] = valeurFiltre;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.creneau.getData();
	},
	
	/*
	 * Gère la désaffectation
	 * (clic sur le bouton correspondant)
	 */
	desAffectation: function(idCreneau, idOrga) {
		// on log l'action
		pmUtils.logAction('desaffectation', idCreneau, idOrga);
		
		// on appelle le webservice
		pmAffectation.models.creneau.affecterCreneau('desaffecter', idCreneau, idOrga, pmAffectation.controllers.creneau.callbackDesaffectation);
	},
	// callback
	callbackDesaffectation: function() {
		pmMessage.success("Désaffectation réalisée");
		
		// ferme l'éventuel popup
		$('#popup').dialog('close');
		$('#popup').remove();

		// on recharge le planning de cet orga (et du coup la liste des tous les orgas)
		pmAffectation.controllers.orga.getData();
		pmAffectation.controllers.creneau.getData(); // et aussi les créneaux dispos
	},
	
	/*
	 * Vide la colonne
	 */
	empty: function() {
		pmAffectation.data.creneaux = {};
		
		$('#liste_taches').empty();
	},
	


}
