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
		// boutons pour changer d'orga
		$('#bouton_orga_prev').button({
			icons: {primary: 'ui-icon-triangle-1-w'},
			text: false
		}).click(function() { $('#orga_'+pmAffectation.current.orga.id).prev().click(); });
		$('#bouton_orga_next').button({
			icons: {primary: 'ui-icon-triangle-1-e'},
			text: false
		}).click(function() { $('#orga_'+pmAffectation.current.orga.id).next().click(); });
		
		$('#bouton_refresh').button({
			icons: {primary: 'ui-icon-refresh'},
			text: false
		}).click(function() { pmAffectation.controllers.orga.getData(); });
		
		this.setFilters(); // met les bonnes valeurs dans les filtres
		
		// filtres : bind les events
		$('#filtre_orga_confiance').change(function() {pmAffectation.controllers.orga.clickFilterConfiance($('#filtre_orga_confiance').val());});
		$('#filtre_orga_permis').change(function() {pmAffectation.controllers.orga.clickFilterPermis($('#filtre_orga_permis').val());});
		$('#filtre_orga_age').change(function() {pmAffectation.controllers.orga.clickFilterAge($('#filtre_orga_age').val());});
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
	setOrgas: function() {
		$('#liste_orgas').removeClass('spinner_medium');
		$('#liste_orgas').empty(); // au cas où
		
		for (var _iOrga in pmAffectation.data.orga) {
			var _html = '<div class="item orga" id="orga_'+_iOrga+'" idOrga="'+_iOrga+'">';
			_html += pmAffectation.data.orga[_iOrga]['prenom']+' '+pmAffectation.data.orga[_iOrga]['nom'];
			(pmAffectation.data.orga[_iOrga]['surnom'] !== undefined) && (_html += ' ('+pmAffectation.data.orga[_iOrga]['surnom']+')')
			_html += '</div>';
			
			$('#liste_orgas').append(_html);
			
			$('#orga_'+_iOrga).bind('click', {id: _iOrga}, pmAffectation.controllers.orga.clickHandler); // handler de click
		}
		
		$("#orga_"+pmAffectation.current.orga.id).addClass('current'); // met le focus là où il faut
	},
}
