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
echo "<h3>"._("Referents pour le mois")." ".$mois." / ".$annee."</h3>";

	echo "<div>
	<ul>
		<li><a href=\"#domaines-referers\">"._("Domaines referents")."</a></li>
		<li><a href=\"#pages-referers\" >"._("Pages referentes")."</a></li>
		<li><a href=\"#moteurs-referers\" >"._("Moteurs de recherche")."</a></li>
	</ul>
	</div>";


include_once("inc.referers.php");

mysql_close($connect_db);
	
?>