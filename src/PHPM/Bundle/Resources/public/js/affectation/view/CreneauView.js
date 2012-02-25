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
	 * Charge la liste des créneaux
	 * Seul truc un peu tordu : on évolue dans une colonne appelée "liste_taches"
	 */
	setCreneaux: function() {
		$('#liste_taches').empty(); // reset la liste
		$('#liste_taches').removeClass('spinner_medium');
		
		for (_iCreneau in pmAffectation.data.creneaux) {
			var _html = '<div class="tache" id="tache_'+_iCreneau+'" idCreneau="'+_iCreneau+'">';
			_html += pmAffectation.data.creneaux[_iCreneau]['nom']+' - '+pmAffectation.data.creneaux[_iCreneau]['lieu']+' (';
			_html += pmAffectation.data.creneaux[_iCreneau]['debut'].getThisFormat('H:I')+' - '+pmAffectation.data.creneaux[_iCreneau]['fin'].getThisFormat('H:I')+')';
			_html += '</div>';
			
			$('#liste_taches').append(_html);
			
			// handler de click
			$('#tache_'+_iCreneau).bind('click', {idCreneau: _iCreneau, idOrga: pmAffectation.current.orga.id}, pmAffectation.controllers.creneau.clickHandler); // handler de click
		}
	},
	
}
