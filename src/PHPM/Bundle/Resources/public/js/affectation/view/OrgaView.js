/*
 * Page Affectation
 * Vue des orgas
 */
function OrgaView() {
	// on lance juste le constructeur
	this.initialize();
}

OrgaView.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		// on écoute les boutons
		$('#bouton_orga_prev').click(function() { $('#orga_'+pmAffectation.current.orga.id).prev().click(); });
		$('#bouton_orga_next').click(function() { $('#orga_'+pmAffectation.current.orga.id).next().click(); });
		$('#bouton_orga_refresh').click(function() { pmAffectation.controllers.orga.getData(); });
		$('#bouton_orga_detail').click(function() { pmAffectation.views.orga.viewOrgaDetails({id: pmAffectation.current.orga.id}); });
		
		this.setFilters(); // met les bonnes valeurs dans les filtres
		
		// filtres : bind les events
		$('#filtre_orga_confiance').change(function() { pmAffectation.controllers.orga.clickFilter('confiance', $('#filtre_orga_confiance').val()); });
		$('#filtre_orga_permis').change(function() { pmAffectation.controllers.orga.clickFilter('permis', $('#filtre_orga_permis').val()); });
		$('#filtre_orga_age').change(function() { pmAffectation.controllers.orga.clickFilter('age', $('#filtre_orga_age').val()); });
		
		// la champ de recherche (caché)
		// on doit empêcher la fermeture du dropdown du champ de recherche
		$('#champ_orga_rechercher').click(function(event) { event.stopImmediatePropagation(); });
		// on écoute lorsque des caractères sont tapés - keyup sinon on ne peut pas lire la valeur
		$('#champ_orga_rechercher').keyup(function(event) { pmAffectation.controllers.orga.filterList($('#champ_orga_rechercher').val()); });
	},
	
	/*
	 * Sélectionne les bons filtres
	 */
	setFilters: function() {
		(pmAffectation.current.orga.confiance !== undefined) && ($('#filtre_orga_confiance').val(pmAffectation.current.orga.confiance));
		(pmAffectation.current.orga.permis !== undefined) && ($('#filtre_orga_permis').val(pmAffectation.current.orga.permis));
		(pmAffectation.current.orga.age !== undefined) && ($('#filtre_orga_age').val(pmAffectation.current.orga.age));
	},
	
	/*
	 * Charge la liste des orgas
	 */
	setOrgas: function(orgas) {
		var _orgas = $.isEmptyObject(orgas) ? pmAffectation.data.orgas : orgas; // valeur par défaut
		
		$('#liste_orgas').removeClass('spinner_medium');
		$('#liste_orgas').empty(); // au cas où
		
		for (var _iOrga in _orgas) {
			var _html = '<div class="item orga" id="orga_'+_iOrga+'" idOrga="'+_iOrga+'">';
			_html += _orgas[_iOrga]['prenom']+' '+_orgas[_iOrga]['nom'];
			(_orgas[_iOrga]['surnom'] !== undefined) && (_html += ' ('+_orgas[_iOrga]['surnom']+')')
			_html += '</div>';
			
			$('#liste_orgas').append(_html);
			
			// handler de click
			$('#orga_'+_iOrga).bind('click', {id: _iOrga}, function(e) {
				if (e.altKey) {
					// Shift + click : affiche les infos détaillées de l'orga
					pmAffectation.views.orga.viewOrgaDetails({id: e.data.id});
				} else {
					// sinon on click sur l'orga
					pmAffectation.controllers.orga.clickHandler({id: e.data.id});
				}
			});
		}
		
		$("#orga_"+pmAffectation.current.orga.id).addClass('current'); // met le focus là où il faut
	},
	
	/*
	 * Affiche un dialogue modal
	 * avec des infos sur l'orga
	 */
	viewOrgaDetails: function(obj) {
		var _popup = window.open(pmAffectation.url+'orga/'+obj.id+'/edit');		
	},
}
