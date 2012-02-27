$(window).load(function() {
    $.datepicker.setDefaults($.datepicker.regional['fr']);
});

$(function() {
	 $('.records_list').dataTable( {
		 	"bInfo": false,
		    "bPaginate": false,
	        "sScrollY": 200,
	        "bJQueryUI": true,
	        "bLengthChange": false
	    } );
	   
});