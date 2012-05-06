/*
 * Page affectation
 * Librairie permettant de travailler avec l'historique & l'url :
 * parser les paramètres en hash, lancer les requêtes...
 */

/*
 * Création du namespace et utils
 */
function PmHistory() {
	this.refreshData = false;
}
	
/*
 * Fonctions
 */
PmHistory.prototype = {
	/*
	 * Init
	 * Set l'event listener sur le changelment d'url
	 * Pas mal de code repris du Github officiel d'History.js (GH)
	 */ 
	initHistoryListener: function() {
	    History = window.History; // Note: We are using a capital H instead of a lower h
	    
	    if (! History.enabled) {
	         // History.js is disabled for this browser.
	         // This is because we can optionally choose to support HTML4 browsers or not.
	        return false;
	    }
	
	    // on va écouter le changement d'adresse, dessus on reparse
	    History.Adapter.bind(window, 'anchorchange', function() { // Adapté pour utiliser anchorchange, sur le hash, au lieu de statechange (popstate marche aussi)
	        pmHistory.parseUrlParam(true);
	    });
	},
	
	/*
	 * Fonction de parsage des paramètres
	 * On les prend, regarde s'ils sont différents des valeurs que l'on a déjà,
	 * va les mettre au bon endroit & relance ce qu'il faut
	 */
	parseUrlParam: function() {
		var _hash = History.getHash(); // le hash est ce qui suit le # (non inclus)
		
		// on récupère les éventuels paramètres passés en GET
		if (location.search !== '') {
			var _get = location.search.substring(1, location.search.length);
			var _parameters = $.deparam(_get);
			
			// paramètre _orga
			if (_parameters.orga_id) {
				pmAffectation.current.orga.id = _parameters.orga_id;
			}
			
			// on re-set les paramètres
			History.replaceState('Paramètre GET parsé', 'Paramètre GET parsé', History.getState().url.split("?")[0]);
		}
		
		if (_hash.substr(0, 6) == 'param&') { // parseur - on a reconnu notre format
			var _str = decodeURIComponent(_hash.substr(6, _hash.length)); // petite décodage du format URL nécessaire
			
			// on décode ça (fonction cf hack.js)
			var _params = $.deparam(_str);

			for (var _iParam in _params) {
				// pour chaque ligne, on va regarder ce qu'il faut faire
				if (pmAffectation.current[_iParam] === undefined) { // il n'existe pas, on créé la valeur
					pmAffectation.current[_iParam] = _params[_iParam];
				} else {
					// sinon faut tester, voir si on met à jour la valeur
					switch (_iParam) {
						case 'orga':
							if (pmUtils.areEquals(_params['orga']['id'], pmAffectation.current['orga']['id']) === false) {
								pmAffectation.current['orga'] = _params['orga'];
								(pmHistory.refreshData === true) && (pmAffectation.controllers.orga.getDispos());
							} else if (pmUtils.areEquals(_params['orga'], pmAffectation.current['orga']) === false) {
								pmAffectation.current['orga'] = _params['orga'];
								(pmHistory.refreshData === true) && (pmAffectation.controllers.orga.getData());
							}
							break;
						case 'plage':
							if (pmUtils.areEquals(_params['plage'], pmAffectation.current['plage']) === false) {
								pmAffectation.current['plage'] = _params['plage'];
								(pmHistory.refreshData === true) && (pmAffectation.controllers.calendar.getData());
							}
							break;
						case 'quart_heure':
							if (pmUtils.areEquals(_params['quart_heure'], pmAffectation.current['quart_heure']) === false) {
								pmAffectation.current['quart_heure'] = _params['quart_heure'];
								
								// on simule un click, bien plus simple, trop d'opés à réaliser sinon
								if (pmHistory.refreshData === true) {
									pmAffectation.controllers.calendar.clickQuartHeure({
										currentTarget: {id: pmAffectation.current['quart_heure']}
									});
								}
							}
							break;
						default:
							// autres cas, on a pas de refresh de donnée à lancer
							if (_params[_iParam] != pmAffectation.current[_iParam]) {
								pmAffectation.current[_iParam] = _params[_iParam];
							}
							break;
					}
				}
			}
		} else {
			// sinon, soit c'était vide soit c'était de la bullshit, on écrase avec ce qu'il faut
			pmHistory.setUrlParam();
		}
	},
	
	/*
	 * Fonction appelée lors de l'udpate d'un paramètres
	 * Va changer le hash en fonction
	 */
	setUrlParam: function() {
		// concrètement, pour ne pas avoir de problèmes, on reconstruit l'url entière
		
		// on va déjà filtrer pour virer les -1
		var _current = pmUtils.filter(pmAffectation.current, function(key, val) {return (val != -1)}, true);
		
		// Jquery goodness for sérialiser rapidemment (et en profondeur)
		// bien préciser 'data' et 'title' dans History.pushState, sinon elle peut bugguer
		var _newHash = '#param&' + $.param(_current);
		
		// à savoir : History.js unescape le hash, mais pas l'état et le titre,
		// qui peuvent le faire planter aléatoirement
        History.pushState({params: escape(_newHash)}, "Etat " + escape(_newHash), _newHash);
	},
}