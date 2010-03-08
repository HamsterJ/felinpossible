/**
 * Affiche le numéro de la ligne
 * 
 * @param data
 * @param index
 * @return le numéro de la ligne.
 */
function formatRowNum(index) {
	return index + 1;
}

/**
 * Formate la date
 * 
 * @param inDatum
 *            la date à formatter
 * @return la date formattée
 */
function formatDate(inDatum) {
	try {
	  if (inDatum == "" || inDatum == null) {
		return "";
	  }
	  return dojo.date.locale.format(new Date(dojo.date.stamp.fromISOString(inDatum)), {
		datePattern : "dd/MM/yyyy",
		selector : "date"
	  });
	} catch(e) {
		return inDatum;
	}
}

/**
 * Formate la date, en rouge si dans le passé.
 * 
 * @param inDatum
 *            la date à formatter
 * @return la date formattée
 */
function formatDateFutur(inDatum) {
	try {
	  if (inDatum == "" || inDatum == null) {
		return "<div style='color:red'>A renseigner</div>";
	  }
	  var dateObj = new Date(dojo.date.stamp.fromISOString(inDatum));
	  var dateCourante = new Date();
	  var style = "color:green";
	  if (dateCourante > dateObj) {
		style = "color:red";
	  }
	  return "<div style='" + style + "'>" + dojo.date.locale.format(dateObj, {
		datePattern : "dd/MM/yyyy",
	  	selector : "date"
	  }) + "</div>";
	} catch (e) {
		return inDatum;
	}
}

/**
 * Formate la date pour la stérilisation, en rouge si dans le passé.
 * 
 * @param inDatum
 *            la date à formatter
 * @return la date formattée
 */
function formatDateFuturSterilisation(inDatum, rowIndex) {
	try {
	  if (inDatum == "" || inDatum == null) {
		return "<div style='color:red'>A renseigner</div>";
	  }
	  
	  var dateObj = new Date(dojo.date.stamp.fromISOString(inDatum));
	  var dateCourante = new Date();
	  var style = "color:green";
	  var data = this.grid.getItem(rowIndex);
	  
	  if (dateCourante > dateObj && (data && data.i.sterilise == 0)) {
		style = "color:red";
	  }
	  return "<div style='" + style + "'>" + dojo.date.locale.format(dateObj, {
		datePattern : "dd/MM/yyyy",
	  	selector : "date"
	  }) + "</div>";
	} catch (e) {
		return inDatum;
	}
}

/**
 * Formatte le timestamp.
 * 
 * @param nbSeconds
 *            le timestamp php (en secondes)
 * @return la date formattée.
 */
function formatTimestamp(nbSeconds) {
	try {
	  var date = new Date();
	  date.setTime(nbSeconds * 1000);
	  return dojo.date.locale.format(date, {
		datePattern : "dd/MM/yyyy",
		selector : "date"});
	} catch (e) {
		return nbSeconds;
	}
}

/**
 * Met la chaine passée en paramètre en majuscule pour l'affichage dans les
 * tableaux.
 * 
 * @param str
 *            la string à formatter
 * @return le string formattée.
 */
function formatUpperCase(str) {
	try {
	  if (str != "") {
		return str.toUpperCase();
	  }
	} catch (e) {}
	return str;
}

/**
 * Format le text en gras
 * 
 * @param str
 * @return
 */
function formatBold(str) {
	return "<div style='font-weight: bold;' >" + str + "</div>";
}

/**
 * Formatte le text en gras + majuscules
 * 
 * @param str
 * @return
 */
function formatUpperCaseBold(str) {
	return formatBold(formatUpperCase(str));
}

/**
 * Met la chaine passée en paramètre (met la première lettre en majuscule) pour
 * l'affichage dans les tableaux.
 * 
 * @param str
 *            la string à formatter
 * @return le string formattée.
 */
function formatUcfirst(str) {
	try {
	  if (str != "" && str.length > 0) {
		return str.charAt(0).toUpperCase() + str.substring(1);
	  }
	} catch (e) {
		alert("Erreur : " + e.message);
	}
	return str;
}

/**
 * Formatte le boolean pour l'affichage dans la grid.
 * 
 * @param str
 *            la string à formatter
 * @return le string formattée.
 */
function formatBoolean(str) {
	if (str == 1) {
		return "<div class='booleanVrai'/>";
	}
	return "<div class='booleanFaux'/>";
}

/**
 * Ajoute la classe css correspondant au statut.
 * 
 * @param idStatut
 * @return la div avec la bonne classe css.
 */
