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
			// handler de click via le onClick, seule solution comme le DOM n'est pas encore construit
			// onClick="pmAffectation.controllers.calendar.clickJour(\''+_dateComplete+'\')"
			_htmlBarreDates += '<div class="titre_date" jour="'+_dateComplete+'" id="titre_date_'+_dateComplete+'" '
								+'style="width: '+100/_nbJours+'%;">'+pmUtils.jours[_date.getDay()]+' '+_date.getThisFormat('d/m') +'</div>';
		
			_htmlJours += this.makeADay(_dateComplete, _date.getDay(), _nbJours);
		}
		
		$('#calendar').append(_htmlBarreDates+'</div><div class="jours" id="jours">'+_htmlHours+_htmlJours+'</div>');
		
		// on sélectionne le bon quart d'heure
		$('#'+pmAffectation.current['quart_heure']).addClass('current');
		
		if (Object.keys(pmAffectation.data.calendar.plage).length != 0) {
			pmAffectation.views.calendar.setBoutonsPlage();
			
			// on met le bon effet sur le bon bouton
			$('#bouton_plage_'+pmAffectation.current.plage).button('toggle');
		}
		
		// on redimensionne l'app comme il faut
		pmUtils.setAppHeight();
	},
	// fabrique un jour
	makeADay: function(date, jourSemaine, nbJours) {
		var _html = '<div class="jour" id="jour_'+date+'" jour="'+date+'" style="width: '+100/nbJours+'%;">';
		
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
	 */
	setFrees: function(obj) {
		// on supprime tout
		$('.quart_heure').removeClass('free');
		$('.quart_heure').off(); // click handlers
		$('.creneau').remove();
		
		if (obj.type === 'orga') {
			if (pmAffectation.data.orgas[obj.id] !== undefined) { // check si l'orga existe bien
				for (var _iDispo in pmAffectation.data.orgas[obj.id]['disponibilites']) {
					// astuce importante : on force la copie en re-créant un objet Date
					var _debut = new Date(pmAffectation.data.orgas[obj.id]['disponibilites'][_iDispo]['debut'].getTime());
					var _fin = pmAffectation.data.orgas[obj.id]['disponibilites'][_iDispo]['fin'];
					
					// on appelle une fonction qui va placer les disponibilités
					pmAffectation.views.calendar.placeDisponibilites(_debut, _fin);

					// on place les créneaux - j'ai passé 4 heures à optimiser le truc, fais gaffe à ce que tu touches
					for (var _iCreneau in pmAffectation.data.orgas[obj.id]['disponibilites'][_iDispo]['creneaux']) {
						// on vérifie si on est bien sur la bonne plage horaire, trim au besoin
						// comparaison "croisée" : permet de tenir compte des créneaux à cheval
						if (pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'] <= pmAffectation.data.orgas[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin'] 
							&& pmAffectation.data.orgas[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin'] >= pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'].getTime()) {
							// c'est bon, on trim les dates
							// -1 sur la date de fin pour ne pas avoir de problèmes quand un créneau finit à minuit
							var _debutCreneau = new Date(Math.max(pmAffectation.data.orgas[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut'].getTime(), pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'].getTime()));
							var _finCreneau = new Date(Math.min(pmAffectation.data.orgas[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin'].getTime(), pmAffectation.data.calendar.plage[pmAffectation.current.plage]['fin'].getTime())-1);	

							var _nbJour = 0; // compteur du nombre de jours
							var _todayMidnight = new Date(_debutCreneau); // bien forcer la recopie
							_todayMidnight.setHours(0, 0, 0, 0);
							
							do {
								_todayMidnight.setDate(_debutCreneau.getDate()+1)
								pmAffectation.views.calendar.placeCreneau(obj.id, _iDispo, _iCreneau, _debutCreneau, (Math.min(_todayMidnight.getTime(), _finCreneau)-_debutCreneau)/1000, _nbJour++);
								_debutCreneau = new Date(_todayMidnight); // bien forcer la recopie
							} while (_debutCreneau.getDate() <= _finCreneau.getDate())
						}
					}
				}
			}
		} else if (obj.type === 'tache') {
			for (var _iCreneau in pmAffectation.data.tache[obj.id]['creneaux']) {
				// astuce importante : on force la copie en re-créant un objet Date
				var _debut = new Date(pmAffectation.data.tache[obj.id]['creneaux'][_iCreneau]['debut'].getTime());
				var _fin = pmAffectation.data.tache[obj.id]['creneaux'][_iCreneau]['fin'];
				
				// on appelle une fonction qui va placer les créneaux non affectés
				pmAffectation.views.calendar.placeDisponibilites(_debut, _fin);

				// on place les créneaux - j'ai passé 4 heures à optimiser le truc, gaffe à la modificaton
				/*for (var _iCreneau in pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['creneaux']) {
					// on récupère les dates trimmées par rapport à la plage
					// _1 sur la date de fin pour ne pas avoir de problèmes quand un créneau finit à minuit
					var _debutCreneau = new Date(Math.max(pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['debut'].getTime(), pmAffectation.data.calendar.plage[pmAffectation.current.plage]['debut'].getTime()));
					var _finCreneau = new Date(Math.min(pmAffectation.data.orga[obj.id]['disponibilites'][_iDispo]['creneaux'][_iCreneau]['fin'].getTime(), pmAffectation.data.calendar.plage[pmAffectation.current.plage]['fin'].getTime())-1);	
					
					var _nbJour = 0; // compteur du nombre de jours
					var _todayMidnight = new Date(_debutCreneau); // bien forcer la recopie
					_todayMidnight.setHours(0, 0, 0, 0);
					
					do {
						_todayMidnight.setDate(_debutCreneau.getDate()+1)
						pmAffectation.views.calendar.placeCreneau(obj.id, _iDispo, _iCreneau, _debutCreneau, (Math.min(_todayMidnight.getTime(), _finCreneau)-_debutCreneau)/1000, _nbJour++);
						_debutCreneau = new Date(_todayMidnight); // bien forcer la recopie
					} while (_debutCreneau.getDate() <= _finCreneau.getDate())
				}*/
			}
		}
	},
	// place une disponibilite
	placeDisponibilites: function(dateDebut, dateFin) {
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
	// place un créneau
	placeCreneau: function(idOrga, idDispo, idCreneau, dateDebut, duree, nbJour) {
		var _prefixe = (nbJour !== 0) ? '>> ' : '';
		
		var _html = '<div id="creneau_'+idCreneau+'_'+nbJour+'" class="creneau" orga="'+idOrga+
				'" disponibilite="'+idDispo+'" creneau="'+idCreneau+'_'+nbJour+'">'+_prefixe+
				pmAffectation.data.orgas[idOrga]['disponibilites'][idDispo]['creneaux'][idCreneau]['tache']['nom']+'</div>';
		
		// on le rajoute, supprime le handler précédent et en rajoute un
		$('.jour[jour="'+dateDebut.getFullYear()+'-'+dateDebut.getPMonth()+'-'+dateDebut.getPDate()+'"] > .heure[heure="'+
		dateDebut.getHours()+'"] > .quart_heure[minute="'+dateDebut.getMinutes()+'"]').append(_html).off('click')
		.bind('click', {idOrga: idOrga, idDispo: idDispo, idCreneau: idCreneau}, pmAffectation.controllers.calendar.clickCreneau);
		
		// set la taille - et -10px à cause du padding vertical de 5px
		$('#creneau_'+idCreneau+'_'+nbJour).height(duree/60/60*40-10+'px');
		
		// on lui met une couleur fonction du n° de la tâche
		$('#creneau_'+idCreneau+'_'+nbJour).css('background', pmAffectation.data.orgas[idOrga]['disponibilites'][idDispo]['creneaux'][idCreneau]['tache']['couleur']);
	},
	
	/*
	 * Ouvre un tooltip avec le détails du créneau
	 * sur lequel on vient de cliquer
	 */
	showDetails: function(obj) {
		var _creneau = pmAffectation.data.orgas[obj.data.idOrga]['disponibilites'][obj.data.idDispo]['creneaux'][obj.data.idCreneau];
		
		// on prépare le contenu du pop-up
		var _html = '<ul>' +
					'<li><a href="'+pmAffectation.url+'tache/'+_creneau.tache.id+'/show" target="_blank">Tâche n°'+_creneau.tache.id+'</a></li>'+
					'<li>'+_creneau.tache.nom+'</li>'+
					'<li>'+_creneau.tache.lieu+'</li>'+
					'<li>'+_creneau.debut.getMyTime()+' - '+_creneau.fin.getMyTime()+'</li>'+
					'<li><button id="desaffect_'+obj.data.idCreneau+'">Désaffecter</button></li>'+
					'</ul>';
		
		// on l'ouvre
		$('<div id="popup">'+_html+'</div>').dialog({
			closeText: 'fermer',
			close: function() {$('#popup').remove();}, // sur le close, on détruit le DOM, évite certains bugs
			dialogClass: 'creneau_details popup',
			draggable: false,
			modal: true, // certes, mais on a un handler qui le ferme si on clique ailleurs
			position: [obj.pageX-150, obj.pageY],
			resizable: false,
			title: 'Créneau n°'+obj.data.idCreneau
		});
		
		// bouton, lien pour la désaffectation - setter quand le popup est visible
		$('#desaffect_'+obj.data.idCreneau).button().click(function() {	pmAffectation.controllers.creneau.desAffectation(obj.data.idCreneau, obj.data.idOrga) });
		
		pmUtils.setPopupClose(); // pour sa fermeture, un seul popup à la fois
	},

	/* 
	 * Set les boutons pour changer de mode
	 */
	initMode: function() {
		$('#bouton_mode_orga').bind('click', {mode: 'orga'}, pmAffectation.controllers.calendar.changeMode);
		$('#bouton_mode_tache').bind('click', {mode: 'tache'}, pmAffectation.controllers.calendar.changeMode);
	},
}
