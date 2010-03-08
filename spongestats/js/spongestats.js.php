<?php
@include_once("../sps.configuration.php");
if(file_exists("../sps.configuration.php")) @include_once("../sps.connect.inc.php");
@include_once("../locale.php");
?>

function GetId(id)
{
return document.getElementById(id);
}

function onglet(id) {
		id="menu-"+id;
		GetId("menu-graphs").className="";
		GetId("menu-pages").className="";
		GetId("menu-hotes").className="";
		GetId("menu-motscles").className="";
		GetId("menu-plateformes").className="";
		GetId("menu-referers").className="";
		GetId("menu-admin").className="";
		GetId(id).className="ongleton";
		}

var i=false; // La variable i nous dit si la bulle est visible ou non

function move(e) {
  if(i) {  
    if (navigator.appName!="Microsoft Internet Explorer") { 
	if(GetId("details"))
		{
	    GetId("details").style.left=e.pageX + 30 + "px";
    	GetId("details").style.top=e.pageY - 30 + "px";
		}
    }
    else {
		if(GetId("details"))
			{
			GetId("details").style.left=window.event.x + 30 +"px";
			GetId("details").style.top=window.event.y - 30 + document.body.scrollTop+"px"; 
			}
    	}
  }
}

function affiche_details(jour,mois,annee,hits,visiteurs) {
	if(i==false) {
	GetId("details").style.display="block";
	if(jour != '' || mois != '' || annee != '')
		{
		var text = '<h2>'+jour+mois+'/'+annee+'</h2>'
		var text = text+'<?php echo _("Pages vues"); ?> : <strong>'+hits+'</strong><br /><?php echo _("Visiteurs"); ?> : <strong>'+visiteurs+'</strong><br />'
		var moyenne = Math.round((hits / visiteurs) * 100) / 100;
		var text = text+'<?php echo _("Moyenne"); ?> : <strong>'+moyenne+'</strong>'
		}
	else
		{
		var text = '<?php echo _("Visiteurs"); ?> : <strong>'+visiteurs+'</strong>';
		}

  GetId("details").innerHTML = text;
  i=true;
  }
}
function cache_details() {
if(i==true) {
GetId("details").style.display="none"; 
i=false;
}
}
document.onmousemove=move;


function setActiveStyleSheet(title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
}
function getActiveStyleSheet() {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && !a.disabled) return a.getAttribute("title");
  }
  return null;
}
function getPreferredStyleSheet() {
  var i, a;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1
       && a.getAttribute("rel").indexOf("alt") == -1
       && a.getAttribute("title")
       ) return a.getAttribute("title");
  }
  return null;
}
function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}
window.onload = function(e) {
  var cookie = readCookie("style");
  var title = cookie ? cookie : getPreferredStyleSheet();
  setActiveStyleSheet(title);
}
window.onunload = function(e) {
  var title = getActiveStyleSheet();
  createCookie("style", title, 365);
}
var cookie = readCookie("style");
var title = cookie ? cookie : getPreferredStyleSheet();
setActiveStyleSheet(title);

//Fonction de rafraichissement automatique
	var refresh_details=function()
		{
		document.getElementById("ajax").style.display='none';
		var duree_rafraichissement=1000*10;
			if ($(".plus_details").get(0))
			{
				var top_id=$(".plus_details").get(0).id.split('-')[1];
			}
			else
			{
				var top_id=5878;
			}

			//Si on est sur l'onglet "Hotes"
			if ($("#hotes").get(0))
			{
				$.get("includes/inc.home.php",{top_id:top_id},function(txt){$(".ligne-pair:first").after(txt);$(".refresh:first").slideDown("slow");setTimeout(refresh_details,duree_rafraichissement);});
			}

		}
