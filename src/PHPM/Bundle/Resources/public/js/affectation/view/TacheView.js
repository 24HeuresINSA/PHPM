/*
 * Page Affectation
 * Vue des taches
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
		/*$('#bouton_orga_prev').button({
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
		}).click(function() { pmAffectation.controllers.orga.getData(); });*/
	},
	
	/*
	 * Charge la liste des taches
	 */
	setTaches: function() {
		$('#liste_taches').empty(); // reset la liste
		$('#liste_taches').removeClass('spinner_medium');
		
		/*for (_iOrga in pmAffectation.data.orga) {
			var _html = '<div class="orga" id="orga_'+_iOrga+'" idOrga="'+_iOrga+'">';
			_html += pmAffectation.data.orga[_iOrga]['prenom']+' '+pmAffectation.data.orga[_iOrga]['nom'];
			(pmAffectation.data.orga[_iOrga]['surnom'] !== null) && (_html += ' ('+pmAffectation.data.orga[_iOrga]['surnom']+')')
			_html += '</div>';
			
			$('#liste_orgas').append(_html);
			
			$('#orga_'+_iOrga).bind('click', {id: _iOrga}, pmAffectation.controllers.orga.clickHandler); // handler de click
		}
		
		$("#orga_"+pmAffectation.current.orga.id).addClass('current'); // met le focus là où il faut*/
	},
	
}
