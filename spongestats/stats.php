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



// Disable the comments in the following line if you have PHP Notice informations in your Web site when SpongeStats is integrated
// Désactiver les commentaires de la ligne ci-dessous si vous avez des informaitons de Notive PHP sur votre site Internet une fois SpongeStats intégré

//error_reporting(E_PARSE);


	include_once("sps.configuration.php");
	include_once("sps.connect.inc.php");

function spongestats($sps_config) {

	$sps_mois = date('m');
	$sps_annee = date('Y');
	$sps_table = $sps_config['db_prefix']."stats_".$sps_annee."_".$sps_mois;
	$sps_table_stats = $sps_config['db_prefix']."statistiques";
	$sps_table_archive = $sps_config['db_prefix']."archives";


	// Table du nombre de pages vue pour chaque mois
	// Monthly seen pages table
	mysql_query("CREATE TABLE IF NOT EXISTS $sps_table(
	`url` VARCHAR(255) NOT NULL,
	`nb_vu` INT(10) NOT NULL
	);");


	$sps_date = date('Ymd');
	$sps_heure = date("H");
	// Uncomment the following code if your web site is hosted behind a Web proxy :
	/*
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			if ($_SERVER['HTTP_X_FORWARDED_FOR']!=$_SERVER['REMOTE_ADDR'])
				{
				$sps_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				}
			else
			{
				$sps_ip = $_SERVER['REMOTE_ADDR'];
			}

		}
	else
	{
		$sps_ip = $_SERVER['REMOTE_ADDR'];
	}
	*/
	$sps_ip = $_SERVER['REMOTE_ADDR'];
	
	$sps_referer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
	$sps_current_url = $_SERVER["REQUEST_URI"];
	$sps_host = gethostbyaddr($sps_ip);
	if(!empty($sps_referer)) {$sps_domaine_referer = ereg_replace("(http://[^/]*/)(.*)", "\\1", $sps_referer);}
	$sps_user_agent = $_SERVER['HTTP_USER_AGENT'];
	$sps_internal_domain = $_SERVER['SERVER_NAME'];

	// Définition des variables
	// Variables definition
	define('sps_filtre',0);

	foreach ($sps_config['excluded_ip'] as $sps_filtre_ip)
		{
		if(eregi($sps_filtre_ip,$sps_ip)) {$sps_filtre = 1;}
		}
	foreach ($sps_config['excluded_host'] as $sps_filtre_host)
		{
		if(eregi($sps_filtre_host,$sps_host)) {$sps_filtre = 1;}
		}
	foreach ($sps_config['excluded_user_agent'] as $sps_filtre_ua)
		{
		if(eregi($sps_filtre_ua,$sps_user_agent)) {$sps_filtre = 1;}
		}
	foreach ($sps_config['excluded_referers'] as $sps_filtre_ref)
		{
		if(eregi($sps_filtre_ref,$sps_referer) || substr($sps_referer, -1, 1) == "#") {$sps_filtre = 1;}
		}

	if($sps_filtre != 1)
	{
	$req_uniq_ip = mysql_query("SELECT ip FROM $sps_table_stats WHERE date='$sps_date' AND ip='$sps_ip' LIMIT 0,1");
	// Si nouveau visiteur de la journée
	// If new visit for the current day
	if(mysql_num_rows($req_uniq_ip) == 0)
		{
		if(!eregi($sps_internal_domain,$sps_domaine_referer))
			{
			$browser = browser($sps_user_agent);
			mysql_query("INSERT INTO ".$sps_config['db_prefix']."statistiques (date,heure,ip,nb_pages,url_page,referer,host,domaine_referer,logiciel,version,plateforme,type_logiciel) VALUES ('$sps_date','$sps_heure','$sps_ip','1','$sps_current_url','$sps_referer','$sps_host','$sps_domaine_referer','".$browser['logiciel']."','".$browser['version']."','".$browser['plateforme']."','".$browser['type']."');");
			if(!empty($sps_referer))
				{
				$sps_serveur = ereg_replace("(http://[^/]*/)(.*)", "\\1", $sps_referer);
				$sps_refererpreg = str_replace($sps_serveur,"",$sps_referer);
				$sps_querystring = parse_url($sps_referer);
				$sps_q = parse_str($sps_querystring['query'], $output);
				//Mots clés Google et autres requêtes
				//Google keywords & others querys
				$sps_keywords = $output['q'];
				if(!$sps_keywords)
					{
					//Mots clés Yahoo!
					//Yahoo! keywords
					$sps_keywords = $output['p'];
					}
				if($sps_keywords)
					{

					$id_ligne = @mysql_result(mysql_query("SELECT id FROM ".$sps_config['db_prefix']."statistiques  WHERE ip='$sps_ip' ORDER BY id DESC LIMIT 0,1;"),°,"id");
					@mysql_query("UPDATE ".$sps_config['db_prefix']."statistiques  SET mot_cle='".eregi_replace("\+"," ",$sps_keywords)."' WHERE id='$id_ligne';");
					}
				}


			// Archives
			$req_archives = mysql_query("SELECT visiteurs FROM ".$sps_config['db_prefix']."archives WHERE date='$sps_date' LIMIT 0,1;");
			$visiteurs =@(mysql_num_rows($req_archives)) ? mysql_result($req_archives,0,"visiteurs") : "";
			if(empty($visiteurs))
				{
				mysql_query("INSERT ".$sps_config['db_prefix']."archives (date,visiteurs,pages) VALUES ('$sps_date','1','1');");
				}
			else
				{
				mysql_query("UPDATE ".$sps_config['db_prefix']."archives SET pages=pages+1,visiteurs=visiteurs+1 WHERE date='$sps_date';");
				}
			}
		}
	else
		{
		mysql_query("UPDATE $sps_table_stats SET nb_pages=nb_pages+1 WHERE ip='$sps_ip' AND date='$sps_date';");
		mysql_query("UPDATE ".$sps_config['db_prefix']."archives SET pages=pages+1 WHERE date='$sps_date';");
		}
		$sps_url = $sps_current_url;
		$sps_req_nb_vu = mysql_query("SELECT nb_vu FROM $sps_table WHERE url='$sps_url';");
		if(mysql_num_rows($sps_req_nb_vu) != 0)
		{
			$nb_vu = mysql_result($sps_req_nb_vu,0,"nb_vu");
			$nb_vu++;
			mysql_query("UPDATE $sps_table SET nb_vu='$nb_vu' WHERE url='$sps_url';");
		}
		else
		{
			$nb_vu=1;
			mysql_query("INSERT INTO $sps_table(url,nb_vu) VALUES('$sps_url','$nb_vu');");
		}
	}
}

