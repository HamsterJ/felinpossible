<?php # ***** BEGIN LICENSE BLOCK *****
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

if (file_exists("sps.configuration.php"))
	{
	include("sps.configuration.php");
	}
	else
	{
	header("Location: install/");
	}
header('Content-type: text/html; charset=utf-8');
$dotclear = 0;
$titre_module = "SpongeStats 3.0.2";
include("sps.connect.inc.php");
include("locale.php");
$mois = date("m");
$annee = date("Y");
$liste_pages=array('stats_mois','pages','hotes','referers','plateformes','mots_cles','admin_home');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php echo $titre_module; ?></title>
<link rel="alternate" type="application/rss+xml" title="SpongeStats" href="rss.php" />
<?php

$dir = "themes/";
$dhstyle  = opendir($dir);
while (false !== ($filename = readdir($dhstyle))) {
    $liste_style[] = $filename;
}

sort($liste_style);

 $i = 2;
 $num = count($liste_style);
 while($i < $num)
 {
  if($liste_style[$i] == $sps_config['default_theme']) { $altern = "stylesheet"; } else { $altern = "alternate stylesheet";}
	echo "<link href=\"themes/".$liste_style[$i]."/style.css\" rel=\"$altern\" type=\"text/css\" title=\"".$liste_style[$i]."\" /> \n ";
 $i++;
 } 

?>

<script type="text/javascript" src="js/spongestats.js.php"></script>
<script type="text/javascript" src="js/jquery-latest.js"></script>
<script type="text/javascript" src="js/livequery/jquery.livequery.pack.js"></script>
<script type="text/javascript" src="js/outils.js.php?default_theme=<?php echo $sps_config['default_theme']; ?>"></script>
</head>
<body>
<?php
	if(is_dir("install/"))
		{
		echo "<div id=\"notification\">"._("Le dossier /install est encore present dans votre repertoire SpongeStats, veuillez le supprimer pour des raisons de securite")."</div>";
		}
?>
<div id="conteneur">
	<div id="intitule">
		<h1><a href="<?php echo str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']); ?>"><?php echo $titre_module; ?></span></a></h1>
	</div>
	
	<div id="menu">
		<ul>
		<li class="graphs" id="menu-graphs">
		<a href="?onglet=stats_mois" class="lien_menu" id="stats_mois-<?php echo $annee; ?>-<?php echo $mois; ?>"><img src="themes/citron-vert/icones/graphs.png" alt="<?php echo _("Graphiques") ?>" /><?php echo _("Graphiques") ?></a></li>
		<li class="pages" id="menu-pages">
		<a href="?onglet=pages" class="lien_menu" id="pages-<?php echo $annee; ?>-<?php echo $mois; ?>"><img src="themes/citron-vert/icones/pages.png" alt="<?php echo _("Pages") ?>" /><?php echo _("Pages") ?></a></li>
		<li class="hotes" id="menu-hotes">
		<a href="?onglet=hotes" class="lien_menu" id="hotes-<?php echo $annee; ?>-<?php echo $mois; ?>"><img src="themes/citron-vert/icones/hosts.png" alt="<?php echo _("Hotes") ?>" /><?php echo _("Hotes") ?></a></li>
		<li class="referers" id="menu-referers">
		<a href="?onglet=referers" class="lien_menu" id="referers-<?php echo $annee; ?>-<?php echo $mois; ?>"><img src="themes/citron-vert/icones/referers.png" alt="<?php echo _("Referents") ?>"/><?php echo _("Referents") ?></a></li>
		<li class="plateformes" id="menu-plateformes">
		<a href="?onglet=plateformes" class="lien_menu" id="plateformes-<?php echo $annee; ?>-<?php echo $mois; ?>"><img src="themes/citron-vert/icones/plateformes.png" alt="<?php echo _("Plateformes") ?>" /><?php echo _("Plateformes") ?></a></li>
		<li class="motscles" id="menu-motscles">
		<a href="?onglet=mots_cles" class="lien_menu" id="mots_cles-<?php echo $annee; ?>-<?php echo $mois; ?>"><img src="themes/citron-vert/icones/keywords.png" alt="<?php echo _("Mots-cles") ?>"/><?php echo _("Mots-cles") ?></a></li>
		<li class="admin" id="menu-admin">
		<a href="?onglet=admin_home" class="lien_menu" id="admin_home-<?php echo $annee; ?>-<?php echo $mois; ?>"><img src="themes/citron-vert/icones/admin.png" alt="<?php echo _("Administration") ?>"/><?php echo _("Administration") ?></a></li>
		</ul>
	</div>
  
	<div id="bas">
  
		<div id="gauche">
			<div id="informations">
				<h3><?php echo _("Informations"); ?></h3>
				<a href="#" class="lien_journee" id="lien_journee"><?php echo _("Visiteurs de la journee") ?></a>
				<a href="."><?php echo _("Dernier visiteurs") ?></a>
				<a href="#" class="lien_doc" id="lien_doc"><?php echo _("Documentation") ?></a>
				<a href="rss.php" class="lien_rss" id="lien_rss"><?php echo _("Fil RSS de vos statistiques") ?></a>
				<a href="./plugins/widgets/widget.php" class="lien_rss" id="lien_rss"><?php echo _("Widgets") ?></a>
			</div>
			<div id="archives">
			<?php include("includes/archives.php"); ?></div>
			<div id="moteur">
				<h3><?php echo _("Rechercher") ?></h3>
				<?php include("includes/moteur.php"); ?>
			</div>
			<div id="theme">
				<h3><?php echo _("Themes") ?></h3>
				<?php include("includes/theme.php"); ?>
			</div>
		</div>
		
		<div id="droite">
			<div id="ajax" style="display:none;"><?php echo _("Chargement de la page en cours"); ?></div>
			<div id="spongestats">
				<?php
				if (isset($_GET['onglet']))
				{
				if(is_numeric(array_search($_GET['onglet'], $liste_pages)))
					{
						$include_name=$_GET['onglet'].".php";
					}
					else
					{
						$include_name="home.php";
					}
				}
				else
					{
						$include_name="home.php";
					}
				include("includes/".$include_name);
				?>
			</div>
		</div>
		
	  	<div id="credits">
		<?php include("includes/update.php"); ?> 
		- <a href="http://spongestats.sourceforge.net/">SpongeStats</a> - <?php echo _("Logiciel libre sous Licence GPL v2") ?> - <?php echo _("Auteurs") ?> : <a href="http://www.gougueule.com">Bastien Bobe</a> &amp; <a href="mailto:contact@lahaut.info">Samy Rabih</a></div>
		</div>
		
	</div>
	
	
<div id="default_language" style="display:none"><?php echo $sps_config['language']; ?></div>
</body>
</html>
