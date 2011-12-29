/*
 * Fonctions Js générales
 */
function error(str) {
	console.error(str);
}

function confirm_delete($lien)
{
	if (window.confirm("Etes vous sur de vouloir supprimer cet élèment ?")) 
	window.location=$lien;
}

/*
 * Permet d'afficher des messages en haut
 * Singleton
 */
message = {
	alert: function (str) {
		$("#message").show();
		
		$('#message').html('<div class="alert">'+str+'</div>');
		
		$("#message").fadeOut(10000, 'linear'); // disparait après 10 secondes
	},
	
	success: function (str) {
		$("#message").show();
		
		$('#message').html('<div class="success">'+str+'</div>');
		
		$("#message").fadeOut(10000, 'linear'); // disparait après 10 secondes
	},
	
	notification: function (str) {
		$("#message").show();
		
		$('#message').html('<div class="notification">'+str+'</div>');
		
		$("#message").fadeOut(12000, 'linear'); // disparait après 12 secondes
	},
}