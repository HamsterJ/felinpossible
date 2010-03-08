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
@header('Content-type: text/html; charset=utf-8');
@include_once("../sps.configuration.php");
@include_once("../sps.connect.inc.php");
@include_once("../locale.php");
$archives = 13;

if(!isset($_GET["vue"]))
	{
	$fichier = "stats_mois";
	}
else
{
	$fichier = $_GET["vue"];
}
switch ($fichier) {
case "stats_mois":
    $nom_archive = _("Archives mensuelles");
    break;
case "mots_cles":
    $nom_archive = _("Archives des mots-cles");
    break;
case "referers":
    $nom_archive = _("Archives des referents");
    break;
case "plateformes":
    $nom_archive = _("Archives des plate-formes");
    break;
case "pages":
    $nom_archive = _("Archives des pages vues");
    break;
case "pages_entree":
    $nom_archive = _("Archives des pages d'entree");
    break;
case "hotes":
    $nom_archive = _("Archives des noms d'hotes");
    break;
default:
	$nom_archive = _("Archives mensuelles");
	$fichier = "stats_mois";
	break;
}

echo "<h3>$nom_archive</h3>";
$iarc = 0;
$rs = mysql_query("SHOW TABLES LIKE '".$sps_config['db_prefix']."stats\_%'"); 
// Merci à Thanh (sutekidane.net) pour la technique
// Thanks to Thanh (sutekidane.net) for this trick
	$iarc = 0;
	while ($tab = mysql_fetch_array($rs)) {
		
		$tab[0]=str_replace("__","#_",$tab[0]);
		$archive = explode("_",$tab[0]);
		foreach($archive as $archive_value)
			{
			$archive_value=str_replace("#","_",$archive_value);
			}		
		$underprefix = count($tab);
		$arc_annee[$iarc] = $archive[$underprefix];	
		$underprefix++;
		$arc_mois[$iarc] = $archive[$underprefix];
	
		$iarc++;
	}
if($arc_mois)
	{
	$arc_mois = array_reverse($arc_mois);
	$arc_annee = array_reverse($arc_annee);
	}
else
	{
	echo "<a name=archives>"._("Aucune archive")."</a>";
	}

$i = 0;

echo "<ul>";

while($i != $archives)
	{
	if(!empty($arc_mois[$i]) and !empty($arc_annee[$i]))
		{
		echo "<li>";
		echo "<a class=\"lien_archive\" href=\"#\"  id=\"$fichier-$arc_mois[$i]-$arc_annee[$i]\" title=\"".$arc_mois[$i]." / ".$arc_annee[$i]."\">".$arc_mois[$i]." / ".$arc_annee[$i]."</a>\n";
		echo "</li>";
		}
	$i++;
	}
echo "</ul>";
?>