function formatStatutFa(idStatut) {
	var className;
	switch (idStatut) {
	case 1:
		className = "faActive";
		break;
	case 2:
		className = "faInactive";
		break;
	case 3:
		className = "faDisponible";
		break;
	case 4:
		className = "faStandBy";
		break;
	case 5:
		className = "faVacances";
		break;
	case 6:
		className = "faIndesirable";
		break;
	case 7:
		className = "faCandidature";
		break;
	default:
		return idStatut;
		break;
	}
	return "<div class='" + className + "'/>"; 
}

/**
 * Ajoute la classe css correspondant au statut de l'indispo.
 * 
 * @param idStatut
 * @return la div avec la bonne classe css.
 */
function formatStatutIndispo(idStatut) {
	var className;
	switch (idStatut) {
	case 1:
		className = "indispoAvenir";
		break;
	case 2:
		className = "indispoEnCours";
		break;
	case 3:
		className = "indispoTerminee";
		break;
	default:
		return idStatut;
		break;
	}
	return "<div class='" + className + "'/>"; 
}

/**
 * Affichage de l'icone correspondant au sexe.
 * 
 * @param str
 * @return
 */
function formatSexe(str) {
	if (str == "Femelle") {
		return "<div class='sexeFemelle'/>";
	}
	return "<div class='sexeMale'/>";
}
/**
 * Fait l'appel l'appel ajax de façon asynchrone.
 */
function callAjaxWithCallback(url, callBackFunction, param) {
	dojo.xhrGet( {
		url : url,
		handleAs : "text",
		load : callBackFunction,
		param : param
	});
}


/**
 * Affiche le formulaire d'ajout d'un élément (utilisé dans les grid).
 */
function addChat(url, eltToUpdateId, eltWaitId, modifyHash) {
	showPage(url, null, eltToUpdateId, eltWaitId, modifyHash);
}

/**
 * Affiche le formulaire d'ajout d'un élément (utilisé dans les grid).
 */
function addItem(url, eltToUpdateId, eltWaitId, modifyHash) {
	showPage(url, null, eltToUpdateId, eltWaitId, modifyHash);
}

/**
 * Affiche le formulaire d'édition d'un un élement du tableau.
 */
function editItem(url, gridId, eltToUpdateId, eltWaitId, modifyHash, paramName) {
	if (!url || url == '') {
		return;
	}
	
	var grid = dijit.byId(gridId);
	var gridStore = grid.store;
	var nbSelectedElements = grid.selection.getSelected().length;
	
	if (! paramName) {
		paramName = 'id';
	}
	
	if (nbSelectedElements) {
		for ( var i = 0; i < nbSelectedElements; i++) {
			var selected = grid.selection.getSelected()[i];
			var itemId = gridStore.getValue(selected, 'id');
			showPage(url + "?" + paramName + "=" + itemId, null, eltToUpdateId, eltWaitId, modifyHash);
			break;
		}
	} else {
		alert('Aucun élément sélectionné');
	}
}

/**
 * Supprime l'élément selectioné.
 */
function deleteItem(url, gridId, paramName, sansConfirmation) {
	var grid = dijit.byId(gridId);
	var gridStore = grid.store;
	var nbSelectedElements = grid.selection.getSelected().length;
	
	if (! paramName) {
		paramName = 'id';
	}
	
	if (nbSelectedElements && (sansConfirmation || confirm('Confirmez-vous la suppression ?'))) {
		grid.showMessage(grid.loadingMessage);
		for ( var i = 0; i < nbSelectedElements; i++) {
			var selected = grid.selection.getSelected()[i];
			var itemId = gridStore.getValue(selected, 'id');
			callAjaxWithCallback(url + "?" + paramName + "=" + itemId, callbackUpdateItems, grid);
		}
		grid.selection.deselectAll();
	} else {
		alert('Aucun élément sélectionné');
	}
}

/**
 * Exporte les données au format csv.
 */
function exportData(url) {
	window.open(url);
}

/**
 * Met à jour l'élément selectioné.
 */
function updateItem(url, gridId, paramName) {
	deleteItem(url, gridId, paramName, true);
}

/**
 * Génère les documents pour les éléments sélectionnés.
 */
function genereDocument(url, gridId, paramName) {
	var grid = dijit.byId(gridId);
	var gridStore = grid.store;
	var nbSelectedElements = grid.selection.getSelected().length;
	
	if (! paramName) {
		paramName = 'id';
	}
	
	if (nbSelectedElements) {
		// Seulement 1 seul document de généré.
		for ( var i = 0; i < 1; i++) {
			var selected = grid.selection.getSelected()[i];
			var itemId = gridStore.getValue(selected, 'id');
			window.open(url + "?" + paramName + "=" + itemId);
		}
	} else {
		alert('Aucun élément sélectionné');
	}
}

/**
 * Trie la grille (force le rafraichissement).
 */
function callbackUpdateItems(response, ioArgs) {
	var grid = ioArgs.args.param;
	grid._refresh();
}

