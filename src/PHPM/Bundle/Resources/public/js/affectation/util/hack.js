/*
 * Surcharge des objets Javascript par des méthodes perso
 */

/*
 * Retourne que la partie date à un format "genre SQL" : YYYY/MM/DD
 */
Date.prototype.getMyDate = function() {
	return date('Y/m/d', Number(this.getTime()/1000));
}

/*
 * Retourne la date à un format "genre SQL" : YYYY/MM/DD hh:mm:ss
 */
Date.prototype.getMyDts = function() {
	return date('Y/m/d H:I:s', Number(this.getTime()/1000));
}

/*
 * Renvoie la date sous le format demandé
 */
Date.prototype.getThisFormat = function(leFormat) {
	return date(leFormat, mktime(this.getUTCHours(), this.getUTCMinutes(), this.getUTCSeconds(), Number(this.getMonth()+1), this.getDate(), this.getUTCFullYear()));
}