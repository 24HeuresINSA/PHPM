$(window).load(function() {
    $.datepicker.setDefaults($.datepicker.regional['fr']);
});

$(function() {
	 $('.records_list').dataTable( {
		 	"bInfo": false,
		    "bPaginate": false,
	        "bJQueryUI": true,
	        "bLengthChange": false
	    } );
	   
});