function browser($agent)
	{
	// Si vous ajouter quelque chose ici, n'oubliez pas de recopier ces tableaux dans le fichier includes/inc.user_agents.php
	// If you add something here, don't forget to copy the arrays to the file includes/inc.user_agents.php

	// Liste des navigateurs, agrégateur & plateformes
	// Browser agents, rss reader & platforms list


	$versionnable = "mozilla msie gecko firefox konqueror opera netscape epiphany seamonkey iceweasel";


	global $sps_config;

	$logiciel = 'unknown';
	$plateforme = 'unknown';
	$version = '0';
	$type = 0;

	foreach($sps_config['navigateurs'] as $chaine)
			{
			if(eregi("$chaine",$agent))
				{
				$logiciel = "$chaine";
				$type = 1;

				if(eregi("$chaine",$versionnable))
					{
					$version = browser_version($agent,$versionnable);
					}

				break;
				}
			}
	foreach($sps_config['plateformes'] as $chaine)
			{
			if(eregi("$chaine",$agent))
				{
				$plateforme = "$chaine";
				break;
				}
			}
	if($logiciel == 'unknown')
			{
				foreach($sps_config['agregateurs'] as $chaine)
				{
				if(eregi("$chaine",$agent))
					{
					$logiciel = "$chaine";
					$type = 2;
					break;
					}
				}
			}
		$browser['logiciel'] = $logiciel;
		$browser['version'] = $version;
		$browser['plateforme'] = $plateforme;
		$browser['type'] = $type;

		return $browser;
	}


function browser_version($user_agent,$browsers)
	{
	$browsers = split(" ", $browsers);

	$nua = strToLower($user_agent);

	$l = strlen($nua);
	for ($i=0; $i<count($browsers); $i++)
		{
		$browser = $browsers[$i];
		$n = stristr($nua, $browser);
		if(strlen($n)>0)
			{
			$version = "";
			$j=strpos($nua, $browser)+$n+strlen($browser)+1;
			for (; $j<=$l; $j++)
				{
				$s = substr ($nua, $j, 1);
				if(is_numeric($version.$s) )
				$version .= $s;
				else
				break;
				}
			}
		}
	return $version;
	}
@spongestats($sps_config);
?>