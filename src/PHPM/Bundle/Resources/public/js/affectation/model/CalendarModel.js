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
			url: pmAffectation.url+pmAffectation.paths.plages,
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
		message.error("Impossible de récupérer les plages : " + statusText);
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère les plages (de jours) définies
	getPlages: function() {	
		var _plages = {};
		
		// cas où rien n'a été défini
		if (Object.keys(this.data).length === 0) {
			message.error("Aucune plage n'a été définie");
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

}
