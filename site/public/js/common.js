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
	if ($(window).width() < 1300) {
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
				eltToUpdateElt.html(data);
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

	var pop = el.parent().find('.popover:first').hasClass('in');
	$('[rel=popover]').popover('destroy');
	if (!pop) {
		$.ajax({
			type: 'GET',
			url: url,
			cache: true,
			dataType: 'html',
			success: function(data) {
				el.attr('data-content', data);
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
