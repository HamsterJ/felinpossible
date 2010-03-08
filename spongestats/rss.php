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

@include_once("sps.configuration.php");
@include_once("sps.connect.inc.php");
@include_once("locale.php");

function affiche_rss_content($format_date,$periode)
	{
	global $sps_config;
	// IP + visiteurs pour la periode
	$req_total = @mysql_query("SELECT SUM(visiteurs) as sum_visiteurs,SUM(pages) as sum_pages FROM ".$sps_config['db_prefix']."archives WHERE $format_date;");
	$res_total = @mysql_result($req_total,0,"sum_pages");
	$res_ip_total = @mysql_result($req_total,0,"sum_visiteurs");

	//	Détail du meilleur site referent
	$req_referer = @mysql_query("SELECT domaine_referer,COUNT(domaine_referer) AS nbref FROM ".$sps_config['db_prefix']."statistiques WHERE domaine_referer != '' AND $format_date GROUP BY domaine_referer ORDER BY nbref DESC LIMIT 0,1;");
	$res_site_referer = @mysql_result($req_referer,0,"domaine_referer");
	$res_site_nbref = @mysql_result($req_referer,0,"nbref");

	//	Détail de la meilleure page referente
	$req_referer = @mysql_query("SELECT referer,COUNT(referer) AS nbref FROM ".$sps_config['db_prefix']."statistiques WHERE referer != '' AND $format_date GROUP BY referer ORDER BY nbref DESC LIMIT 0,1;");
	$res_page_referer = @mysql_result($req_referer,0,"referer");
	$res_page_nbref = @mysql_result($req_referer,0,"nbref");

	// Contenu du fil
	echo "<item>\n";
	//echo "<guid isPermalink=\"false\">".date("D.M.Y").$res_ip_total."</guid>";
	if($periode=="jour") {$echo_periode = _("Statistiques quotidiennes du");$format_date = ereg_replace("date='","",$format_date);}
	if($periode=="semaine") {$echo_periode = _("Statistiques hebdomadaires du");$format_date = ereg_replace("' <= date AND date <= '"," au ",$format_date);}
	if($periode=="mois") {$echo_periode = _("Statistiques mensuelles du");$format_date = ereg_replace("' <= date AND date <= '"," au ",$format_date);}
	if($periode=="annee") {$echo_periode = _("Statistiques annuelles du");$format_date = ereg_replace("' <= date AND date <= '"," au ",$format_date);}
	$format_date = ereg_replace("'","",$format_date);

	echo "	<title>$echo_periode ".$format_date."</title>\n";
	echo "	<link>http://".$_SERVER['SERVER_NAME']."/".$sps_config['sponge_folder']."</link>\n";
	echo "	<description>\n";
	echo " <![CDATA[ ";
	echo "		<strong>"._("Nombre de visiteurs")." : </strong>".$res_ip_total."<br />\n";
	echo "		<strong>"._("Nombre de pages vues")." : </strong>".$res_total."<br /><br />\n";
	echo "		<strong>"._("Meilleur site referent")." : </strong>".$res_site_referer." (".$res_site_nbref.")<br />\n";
	echo "		<strong>"._("Meilleure page referente")." : </strong>".$res_page_referer." (".$res_page_nbref.")<br /><br />\n";
	echo " ]]>";
	echo "	</description>\n";
	echo "</item>\n";
	}


// Début du fil
header('Content-type: application/rss+xml; charset=utf-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
echo "<rss version=\"2.0\">\n";
echo "<channel>\n";
echo "<title>SpongeStats</title>\n";
echo "<link>http://".$_SERVER['SERVER_NAME']."/".$sps_config['sponge_folder']."</link>\n";
// Description flux
echo "<description>Spongestats</description>";

$i_date = 1;
while($i_date != 30)
	{
	// Définition de la période
	$timestamp = mktime(0, 0, 0, date("m")  , date("d")-$i_date, date("Y"));
	$format_date = date("Y-m-d",$timestamp);
	// Affichage des statistiques de la période
	affiche_rss_content("date='".$format_date."'","jour");

	// Stats hebdo
	if(date("w",$timestamp) == 1)
		{
		$timestamp_semaine = mktime(0, 0, 0, date("m")  , date("d")-$i_date-7, date("Y"));
		$format_date_semaine = "'".date("Y-m-d",$timestamp_semaine)."' <= date AND date <= '".date("Y-m-d",$timestamp-86400)."'";
		affiche_rss_content($format_date_semaine,"semaine");
		}

	// Stats mensuelles
	if(date("d",$timestamp) == "01")
		{
		$timestamp_mois = mktime(0, 0, 0, date("m")-1  , date("d")-$i_date, date("Y"));
		$format_date_mois = "'".date("Y-m-d",$timestamp_mois)."' <= date AND date <= '".date("Y-m-d",$timestamp-86400)."'";
		affiche_rss_content($format_date_mois,"mois");
		}

	// Stats annuelle
	if(date("d",$timestamp) == "01" AND date("m",$timestamp) == "01")
		{
		$timestamp_annee = mktime(0, 0, 0, date("m")-1  , date("d")-$i_date, date("Y"));
		$format_date_annee = "'".date("Y-m-d",$timestamp_annee)."' <= date AND date <= '".date("Y-m-d",$timestamp-86400)."'";
		affiche_rss_content($format_date_annee,"annee");
		}


	$i_date++;
	}


// Fin du fil
echo "</channel>\n";
echo "</rss>\n";


?>
