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
header('Content-type: text/html; charset=utf-8');

@include_once("../sps.configuration.php");
@include_once("../sps.connect.inc.php");
@include_once("../locale.php");
include("functions.php");

echo "<h3>"._("Statistiques du")." $jour / $mois / $annee</h3>";


	echo "<div>";
	echo "<div id=\"details\" style=\"position:absolute;\" class=\"survol\"></div>";
	echo "<ul>
		<li><a href=\"#plages-horaires\" >"._("Voir les statistiques horaires")."</a></li>
		<li><a href=\"#hotes\" >"._("Voir les hotes")."</a></li>
		<li><a href=\"#ip\" >"._("Voir les IP")."</a></li>
		<li><a href=\"#domaines-referers\" >"._("Voir les domaines referents")."</a></li>
		<li><a href=\"#pages-referers\" >"._("Voir les pages referentes")."</a></li>
		<li><a href=\"#moteurs-referers\" >"._("Moteurs de recherche")."</a></li>
		<li><a href=\"#pages-entree\" >"._("Voir les pages d'entree")."</a></li>
		<li><a href=\"#navigateurs\" >"._("Voir les navigateurs")."</a></li>
		<li><a href=\"#agregateurs\" >"._("Voir les agregateurs")."</a></li>
		<li><a href=\"#os\" >"._("Voir les systemes d'exploitation")."</a></li>
		<li><a href=\"#keyword\" >"._("Voir les mots cles")."</a></li>
	</ul>
	</div>";

	// Affichage des plages horaires de la journée
	// Day time slots

	include_once("inc.graph_horaire.php");

	// Affichage des nom d'hôtes et adresses IP
	// Hostnames and IP addresses display

	include_once("inc.hotes.php");

	// Affichage des referents
	// Referers display

	include_once("inc.referers.php");
	
	// Affichage des pages d'entréé
	// Entering pages display

	include_once("inc.pages_ent.php");


	// Affichage des plateformes, navigateurs et agrégateurs
	// Platforms, browsers and rss reader display
	
	include_once("inc.user_agents.php");
	
	// Affichage des mots clés
	// Keywords display
	
	include_once("inc.mots_cles.php");


mysql_close($connect_db);
?>