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
header('Content-type: text/html; charset=utf-8');
@include_once("../sps.configuration.php");
@include_once("../sps.connect.inc.php");
@include_once("../locale.php");
$order = (isset($_POST["order"])) ? $_POST["order"] : "";
$valeur = (isset($_POST["valeur"])) ? $_POST["valeur"] : "";
	
if($valeur)
	{
	echo "<h3>".("Recherche par")."&nbsp;".$order."\"".$valeur."\""."</h3>";
	$req = "SELECT host,referer,date,ip,referer,nb_pages FROM ".$sps_config['db_prefix']."statistiques WHERE $order LIKE '%$valeur%' ORDER BY date DESC;";
	}
else
	{
	echo "<h3>".("Visiteurs de la journee")."</h3>";
	$date = date("Y-m-d");
	$req = "SELECT host,referer,date,ip,referer,nb_pages FROM ".$sps_config['db_prefix']."statistiques WHERE date='$date';";

	}

$requete = @mysql_query($req);
$nbresult = @mysql_num_rows($requete);

	//On réaffiche le formulaire de recherche avec la valeur
	//Search form is re-displayed with right search value


	if($nbresult)
		{
		
		echo "<h2>".$nbresult." "._("enregistrements")."</h2>";
		echo "<table style=\"width:100%;border:none;\">\n".
			"<tr>\n".
			"<td style=\"width:100px;\"><b>".("Date")."</b></td>\n".
			"<td style=\"width:190px;\"><b>".("Hote")."</b></td>\n".
			"<td style=\"width:100px;\"><b>"._("IP")."</b></td>\n".
			"<td style=\"\"><b>"._("Referent")."</b></td>\n".
			"<td style=\"width:50px\;\"><b>"._("Hits")."</b></td>\n".
			"</tr>\n";
		$i=0;
		while($nbresult != $i)
			{


	
	$host = @mysql_result($requete,$i,"host");
	$referer = @mysql_result($requete,$i,"referer");
			
	$longueur_chaine = 25;
	if (strlen($host) > $longueur_chaine)
	{
		$host = substr($host, 0, $longueur_chaine)."...";
	}
	$refererlink = $referer;
	$longueur_chaine = 35;
	if (strlen($referer) > $longueur_chaine)
	{
		$referer = substr($referer, 0, $longueur_chaine)."...";
	}

			echo "<tr>\n".
			"<td style=\"padding:2px\">".@mysql_result($requete,$i,"date")."</td>\n".
			"<td style=\"padding:2px\">".$host."</td>\n".
			"<td style=\"padding:2px\">".@mysql_result($requete,$i,"ip")."</td>\n";
			
			echo "<td style=\"padding:2px;color:#000;\"><a href=\"$refererlink\" target=\"blank\">".$referer."</a></td>\n".
			"<td style=\"padding:2px\">".@mysql_result($requete,$i,"nb_pages")."</td>\n".
			"</tr>\n";
			$i++;
			unset($host,$referer);
			}
		echo "</table>\n";
		}

	else
		{
		echo "<b>"._("Aucune valeur enregistree pour l'").$order." ".$valeur."</b>";
		}

	mysql_close($connect_db);
?>
