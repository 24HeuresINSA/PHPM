/*
 * Page Affectation
 * Vue du calendier central
 */
function CalendarView() {
	// on lance juste le constructeur
	this.initialize();
}

CalendarView.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
	},
	
	/*
	 * Set la plage qu'il faut
	 */
	setPlage: function(plage) {
		// calcule le nombre de jours - il faut passer par les TS, +1
		var _nbJours = (pmAffectation.data.parameter.plage[plage]['jour_fin'].getTime()-pmAffectation.data.parameter.plage[plage]['jour_debut'].getTime())/(24*60*60*1000)+1;
		
		console.log(_nbJours);
		
		for (i=0;i<_nbJours;i++) {
			$('#calendar').append(this.makeADay("04/12/1990", _nbJours));
		}
	},
	// fabrique un jour
	makeADay: function(date, nbJours) {
		var _html = '<div class="jour" id="jour" date="'+date+'" style="width: '+99/nbJours+'%;">'; // 99% because of borders
		_html += '<div class="titre_date">'+date+'</div>';
		_html += '</div>';
		
		return _html;
	}
}
