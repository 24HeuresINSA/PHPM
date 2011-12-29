/*
 * Fonctions Js générales
 */
function error(str) {
	console.error(str);
}

/*
 * Permet d'afficher des messages en haut
 * Singleton
 */
message = {
	alert: function (str) {
		$("#message").show();
		
		$('#message').html('<div class="alert">'+str+'</div>');
		
		$("#message").fadeOut(10000, 'swing'); // disparait après 10 secondes
	},
	
	success: function (str) {
		$("#message").show();
		
		$('#message').html('<div class="success">'+str+'</div>');
		
		$("#message").fadeOut(10000, 'swing'); // disparait après 10 secondes
	}
}