<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of SpongeStats
# Copyright (c) Bastien Bobe / Samy Rabih. All rights reserved.
#
# SpongeStats is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# SpongeStats is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# ***** END LICENSE BLOCK *****

@include_once("../../sps.configuration.php");
@include_once("../../sps.connect.inc.php");
@include_once("../../locale.php");

function affiche_rss_content()
	{
	global $sps_config;
//
	$req_jour = mysql_fetch_array(mysql_query( "SELECT COUNT(ip) as visiteurs,SUM(nb_pages) as pages FROM ".$sps_config['db_prefix']."statistiques WHERE date='".date('Y-m-d', time())."' AND heure <=".date("G")));
	$req_veille = mysql_fetch_array(mysql_query( "SELECT COUNT(ip) as visiteurs,SUM(nb_pages) as pages FROM ".$sps_config['db_prefix']."statistiques WHERE date='".date('Y-m-d', time()-3600*24)."' AND heure <=".date("G")));
	$req_browser= mysql_query("SELECT count(logiciel) as logicielmax, logiciel FROM sps_statistiques WHERE logiciel<>'' GROUP BY logiciel ORDER BY logicielmax DESC LIMIT 5");

	$req_referer= mysql_query("SELECT count(domaine_referer) as referermax, domaine_referer FROM sps_statistiques WHERE domaine_referer<>'' GROUP BY domaine_referer ORDER BY referermax DESC LIMIT 5");

	$stats_jour=array();
	$stats_jour["aujourdhui"]=array("visiteurs" => $req_jour["visiteurs"],"pages" => $req_jour["pages"]);
	$stats_jour["hier"]=array("visiteurs" => $req_veille["visiteurs"],"pages" => $req_veille["pages"]);

	$top_pages_vues=	mysql_query("SELECT count(url_page) as urlpagemax, url_page FROM sps_statistiques WHERE url_page<>'' GROUP BY url_page ORDER BY urlpagemax DESC LIMIT 5");
	

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	echo "<rss version=\"2.0\">";
	echo "<channel>\n";
	echo "<title>Spongestats</title>"; 
	echo "<description>Spongestats</description>";
		echo "<base_url>http://".$_SERVER['SERVER_NAME']."</base_url>";

	//echo "<stats>\n";
	echo "<item>\n<title>day_visits</title>\n";
	echo "<description>".("Visites du jour")."</description>\n";
	echo "<day>".(($stats_jour["aujourdhui"]["visiteurs"])? $stats_jour["aujourdhui"]["visiteurs"] : "1" )."</day>\n";
	echo "<eve>".(($stats_jour["hier"]["visiteurs"])? $stats_jour["hier"]["visiteurs"] : "1" )."</eve>\n";
	echo "</item>\n";
	echo "<item>\n<title>day_pages</title>\n";
	echo "<description>\nPages du jour</description>\n";
	echo "<day>".(($stats_jour["aujourdhui"]["pages"])? $stats_jour["aujourdhui"]["pages"] : "1" )."</day>\n";
	echo "<eve>".(($stats_jour["hier"]["pages"])? $stats_jour["hier"]["pages"] : "1" ) ."</eve>\n";
	echo "</item>\n";

	//Top X des navigateurs
	echo "<item>\n<title>browser</title>\n";
	echo "<description>\nTop navigateurs</description>\n";
		while($machin=mysql_fetch_assoc($req_browser))
		{
		echo "<browser>".$machin["logiciel"]."</browser>";
		}
	echo "</item>";

	//Top X des referers
	echo "<item>\n<title>referer</title>\n";
	echo "<description>\nTop referers</description>\n";
		while($machin=mysql_fetch_assoc($req_referer))
		{
		echo "<referer>".$machin["domaine_referer"]."</referer>";
		}
	echo "</item>";

	//Top X des pages vues
	echo "<item>\n<title>page_vue</title>\n";
	echo "<description>\nTop pages vues</description>\n";
		while($machin=mysql_fetch_assoc($top_pages_vues))
		{
		echo "<page_vue>".$machin["url_page"]."</page_vue>";
		}
	echo "</item>";

	echo "</channel>";
	echo "</rss>";


	//echo "<item>\n<name>day_pages</name>\n<value>".$stats_jour["aujourdhui"]["pages"]."</value>\n</item>\n";
	//echo "<item>\n<name>eve_visitors</name>\n<value>".$stats_jour["hier"]["visiteurs"]."</value>\n</item>\n";
	//echo "<item>\n<name>eve_pages</name>\n<value>".$stats_jour["hier"]["pages"]."</value>\n</item>\n";
	//echo "</stats>";

	
	
	//	Détail du meilleur site referent
	$req_referer = @mysql_query("SELECT domaine_referer,COUNT(domaine_referer) AS nbref FROM ".$sps_config['db_prefix']."statistiques WHERE domaine_referer != '' AND $format_date GROUP BY domaine_referer ORDER BY nbref DESC LIMIT 0,1;");
	$res_site_referer = @mysql_result($req_referer,0,"domaine_referer");
	$res_site_nbref = @mysql_result($req_referer,0,"nbref");
	
	//	Détail de la meilleure page referente
	$req_referer = @mysql_query("SELECT referer,COUNT(referer) AS nbref FROM ".$sps_config['db_prefix']."statistiques WHERE referer != '' AND $format_date GROUP BY referer ORDER BY nbref DESC LIMIT 0,1;");
	$res_page_referer = @mysql_result($req_referer,0,"referer");
	$res_page_nbref = @mysql_result($req_referer,0,"nbref");
	

	
	// Contenu du fil
//	echo "<item>\n";
//	//echo "<guid isPermalink=\"false\">".date("D.M.Y").$res_ip_total."</guid>";
//	if($periode=="jour") {$echo_periode = _("Statistiques quotidiennes du");$format_date = ereg_replace("date='","",$format_date);}
//	if($periode=="semaine") {$echo_periode = _("Statistiques hebdomadaires du");$format_date = ereg_replace("' <= date AND date <= '"," au ",$format_date);}
//	if($periode=="mois") {$echo_periode = _("Statistiques mensuelles du");$format_date = ereg_replace("' <= date AND date <= '"," au ",$format_date);}
//	if($periode=="annee") {$echo_periode = _("Statistiques annuelles du");$format_date = ereg_replace("' <= date AND date <= '"," au ",$format_date);}
//	$format_date = ereg_replace("'","",$format_date);
//	
//	echo "	<title>$echo_periode ".$format_date."</title>\n";
//	echo "	<link>http://".$_SERVER['SERVER_NAME']."/".$sps_config['sponge_folder']."</link>\n";
//	echo "	<description>\n";
//	echo " <![CDATA[ ";
//	echo "		<strong>"._("Nombre de visiteurs")." : </strong>".$res_ip_total."<br />\n";
//	echo "		<strong>"._("Nombre de pages vues")." : </strong>".$res_total."<br /><br />\n";
//	echo "		<strong>"._("Meilleur site referent")." : </strong>".$res_site_referer." (".$res_site_nbref.")<br />\n";
//	echo "		<strong>"._("Meilleure page referente")." : </strong>".$res_page_referer." (".$res_page_nbref.")<br /><br />\n";
//	echo " ]]>";
//	echo "	</description>\n";
//	echo "</item>\n";
	}
	

