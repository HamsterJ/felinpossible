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
#
# Fichier de connexion à la base de données.
# Database connection file
#
# Les informations sont récupérées depuis le fichier sps.configuration.php à la racine du dossier
# Settings are provided by root folder sps.configuration.php file


// Connexion à la base de données
// Database connection

global $sps_config;

	$connect_db = mysql_connect(sps_server, sps_user, sps_pass);
	@mysql_select_db(sps_base,$connect_db);

	if(!@mysql_select_db(sps_base,$connect_db))
		{
			echo _("Connexion a la base impossible");
			exit;
		}


$req_config = mysql_query("SELECT param,valeur FROM ".db_prefix."config;");
$iconf=0;
while(mysql_num_rows($req_config) != $iconf)
	{
	$param = mysql_result($req_config,$iconf,"param");
	$valeur = mysql_result($req_config,$iconf,"valeur");
	if(substr($valeur, 0, 2) == "a:")
		{
		$sps_config["$param"] = unserialize($valeur);
		}
	else
		{
		$sps_config["$param"] = $valeur;
		}
	$iconf++;
	}

	$sps_config["db_prefix"] = db_prefix;




//print_r($sps_config);


?>