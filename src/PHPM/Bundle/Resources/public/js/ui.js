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