/**
 * Montre le tooltip pour l'édition dans les grids.
 * 
 * @param e
 *            l'événement.
 */
function showTooltip(e) {
	dijit.showTooltip("Double-cliquer pour éditer.", e.cellNode);
}

/**
 * Montre le tooltip pour l'ajout dans les grids.
 * 
 * @param e
 *            l'événement.
 */
function showTooltipAjout(e) {
	dijit.showTooltip("Double-cliquer pour ajouter.", e.cellNode);
}

/**
 * Cache le tooltip.
 * 
 * @param e
 *            l'événement.
 */
function hideTooltip(e) {
	dijit.hideTooltip(e.cellNode);
}

/**
 * Initialisation de la grille (force l'affichage du message si aucune données
 * lors du chargement initial)
 * 
 * @param gridName
 */
function initGrid(gridName){
	var grid = dijit.byId(gridName);
	if (grid.rowCount == 0) { 
		grid.updateRowCount(0);
	}
}

/**
 * Fonction appelée lors à la fin de la mise à jour de la grille.
 * 
 * @param gridName
 */
function endGrid(gridName) {
	var grid = dijit.byId(gridName);
	var nbElt = document.getElementById('nbElements');
	if (nbElt) { 
		nbElt.innerHTML = grid.rowCount;
	}
	grid.showMessage(null);
}

/**
 * Filtre pour les FA.
 */
function filterFa(gridName) {
	var filterStatut = dijit.byId('filterFaStatut');
	
	if (filterStatut.isValid()) {
		var obj = new Object();
		obj['statut'] = filterStatut.getValue();
		obj['nom'] = dijit.byId('filterFaNom').getValue();
		obj['prenom'] = dijit.byId('filterFaPrenom').getValue();
		obj['login'] = dijit.byId('filterFaLogin').getValue();
		
		if(dijit.byId('filterFaSansContrat').checked) {
			  obj['dateContratFa'] = 'is null';
		}
		
		var grid = dijit.byId(gridName);
		grid.filter(obj);
		grid.showMessage(grid.loadingMessage);
	}
}

/**
 * Fonction pour filtrer les indispos.
 * 
 * @return
 */
function filterIndispo() {
	var filterStatut = dijit.byId('filterIndispoStatut');
	if (filterStatut.isValid()) {
		var obj = new Object();
		obj['statutIndispo'] = filterStatut.getValue();
		
		var grid = dijit.byId('commonGrid');
		grid.filter(obj);
		grid.showMessage(grid.loadingMessage);
	}
}
/**
 * Filtre pour les Adoptants.
 */
function filterAdoptant(gridName) {
  var obj = new Object();
  obj['nom'] = dijit.byId('filterAdNom').getValue();
  obj['prenom'] = dijit.byId('filterAdPrenom').getValue();
  obj['login'] = dijit.byId('filterAdLogin').getValue();
  
  var grid = dijit.byId(gridName);
  grid.filter(obj);
  grid.showMessage(grid.loadingMessage);
}

/**
 * Filtre pour les chats.
 */
function filterChat(gridName) {
  var obj = new Object();
  obj['nom'] = dijit.byId('filterChatNom').getValue();
  
  if (dijit.byId('filterChatValider').checked) {
    obj['aValider'] =  1;
    obj['disparu'] =  0;
  }
  if (dijit.byId('filterChatAdoption').checked) {
	  obj['adopte'] =  0;
	  obj['disparu'] =  0;
  }
  if (dijit.byId('filterChatReserve').checked) {
	    obj['reserve'] =  1;
	    obj['disparu'] =  0;
  }
  if (dijit.byId('filterChatParrainer').checked) {
	    obj['aParrainer'] =  1;
	    obj['disparu'] =  0;
  }
  if(dijit.byId('filterChatDisparu').checked) {
	  obj['disparu'] = 1;
  }
  if(dijit.byId('filterChatSansContrat').checked) {
	  obj['dateContratAdoption'] = 'is null';
	  obj['adopte'] =  1;
	  obj['disparu'] =  0;
  }
  
  var grid = dijit.byId(gridName);
  grid.filter(obj);
  grid.showMessage(grid.loadingMessage);
}
/**
 * Désactive les filtres.
 */
function disableFilters() {
  var container = document.getElementById('filters');
  if (container) {
    var inputs = container.getElementsByTagName('input');
    for (var i=0; i<inputs.length; i++) {
	    inputs[i].disabled = 'true';
    }
  }
}

/**
 * Active les filtres.
 */
function enableFilters() {
	  var container = document.getElementById('filters');
	  if (container) {
	    var inputs = container.getElementsByTagName('input');
	    for (var i=0; i<inputs.length; i++) {
		    inputs[i].disabled = '';
	    }
	  }
	}