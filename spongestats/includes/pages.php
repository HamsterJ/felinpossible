<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of SpongeStats
# Copyright (c) 2006 Bastien Bobe. All rights reserved.
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
if (!isset($_GET['onglet']))
{
	header('Content-type: text/html; charset=utf-8');
	@include_once("../sps.configuration.php");
	@include_once("../sps.connect.inc.php");
}
@include_once("../locale.php");
include("functions.php");

	if(strlen($mois) == 1) {$mois = "0".$mois;}
	$table = $sps_config['db_prefix']."stats_".$annee."_".$mois;
	$req = @mysql_query("SELECT * FROM $table ORDER BY nb_vu DESC LIMIT 0,".$sps_config['display_pages_vues'].";");
	
	$i=0;
	
	
	echo "<h3>"._("Pages les plus vues pour le mois")." ".$mois." / ".$annee."</h3>";
	echo "<div>
	<ul>
		<li><a href=\"#pages-vues\">"._("Pages vues")."</a></li>
		<li><a href=\"#pages-entree\" >"._("Pages d'entree")."</a></li>
	</ul>
	</div>";
	echo "<a name=\"pages-vues\"></a>";
	echo "<h2>"._("Pages les plus vues")." (".$sps_config['display_pages_vues'].")</h2>\n";
	echo "<div>\n";
	
	$texte = _("Les pages les plus vues sont les pages les plus consultees de votre site par l'ensemble des visiteurs. Elles sont classees par nombre de consultation pour la periode en cours. Si vous cliquez sur le nom de la page, vous serez redirige automatiquement vers la page affichee");
affiche_aide($texte,"themes/".$sps_config['default_theme']."/icones/help.png",$sps_config['aide']);
	
	while($i!=@mysql_num_rows($req))
		{
	$url = @mysql_result($req,$i,"url");
	$url_nb = explode(".",$url);
	$id_doc=$url_nb[0];
	
	$gauche = @mysql_result($req,$i,"url");
	$droite = @mysql_result($req,$i,"nb_vu");
	
	
	affiche_table($gauche,$droite,$gauche,"themes/".$sps_config['default_theme']."/icones/pages.png",$i);
	

	$i++;
		}
	echo "</div>";
	
	
include_once("inc.pages_ent.php");
	
	mysql_close($connect_db);
?>