/*
 * Page Affectation
 * Modèle Calendar
 */

function CalendarModel() {
	// on lance juste le constructeur
	this.initialize();
}

CalendarModel.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
	},
	
	/*
	 * Lance les requêtes
	 */
	getData: function(callBack) {
		pmAffectation.models.calendar.callBack = callBack;
		
		$.ajax({
			url: pmAffectation.urls.plages,
			dataType: 'json',
			success: pmAffectation.models.calendar.requestSuccess,
			error: pmAffectation.models.calendar.requestError,
			type: 'GET',
			async: false
		});
	},
	
	/*
	 * Récup les résultats
	 */
	requestSuccess: function(data) {
		pmAffectation.models.calendar.data = data;
	
		pmAffectation.models.calendar.callBack();
	},
	requestError: function(data, statusText) {
		pmMessage.alert("Impossible de récupérer les plages : " + statusText);
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère les plages (de jours) définies
	getPlages: function() {	
		var _plages = {};
		
		// cas où rien n'a été défini
		if (Object.keys(this.data).length === 0) {
			pmMessage.alert("Aucune plage n'a été définie");
		}
		
		// on fait des conversions vers des objets time javascript
		for (unePlage in this.data) {
			_plages[unePlage] = {
									nom: this.data[unePlage]["nom"],
									debut: new Date(this.data[unePlage]["debut"]),
									fin: new Date(this.data[unePlage]["fin"])
								};
		}
		
		return _plages;
	},

	/*
	 * Réalise la (dés)affectation entre un créneau et un orga
	 * @param 'sens' : 'affecter' ou 'desaffecter'
	 * On gère 2 callbacks différents, c'est plus robuste
	 */
	affecterCreneau: function(sens, idCreneau, idOrga, callBack) {
		if (sens === 'affecter') {
			this.callBackAffectation = callBack;
		} else {
			this.callBackDesaffectation = callBack;
		}
		
		$.ajax({
			url: pmAffectation.url+pmAffectation.paths.affecter+idCreneau+'/'+sens+'/'+idOrga,
			dataType: 'text',
			success: function(data) {pmAffectation.models.calendar.affectationSuccess(data, sens)},
			error: pmAffectation.models.calendar.requestError,
			type: 'POST'
		});	
	},
	// les callbacks
	affectationSuccess: function(data, sens) {
		// on test ce qui le serveur nous a retourné
		if (data == "OK") {
			(sens === 'affecter') ? pmAffectation.models.calendar.callBackAffectation() : pmAffectation.models.calendar.callBackDesaffectation();
		} else {
			pmMessage.alert("Impossible de réaliser l'opération : "+data);
		}
	},
}
