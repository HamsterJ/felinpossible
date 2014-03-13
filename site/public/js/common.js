/**
* Variables globales.
*/
hash = "";
ancreExpr = new RegExp("^#ancre.*", "g");

/**
* Initialisation.
*/
function init() {
	initPage();
	initLinks();
	window.onresize = resizeIframe;
}

/**
* Initialise la page en cas d'appel Ajax.
*/
function initPage() {
	interval = setInterval(function() {
		var newHash = window.location.hash;
		if (newHash != hash) {
			hash = newHash;
			if (hash != "") {
				callAjax(hash);
			}
		}
		;
	}, 200);

	$('a[rel=ajax]').click(function(e){
		setHash(this.hash);
		callAjax(this.hash);
	});

	$(window).resize(handleResize);
	handleResize();
}


/**
* Récupération du hash.
* 
* @return le hash
*/
function removeHash(h) {
	if (h.charAt(0) == "#") {
		h = h.substring(1);
	}
	return h;
}

/**
* Mise à jour du hash.
*/
function setHash(h) {
	hash = h;
	window.location.hash = h;
}

/**
* Gestion des span pour le menu suivant la taille d'affichage
* (pour garder un menu toujours lisible)
*/
function handleResize() {
	if ($(window).width() < 980) {
		$('#main-menu').removeClass('span3');
		$('#main-menu').addClass('span4');
		$('#main-content').removeClass('span9');
		$('#main-content').addClass('span8');
	} else {
		$('#main-menu').removeClass('span4');
		$('#main-menu').addClass('span3');
		$('#main-content').removeClass('span8');
		$('#main-content').addClass('span9');
	}
}

/**
* Init. la class pour les liens du menu
*/
function initLinks() {
	$('div.accordion-inner > ul.nav > li').click(function (e) {
		e.preventDefault();
		$('ul.nav > li').removeClass('active');
		$(this).addClass('active');                
	});      
}

/**
* Fait l'appel la page passée en paramètre.
*/
function callAjax(page, eltToUpdateId, eltWaitId, formId) {
	if (!ancreExpr.test(page)) {
		eltToUpdateId = eltToUpdateId || 'corps';
		eltWaitId = eltWaitId || 'chargementCorps';

		var eltWaitIdElt = $('#' + eltWaitId);
		var eltToUpdateElt = $('#' + eltToUpdateId);
		var typeAjax = formId ? 'POST' : 'GET';

		$.ajax({
			type: typeAjax,
			data: $('#'+formId).serialize(),
			url: removeHash(page),
			beforeSend: function() {
				scrollToContent();
				eltToUpdateElt.hide();
				eltWaitIdElt.fadeIn('medium');
			},
			success: function (data) {
				deleteWidgets(eltToUpdateId);
				eltToUpdateElt.html(data);
				try {
					dojo.parser.parse(eltToUpdateId);
				} catch(e) {}

				eltToUpdateElt.find("a[rel=ancre]").click(function (e) {
					e.preventDefault();
					$('html, body').animate({
						scrollTop: $($(this).attr("href")).offset().top - 60
					}, 'fast');
				});
			},
			error: function(jqXHR, error, exception) {
				console.error("HTTP status code: ", jqXHR.status, "Message: ", exception);
				eltToUpdateElt.html(jqXHR.responseText);
			},
			complete: function () {
				resizeIframe();
				eltWaitIdElt.hide();
				eltToUpdateElt.fadeIn('medium');
			}
		});
	}
}

/**
* Scroll sur le contenu.
*/
function scrollToContent(element){
	var mainContentOffset = $('#main-content').offset().top;
	var offset = mainContentOffset - $(window).scrollTop();

	if(offset > window.innerHeight){
		$('html, body').animate({  
			scrollTop:mainContentOffset  
		}, 'medium');
	}
}

/**
 * Delete widgets under input.
 * 
 * @param input
 */
 function deleteWidgets(input) {
 	try {
 		var childDijit = document.getElementById(input).getElementsByTagName('*');
 		var i = 0;
 		var idElt = "";
 		var elt = null;
 		var eltToDestroy = new Array();

 		while (i < childDijit.length) {
 			idElt = childDijit[i].id;
 			elt = dijit.byId(idElt);
 			if (elt) {
 				eltToDestroy.push(elt);
 			}
 			i++;
 		}
 		elt = eltToDestroy.pop();
 		while (elt) {
 			if (elt.destroy) {
 				try {
 					elt.destroyRecursive();
 				} catch (e) {
 				}
 			}
 			elt = eltToDestroy.pop();
 		}

 		var cell = document.getElementById(input);
 		if (cell.hasChildNodes()) {
 			while (cell.childNodes.length >= 1) {
 				cell.removeChild(cell.firstChild);
 			}
 		}
 	} catch (e) {
 	}
 }

