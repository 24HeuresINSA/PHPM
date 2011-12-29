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
		var _nbJours = (pmAffectation.data.calendar.plage[plage]['jour_fin'].getTime()-pmAffectation.data.calendar.plage[plage]['jour_debut'].getTime())/(24*60*60*1000)+1;
		
		/// 1ère colonne : les heures
		var _hours = '<div class="hours" id="hours">';
		for (var _j=0;_j<24;_j++) {
			_hours += '<div class="heure">'+_j+'h</div>';
		}
		_hours += '</div>';
		
		$('#calendar').append(_hours);
		
		for (var _i=0;_i<_nbJours;_i++) {
			var _date = new Date(pmAffectation.data.calendar.plage[plage]['jour_debut'].getTime()+_i*24*60*60*1000);
			$('#calendar').append(this.makeADay(_date.getDate()+'/'+Number(_date.getMonth()+1), _nbJours));
		}
	},
	// fabrique un jour
	makeADay: function(date, nbJours) {
		var _html = '<div class="jour" id="jour_'+date+'" date="'+date+'" style="width: '+94/nbJours+'%;">'; // 99% because of borders
		_html += '<div class="titre_date">'+date+'</div>';
		
		for (var _i=0;_i<96;_i++) {
			var _dts = date+' '+Math.floor(_i/4)+':'+_i%4*15;
			_html += '<div class="quart_heure" id="quart_heure_'+date+'_'+_i+'" heure="'+_dts+'" ';
			_html += 'onclick="pmAffectation.controllers.calendar.click(\''+_dts+'\')"></div>';
		}
		
		_html += '</div>';
		
		return _html;
	}
}
