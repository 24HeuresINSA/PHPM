/*
 * Page affectation
 * Petite librairie spécifique
 */

/*
 * Création du namespace et utils
 */
function PmUtils() {
	// les jours de la semaine
	this.jours = new Array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');
}
	
/*
 * Fonctions
 */
PmUtils.prototype = {
	/* ******************** */
	/* ****** LAYOUT ****** */
	/* ******************** */
	
	/*
	 * Adapte la taille du layout
	 */
	setAppHeight: function() {
		var _headerHeight = $('#header').height();
		var _contentHeight = $(window).height()-_headerHeight;
		var _clientHeight = _contentHeight-$('#menu_calendar').outerHeight()-3; // 3 de border tout en bas
		
		$('#content').height(_contentHeight+'px');
		$('#client').height(_clientHeight+'px');
		$('#jours').height(_clientHeight-30+'px');
	},
	// fonction appelée lorsqu'on touche au calendrier
	setResizeableApp: function() {
		window.onresize = pmUtils.setAppHeight;
		
		pmUtils.setAppHeight();
	},
	
	/*
	 * Pour les sidebars redimensionnables
	 */
	setResizeableSidebars: function() {
		$(function() {
			$("#sidebar_orga").resizable({
									minWidth: 150,
									maxWidth: 400,
									autoHide: true,
									handles: 'e',
									resize: pmUtils.hideCalendar,
									stop: pmUtils.resizeHandler
									});
			
			$("#sidebar_tache").resizable({
									minWidth: 150,
									maxWidth: 400,
									autoHide: true,
									handles: 'w',
									resize: pmUtils.hideCalendar,
									stop: pmUtils.resizeHandler
									});
		});
	},
	// handler pendant le resize
	hideCalendar: function() {
		 $('#calendar').css('visibility', 'hidden');
	},
	// handler de fin
	resizeHandler: function(event, ui) {
		$('#calendar').css('visibility', 'visible'); // raffiche
		
		pmUtils.resizeCalendar(ui.originalSize.width-ui.size.width);
		
		// on stock ces tailles dans les paramètres de l'utilisateur
		// volontairement on travaille en pixels
		pmUtils.setLocalStorage('SizeSidebarOrga', $('#sidebar_orga').width());
		pmUtils.setLocalStorage('SizeSidebarTache', $('#sidebar_tache').width());
	},
	// resize le calendar et tout ce qui va avec
	resizeCalendar: function(deltaTaille) {
		// on convertit tout en % pour mieux gérer le redimensionnement de la fenêtre
		var _newWidth = ($('#calendar').width()+deltaTaille)/$('.content').width()*100;
		$('#calendar').width(_newWidth+'%');

		// même chose pour les sidebars
		pmUtils.setPourcentWidth('#sidebar_orga');
		pmUtils.setPourcentWidth('#sidebar_tache');
	},
	
	/*
	 * Fonction disant aux popups (modaux)
	 * de se fermer quand on clique ailleurs
	 */
	setPopupClose: function() {
		$(".ui-widget-overlay").click (function () {
		    $('#popup').dialog('close');
		});
	},
	

	/* ******************** */
	/* ***** JOURNAL ****** */
	/* ******************** */
	/*
	 * "Log" dans le journal une affectation
	 * ou une désaffectation
	 */
	logAction: function(typeAction, idCreneau, idOrga) {
		pmAffectation.journal.push({type: typeAction, idCreneau: idCreneau, idOrga: idOrga});
	},
	
	
	/* ******************** */
	/* *** UTILITAIRES **** */
	/* ******************** */
	/*
	 * Retourne la taille de l'élément (jQuery) passé
	 * en pourcentage %
	 */
	getPourcentWidth: function(unElement, elementRelatif) {
		if ($(elementRelatif).width() === null) {
			var _rapport = $(document);
		} else {
			var _rapport = $(elementRelatif);
		}
		
		return $(unElement).width()/_rapport.width()*100;
	},
	/* 
	 * Même chose, l'applique
	 */
	setPourcentWidth: function(unElement, elementRelatif) {
		$(unElement).width(pmUtils.getPourcentWidth(unElement, elementRelatif)+'%');
	},
	
	/*
	 * Stockage et retrieve dans localStorage
	 */
	setLocalStorage: function(uneClef, unElement) {
		try {
			localStorage[uneClef] = JSON.stringify(unElement);
		} catch(err) {
			console.error("Impossible d'accéder à localStorage",err);
		}
	},
	getLocalStorage: function(uneClef) {
		try {
			var _value = localStorage[uneClef];
			
			if (_value !== undefined) {
				return $.parseJSON(_value);
			} else {
				return undefined;
			}
		} catch(err) {
			console.error("Impossible d'accéder à localStorage",err);
		}
	},
	
	/*
	 * Tri un objet associatif - source :
	 * http://www.latentmotion.com/how-to-sort-an-associative-array-object-in-javascript/
	 */
	sortObject: function(arr) {
		// Setup Arrays
		var sortedKeys = new Array();
		var sortedObj = {};
	
		// Separate keys and sort them
		for (var i in arr){
			sortedKeys.push(i);
		}
		sortedKeys.sort();
	
		// Reconstruct sorted obj based on keys
		for (var i in sortedKeys){
			sortedObj[sortedKeys[i]] = arr[sortedKeys[i]];
		}
		
		return sortedObj;
	},
	
	/*
	 * Permet de comparer 2 objects
	 * Fonction perso, rapide se basant sur la représentation JSON
	 */
	areEquals: function(xObj, yObj) {
		if (JSON.stringify(xObj) === JSON.stringify(yObj)) {
			return true;
		} else {
			return false;
		}
	},
	
	/*
	 * Petite fonction permettant de retourner une string 'myDts'
	 * A partir d'une date hashée (comme dans les paramètres de l'url)
	 * Ne pas chercher à faire autrement, trop de chars spéciaux pour passer la date dans l'url !
	 */
	getDateTimeBack: function(str) {
		if (str == -1) {
			return -1; // rien à parser, c'est le wildcart
		}
		
		var _tab = str.split('_'); // pour récupérer le hash de fin
		var _date = _tab[2].split('-');
		
		// on construit et retourne le tout sous forme d'un string
		return _date[0]+'/'+pmUtils.pad2(_date[1])+'/'+pmUtils.pad2(_date[2])+' '+pmUtils.pad2(_date[3])+':'+pmUtils.pad2(_date[4])+':00';
	},
	
	/*
	 * S'assurer qu'un nombre est bien sur 2 chiffres,
	 * Rajoutant un 0 au besoin
	 * Source : http://www.electrictoolbox.com/pad-number-two-digits-javascript/
	 * Petite amélioration : cast du number pour virer un potentiel 0 non significatif
	 */
	pad2: function(number) {
		return (number < 10 ? '0' : '') + Number(number);
	},

	/*
	 * Permet de parcourir récursivement un objet littéral
	 * et de le filtrer avec la fonction callback(key, value)
	 * La valeur n'est gardée que si callback renvoie true
	 */
	filter: function(object, callback) {
		var _result = {};
		
		for (var _i in object) {
			if (typeof(object[_i]) === "object") {
				_result[_i] = pmUtils.filter(object[_i], callback);
			} else if (typeof(object[_i]) !== "function") {
				if (callback(_i, object[_i]) === true) {
					_result[_i] = object[_i];
				}
			}
		}
		
		return _result;
	},
	
	/*
	 * Set le mode de l'application :
	 * Orga -> Créneau ou Tache -> Orga
	 */
	setMode: function(mode) {
		if (mode === 'orga') {
			$('#boutons_tache').hide();
			$('#boutons_orga').show();
			
			// on va chercher la colonne orgas
			pmAffectation.controllers.orga = new OrgaController();
			pmAffectation.controllers.orga.getData();
	
			// colonne tache - dedans on met des créneaux
			pmAffectation.controllers.creneau = new CreneauController();
			// pas besoin d'aller chercher des données dedans
		} else if (mode === 'tache') {
			$('#boutons_orga').hide();
			$('#boutons_tache').show();
			
			// on va chercher la colonne tche
			pmAffectation.controllers.tache = new TacheController();
			pmAffectation.controllers.tache.getData();
	
			// colonne orga
			pmAffectation.controllers.orga = new OrgaController();
			// pas besoin d'aller chercher des données dedans
		}
	},
		
};

