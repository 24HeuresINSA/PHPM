/*
 * Page affectation
 * Librairie permettant d'afficher des messages
 * d'une maniè§re plus adaptée
 * Ainsi que de gérer le log des affectations
 */

/*
 * Création du namespace et utils
 */
function PmMessage() {
}
	
/*
 * Fonctions
 */
PmMessage.prototype = {
	/*
	 * Afficher un message de succès
	 */
	success: function(str) {
		$('#message').show();
		$('#message').html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'+str+'</div>');
		
		setTimeout(function() {$('#message').fadeOut(2000, 'linear')}, 5000); // effet opaque 5s puis disparait en 2
	},
	
	/*
	 * Afficher un message d'erreur
	 */
	alert: function(str) {
		console.error(str);
		
		$('#message').show();
		$('#message').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>'+str+'</div>');
		
		setTimeout(function() {$('#message').fadeOut(2000, 'linear')}, 5000); // effet opaque 5s puis disparait en 2
	},
}