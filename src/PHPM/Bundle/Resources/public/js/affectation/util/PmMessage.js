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
		$("#message_affection").show();
		$('#message_affection').html('<div class="success">'+str+'</div>');
		$("#message_affection").fadeOut(10000, 'linear'); // disparait après 10 secondes
	},
	
	/*
	 * Afficher un message d'erreur
	 */
	alert: function(str) {
		console.error(str);
		
		$("#message_affection").show();
		$('#message_affection').html('<div class="alert">'+str+'</div>');
		$("#message_affection").fadeOut(10000, 'linear'); // disparait après 10 secondes
	},
}