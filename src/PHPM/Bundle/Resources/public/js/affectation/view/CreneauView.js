/*
 * Page Affectation
 * Vue des créneaux
 */
function CreneauView() {
	// on lance juste le constructeur
	this.initialize();
}

CreneauView.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		// écoute des boutons
		$('#bouton_tache_refresh').click(function() { pmAffectation.controllers.creneau.getData(); });
		
		this.setFilters(); // met les bonnes valeurs dans les filtres
		
		// filtres : bind les events
		$('#filtre_tache_confiance').change(function() {pmAffectation.controllers.creneau.clickFilter('confiance', $('#filtre_tache_confiance').val());});
		$('#filtre_tache_permis').change(function() {pmAffectation.controllers.creneau.clickFilter('permis', $('#filtre_tache_permis').val());});
		$('#filtre_tache_categorie').change(function() {pmAffectation.controllers.creneau.clickFilter('categorie', $('#filtre_tache_categorie').val());});
		$('#filtre_tache_duree').change(function() {pmAffectation.controllers.creneau.clickFilter('duree', $('#filtre_tache_duree').val());});
	},
	
	/*
	 * Sélectionne les bons filtres
	 */
	setFilters: function() {
		(pmAffectation.current.creneau.confiance !== undefined) && ($('#filtre_tache_confiance').val(pmAffectation.current.creneau.confiance));
		(pmAffectation.current.creneau.permis !== undefined) && ($('#filtre_tache_permis').val(pmAffectation.current.creneau.permis));
		(pmAffectation.current.creneau.categorie !== undefined) && ($('#filtre_tache_categorie').val(pmAffectation.current.creneau.categorie));
		(pmAffectation.current.creneau.duree !== undefined) && ($('#filtre_tache_duree').val(pmAffectation.current.creneau.duree));
	},
	
	/*
	 * Charge la liste des créneaux
	 * Seul truc un peu tordu : on évolue dans une colonne appelée "liste_taches"
	 */
	setCreneaux: function() {
		$('#liste_taches').empty(); // reset la liste
		$('#liste_taches').removeClass('spinner_medium');
		
		for (var _iCreneau in pmAffectation.data.creneaux) {
			var _html = '<div class="item tache" id="tache_'+_iCreneau+'" idCreneau="'+_iCreneau+'">';
			_html += pmAffectation.data.creneaux[_iCreneau]['nom']+' - '+pmAffectation.data.creneaux[_iCreneau]['lieu']+' (';
			_html += pmAffectation.data.creneaux[_iCreneau]['debut'].getThisFormat('j')+' : ';
			_html += pmAffectation.data.creneaux[_iCreneau]['debut'].getThisFormat('H:I')+' - '+pmAffectation.data.creneaux[_iCreneau]['fin'].getThisFormat('H:I')+')';
			_html += '</div>';
			
			$('#liste_taches').append(_html);
			
			// handler de click
			$('#tache_'+_iCreneau).bind('click', {idCreneau: _iCreneau}, function(e) {
				if (e.altKey) {
					// Shift + click : affiche la page pour modifier le créneau
					var _popup = window.open(pmAffectation.url+'creneau/'+e.data.idCreneau+'/edit', '');
					
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
			});
		}
	},
}
