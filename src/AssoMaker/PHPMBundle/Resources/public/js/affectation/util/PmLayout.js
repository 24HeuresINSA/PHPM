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
		
		pmLayout.resizeCalendar();
		
		// on stock ces tailles dans les paramètres de l'utilisateur
		// volontairement on travaille en pixels
		pmUtils.setLocalStorage('SizeSidebarOrga', $('#sidebar_orga').width());
		pmUtils.setLocalStorage('SizeSidebarTache', $('#sidebar_tache').width());
	},
	// resize le calendar et tout ce qui va avec
	resizeCalendar: function() {
		// on ne dira jamais assez merci à CSS3 !
		// 8px pour la scrollbar
		var _width = $("#sidebar_orga").width()+$("#sidebar_tache").width()+8;
		$('#calendar').width('-moz-calc(100% - '+_width+'px)');
		$('#calendar').width('-webkit-calc(100% - '+_width+'px)');
		$('#calendar').width('calc(100% - '+_width+'px)');
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