/*
 * Petit ajout à jQuery
 */

// jQuery Deparam - v0.1.0 - 6/14/2011
// http://benalman.com/
// https://gist.github.com/1025817
// Copyright (c) 2011 Ben Alman; Licensed MIT, GPL
(function($){var a,b=decodeURIComponent,c=$.deparam=function(a,d){var e={};$.each(a.replace(/\+/g," ").split("&"),function(a,f){var g=f.split("="),h=b(g[0]);if(!!h){var i=b(g[1]||""),j=h.split("]["),k=j.length-1,l=0,m=e;j[0].indexOf("[")>=0&&/\]$/.test(j[k])?(j[k]=j[k].replace(/\]$/,""),j=j.shift().split("[").concat(j),k++):k=0,$.isFunction(d)?i=d(h,i):d&&(i=c.reviver(h,i));if(k)for(;l<=k;l++)h=j[l]!==""?j[l]:m.length,l<k?m=m[h]=m[h]||(isNaN(j[l+1])?{}:[]):m[h]=i;else $.isArray(e[h])?e[h].push(i):h in e?e[h]=[e[h],i]:e[h]=i}});return e};c.reviver=function(b,c){var d={"true":!0,"false":!1,"null":null,"undefined":a};return+c+""===c?+c:c in d?d[c]:c}})(jQuery)

/*
 * Permet de vérifier l'existence d'un object
 * sélectionné par jQuery via $()...
 */
$.fn.exists = function () {
	return this.length !== 0;
}

/*
 * Surcharge des objets Javascript par des méthodes perso
 * Surcharger le prototype Date est ok,
 * RIGOUREUSEMENT INTERDIT de surchargeur Object
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
 * Retourne que la partie time à un format "joli" : hh:mm
 */
Date.prototype.getMyTime = function() {
	return date('H:I', Number(this.getTime()/1000));
}

/*
 * Renvoie la date sous le format demandé
 */
Date.prototype.getThisFormat = function(leFormat) {
	return date(leFormat, mktime(this.getHours(), this.getMinutes(), this.getSeconds(), Number(this.getMonth()+1), this.getDate(), this.getFullYear()));
}

/*
 * Fonctions renvoyant la même chose que la date Javascript
 * mais bien sur 2 caratères (01 pour Janvier au lieu de 1 par ex.)
 * On n'utilise pas pmUtils.pad2, peut-être spécifique à PHPM
 */
Date.prototype.getPMonth = function() {
	var _mois = this.getMonth()+1; // on rajoute 1 pour coller à ce que fait PHP
	return (_mois < 10 ? '0' : '') + Number(_mois);
}
Date.prototype.getPDate = function() {
	var _jour = this.getDate();
	return (_jour < 10 ? '0' : '') + Number(_jour);
}
Date.prototype.getPHours = function() {
	var _heure = this.getHours();
	return (_heure < 10 ? '0' : '') + Number(_heure);
}
Date.prototype.getPMinutes = function() {
	var _minute = this.getMinutes();
	return (_minute < 10 ? '0' : '') + Number(_minute);
}
