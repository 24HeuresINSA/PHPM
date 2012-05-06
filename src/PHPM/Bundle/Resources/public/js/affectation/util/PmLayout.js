/*
 * Page affectation
 * Petite librairie spécifique
 * S'occupe de totu ce qui touche au layout
 */

/*
 * Création du namespace et utils
 */
function PmLayout() {
}
	
/*
 * Fonctions
 */
PmLayout.prototype = {
	/*
	 * Adapte la hauteur du layout
	 */
	setAppHeight: function() {
		var _headerHeight = $('.navbar').height();
		var _contentHeight = $(window).height()-_headerHeight;
		var _clientHeight = _contentHeight-$('#menu_calendar').outerHeight()-3; // 3 de border tout en bas
		
		$('#content').height(_contentHeight+'px');
		$('#client').height(_clientHeight+'px');
		
		$('#jours').height(_clientHeight-30+'px');
		
		var _sidebarHeight = $('#sidebar_tache').height();
		
		$('#liste_orgas').height(_sidebarHeight-34-6+'px'); // 6px de diverses marges
		$('#liste_taches').height(_sidebarHeight-34-6+'px');
	},
	// fonction appelée lorsqu'on touche au calendrier
	setResizeableApp: function() {
		window.onresize = this.setAppHeight;
		
		this.setAppHeight();
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
									resize: pmLayout.hideCalendar,
									stop: pmLayout.resizeHandler
									});
			
			$("#sidebar_tache").resizable({
									minWidth: 150,
									maxWidth: 400,
									autoHide: true,
									handles: 'w',
									resize: pmLayout.hideCalendar,
									stop: pmLayout.resizeHandler
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
		
		pmLayout.resizeCalendar(ui.originalSize.width-ui.size.width);
		
		// on stock ces tailles dans les paramètres de l'utilisateur
		// volontairement on travaille en pixels
		pmUtils.setLocalStorage('SizeSidebarOrga', $('#sidebar_orga').width());
		pmUtils.setLocalStorage('SizeSidebarTache', $('#sidebar_tache').width());
	},
	// resize le calendar et tout ce qui va avec
	resizeCalendar: function(deltaTaille) {
		// on convertit tout en % pour mieux gérer le redimensionnement de la fenêtre
		// 1 px de décalage qui vient de je ne sais où, juste le rajouter
		var _newWidth = ($('#calendar').width()+deltaTaille+1)/$('.affectation-wrapper').width()*100;
		$('#calendar').width(_newWidth+'%');

		// même chose pour les sidebars
		pmLayout.setPourcentWidth('#sidebar_orga');
		pmLayout.setPourcentWidth('#sidebar_tache');
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
		$(unElement).width(this.getPourcentWidth(unElement, elementRelatif)+'%');
	}
}