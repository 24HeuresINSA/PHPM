/*
 * Page Affectation
 * Modèle Orga
 */

function OrgaModel() {
	// on lance juste le constructeur
	this.initialize();
}

OrgaModel.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
	},
	
	/*
	 * Lance les requêtes
	 */
	getData: function(callBack) {
		this.callBack = callBack;
				
		// construit les paramètres que l'on va envoyer, -1 est le wildcart
		var _params = {
			plage_id: pmAffectation.current.plage, // on fournit toujours la plage, la base
		};
		
		// filtres
		($.isNumeric(pmAffectation.current.orga.confiance) === true) && (pmAffectation.current.orga.confiance != -1) && (_params.confiance_id = pmAffectation.current.orga.confiance);
		($.isNumeric(pmAffectation.current.orga.permis) === true) && (pmAffectation.current.orga.permis != -1) && (_params.annee_permis = pmAffectation.current.orga.permis);
		($.isNumeric(pmAffectation.current.orga.age) === true) && (pmAffectation.current.orga.age != -1) && (_params.age = pmAffectation.current.orga.age);
		($.isNumeric(pmAffectation.current.orga.equipe) === true) && (pmAffectation.current.orga.equipe != -1) && (_params.equipe_id = pmAffectation.current.orga.equipe);
		
		// filtres pour le sens tâches ->
		($.isNumeric(pmAffectation.current.tache.id) === true) && (pmAffectation.current.tache.id != -1) && (_params.equipe_id = pmAffectation.current.orga.equipe);
		// TODO : relire ligne précédente
		($.isNumeric(pmAffectation.current.creneau.id) === true) && (pmAffectation.current.creneau.id != -1) && (_params.creneau_id = pmAffectation.current.creneau.id);

		$.ajax({
			url: pmAffectation.urls.orgas,
			dataType: 'json',
			data: _params,
			success: pmAffectation.models.orga.requestSuccess,
			error: pmAffectation.models.orga.requestError,
			type: 'POST'
		});
	},
	getDataDispos: function(callBackDispos) {
		this.callBackDispos = callBackDispos;
				
		// construit les paramètres que l'on va envoyer, -1 est le wildcart
		var _params = {
			plage_id: pmAffectation.current.plage, // on fournit toujours la plage, la base
			orga_id: pmAffectation.current.orga.id
		};
		
		$.ajax({
			url: pmAffectation.urls.dispos,
			dataType: 'json',
			data: _params,
			success: pmAffectation.models.orga.requestSuccessDispos,
			error: pmAffectation.models.orga.requestError,
			type: 'POST'
		});
	},
	// permet de changer le statut d'un orga
	changeStatut: function(callback, idOrga, nouveauStatut) {
		this.callbackChangeStatut = callback;
		
		$.ajax({
			url: pmAffectation.urls.orgaChangeStatut.replace(new RegExp('(_ID_)', 'g'), idOrga).replace(new RegExp('(_STATUT_)', 'g'), nouveauStatut),
			dataType: 'json',
			success: pmAffectation.models.orga.requestSuccessChangeStatut,
			error: pmAffectation.models.orga.requestError,
			type: 'GET'
		});	
	},
	
	/*
	 * Récup les résultats
	 */
	requestSuccess: function(data) {
		// attention, on reçoit un tableau (l'ordre compte)
		
		pmAffectation.models.orga.data = data;
	
		pmAffectation.models.orga.callBack();
	},
	requestSuccessDispos: function(data) {
		pmAffectation.models.orga.dataDispo = data;
	
		pmAffectation.models.orga.callBackDispos();
	},
	requestSuccessChangeStatut: function(data) {
		if (data === 'OK') {
			pmAffectation.models.orga.callbackChangeStatut();
		} else {
			pmAffectation.models.orga.requestError(null, 'impossible de changer le statut de cet orga');
		}
	},
	requestError: function(data, statusText) {
		pmMessage.alert("Impossible de récupérer les orgas : " + statusText);
	},
	
	/*
	 * Getters des résultats
	 */
	// récupère la liste des orgas
	getOrgas: function() {	
		var _orgas = {};
		
		// traitement des orgas
		for (var _iOrga in this.data) {
			var _orga = {};
			
			// récupère toutes les données
			for (var _iChamp in this.data[_iOrga]) {
				_orga[_iChamp] = this.data[_iOrga][_iChamp];
			}
			
			// "conversion" de la date de naissance
			_orga['dateDeNaissance'] = new Date(this.data[_iOrga]['dateDeNaissance']);
		
			_orgas[_iOrga] = _orga;
		}
		
		return _orgas;
	},
	getDispos: function() {
		var _dispos = {};
		
		// traitement des orgas
		for (var _iDispo in this.dataDispo) {
			var _dispo = {};
			
			// récupère toutes les données
			for (var _iChamp in this.dataDispo[_iDispo]) {
				_dispo[_iChamp] = this.dataDispo[_iDispo][_iChamp];
			}
			
			// re-traitement de plusieurs champs
			_dispo['debut'] = new Date(this.dataDispo[_iDispo]['debut']['date']);
			_dispo['fin'] = new Date(this.dataDispo[_iDispo]['fin']['date']);
			
			// créneaux, encore un niveau
			for (var _iCreneau in this.dataDispo[_iDispo]['creneaux']) {
				_dispo['creneaux'][_iCreneau]['debut'] = new Date(this.dataDispo[_iDispo]['creneaux'][_iCreneau]['debut']['date']);
				_dispo['creneaux'][_iCreneau]['fin'] = new Date(this.dataDispo[_iDispo]['creneaux'][_iCreneau]['fin']['date']);
			
				_dispo['creneaux'][_iCreneau]['couleur'] = pmAffectation.data.parameter.equipes[this.dataDispo[_iDispo]['creneaux'][_iCreneau]['plageHoraire']['tache']['groupeTache']['id']]['couleur'];
			}
		
			_dispos[_iDispo] = _dispo;
		}
		
		return _dispos;
	}
}
