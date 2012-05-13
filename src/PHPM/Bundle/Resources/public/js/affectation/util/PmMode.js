/*
 * Page affectation
 * Helper offrant une "abstraction" du mode,
 * permettant de simplifier le code
 */

/*
 * Création du namespace et utils
 */
function PmMode() {
	this.master = {};
	this.slave = {};
	this.calendar = {};
}
	
/*
 * Fonctions
 */
PmMode.prototype = {
	/*
	 * Set les boutons pour changer de mode
	 */
	setBoutons: function() {
		$('#bouton_mode_orga').bind('click', {mode: 'orga'}, pmAffectation.controllers.calendar.changeMode);
		$('#bouton_mode_tache').bind('click', {mode: 'tache'}, pmAffectation.controllers.calendar.changeMode);
	},
	
	/*
	 * Set le mode de l'application :
	 * Orga -> Créneau ou Tache -> Orga
	 */
	setMode: function(mode) {
		(mode) && (pmAffectation.current.mode = mode);
		
		// TODO : off les bind
		
		pmUtils.setDefault();
		
		if (pmAffectation.current.mode === 'orga') {
			pmAffectation.current.tache.id = -1;
			pmHistory.setUrlParam();
			
			// on filtre les boutons
			$('#boutons_orga_nav').show();
			$('#boutons_tache_nav').hide();
			$('bouton_orga_detail').show();
			$('bouton_tache_detail').hide();
			$('#bouton_mode_orga').button('toggle');
			
			// on va chercher la colonne orgas
			pmAffectation.controllers.orga = new OrgaController();
			pmAffectation.controllers.orga.getData();
	
			// colonne tache - dedans on met des créneaux
			pmAffectation.controllers.creneau = new CreneauController();
			// pas besoin d'aller chercher des données dedans
			
			// on met à jour les références
			this.master.update = function() { pmAffectation.controllers.orga.getData() };
			this.calendar.update = function() { pmAffectation.controllers.orga.getDispos() };
			this.slave.update = function() { pmAffectation.controllers.creneau.getData() };
		} else if (pmAffectation.current.mode === 'tache') {
			pmAffectation.current.orga.id == -1;
			pmHistory.setUrlParam();
			
			// on filtre les boutons
			$('#boutons_orga_nav').hide();
			$('#boutons_tache_nav').show();
			$('bouton_orga_detail').hide();
			$('bouton_tache_detail').show();
			$('#bouton_mode_tache').button('toggle');
			
			// on va chercher la colonne tache
			pmAffectation.controllers.tache = new TacheController();
			pmAffectation.controllers.tache.getData();
	
			// colonne orga
			pmAffectation.controllers.orga = new OrgaController();
			// pas besoin d'aller chercher des données dedans
			
			// on met à jour les références
			this.master.update = function() { pmAffectation.controllers.tache.getData() };
			this.calendar.update = function() { pmAffectation.controllers.tache.getCreneaux() };
			this.slave.update = function() { pmAffectation.controllers.orga.getData() };
		}
	},
}