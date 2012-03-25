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
		// boutons pour changer d'orga
		$('#bouton_tache_prev').button({
			icons: {primary: 'ui-icon-triangle-1-w'},
			text: false
		}).click(function() { $('#tache_'+pmAffectation.current.tache.id).prev().click(); });
		$('#bouton_tache_next').button({
			icons: {primary: 'ui-icon-triangle-1-e'},
			text: false
		}).click(function() { $('#tache_'+pmAffectation.current.tache.id).next().click(); });
		
		$('#bouton_tache_refresh').button({
			icons: {primary: 'ui-icon-refresh'},
			text: false
		}).click(function() { pmAffectation.controllers.tache.getData(); });
		
		this.setFilters(); // met les bonnes valeurs dans les filtres
		
		// filtres : bind les events
		$('#filtre_tache_confiance').change(function() {pmAffectation.controllers.tache.clickFilter('confiance', $('#filtre_tache_confiance').val());});
		$('#filtre_tache_permis').change(function() {pmAffectation.controllers.tache.clickFilter('permis', $('#filtre_tache_permis').val());});
		$('#filtre_tache_categorie').change(function() {pmAffectation.controllers.tache.clickFilter('categorie', $('#filtre_tache_categorie').val());});
		$('#filtre_tache_duree').change(function() {pmAffectation.controllers.tache.clickFilter('duree', $('#filtre_tache_duree').val());});
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
	setTaches: function() {
		$('#liste_taches').empty(); // reset la liste
		$('#liste_taches').removeClass('spinner_medium');
		
		for (var _iTache in pmAffectation.data.taches) {
			var _html = '<div class="item tache" id="tache_'+_iTache+'" idTache="'+_iTache+'">';
			_html += pmAffectation.data.taches[_iTache]['nom']+' ('+pmAffectation.data.taches[_iTache]['lieu']+')';
			_html += '</div>';
			
			$('#liste_taches').append(_html);
			
			// handler de click
			// TODO
			/*$('#tache_'+_iTache).bind('click', {idTache: _iTache}, function(e) {
				if (e.altKey) {
					// Shift + click : affiche la page pour modifier le créneau
					var _popup = window.open(pmAffectation.url+'creneau/'+e.data.idCreneau+'/edit', '', config='height=600, width=600, toolbar=no, menubar=no, location=no, directories=no, status=no');
					
					// à la fermeture, refresh la liste des créneaux
					// unload est firé au chargement (unload de about:blank),
					// on attache le vrai handler qu'après le chargement initial donc
					_popup.onunload = function() {
						_popup.bind('unload', pmAffectation.controllers.creneau.getData());
					};
				} else {
					// sinon on fait l'affectation
					pmAffectation.controllers.creneau.clickHandler({idCreneau: e.data.idCreneau, idOrga: pmAffectation.current.orga.id});
				}
			});*/
		}
	},
	
}
