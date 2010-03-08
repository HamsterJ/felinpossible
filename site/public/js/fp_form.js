function popup(page,title,options)
{
	window.open(page,title,options);
}

function updateDon()
{
	var don = document.getElementById("don");
	var valeur = document.getElementById("donValeur");
	var index = don.selectedIndex;
	var texte = don.options[index].text;
	
	if (texte == "Autres") {
		valeur.value = "40";
		valeur.disabled = false;
	} else {
		valeur.disabled = true;
		valeur.value = texte.substr(0,2);
	}
}

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
