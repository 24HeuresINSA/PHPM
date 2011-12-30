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
			_hours += '<div class="hour">'+_j+'h</div>';
		}
		_hours += '</div>';
		
		$('#calendar').append(_hours);
		
		for (var _i=0;_i<_nbJours;_i++) {
			var _date = new Date(pmAffectation.data.calendar.plage[plage]['jour_debut'].getTime()+_i*24*60*60*1000);
			$('#calendar').append(this.makeADay(_date.getDate()+'/'+Number(_date.getMonth()+1), _date.getDay(), _nbJours));
		}
		
		if (Object.keys(pmAffectation.data.calendar.plage).length != 0) {
			this.setBoutonsPlage();
		}
	},
	// fabrique un jour
	makeADay: function(date, day, nbJours) {
		var _html = '<div class="jour" id="jour_'+date+'" date="'+date+'" style="width: '+94/nbJours+'%;">'; // -1% because of borders, -5% pour les heures
		_html += '<div class="titre_date_fixed" style="width: '+0.6*94/nbJours+'%;">'+jours[day]+' '+date+'</div>';  // pour un positionnement fixe, la largeur se calcule par rapport à l'écran
		_html += '<div class="titre_date">'+jours[day]+' '+date+'</div>'; // celui-ci reste toujours en haut
		
		for (var _i=0;_i<24;_i++) {
			_html += '<div class="heure" id="heure_'+date+'_'+_i+'h">';
			
			for (var _j=0;_j<4;_j++) {
				var _dts = date+' '+_i+':'+_j*15;
				_html += '<div class="quart_heure" id="quart_heure_'+date+'_'+_i+'h'+_j*15+'" heure="'+_dts+'" ';
				_html += 'onclick="pmAffectation.controllers.calendar.click(\''+_dts+'\')"></div>';
			}
			
			_html += '</div>';
		}
		
		_html += '</div>';
		
		return _html;
	},
	
	/*
	 * Set les boutons pour changer de plage
	 */
	setBoutonsPlage: function() {
		var _html = '<form><div id="radio">';
		
		for (unePlage in pmAffectation.data.calendar.plage) {
			_html += '<input type="radio" id="radio_'+unePlage+'" name="radio" onclick="pmAffectation.controllers.calendar.changePlage('+unePlage+')"" />';
			_html += '<label for="radio_'+unePlage+'">'+pmAffectation.data.calendar.plage[unePlage]['nom']+'</label>';
		}
	
		_html += '</div></form>';
		
		$('#boutons_plage').html(_html);
		
		$("#radio").buttonset(); //jQuery goodness
	}
}
