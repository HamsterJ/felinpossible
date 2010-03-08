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
include("functions.php");

####################################################################################################
echo "<h3>".$sps_config['display_visiteurs']._(" derniers visiteurs")."</h3>";
	
echo "<div id=\"hotes\">\n";

$texte = _("Ce tableau affiche les derniers visiteurs ayant parcouru votre site. En cliquant sur l'icone a cote du nom d'hote, vous pourrez voir le detail de ce visiteur (adresse ip, referent, logiciel, date de premiere visite, etc...)");
	affiche_aide($texte,"themes/".$sps_config['default_theme']."/icones/help.png",$sps_config['aide']);

$req = @mysql_query("SELECT id,host,nb_pages,heure,date FROM ".$sps_config['db_prefix']."statistiques ORDER BY id DESC LIMIT 0,".$sps_config['display_visiteurs'].";");



affiche_table("<strong>"._("Hote")."</strong>",_("Pages vues"),0,0,1,1);
$i = 0;

$res_all_request = @mysql_num_rows($req);
	
while($i != $res_all_request)
	{
	$id = mysql_result($req,$i,"id");
	if(mysql_result($req,$i,"date") == date("Y-m-d"))
		{
		$timeday = mysql_result($req,$i,"heure")."h";
		}
	else
		{
		$timeday = mysql_result($req,$i,"date");
		}
	$gauche = "<a href=\"javascript:void(0);\" id=\"plus_details-$id-last\" class=\"plus_details\" ><img src=\"themes/".$sps_config['default_theme']."/plus-details.png\" alt=\""._("Plus de details")."\"/></a>"."<span style=\"font-weight:bold;padding-right:20px;\">$timeday</span>".mysql_result($req,$i,"host");
	
	$droite = mysql_result($req,$i,"nb_pages");


	affiche_table($gauche,$droite,0,0,$id);
	echo "<span id=\"details-$id\" style=\"display:none;\" class=\"details\"></span>";
	
	$i++;
	}
	echo "</div>\n";

mysql_close($connect_db);
?>
<script type="text/javascript">
refresh_details();
</script>