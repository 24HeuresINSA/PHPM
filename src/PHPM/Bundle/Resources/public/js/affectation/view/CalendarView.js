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
		$('#client').removeClass('spinner_large');
		
		if (Object.keys(pmAffectation.data.calendar.plage).length !== 0) {
			if ($('#boutons_plage').html() === '') {
				pmAffectation.views.calendar.setBoutonsPlage();
			}
			
			// on met le bon effet sur le bon bouton
			$('#bouton_plage_'+pmAffectation.current.plage).button('toggle');
		}
		
		// calcule le nombre de jours - il faut passer par les TS, +1
		var _nbJours = (pmAffectation.data.calendar.plage[plage]['fin'].getTime()-pmAffectation.data.calendar.plage[plage]['debut'].getTime())/(24*60*60*1000)+1;
		
		// on va tout mettre en attente dans des variables et appender comme on veut à la fin
		var _htmlBarreDates = '<div id="titres" class="titres">';
		var _htmlJours = '';
		
		// colonne des heures
		var _htmlHours = '<div class="hours" id="hours">';
		for (var _j=0;_j<24;_j++) {
			_htmlHours += '<div class="hour">'+_j+'h</div>';
		}
		_htmlHours += '</div>';
		
		for (var _i=0;_i<_nbJours;_i++) {
			var _date = new Date(pmAffectation.data.calendar.plage[plage]['debut'].getTime()+_i*24*60*60*1000);
			var _dateComplete = _date.getThisFormat('Y-m-d');

			// on fait déjà la barre de titre
			_htmlBarreDates += '<div class="titre_date" jour="'+_dateComplete+'" id="titre_date_'+_dateComplete+'">'+
								pmUtils.jours[_date.getDay()]+' '+_date.getThisFormat('d/m') +'</div>';
		
			_htmlJours += this.makeADay(_dateComplete, _date.getDay(), _nbJours);
		}
		
		$('#calendar').append(_htmlBarreDates+'</div><div class="jours" id="jours">'+_htmlHours+_htmlJours+'</div>');
		
		// on sélectionne le bon quart d'heure
		$('#'+pmAffectation.current['quart_heure']).addClass('current');
		
		// on redimensionne l'app comme il faut
		pmLayout.setAppHeight();
	},
	// fabrique un jour
	makeADay: function(date, jourSemaine, nbJours) {
		var _html = '<div class="jour" id="jour_'+date+'" jour="'+date+'">';
		
		for (var _i=0;_i<24;_i++) {
			_html += '<div class="heure" id="heure_'+date+'-'+_i+'" heure="'+_i+'">';
			
			for (var _j=0;_j<4;_j++) {
				var _dts = date+' '+_i+'_'+_j*15;
				
				_html += '<div class="quart_heure" id="quart_heure_'+date+'-'+_i+'-'+_j*15+'" minute="'+_j*15+'"></div>';
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
		var _html = '';
		
		for (var _unePlage in pmAffectation.data.calendar.plage) {
			// utiliser un onClick est sale, mais force la recopie de _unePlage sinon cela plante
			_html += '<button class="btn btn-primary" id="bouton_plage_'+_unePlage+'" onclick="pmAffectation.controllers.calendar.changePlage('+_unePlage+')" type="button">'+pmAffectation.data.calendar.plage[_unePlage]['nom']+'</button>';
		}
		
		$('#boutons_plage').html(_html);
	},
	
	/*
	 * Va setter les quart d'heure "free" :
	 * orga disponible sur ce créneau OU créneau de tâche à attribuer
	 * attention, c'est un index de tableau que l'on passe dans obj.id, pas le vrai ID de l'orga
	 */
	setDispos: function() {
		// on supprime tout
		$('.quart_heure').removeClass('free');
		$('.quart_heure').off('click'); // click handlers
		$('.creneau').remove();
		
		var _dispos = pmAffectation.data.dispos;

		for (var _iDispo in _dispos) {
			// astuce importante : on force la copie en re-créant un objet Date
			var _debut = new Date(_dispos[_iDispo]['debut'].getTime());
			var _fin = _dispos[_iDispo]['fin'];
			
			// on appelle une fonction qui va placer les disponibilités
			pmAffectation.views.calendar.placeDisponibilitesOrga(_debut, _fin);

			// on place les créneaux - j'ai passé 4 heures à optimiser le truc, fais gaffe à ce que tu touches
			for (var _iCreneau in _dispos[_iDispo]['creneaux']) {
				// on vérifie si on est bien sur la bonne plage horaire, trim au besoin
				// comparaison "croisée" : permet de tenir compte des créneaux à cheval
				if (pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'] < _dispos[_iDispo]['creneaux'][_iCreneau]['fin'] 
					&& _dispos[_iDispo]['creneaux'][_iCreneau]['debut'] < pmAffectation.data.calendar.plage[pmAffectation.current.plage]['fin']) {
					// c'est bon, on trim les dates
					// -1 sur la date de fin pour ne pas avoir de problèmes quand un créneau finit à minuit
					var _debutCreneau = new Date(Math.max(_dispos[_iDispo]['creneaux'][_iCreneau]['debut'].getTime(), 
															pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'].getTime()));
					// faut pas oublier de rajouter 1j, car les plages sont définies comme date du jour 00:00:00
					var _finCreneau = new Date(Math.min(_dispos[_iDispo]['creneaux'][_iCreneau]['fin'].getTime(), 
														pmAffectation.data.calendar.plage[pmAffectation.current.plage]['fin'].getTime()+86400000)-1);
												
					var _nbJour = 0; // compteur du nombre de jours
					var _todayMidnight = new Date(_debutCreneau); // bien forcer la recopie
					_todayMidnight.setHours(0, 0, 0, 0);
											
					do {
						_todayMidnight.setDate(_debutCreneau.getDate()+1)
						pmAffectation.views.calendar.placeCreneauOrga(pmAffectation.current.orga.id, _dispos, _iDispo, _iCreneau, _debutCreneau, (Math.min(_todayMidnight.getTime(), _finCreneau)-_debutCreneau)/1000, _nbJour++);
						_debutCreneau = new Date(_todayMidnight); // bien forcer la recopie
					} while (_debutCreneau.getDate() <= _finCreneau.getDate())
				}
			}
		}
	},
	// place les créneaux (mode tache)
	setCreneaux: function() {
		// on supprime tout
		$('.quart_heure').removeClass('free');
		$('.quart_heure').off('click'); // click handlers
		$('.creneau').remove();
		
		var _creneaux = pmAffectation.data.creneauxTaches;
		
		for (var _iCreneau in _creneaux) {
			// on vérifie si on est bien sur la bonne plage horaire, trim au besoin
			// comparaison "croisée" : permet de tenir compte des créneaux à cheval
			if (pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'] < _creneaux[_iCreneau]['fin']
				&& _creneaux[_iCreneau]['debut'] < pmAffectation.data.calendar.plage[pmAffectation.current.plage]['fin']) {
				// c'est bon, on trim les dates
				// -1 sur la date de fin pour ne pas avoir de problèmes quand un créneau finit à minuit
				var _debutCreneau = new Date(Math.max(_creneaux[_iCreneau]['debut'].getTime(), 
														pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'].getTime()));
				// faut pas oublier de rajouter 1j, car les plages sont définies comme date du jour 00:00:00
				var _finCreneau = new Date(Math.min(_creneaux[_iCreneau]['fin'].getTime(), 
													pmAffectation.data.calendar.plage[pmAffectation.current.plage]['fin'].getTime()+86400000)-1);
											
				var _nbJour = 0; // compteur du nombre de jours
				var _todayMidnight = new Date(_debutCreneau); // bien forcer la recopie
				_todayMidnight.setHours(0, 0, 0, 0);
										
				do {
					_todayMidnight.setDate(_debutCreneau.getDate()+1)
					pmAffectation.views.calendar.placeCreneauTache(_creneaux, _iCreneau, _debutCreneau, (Math.min(_todayMidnight.getTime(), _finCreneau)-_debutCreneau)/1000, _nbJour++);
					_debutCreneau = new Date(_todayMidnight); // bien forcer la recopie
				} while (_debutCreneau.getDate() <= _finCreneau.getDate())
			}
		}
	},
	// place une disponibilite (mode orga)
	placeDisponibilitesOrga: function(dateDebut, dateFin) {
		// on place les dispos, avec la classe et le click
		for (var _iDts = dateDebut; _iDts.getTime() < dateFin.getTime(); _iDts.setTime(_iDts.getTime()+15*60*1000)) {
			var _dateComplete = _iDts.getFullYear()+'-'+_iDts.getPMonth()+'-'+_iDts.getPDate();
			
			// on rend le titre du jour cliquable - on ne sait pas s'il y a déjà un event ou pas, donc unbind
			$('#titre_date_'+_dateComplete).unbind('click');
			$('#titre_date_'+_dateComplete).bind('click', {date: _dateComplete}, pmAffectation.controllers.calendar.clickJour);
			
			// sélection des quarts d'heure suivant les attributs de temps de plus en plus précis
			$('.jour[jour="'+_dateComplete+'"] > .heure[heure="'+_iDts.getHours()
			+'"] > .quart_heure[minute="'+_iDts.getMinutes()+'"]').addClass('free')
			.bind('click', {}, pmAffectation.controllers.calendar.clickQuartHeure);
		}
	},
	// place un créneau (mode orga)
	placeCreneauOrga: function(idOrga, dispos, idDispo, idCreneau, dateDebut, duree, nbJour) {
		var _prefixe = (nbJour !== 0) ? '>> ' : '';
				
		var _html = '<div id="creneau_'+dispos[idDispo]['creneaux'][idCreneau]['id']+'_'+nbJour+'" class="creneau">'+
					_prefixe+dispos[idDispo]['creneaux'][idCreneau]['plageHoraire']['tache']['nom']+'</div>';
		
		// on le rajoute, supprime le handler précédent et en rajoute un
		$('.jour[jour="'+dateDebut.getFullYear()+'-'+dateDebut.getPMonth()+'-'+dateDebut.getPDate()+'"] > .heure[heure="'+
		dateDebut.getHours()+'"] > .quart_heure[minute="'+dateDebut.getMinutes()+'"]').append(_html).off('click')
		.on('click', {indexDispo: idDispo, indexCreneau: idCreneau, idOrga: idOrga}, pmAffectation.controllers.calendar.clickCreneauOrga);
		
		// mise en forme
		$('#creneau_'+dispos[idDispo]['creneaux'][idCreneau]['id']+'_'+nbJour).height(Math.round(duree/60/60*40)+'px')
			.css('background', pmUtils.hexToRgba(dispos[idDispo]['creneaux'][idCreneau]['couleur'], 0.6));
	},
	// place un créneau (mode tâche)
	placeCreneauTache: function(creneaux, idCreneau, dateDebut, duree, nbJour) {
		var _prefixe = (nbJour !== 0) ? '>> ' : '';
				
		// on regarde s'il est affecté ou pas
		// pour un peu d'optimisation il faut faire un gros if
		var _opacite = 0.4;
		var _html;
		
		if (creneaux[idCreneau]['did']) {
			_opacite = 0.8;
			
			// il faut passer par un onclick, le on de jQuery de mange avec les flex-box
			_html = '<div id="creneau_'+creneaux[idCreneau]['id']+'_'+nbJour+'" class="creneau affecte"'+
						'onclick="pmAffectation.controllers.calendar.clickCreneauTache({indexCreneau: '+
						idCreneau+', idCreneau: '+creneaux[idCreneau]['id']+', affecte: true}, event)">'+
						_prefixe+creneaux[idCreneau]['plageHoraire']['tache']['nom']+'</div>';
		} else {
			_html = '<div id="creneau_'+creneaux[idCreneau]['id']+'_'+nbJour+'" class="creneau"'+
						'onclick="pmAffectation.controllers.calendar.clickCreneauTache({indexCreneau: '+
						idCreneau+', idCreneau: '+creneaux[idCreneau]['id']+', affecte: false}, event)">'+
						_prefixe+creneaux[idCreneau]['plageHoraire']['tache']['nom']+'</div>';
		}

		$('.jour[jour="'+dateDebut.getFullYear()+'-'+dateDebut.getPMonth()+'-'+dateDebut.getPDate()+'"] > .heure[heure="'+
		dateDebut.getHours()+'"] > .quart_heure[minute="'+dateDebut.getMinutes()+'"]').append(_html);
		
		// mise en forme
		$('#creneau_'+creneaux[idCreneau]['id']+'_'+nbJour).height(Math.round(duree/60/60*40)+'px')
			.css('background', pmUtils.hexToRgba(creneaux[idCreneau]['couleur'], _opacite));
	},

	
	/*
	 * Ouvre un tooltip avec le détails du créneau
	 * sur lequel on vient de cliquer
	 */
	showDetails: function(obj) {
		var _creneau = (obj.data.indexDispo) ? pmAffectation.data.dispos[obj.data.indexDispo]['creneaux'][obj.data.indexCreneau] : pmAffectation.data.creneauxTaches[obj.data.indexCreneau];
		
		// on prépare le contenu du pop-up
		var _html = '<div class="modal hide creneau_details" id="creneau_details">'+
					'<div class="modal-header"><a class="close" data-dismiss="modal">×</a>'+
					'<h3>Détails du créneau n°'+_creneau.id+'</h3></div>'+
					'<div class="modal-body"><ul>'+
					'<li><a href="'+pmAffectation.url+'tache/'+_creneau.plageHoraire.tache.id+'/edit" target="_blank">Tâche n°'+_creneau.plageHoraire.tache.id+'</a></li>'+
					'<li><strong>'+_creneau.plageHoraire.tache.nom+'</strong></li>'+
					'<li>Lieu : '+_creneau.plageHoraire.tache.lieu+'</li>'+
					'<li>'+_creneau.debut.getThisFormat('d/m H:I')+' - '+_creneau.fin.getThisFormat('d/m H:I')+'</li>'+
					'</ul></div><div class="modal-footer">'+
					'<button class="btn btn-warning" id="creneaumaker_'+_creneau.id+'" data-dismiss="modal">Ouvrir dans CréneauMaker</button>';
					
		(obj.data.idOrga) && (_html += '<button class="btn btn-danger" id="desaffect_'+_creneau.id+'" data-dismiss="modal">Désaffecter</button>');
		
    	_html += '<a href="#" class="btn" data-dismiss="modal">Fermer</a></div></div>';
		
		// on l'ouvre - modal de Twitter Bootstrap
		$(_html).modal();
		
		// bouton, lien pour la désaffectation - setter quand le popup est visible
		if (obj.data.idOrga) {
			$('#desaffect_'+_creneau.id).on('click', {idCreneau: _creneau['id'], idOrga: obj.data.idOrga}, function(obj) {
				pmAffectation.controllers.creneau.desAffectation(obj.data.idCreneau, obj.data.idOrga);
			});
		}
		$('#creneaumaker_'+_creneau.id).on('click', {idTache: _creneau['plageHoraire']['tache']['id']}, function(obj) {
			var _popup = window.open(pmAffectation.urls.creneauMaker+'/'+obj.data.idTache, '');
					
			// à la fermeture, refresh la liste des créneaux
			// unload est firé au chargement (unload de about:blank),
			// on attache le vrai handler qu'après le chargement initial donc
			_popup.onunload = function() {
				_popup.bind('unload', pmAffectation.controllers.orga.getDispos());
			};
		});
	}
}
