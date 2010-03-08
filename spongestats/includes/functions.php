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


function effectuer_test($item,$texte)
{
	if($item)
		{
		echo "<p><img src='../images/possible.png'> ".$texte." OK</p>";
		}
	else
		{
		echo "<p><img src='../images/impossible.png'> ".$texte." impossible</p>";
		}
}


function affiche_table($gauche,$droite,$url,$icone,$i)
	{
	global $sps_config;
	if($i%2)
		{
		$class = "ligne-pair";
		}
	else
		{
		$class = "ligne-impair";
		}
	echo "<div class=\"$class\" >\n";
	echo "<span class=\"span-gauche\">";
	if(!empty($icone))
		{
		echo "<span class=\"span-icone\">";
		if($sps_config['display_icones'])
			{
			foreach ($sps_config['excluded_domaines_icones'] as $filtre_icone)
				{
				if(@eregi($filtre_icone,$icone))
					{
					$showicon = "off";
					}
				else
					{
					$showicon = "on";
					}
				}
			if(eregi("hostip.info",$icone))
					{
					$class="class=\"flags\"";
					}
				if($showicon != "off")
					{
					echo "<img src=\"$icone\" $class />";
					}
				unset($showicon);
				unset($class);
			}
		echo "</span>";
		}
	if(!empty($url))
		{
		echo "<a href=\"".$url."\" target=\"_blank\">";
		}
	echo $gauche;
	if(!empty($url))
		{
		echo "</a>";
		}
	echo "</span>\n";
	echo "<span class=\"span-droite\">$droite</span>\n";
	echo "</div>";
	}
	
function affiche_aide($texte,$icone)
	{
	global $sps_config;
	if($sps_config['aide']) { echo "<div class=\"help\"><img src=\"$icone\" alt=\"$texte\" /> $texte</div>"; }
	}

//Calcul placé ici pour épurer les pages y faisant appel
$annee = isset($_GET['annee']) ? $_GET['annee'] : date("Y");
$mois = isset($_GET['mois']) ? $_GET['mois'] : date("m");
$jour = isset($_GET['jour']) ? $_GET['jour'] : date("d");
if(!empty($_GET['annee']) && !empty($_GET['mois']) && !empty($_GET['jour'])) { $format_date_sql = "date='$annee-$mois-$jour'";}
if(!empty($_GET['annee']) && !empty($_GET['mois']) && empty($_GET['jour']))  { $format_date_sql = "'$annee-$mois-01' <= date AND date <= '$annee-$mois-31'";}
if(!empty($_GET['annee']) && empty($_GET['mois']) && empty($_GET['jour']))	 { $format_date_sql = "'$annee-01-01' <= date AND date <= '$annee-12-31'";}
if(empty($annee) && empty($mois) && empty($jour))  { $format_date_sql = "'".date("Y")."-".date("m")."-01' <= date AND date <= '".date("Y")."-".date("m")."-31'";}

?>
