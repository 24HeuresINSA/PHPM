/*
 * Page Affectation
 * Controlleur pour les tâches
 */
function TacheController() {
	// on lance juste le constructeur
	this.initialize();
}

TacheController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.tache = {};

		pmAffectation.models.tache = new TacheModel();
		pmAffectation.views.tache = new TacheView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		$('#liste_taches').empty();
		$('#liste_taches').addClass('spinner_medium');
		
		pmAffectation.models.tache.getData(pmAffectation.controllers.tache.callbackTaches);
	},
	
	/*
	 * Callbacks
	 */
	callbackTaches: function() {
		pmAffectation.data.taches = pmAffectation.models.tache.getTaches();
		
		// si aucun orga n'est sélectionné, on choisit le 1er
		if (pmAffectation.current.mode === 'tache' && pmAffectation.current.tache.id == -1 && Object.keys(pmAffectation.data.taches)[0]  !== undefined) {
			pmAffectation.current.tache.id = Object.keys(pmAffectation.data.taches)[0];
			
			pmHistory.setUrlParam(); // maj de l'url
		}
		
		pmAffectation.views.tache.setTaches();
		pmAffectation.views.calendar.setFrees({type: 'tache', id: pmAffectation.current.tache.id});
		
		// force la mise à jour des orgas - tous ceux pouvant aller à cette tâche
		pmAffectation.controllers.orga.getData();
	},
	
	/*
	 * Handlers
	 */
	// clic sur une tache : affiche son planning
	clickHandler: function(obj) {
		$("#tache_"+pmAffectation.current.tache.id).removeClass('current');
		$("#tache_"+obj.id).addClass('current');
		
		pmAffectation.controllers.orga.empty(); // vide la colonne creneau
		
		// on de-set le quart d'heure et le jour
		pmAffectation.controllers.calendar.resetDateHeure(true);

		pmAffectation.current.tache.id = obj.id;
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.views.calendar.setFrees({type: 'tache', id: pmAffectation.current.tache.id});
	},
	// changement de la valeur d'un filtre
	clickFilter: function(nomFiltre, valeurFiltre) {
		pmAffectation.current.tache[nomFiltre] = valeurFiltre;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.tache.getData();
	},
	// handler du champ de recherche, filtre la liste des orgas avec la str passée
	filterList: function(str) {
		str = pmUtils.removeDiacritics(str); // retire les accents
		
		var _taches = pmUtils.filter(pmAffectation.data.taches, function(key, val) {
			// on recherche sur les champs nom, prénom, et surnom (test leur existence)
			return (
				(val.nom && pmUtils.removeDiacritics(val.nom.substr(0, str.length).toLowerCase()) == str) || 
				(val.lieu && pmUtils.removeDiacritics(val.lieu.substr(0, str.length).toLowerCase()) == str)
			);
		});
		
		if ($.isEmptyObject(_taches) === true) {
			$('#liste_taches').html('<div class="alert">Aucune tâche correspondante !</div>');
		} else {
			pmAffectation.views.tache.setTaches(_taches);
		}
	},
	
	/*
	 * Vide la colonne
	 */
	empty: function() {
		pmAffectation.data.taches = {};
		
		$('#liste_taches').empty();
	},

}
