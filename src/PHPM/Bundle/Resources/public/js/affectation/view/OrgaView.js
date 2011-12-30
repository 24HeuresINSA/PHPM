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
	},
	
	/*
	 * Charge la liste des orgas
	 */
	setOrgas: function() {
		$('#liste_orgas').empty(); // reset la liste
		
		for (_iOrga in pmAffectation.data.orga) {
			var _html = '<div class="orga" id="orga_'+_iOrga+'" idOrga="'+_iOrga+'">';
			_html += pmAffectation.data.orga[_iOrga]['prenom']+' '+pmAffectation.data.orga[_iOrga]['nom'];
			(pmAffectation.data.orga[_iOrga]['surnom'] !== null) && (_html += ' ('+pmAffectation.data.orga[_iOrga]['surnom']+')')
			_html += '</div>';
			
			$('#liste_orgas').append(_html);
			
			$('#orga_'+_iOrga).bind('click', {id: _iOrga}, pmAffectation.controllers.orga.click); // handler de click
		}
	}
	
}
