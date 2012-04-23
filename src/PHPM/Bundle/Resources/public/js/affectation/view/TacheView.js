/*
 * Page Affectation
 * Vue des tâches
 */
function TacheView() {
	// on lance juste le constructeur
	this.initialize();
}

TacheView.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		// on écoute les boutons
		$('#bouton_tache_prev').click(function() { $('#tache_'+pmAffectation.current.tache.id).prev().click(); });
		$('#bouton_tache_next').click(function() { $('#tache_'+pmAffectation.current.tache.id).next().click(); });
		$('#bouton_tache_refresh').click(function() { pmAffectation.controllers.tache.getData(); });
		$('#bouton_tache_detail').click(function() { pmAffectation.views.tache.viewTacheDetails({id: pmAffectation.current.tache.id}); });
		
		this.setFilters(); // met les bonnes valeurs dans les filtres
		
		// filtres : bind les events
		$('#filtre_tache_confiance').change(function() {pmAffectation.controllers.tache.clickFilter('confiance', $('#filtre_tache_confiance').val());});
		$('#filtre_tache_permis').change(function() {pmAffectation.controllers.tache.clickFilter('permis', $('#filtre_tache_permis').val());});
		$('#filtre_tache_categorie').change(function() {pmAffectation.controllers.tache.clickFilter('categorie', $('#filtre_tache_categorie').val());});
		$('#filtre_tache_duree').change(function() {pmAffectation.controllers.tache.clickFilter('duree', $('#filtre_tache_duree').val());});
		
		// la champ de recherche (caché)
		// on doit empêcher la fermeture du dropdown du champ de recherche
		$('#champ_tache_rechercher').click(function(event) { event.stopImmediatePropagation(); });
		// on écoute lorsque des caractères sont tapés - keyup sinon on ne peut pas lire la valeur
		$('#champ_tache_rechercher').keyup(function(event) { pmAffectation.controllers.tache.filterList($('#champ_tache_rechercher').val()); });
		// mochement, on attend 50 ms, sinon on ne peut pas focuser un élément en display: none...
		$('#bouton_tache_rechercher').bind('click', function(event) { setInterval(function() { $('#champ_tache_rechercher').focus(); }, 50); })
	},
	
	/*
	 * Sélectionne les bons filtres
	 */
	setFilters: function() {
		(pmAffectation.current.tache.confiance !== undefined) && ($('#filtre_tache_confiance').val(pmAffectation.current.tache.confiance));
		(pmAffectation.current.tache.permis !== undefined) && ($('#filtre_tache_permis').val(pmAffectation.current.tache.permis));
		(pmAffectation.current.tache.categorie !== undefined) && ($('#filtre_tache_categorie').val(pmAffectation.current.tache.categorie));
		(pmAffectation.current.tache.duree !== undefined) && ($('#filtre_tache_duree').val(pmAffectation.current.tache.duree));
	},
	
	/*
	 * Charge la liste des tâches
	 */
	setTaches: function(taches) {
		var _taches = $.isEmptyObject(taches) ? pmAffectation.data.taches : taches; // valeur par défaut
		
		$('#liste_taches').empty(); // reset la liste
		$('#liste_taches').removeClass('spinner_medium');
		
		for (var _iTache in _taches) {
			var _html = '<div class="item tache" id="tache_'+_iTache+'" idTache="'+_iTache+'">'+
						_taches[_iTache]['nom']+' ('+_taches[_iTache]['lieu']+')</div>';
			
			$('#liste_taches').append(_html);
			
			// handler de click
			$('#tache_'+_iTache).bind('click', {idTache: _iTache}, function(e) {
				if (e.altKey) {
					// Alt + click : affiche les infos détaillées de la tâche
					pmAffectation.views.tache.viewTacheDetails({id: e.data.idTache});
				} else {
					// sinon on click sur la tâche
					pmAffectation.controllers.tache.clickHandler({id: e.data.idTache});
				}
			});
		}
	},
	
	/*
	 * Affiche un dialogue modal
	 * avec des infos sur l'orga
	 */
	viewTacheDetails: function(obj) {
		var _popup = window.open(pmAffectation.url+'tache/'+obj.id+'/edit');		
	},
}
