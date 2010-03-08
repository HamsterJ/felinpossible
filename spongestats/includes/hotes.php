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
$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");
$mois = isset($_GET['mois']) ? $_GET['mois'] : date("m");

####################################################################################################
// Affichage des noms d'hotes et adresses IP
// Hostnames and IP addresses display

echo "<h3>"._("Nom d'hotes et adresses IP pour le mois")." $mois / $annee</h3>";


	echo "<div>
	<ul>
		<li><a href=\"#hotes\">"._("Noms d'hotes")."</a></li>
		<li><a href=\"#ip\" >"._("Adresses IP")."</a></li>
	</ul>
	</div>";
	
include("inc.hotes.php");

mysql_close($connect_db);
?>