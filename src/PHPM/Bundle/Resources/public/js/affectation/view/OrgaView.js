/*
 * Page Affectation
 * Vue des orgas
 */
function OrgaView() {
	// on lance juste le constructeur
	this.initialize();
}

OrgaView.prototype = {
	/*
	 * Constructeur
	 */
	initialize: function() {
		// on écoute les boutons
		$('#bouton_orga_prev').off('click').on('click', function() { $('#orga_'+pmAffectation.current.orga.id).prev().click(); });
		$('#bouton_orga_next').off('click').on('click', function() { $('#orga_'+pmAffectation.current.orga.id).next().click(); });
		$('#bouton_orga_refresh').off('click').on('click', function() { pmAffectation.controllers.orga.getData(); });
		$('#bouton_orga_detail').off('click').on('click', function() { pmAffectation.views.orga.viewOrgaDetails({id: pmAffectation.current.orga.id}); });
		
		this.setFilters(); // met les bonnes valeurs dans les filtres
		
		// filtres : bind les events
		$('#filtre_orga_confiance').off('change').on('change', function() { pmAffectation.controllers.orga.clickFilter('confiance', $('#filtre_orga_confiance').val()); });
		$('#filtre_orga_equipe').off('change').on('change', function() {pmAffectation.controllers.orga.clickFilter('equipe', $('#filtre_orga_equipe').val());});
		$('#filtre_orga_permis').off('change').on('change', function() { pmAffectation.controllers.orga.clickFilter('permis', $('#filtre_orga_permis').val()); });
		$('#filtre_orga_age').off('change').on('change', function() { pmAffectation.controllers.orga.clickFilter('age', $('#filtre_orga_age').val()); });
		
		// la champ de recherche (caché)
		// on doit empêcher la fermeture du dropdown du champ de recherche
		$('#champ_orga_rechercher').off('click').on('click', function(event) { event.stopImmediatePropagation(); });
		// on écoute lorsque des caractères sont tapés - keyup sinon on ne peut pas lire la valeur
		$('#champ_orga_rechercher').off('keyup').on('keyup', function(event) { pmAffectation.controllers.orga.filterList($('#champ_orga_rechercher').val()); });
		// mochement, on attend 50 ms, sinon on ne peut pas focuser un élément en display: none...
		$('#bouton_orga_rechercher').off('click').on('click', function(event) {
			$('#champ_orga_rechercher').val('');
			setInterval(function() { $('#champ_orga_rechercher').focus(); }, 50);
		});
	},
	
	/*
	 * Sélectionne les bons filtres
	 */
	setFilters: function() {
		(pmAffectation.current.orga.confiance !== undefined) && ($('#filtre_orga_confiance').val(pmAffectation.current.orga.confiance));
		(pmAffectation.current.orga.orga !== undefined) && ($('#filtre_orga_equipe').val(pmAffectation.current.orga.equipe));
		(pmAffectation.current.orga.permis !== undefined) && ($('#filtre_orga_permis').val(pmAffectation.current.orga.permis));
		(pmAffectation.current.orga.age !== undefined) && ($('#filtre_orga_age').val(pmAffectation.current.orga.age));
	},
	
	/*
	 * Charge la liste des orgas
	 */
	setOrgas: function(orgas) {
		var _orgas = $.isEmptyObject(orgas) ? pmAffectation.data.orgas : orgas; // valeur par défaut
		
		$('#liste_orgas').removeClass('spinner_medium');
		$('#liste_orgas').empty(); // au cas où
		
		for (var _iOrga in _orgas) {
			// on affiche l'équipe et la confiance
			var _equipes = ' <span class="label" style="background-color: '+pmAffectation.data.parameter.confiance[_orgas[_iOrga]['confiance']]['couleur']+';">'+
								pmAffectation.data.parameter.confiance[_orgas[_iOrga]['confiance']]['nom'].toLowerCase()+'</span>';
			if (pmAffectation.data.parameter.equipes[_orgas[_iOrga]['equipe']]['nom'].toLowerCase()
				!= pmAffectation.data.parameter.confiance[_orgas[_iOrga]['confiance']]['nom'].toLowerCase()) {
				_equipes += ' <span class="label label-info">'+pmAffectation.data.parameter.equipes[_orgas[_iOrga]['equipe']]['nom'].toLowerCase()+'</span>';
			}
			
			var _html = '<div class="item orga" id="orga_'+_orgas[_iOrga]['id']+'" idOrga="'+_orgas[_iOrga]['id']+'">'+
						 _orgas[_iOrga]['prenom']+' '+_orgas[_iOrga]['nom'];
			(_orgas[_iOrga]['surnom'] !== null) && (_html += ' ('+_orgas[_iOrga]['surnom']+')')
			_html += _equipes+'</div>';
			
			$('#liste_orgas').append(_html);
			
			// handler de click
			$('#orga_'+_orgas[_iOrga]['id']).bind('click', {id: _orgas[_iOrga]['id']}, function(e) {
				if (e.altKey) {
					// Alt + click : affiche les infos détaillées de l'orga
					pmAffectation.views.orga.viewOrgaDetails({id: e.data.id});
				} else {
					// sinon on click sur l'orga
					pmMode.orgaClick({id: e.data.id});
				}
			});
		}
		
		// on s'occupe de l'orga sélectionné
		$('#orga_'+pmAffectation.current.orga.id).addClass('current'); // focus
		$('#liste_orgas').scrollTop($('#orga_'+pmAffectation.current.orga.id).position().top-100);// scroll
	},
	
	/*
	 * Affiche un dialogue modal
	 * avec des infos sur l'orga
	 */
	viewOrgaDetails: function(obj) {
		var _popup = window.open(pmAffectation.url+'orga/'+obj.id+'/edit');		
	},
}