// Début du fil
//header('Content-type: application/rss+xml; charset=utf-8');
affiche_rss_content();
//$i_date = 1;
//while($i_date != 30)
//	{
//	
////	// Définition de la période
////	$timestamp = mktime(0, 0, 0, date("m")  , date("d")-$i_date, date("Y"));
////	$format_date = date("Y-m-d",$timestamp);
////	// Affichage des statistiques de la période
////	affiche_rss_content("date='".$format_date."'","jour");
////	
////	// Stats hebdo
////	if(date("w",$timestamp) == 1)
////		{
////		$timestamp_semaine = mktime(0, 0, 0, date("m")  , date("d")-$i_date-7, date("Y"));
////		$format_date_semaine = "'".date("Y-m-d",$timestamp_semaine)."' <= date AND date <= '".date("Y-m-d",$timestamp-86400)."'";
////		affiche_rss_content($format_date_semaine,"semaine");
////		}
////		
////	// Stats mensuelles
////	if(date("d",$timestamp) == "01")
////		{
////		$timestamp_mois = mktime(0, 0, 0, date("m")-1  , date("d")-$i_date, date("Y"));
////		$format_date_mois = "'".date("Y-m-d",$timestamp_mois)."' <= date AND date <= '".date("Y-m-d",$timestamp-86400)."'";
////		affiche_rss_content($format_date_mois,"mois");
////		}
////	
////	// Stats annuelle
////	if(date("d",$timestamp) == "01" AND date("m",$timestamp) == "01")
////		{
////		$timestamp_annee = mktime(0, 0, 0, date("m")-1  , date("d")-$i_date, date("Y"));
////		$format_date_annee = "'".date("Y-m-d",$timestamp_annee)."' <= date AND date <= '".date("Y-m-d",$timestamp-86400)."'";
////		affiche_rss_content($format_date_annee,"annee");
////		}
//
//	
//	$i_date++;
//	}
	
	
// Fin du fil
//echo "</channel>\n";
//echo "</rss>\n";


?>
