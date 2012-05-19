/*
 * Page Affectation
 * Controlleur pour les orgas
 */
function OrgaController() {
	// on lance juste le constructeur
	this.initialize();
}

OrgaController.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		pmAffectation.data.orgas = {};
		pmAffectation.models.orga = new OrgaModel();
		pmAffectation.views.orga = new OrgaView();
	},
	
	/*
	 * Lancement des requêtes
	 */
	getData: function() {
		$('#liste_orgas').empty();
		$('#liste_orgas').addClass('spinner_medium');
		
		// si un orga est déjà sélectionné, on va chercher le reste en parallèle
		if (pmAffectation.current.mode === 'orga' && pmAffectation.current.orga.id != -1) {
			this.getDispos(); // dispos
		}
		
		pmAffectation.models.orga.getData(this.callbackOrgas);
	},
	getDispos: function() {
		pmAffectation.models.orga.getDataDispos(this.callbackDispos);
	},
	
	/*
	 * Callbacks
	 */
	callbackOrgas: function() {
		pmAffectation.data.orgas = pmAffectation.models.orga.getOrgas();
		
		// si aucun orga n'est sélectionné, on choisit le 1er
		if (pmAffectation.current.mode === 'orga' && pmAffectation.current.orga.id == -1 && pmAffectation.data.orgas[0]  !== undefined) {
			pmAffectation.current.orga.id = pmAffectation.data.orgas[0]['id'];
			
			pmHistory.setUrlParam(); // maj de l'url
			
			pmAffectation.controllers.orga.getDispos(); // on va chercher ses dispos
		}
		
		pmAffectation.views.orga.setOrgas();
		
		$("#orga_"+pmAffectation.current.orga.id).addClass('current');
		pmAffectation.controllers.orga.setOrgaStatut();
		
		(pmAffectation.current.mode === 'orga') && (pmAffectation.controllers.creneau.getData());
		
		// s'il y avait un filtre de recherche, on le re-set
		if ($('#champ_orga_rechercher').val() != '') {
			$('#bouton_orga_rechercher').click();
			pmAffectation.controllers.orga.filterList($('#champ_orga_rechercher').val());
		}
	},
	callbackDispos: function() {
		pmAffectation.data.dispos = pmAffectation.models.orga.getDispos();
		
		pmAffectation.views.calendar.setDispos();
	},
	
	/*
	 * Handlers
	 */
	// clic sur un orga, mode orga
	chargerListeOrgas: function(obj) {
		$("#orga_"+pmAffectation.current.orga.id).removeClass('current');
		$("#orga_"+obj.id).addClass('current');

		pmAffectation.controllers.creneau.empty(); // vide la colonne creneau

		// on de-set le quart d'heure et le jour
		pmAffectation.controllers.calendar.resetDateHeure(true);

		pmAffectation.current.orga.id = obj.id;
		pmHistory.setUrlParam(); // maj de l'url

		this.getDispos();
		pmAffectation.controllers.creneau.getData(); // récupère les taches à jour
		
		pmAffectation.controllers.orga.setOrgaStatut(); // set le bouton pour changer son statut
	},
	// clic sur un orga, mode tâche
	affecterOrga: function(obj) {
		// on demande l'affectation
		pmAffectation.models.calendar.affecterCreneau('affecter', pmAffectation.current.creneau.id, obj.id, pmAffectation.controllers.orga.callbackAffectation);
	},
	callbackAffectation: function() {
		pmMessage.success('<strong>Affectation réalisée !</strong>');
		
		// on recharge le planning de cet orga (et du coup la liste des tous les orgas)
		pmAffectation.controllers.tache.getCreneaux();
		pmAffectation.controllers.orga.getData();
	},
	// changement de la valeur d'un filtre
	clickFilter: function(nomFiltre, valeurFiltre) {
		pmAffectation.current.orga[nomFiltre] = valeurFiltre;
		
		pmHistory.setUrlParam(); // maj de l'url
		
		pmAffectation.controllers.orga.getData();
	},
	// handler du champ de recherche, filtre la liste des orgas avec la str passée
	filterList: function(str) {
		str = pmUtils.removeDiacritics(str); // retire les accents
		
		var _orgas = pmUtils.filter(pmAffectation.data.orgas, function(key, val) {
			// on recherche sur les champs nom, prénom, et surnom (test leur existence)
			return (
				(val.nom && pmUtils.removeDiacritics(val.nom.substr(0, str.length).toLowerCase()) == str) || 
				(val.prenom && pmUtils.removeDiacritics(val.prenom.substr(0, str.length).toLowerCase()) == str) || 
				(val.surnom && pmUtils.removeDiacritics(val.surnom.substr(0, str.length).toLowerCase()) == str)
			);
		});

		if ($.isEmptyObject(_orgas) === true) {
			$('#liste_orgas').html('<div class="alert">Aucun orga correspondant !</div>');
		} else {
			pmAffectation.views.orga.setOrgas(_orgas);
		}
	},
	// change le statut d'un orga (star)
	changeStatut: function(idOrga) {
		var _orga = pmUtils.find(pmAffectation.data.orgas, 'id', idOrga);
		
		// on regarde son statut actuel, le change
		_orga.statut = (_orga.statut == 1)? 2 : 1;

		pmAffectation.models.orga.changeStatut(this.setOrgaStatut, idOrga, _orga.statut);
	},
	// la fonction suivante est aussi callback du WS changeStatut
	setOrgaStatut: function() {
		var _orga = pmUtils.find(pmAffectation.data.orgas, 'id', pmAffectation.current.orga.id);
		
		if (_orga.statut == 1) {
			$('#bouton_orga_statut > i').removeClass().addClass('icon-star-empty');
			$("#orga_"+_orga.id).removeClass('star');
		} else {
			$('#bouton_orga_statut > i').removeClass().addClass('icon-star');
			$("#orga_"+_orga.id).addClass('star');
		}
	},

	/*
	 * Vide la colonne
	 */
	empty: function() {
		pmAffectation.data.orgas = {};
		
		$('#liste_orgas').empty();
	}
}
