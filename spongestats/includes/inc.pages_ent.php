<?php
@header('Content-type: text/html; charset=utf-8');

if(!empty($annee) && !empty($mois) && !empty($jour)) { $format_date_sql = "date='$annee-$mois-$jour'";}
if(!empty($annee) && !empty($mois) && empty($jour))  { $format_date_sql = "'$annee-$mois-01' <= date AND date <= '$annee-$mois-31'";}
if(!empty($annee) && empty($mois) && empty($jour))	 { $format_date_sql = "'$annee-01-01' <= date AND date <= '$annee-12-31'";}

@include_once("../sps.configuration.php");
@include_once("../sps.connect.inc.php");
@include_once("../locale.php");


echo "<a name=\"pages-entree\"></a>";
$lang_top_pages = _("Top %s des pages d'entree");
echo "<h2>";printf($lang_top_pages,$sps_config['display_pages_entree']);echo "</h2>\n";
echo "<div id=\"pages-entree\">\n";

$texte = _("Les pages d'entree sont les premieres pages consultees par un visiteur lorsqu'il entre sur votre site. Elles sont classees par nombre de consultation pour la periode en cours.");
affiche_aide($texte,"themes/".$sps_config['default_theme']."/icones/help.png",$sps_config['aide']);

	
	$req = @mysql_query("SELECT url_page,COUNT(url_page) AS nbip FROM ".$sps_config['db_prefix']."statistiques WHERE $format_date_sql GROUP BY url_page ORDER BY nbip DESC LIMIT 0,".$sps_config['display_pages_entree'].";");
	$res_all_request = @mysql_num_rows($req);
	$i = 0;
	while($i != $res_all_request)
	{

	$gauche = @mysql_result($req,$i,"url_page");
	$droite = @mysql_result($req,$i,"nbip");
	$url = $gauche;
	
	
	affiche_table($gauche,$droite,$url,"themes/".$sps_config['default_theme']."/icones/pages.png",$i);

	$i++;
	}
	echo "</div>\n";