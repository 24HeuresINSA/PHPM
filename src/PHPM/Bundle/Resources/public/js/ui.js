$(window).load(function() {
var regional = $.datepicker.regional['fr'];
regional.dateFormat = 'yy-mm-dd';
 $.datepicker.setDefaults(regional);
});

$(function() {
	 $('.records_list').dataTable( {
		"bInfo": false,
		"bPaginate": false,
		"bJQueryUI": true
	    
	} );
	 

	$( '.birthdaydp' ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		yearRange: '1985:1995',
	});
	
	$( '.debutdp' ).datetimepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss',
		stepHour: 1,
		stepMinute: 15,
		hourGrid: 4,
		minuteGrid: 15,
		onClose: function(dateText, inst) { $('.findp').val(dateText);}

	});
	
	$( '.findp' ).datetimepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		timeFormat: 'hh:mm:ss',		
		stepHour: 1,
		stepMinute: 15,
		hourGrid: 4,
		minuteGrid: 15


	});
	
	$( ".datep" ).datepicker({
		dateFormat: 'yy-mm-dd',
	});
	
	
	
	
	
	

	   
	   
});

function fnShowHide( iCol )
{
    /* Get the DataTables object again - this is not a recreation, just a get of the object */
    var oTable = $('.records_list').dataTable();
     
    var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
    oTable.fnSetColumnVis( iCol, bVis ? false : true );
}

function copyDateToEnd()
{

    $('.findp').val($('.debutdp')[0].val());
    
}