/**
* Iframe "perdus/Trouvés"
*/
function resizeIframe() {
	var elt = document.getElementById('framePerdusTrouves');
	if (elt) {
		var height = document.documentElement.clientHeight;
		height -= elt.offsetTop;

		// not sure how to get this dynamically
		height -= 20; 
		/*
		* whatever you set your body bottom margin/padding to
		* be
		*/

		elt.style.height = height + "px";
	}
}

/**
* Toggle le popover sélectionner.
* Détruit les autres.
*/
function popoverAction(eltId, url) {
	var el =  $('#' + eltId);
	var elLoad = $('#load' + eltId);

	var pop = el.parent().find('.popover:first').hasClass('in');
	$('[rel=popover]').popover('destroy');
	if (!pop) {
		$.ajax({
			type: 'GET',
			url: url,
			cache: true,
			dataType: 'html',
			beforeSend: function() {
				elLoad.show();
			},
			success: function(data) {
				el.attr('data-content', data);
				elLoad.hide();
				el.popover('show');
			}
		});
	}
}

/**
* Détruit le popover sélectionné.
*/
function popoverClose(eltId) {
	$('#' + eltId).popover('destroy');
}

/**
* Ouverture popup
*/
function popup(page,title,options)
{
	window.open(page,title,options);
}

/**
* Ajout animal.
*/
function submit_animal()
{	
	var sExisteDeja = window.opener.document.getElementById("foyerForm-animauxAutres").value;
	var sChaine;
	
	if (sExisteDeja != "") {
		sChaine = sExisteDeja;
		sChaine += "\n";
		sChaine += "Type:" + document.getElementById("typeAnimal").value;	
	} else {
		sChaine = "Type:" + document.getElementById("typeAnimal").value;
	}
	
	sChaine += ",";
	sChaine += "Race:" + document.getElementById("race").value;
	sChaine += ",";
	sChaine += "Age:" + document.getElementById("age").value;
	
	window.opener.document.getElementById("foyerForm-animauxAutres").value = sChaine;	 
}

/**
* Ajout chat.
*/
function submit_chatFA()
{	
	var sExisteDeja = window.opener.document.getElementById("foyerForm-chats").value;
	var sChaine;
	
	if (sExisteDeja != "") {
		sChaine = sExisteDeja;
		sChaine += "\n";
		sChaine += "Nom:" + document.getElementById("nom").value;	
	} else {
		sChaine = "Nom:" + document.getElementById("nom").value;
	}
	
	sChaine += ",";
	sChaine += "Age:" + document.getElementById("age").value;
	sChaine += ",";
	if(document.getElementById("vacTyphus").checked == true) {
		sChaine += "Typhus:" + "1";
	} else {
		sChaine += "Typhus:" + "0";
	}
	sChaine += ",";
	if(document.getElementById("vacCoryza").checked == true) {
		sChaine += "Coryza:" + "1";
	} else {
		sChaine += "Coryza:" + "0";
	}
	sChaine += ",";
	if(document.getElementById("vacLeucose").checked == true) {
		sChaine += "Leucose:" + "1";
	} else {
		sChaine += "Leucose:" + "0";
	}	
	sChaine += ",";
	if(document.getElementById("vacRage").checked == true) {
		sChaine += "Rage:" + "1";
	} else {
		sChaine += "Rage:" + "0";
	}	
	sChaine += ",";
	if(document.getElementById("sterilise").checked == true) {
		sChaine += "Sterilisé:" + "1";
	} else {
		sChaine += "Sterilisé:" + "0";
	}	
	sChaine += ",";
	if(document.getElementById("visMaison").checked == true) {
		sChaine += "visMaison:" + "1";
	} else {
		sChaine += "visMaison:" + "0";
	}	
	
	window.opener.document.getElementById("foyerForm-chats").value = sChaine;	 
}

/**
* Gestion précision connaissance asso.
*/
function connaissanceAsso()
{
	var idLabel = '#remarquesForm-connaissanceAssoDetail-label';
	var idElt = '#remarquesForm-connaissanceAssoDetail';
	var selected = parseInt($( "#remarquesForm-idConnaissanceAsso" ).val());

	switch (selected) {
		case 1 :
			// Commerce/véto
			$(idLabel).find('label').text('Lequel ?');
			$(idLabel).show();
			$(idElt).show();
			break;
		case 2:
			// Annonce internet
			$(idLabel).find('label').text('Sur quel site ?');
			$(idLabel).show();
			$(idElt).show();
		break;
		case 6:
			// Autres
			$(idLabel).find('label').text('Précisez ');
			$(idLabel).show();
			$(idElt).show();
		break;
		case 4:
			// Autre forum
			$(idLabel).find('label').text('Lequel ?');
			$(idLabel).show();
			$(idElt).show();
			break;
		default:
			$(idLabel).hide();
			$(idElt).hide();
	}
}

