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
		
		$.getJSON(pmAffectation.url+pmAffectation.paths.plages, pmAffectation.models.calendar.requestSuccess);
	},
	
	/*
	 * Récup les résultats
	 */
	requestSuccess: function(data, statusText) {
		if (statusText == "success") {
			pmAffectation.models.calendar.data = data;
		
			pmAffectation.models.calendar.callBack();
		} else {
			console.error("Impossible de récupérer les plages : ", statusText);
		}
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère les plages (de jours) définies
	getPlages: function() {	
		var _plages = {};
		
		// on fait des conversions vers des objets time javascript
		for (unePlage in this.data) {
			_plages[unePlage] = {
									nom: this.data[unePlage]["nom"],
									debut: new Date(this.data[unePlage]["debut"]),
									fin: new Date(this.data[unePlage]["fin"])
								};
		}
		
		return _plages;
	}
}
