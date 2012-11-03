/**
 * Montre la page passée en paramètre.
 */
function showPage(page, formId, eltToUpdateId, eltWaitId, modifyHash) {
	if (!eltToUpdateId) {
		eltToUpdateId = 'corps';
	}

	if (!eltWaitId) {
		eltWaitId = 'chargementCorps';
	}

	document.getElementById(eltWaitId).style.display = "block";
	document.getElementById(eltToUpdateId).style.display = "none";

	if (formId || page == getHash() || modifyHash == false) {
		callAjax(page, eltToUpdateId, eltWaitId, formId);
	} else {
		setHash(page);
	}
}

/**
 * Fait l'appel la page passée en paramètre.
 */
function callAjax(page, eltToUpdateId, eltWaitId, formId) {
	if (!eltToUpdateId) {
		eltToUpdateId = 'corps';
	}

	if (!eltWaitId) {
		eltWaitId = 'chargementCorps';
	}

	if (formId) {
		dojo.xhrPost( {
			url : page,
			load : ajaxHandleLoad,
			error : ajaxHandleError,
			content : dojo.formToObject(formId),
			eltWaitId : eltWaitId,
			eltToUpdateId : eltToUpdateId
		});
	} else {
		dojo.xhrGet( {
			url : page,
			handleAs : "text",
			load : ajaxHandleLoad,
			error : ajaxHandleError,
			eltWaitId : eltWaitId,
			eltToUpdateId : eltToUpdateId
		});
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
 * Traitement lors du chargement avec succès de la page via ajax.
 * 
 * @param response
 *            la réponse
 * @param ioArgs
 *            les arguments de la requête
 * @return la réponse
 */
function ajaxHandleLoad(response, ioArgs) {
	var eltToUpdate = document.getElementById(ioArgs.args.eltToUpdateId);

	deleteWidgets(ioArgs.args.eltToUpdateId);

	document.getElementById(ioArgs.args.eltWaitId).style.display = "none";
	eltToUpdate.innerHTML = response;

	if (dojo.parser) {
		dojo.parser.parse(eltToUpdate);
	}
	eltToUpdate.style.display = "block";
	resizeIframe();

	return response;
}

/**
 * Traitement lors du chargement avec erreur de la page via ajax.
 * 
 * @param response
 *            la réponse
 * @param ioArgs
 *            les arguments de la requête
 * @return la réponse
 */
function ajaxHandleError(response, ioArgs) {
	var eltToUpdate = document.getElementById(ioArgs.args.eltToUpdateId);
	deleteWidgets(ioArgs.args.eltToUpdateId);
	console.error("HTTP status code: ", ioArgs.xhr.status);
	document.getElementById(ioArgs.args.eltWaitId).style.display = "none";
	if (response.responseText) {
		eltToUpdate.innerHTML = response.responseText;
	} else {
		eltToUpdate.innerHTML = response;
	}
	eltToUpdate.style.display = "block";
	return response;
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
		height -= 20; /*
						 * whatever you set your body bottom margin/padding to
						 * be
						 */

		elt.style.height = height + "px";
	}
}
window.onresize = resizeIframe;

/**
 * Initialisation.
 */
function init() {
	initPage();
}

/**
 * Récupération du hash.
 * 
 * @return le hash
 */
function getHash() {
	var h = window.location.hash;
	if (h.charAt(0) == "#") {
		h = h.substring(1);
	}
	return h;
}

/**
 * Mise à jour du hash.
 */
function setHash(h) {
	window.location.hash = h;
}

/**
 * Initialise la page en cas d'appel Ajax.
 */
function initPage() {
	hash = "";
	ancreExpr = new RegExp("^ancre.*", "g");
	pageHome = "/index/home";

	if (getHash() == "") {
		document.getElementById('chargementCorps').style.display = "none";
		document.getElementById('corps').style.display = "block";
	}

	interval = setInterval(function() {
		var newHash = getHash();
		if (newHash != hash) {
			hash = newHash;
			if (hash != "" && !ancreExpr.test(newHash)) {
				document.getElementById('chargementCorps').style.display = "block";
				document.getElementById('corps').style.display = "none";
				setHash(hash);
				callAjax(hash, null, null, null);
			} else if (hash == "") {
				callAjax(pageHome, null, null, null);
			}
		}
		;
	}, 200);
}

/**
 * Cache l'élément passé en paramètre
 */
function hideElement(eltId) {
	var elt = document.getElementById(eltId);
	if (elt) {
		elt.style.display = 'none';
	}
}

/**
 * Affiche le contenu de l'info bulle.
 */
function showInfoBulleContent(pageToShow, eltId, eltWaitId) {
	var eltWait = document.getElementById(eltWaitId);
	var elt = document.getElementById(eltId);

	if (elt && elt.style.display != "block") {
		if (eltWait) {
			document.getElementById(eltWaitId).style.display = "block";
		}
		callAjax(pageToShow, eltId, eltWaitId);
	}
}
