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
		// on vide déjà la div
		$('#calendar').empty();
		
		// calcule le nombre de jours - il faut passer par les TS, +1
		var _nbJours = (pmAffectation.data.calendar.plage[plage]['fin'].getTime()-pmAffectation.data.calendar.plage[plage]['debut'].getTime())/(24*60*60*1000)+1;
		
		/// 1ère colonne : les heures
		var _hours = '<div class="hours" id="hours">';
		for (var _j=0;_j<24;_j++) {
			_hours += '<div class="hour">'+_j+'h</div>';
		}
		_hours += '</div>';
		
		$('#calendar').append(_hours);
		
		for (var _i=0;_i<_nbJours;_i++) {
			var _date = new Date(pmAffectation.data.calendar.plage[plage]['debut'].getTime()+_i*24*60*60*1000);
			$('#calendar').append(this.makeADay(_date.getThisFormat('d/n'), _date.getDay(), _nbJours));
		}
		
		pmUtils.resizeTitres(); // synchro la taille des titres
		
		if (Object.keys(pmAffectation.data.calendar.plage).length != 0) {
			this.setBoutonsPlage();
		}
		
		this.setTitreBarre();
	},
	// sette la barre du titre des jours en haut de la page lors du scroll
	setTitreBarre: function() {
		$(window).scroll(function() {
			if ($(window).scrollTop() > 0 && $(window).scrollTop() > $('.titre_date').position().top) {
				$('.titre_date_fixed').css('top', '0');
			} else if ($(window).scrollTop() > 0) {
				$('.titre_date_fixed').css('top', Number($('.titre_date').position().top-$(window).scrollTop())+'px');
			} else {
				$('.titre_date_fixed').css('top', '');
			}
		});		
	},
	// fabrique un jour
	makeADay: function(date, jourSemaine, nbJours) {
		var _html = '<div class="jour" id="jour_'+date+'" jour="'+date+'" style="width: '+94/nbJours+'%;">'; // -1% because of borders, -5% pour les heures
		_html += '<div class="titre_date_fixed">'+pmUtils.jours[jourSemaine]+' '+date+'</div>';
		_html += '<div class="titre_date">'+pmUtils.jours[jourSemaine]+' '+date+'</div>'; // celui-ci reste toujours en haut
		
		for (var _i=0;_i<24;_i++) {
			_html += '<div class="heure" id="heure_'+date+'_'+_i+'" heure="'+_i+'">';
			
			for (var _j=0;_j<4;_j++) {
				var _dts = date+' '+_i+':'+_j*15;
				
				_html += '<div class="quart_heure" id="quart_heure_'+date+'_'+_i+':'+_j*15+'" minute="'+_j*15+'"></div>';
				//_html += 'onclick="pmAffectation.controllers.calendar.click(\''+_dts+'\')"></div>';
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
		
		for (var _unePlage in pmAffectation.data.calendar.plage) {
			_html += '<input type="radio" id="radio_'+_unePlage+'" name="radio" onclick="pmAffectation.controllers.calendar.changePlage('+_unePlage+')"" />';
			_html += '<label for="radio_'+_unePlage+'">'+pmAffectation.data.calendar.plage[_unePlage]['nom']+'</label>';
		}
	
		_html += '</div></form>';
		
		$('#boutons_plage').html(_html);
		
		$("#radio").buttonset(); // jQuery goodness
	},
	
	/*
	 * Va setter les quart d'heure "free" :
	 * orga disponible sur ce créneau OU créneau de tâche à attribuer
	 */
	setFrees: function(obj) {
		// on supprime tout
		$('.quart_heure').removeClass('free');
		$('.quart_heure').off(); // click handlers
		$('.creneau').remove();
		
		if (obj.type === 'orga') {
			for (var _iDispo in pmAffectation.data.orga[obj.id]['disponibilites']) {
				var _debut = pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['debut'];
				var _fin = pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['fin'];
				
				// on vérifie si on est bien dans les bornes de la plage, trim au besoin
				if (_debut.getTime() < pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'].getTime()) {
					_debut.setTime(pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'].getTime());
				}
				if (_fin.getTime() > pmAffectation.data.calendar.plage[pmAffectation.current.plage]['fin'].getTime()) {
					_fin.setTime(pmAffectation.data.calendar.plage[pmAffectation.current.plage]['fin'].getTime());
				}
				
				// on place les dispos, avec la classe et le click
				for (var _iDts = _debut; _iDts.getTime() < _fin.getTime(); _iDts.setTime(_iDts.getTime()+15*60*1000)) {
					// sélection suivant les attributs de temps de plus en plus précis
					$('.jour[jour="'+_iDts.getDate()+'/'+Number(_iDts.getMonth()+1)+'"] > .heure[heure="'
					+_iDts.getHours()+'"] > .quart_heure[minute="'+_iDts.getMinutes()+'"]').addClass('free')
					.bind('click', {date: _iDts.getMyDts()}, pmAffectation.controllers.calendar.clickQuartHeure);
				}
				
				// on place les créneaux (et retire le handler)
				for (var _iCreneau in pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['creneaux']) {
					var _hDebut = pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut'];
					
					console.log(_iCreneau, _hDebut.getMyDts());
					
					_html = '<div id="creneau_'+_iCreneau+'" class="creneau" creneau="'+_iCreneau+'">';
					_html += pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut'].getThisFormat('H:I')+' - ';
					_html += pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin'].getThisFormat('H:I')+'</div>';
					
					// on le rajoute, supprime le handler précédent et en rajoute un
					$('.jour[jour="'+_hDebut.getDate()+'/'+Number(_hDebut.getMonth()+1)+'"] > .heure[heure="'
					+_hDebut.getHours()+'"] > .quart_heure[minute="'+_hDebut.getMinutes()+'"]').append(_html).off('click')
					.bind('click', {creneauId: _iCreneau}, pmAffectation.controllers.calendar.clickCreneau);
					
					// set la taille
					var _taille = Number(pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['duree']/60/60*40);
					$('#creneau_'+_iCreneau).height(_taille+'px');
				}
			}
		} else {
			// à faire, pour la vue dans l'autre sens
		}
	}
}
