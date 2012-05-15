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
		pmAffectation.data.taches = {};
		pmAffectation.models.tache = new TacheModel();
		pmAffectation.views.tache = new TacheView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		$('#liste_taches').empty();
		$('#liste_taches').addClass('spinner_medium');
		pmAffectation.controllers.orga.empty(); // vide la colonne orga
		
		// si une tâche est déjà sélectionnée, on va chercher le reste en parallèle
		if (pmAffectation.current.mode === 'tache' && pmAffectation.current.tache.id != -1) {
			this.getCreneaux(); // dispos
		}
		
		pmAffectation.models.tache.getData(this.callbackTaches);
	},
	getCreneaux: function() {
		pmAffectation.models.tache.getDataCreneaux(this.callbackCreneaux);
	},
	
	/*
	 * Callbacks
	 */
	callbackTaches: function() {
		pmAffectation.data.taches = pmAffectation.models.tache.getTaches();
		
		// si aucune tâche n'est sélectionnée, on choisit la 1ère
		if (pmAffectation.current.mode === 'tache' && pmAffectation.current.tache.id == -1 && pmAffectation.data.taches[0]  !== undefined) {
			pmAffectation.current.tache.id = pmAffectation.data.taches[0]['id'];
			
			pmHistory.setUrlParam(); // maj de l'url
			
			this.getCreneaux(); // on va chercher ses créneaux
		}
		
		pmAffectation.views.tache.setTaches();
	},
	callbackCreneaux: function() {
		pmAffectation.data.creneauxTaches = pmAffectation.models.tache.getCreneaux();
		
		pmAffectation.views.calendar.setCreneaux();
	},
	
	/*
	 * Handlers
	 */
	// clic sur une tache : affiche son planning
	clickHandler: function(obj) {
		$("#tache_"+pmAffectation.current.tache.id).removeClass('current');
		$("#tache_"+obj.id).addClass('current');

		// on de-set le quart d'heure et le jour
		pmAffectation.controllers.calendar.resetDateHeure(true);
		
		pmAffectation.controllers.orga.empty(); // vide la colonne orga

		pmAffectation.current.tache.id = obj.id;
		pmHistory.setUrlParam(); // maj de l'url
		
		this.getCreneaux();
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
	}

}
