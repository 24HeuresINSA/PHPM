$(window).load(function() {
	var regional = $.datepicker.regional['fr'];
	regional.dateFormat = 'yy-mm-dd';
	 $.datepicker.setDefaults(regional);
});

$(function() {
	// auto-complete pour le champ de recherche en haut de page
	$.widget('custom.catcomplete', $.ui.autocomplete, {
		_renderMenu: function(ul, items) {
			var self = this,
				currentType = '';
				
			$.each(items, function(index, item) {
				if (item.type != currentType) {
					ul.append('<li class="ui-autocomplete-category">' + item.type + '</li>');
					currentType = item.type;
				}
				self._renderItem( ul, item );
			});
		}
	}); // là on a mis un peu en forme
	$('#searchBox').catcomplete({
		source: function(request, response) {
			$.ajax({
				url: app_search_url,
				dataType: 'json',
				type: 'post',
				data: {
					s: request.term
				},
				success: function(data) {
					var _result = new Array();
					
					// on parcourt les résultats
					// la clef _item est bien unique
					for (var _item in data) {
						if (data[_item]['type'] === 'orga') {
							_result.push({label: data[_item]['prenom'] + ' ' + data[_item]['nom'] + ' (' + data[_item]['surnom'] + ')',
											value: data[_item]['telephone'],
											type: data[_item]['type'],
											id: data[_item]['id']}); // on prend le téléphone qui est unique
						} else if  (data[_item]['type'] === 'tache') {
							_result.push({label: data[_item]['nom'],
											value: data[_item]['nom'],
											type: data[_item]['type'],
											id: data[_item]['id']});
						}
					}
					
					response(_result); // on appelle le callback
				},
				position: {my : 'right top', at: 'right bottom'}
			});
		},
		minLength: 2,
		select: function(event, ui) {
			// on va sur la bonne page lors de la sélection
			if (ui.item.type === 'orga') {
				window.location.href = app_orga_url + ui.item.id + '/edit';
			} else if (ui.item.type === 'tache') {
				window.location.href = app_tache_url + ui.item.id + '/edit';
			}
		}
	});
	
	$('.records_list').dataTable( {
		"bInfo": false,
		"bPaginate": false,
		"bJQueryUI": true
	    
	} );
	 
	// page orga, champ age
	$('.birthdaydp').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		yearRange: '1985:1995',
	});
	
	/*
	 * Mise en place d'un DateTimePicker
	 * Utilisé sur les pages
	 * créneau (edit/new)
	 * et plage horaire (edit)
	 */ 
	// début de la plage
	$('input.debutdp').datetimepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss',
		stepHour: 1,
		stepMinute: 15,
		hourGrid: 4,
		minuteGrid: 15,
		showButtonPanel: false, // on cache les boutons du bas
		beforeShow: function(input, inst) {
			// on calcule la durée du créneau
			var _debut = $('input.debutdp').datepicker('getDate');
			var _fin = $('input.findp').datepicker('getDate');
			
			if (_debut != null && _fin != null) {
				// stock la durée dans l'inst, plus propre
				inst.duree = $('input.findp').datepicker('getDate').getTime()-$('input.debutdp').datepicker('getDate').getTime();
			}
		},
		onClose: function(dateText, inst) {
			// on modifie l'heure de fin pour garder la durée
			if (inst.duree != undefined && $('input.debutdp').datepicker('getDate') != null) {
				var _heureFin = $('input.debutdp').datepicker('getDate').getTime()+inst.duree;
				$('input.findp').datepicker('setDate', new Date(_heureFin));
			} else if ($('input.debutdp').datepicker('getDate') != null) {
				// cas où le champs fin était vide avant, par défaut on met 2 heures
				var _heureFin = $('input.debutdp').datepicker('getDate').getTime()+7200000;
				$('input.findp').datepicker('setDate', new Date(_heureFin));
			}
		}
	});
	// fin de la plage
	$('input.findp').datetimepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss',		
		stepHour: 1,
		stepMinute: 15,
		hourGrid: 4,
		minuteGrid: 15,
		showButtonPanel: false, // on cache les boutons du bas
	});
	
	// page ?
	$('.datep').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});

	// tabs
	$( "#tabs" ).tabs();

	// page ?
	/*
	$('input.dtp').datetimepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss',		
		stepHour: 1,
		stepMinute: 15,
		hourGrid: 4,
		minuteGrid: 15,
		showButtonPanel: false, // on cache les boutons du bas
	});*/
	
});

function fnShowHide(iCol) {
    /* Get the DataTables object again - this is not a recreation, just a get of the object */
    var oTable = $('.records_list').dataTable();
     
    var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
    oTable.fnSetColumnVis( iCol, bVis ? false : true );
}

function copyDateToEnd() {

    $('.findp').val($('.debutdp')[0].val());
    
}