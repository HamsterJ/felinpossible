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

//Page accedee directement par l'URL
if (!isset($_GET['onglet']))
{
	session_start();
	header('Content-type: text/html; charset=utf-8');
@include_once("../sps.configuration.php");
@include_once("../sps.connect.inc.php");
}

if(isset($_POST['pass']))
	{
	$_SESSION['sps_passwd'] = md5($_POST['pass']);
	}
else
{
	$_SESSION['sps_passwd'] = "";
}

@include_once("../locale.php");
include("functions.php");
echo "<h3>"._("Administration")."</h3>";
echo "<div id=\"administration\">";

$res_auth = @mysql_result(mysql_query("SELECT valeur FROM ".$sps_config['db_prefix']."config WHERE param='sps_admin_pass';"),0,"valeur");
if ($_SESSION['sps_passwd'] !== $res_auth)
	{
	echo "<h4>"._("Entrez le mot de passe pour acceder a l'administration")."</h4>";
	echo "<br /><form onsubmit=\"return false;\" method=\"post\"><input type=\"password\" id=\"sps_admin_pass\" name=\"sps_admin_pass\"><br />\n";
	echo "<input type=\"button\" value=\""._("Valider")."\" id=\"auth\" class=\"lien_go_auth\"/></form>\n";
	}
else
	{

	if((isset($_POST['passwd']))&&($_POST['passwd'] == 1))
		{
		
		$password = md5($_POST['password']);
		$_SESSION['sps_passwd'] = $password;
		mysql_query("UPDATE ".$sps_config['db_prefix']."config SET valeur='$password' WHERE param='sps_admin_pass';");
		echo "<h2 class=\"infoadmin\">"._("Le mot de passe a bien ete change")."</h2>";
		}

	//Export de la base de données
	if((isset($_POST['purge']))&&($_POST['purge'] == 1))
		{
		
		$datestart = $_POST['purgemois'];
		$exploded = explode("-",$datestart);
		mysql_query("DELETE FROM ".$sps_config['db_prefix']."statistiques WHERE date <= '$datestart-31';");
		mysql_query("DELETE FROM ".$sps_config['db_prefix']."archives WHERE date <= '$datestart-31';");
		mysql_query("OPTIMIZE ".$sps_config['db_prefix']."statistiques;");
		mysql_query("OPTIMIZE ".$sps_config['db_prefix']."archives;");
		
		$rs = mysql_query("SHOW TABLES LIKE '".$sps_config['db_prefix']."stats\_%'");
		$iarc = 0;
		$continue = 1;
		while ($tab = mysql_fetch_array($rs)) {
			
			$archive = explode("_",$tab[0]);
			
			if($continue==1)
				{
				mysql_query("DROP TABLE .".$tab[0].";");
				if(eregi($exploded[0]."_".$exploded[1],$tab[0])) $continue=0;
				}
			
		
			$iarc++;
		}
		echo "<h2 class=\"infoadmin\">"._("Les donnees anterieures a la date selectionnee ont ete purgees")."</h2>";
		
		?>
		<script type="text/javascript">
		// Refresh archive content
		function()
					{
					$.get("includes/archives.php",{mois:<?php echo date("m"); ?>,annee:<?php echo date("Y"); ?>},function(txt){$("#archives").html(txt);});
					});
		}
		</script>
		<?php
		
		
		

		}

	
	$texte = _("ATENTION: Ne faites des modifications dans les champs ci-dessous uniquement si vous savez ce que vous faites, tous les changements effecutes ne peuvent etre annules.");
		affiche_aide($texte,"themes/".$sps_config['default_theme']."/icones/help.png",$sps_config['aide']);
		
	echo "</div>";
	
	echo "<div>
	<ul>
		<li><a href=\"#configuration\">"._("Configuration")."</a></li>
		<li><a href=\"#chg-pass\" >"._("Changement du mot de passe")."</a></li>
		<li><a href=\"#purge-db\" >"._("Purger les donnees")."</a></li>
		<li><a href=\"#export-db\" >"._("Exporter les donnees")."</a></li>
	</ul>
	</div>";	
	echo "<a name=\"configuration\"></a>";
	echo "<h3>"._("Configuration")."</h3>";
	echo "<h2>"._("Exclusions")."</h2>";
	
	function affiche_label($label)
		{
		echo "<tr><td>
		$label</td>";
		}
	
	function affiche_field($intitule,$nom,$type) {
	global $sps_config; 
	if($type == "select")
		{
		echo "<td style=\"padding:5px;\">";
		$valeur_select = "";
		echo "<select name=\"$nom\" id=\"$nom\" style=\"width:312px;padding:5px;\">";
		foreach ($intitule as $value)
			{
			$valeur_champ = $value;
			echo "<option id=\"$nom\" value=\"".$valeur_champ."\" ";
			if($valeur_champ==$sps_config["$nom"]) {echo "selected=\"selected\""; }
			echo ">".$valeur_champ."</option>";
			}
		echo "<td></tr>";
		}
	if($type == "boolean")
		{
		echo "<td style=\"padding:5px;\">";
		
		echo "<input id=\"$nom\" type=\"radio\" name=\"".$nom."\" value=\"1\" style=\"width:20px\"";
		if($sps_config["$nom"]==1) {echo "checked"; } echo "/>";
		echo _("oui");
		echo "<input id=\"$nom\" type=\"radio\" name=\"".$nom."\" value=\"0\" style=\"width:20px\"";
		if($sps_config["$nom"]==0) {echo "checked"; } echo "/>";
		echo _("non");
		echo "<td></tr>";
		}
	if($type=="table")
			{
			echo "<td style=\"padding:5px;\">";
			$valeur_config = "";
			foreach($intitule as $value) {$valeur_config .= $value."\n";}
			echo "<textarea id=\"$nom\" name=\"".$nom."\" rows=\"4\" style=\"width:300px;padding:5px;\">".trim($valeur_config)."</textarea>";
			echo "</td></tr>";
			}
	if($type=="text")
			{
			echo "<td style=\"padding:5px;\">";
			echo "<input id=\"$nom\" type=\"text\" name=\"".$nom."\" value=\"".$intitule."\"  style=\"width:300px;padding:5px;\"/>";
			echo "</td></tr>";
			}
		}

	echo "<form name=\"conf\" id=\"conf\" action=\"includes/admin_change-conf.php\" method=\"POST\" >";
	echo "<table class=\"spongeadmin-table\" style=\"padding:15px;\">";
	
	affiche_label(_("IP exclues"));
	affiche_field($sps_config['excluded_ip'],'excluded_ip','table')."<br />";
	
	affiche_label(_("Hote exclus"));
	affiche_field($sps_config['excluded_host'],'excluded_host','table')."<br />";
	
	affiche_label(_("Referers exclus"));
	affiche_field($sps_config['excluded_referers'],'excluded_referers','table')."<br />";
	
	affiche_label(_("Logiciels exclus"));
	affiche_field($sps_config['excluded_user_agent'],'excluded_user_agent','table')."<br />";
	
	echo "</table>";
	
	echo "<h2>"._("Interface et affichage")."</h2>";
	
	echo "<table class=\"spongeadmin-table\"  style=\"padding:15px;\">";
	
	affiche_label(_("Langue par defaut"));
	$dir = "../locale";
	$dhstyle  = opendir($dir);
	while (false !== ($filename = readdir($dhstyle))) {
		$liste_style[] = $filename;
	}
	
	sort($liste_style);
	
	$i = 2;
	$num = count($liste_style);
	$contentthemes = array();
	while($i < $num)
		{
		$contentlanguage[$i] = $liste_style[$i];
		$i++;
		}
		unset($liste_style);
	affiche_field($contentlanguage,'language','select');
	
	affiche_label(_("Affichage de l'aide"));
	affiche_field($sps_config['aide'],'aide','boolean');
	
	affiche_label(_("Theme par defaut"));
	$dir = "../themes/";
	$dhstyle  = opendir($dir);
	while (false !== ($filename = readdir($dhstyle))) {
		$liste_style[] = $filename;
	}
	
	sort($liste_style);
	
	$i = 2;
	$num = count($liste_style);
	$contentthemes = array();
	while($i < $num)
		{
		$contentthemes[$i] = $liste_style[$i];
		$i++;
		}
	affiche_field($contentthemes,'default_theme','select');
	
	affiche_label(_("Nombre de visiteurs affiches"));
	affiche_field($sps_config['display_visiteurs'],'display_visiteurs','text');
	
	affiche_label(_("Nombre de pages vues affichees"));
	affiche_field($sps_config['display_pages_vues'],'display_pages_vues','text');
	
	affiche_label(_("Nombre de pages d'entree affichees"));
	affiche_field($sps_config['display_pages_entree'],'display_pages_entree','text');
	
	affiche_label(_("Nombre de pages referers affichees"));
	affiche_field($sps_config['display_pages_referers'],'display_pages_referers','text');
	
	affiche_label(_("Nombre de domaines referers affichees"));
	affiche_field($sps_config['display_domains_referers'],'display_domains_referers','text');
	
	affiche_label(_("Nombre de mots cles affiches"));
	affiche_field($sps_config['display_mots_cles'],'display_mots_cles','text');
	
	affiche_label(_("Nombre d'occurence des mots cles pour l'affichage"));
	affiche_field($sps_config['display_mots_cles_occurences'],'display_mots_cles_occurences','text');
	
	affiche_label(_("Nombre de jour pour l'evolution des mots cles"));
	affiche_field($sps_config['display_historique'],'display_historique','text');
	
	affiche_label(_("Nombre d'ip affichees"));
	affiche_field($sps_config['display_ip'],'display_ip','text');
	
	affiche_label(_("Nombre d'hotes affiches"));
	affiche_field($sps_config['display_hotes'],'display_hotes','text');
	
	affiche_label(_("Afficher les favicon"));
	affiche_field($sps_config['display_icones'],'display_icones','boolean');
	
	affiche_label(_("Exclusions des domaines pour l'affichage des favicons"));
	affiche_field($sps_config['excluded_domaines_icones'],'excluded_domaines_icones','table');
	
	echo "</table>";
	
	echo "<h2>"._("Logiciels et plateformes")."</h2>";
	
	echo "<table class=\"spongeadmin-table\"  style=\"padding:15px;\">";
	
	affiche_label(_("Navigateurs"));
	affiche_field($sps_config['navigateurs'],'navigateurs','table');
	
	affiche_label(_("Agregateurs"));
	affiche_field($sps_config['agregateurs'],'agregateurs','table');
	
	affiche_label(_("Systemes d'exploitation"));
	affiche_field($sps_config['plateformes'],'plateformes','table');
	
	echo "</table>";
	
	echo "<span style=\"display:block;width:100%;text-align:center;margin-bottom:20px;\" >";
	echo "<input type=\"submit\" value=\""._("Modifier la configuration")."\" id=\"lien_go_conf\" class=\"lien_go_conf\" /></span></form>";
	
	echo "<a name=\"chg-pass\"></a>";
	echo "<h3>"._("Changement du mot de passe")."</h3>";
	
	echo "<div>";
	
	echo "<form onsubmit=\"return false;\" >";
	echo "<p>"._("Nouveau mot de passe")." : ";
	echo "<input type=\"password\" id=\"pass1\" value=\"\" /></p>";
	
	echo "<p>"._("Confirmation")." : ";
	echo "<input type=\"password\" id=\"pass2\" value=\"\" /></p>";
	
	echo "<input type=\"button\" value=\""._("Changer le mot de passe")."\" id=\"lien_go_pass\" class=\"lien_go_pass\" /></form>";
	
	echo "</div>";

	
	echo "<a name=\"purge-db\"></a>";
	echo "<h3>"._("Purger les donnees")."</h3>";
	
	echo "<div>";
	
	echo _("La purge des donnees vous permet d'alleger votre base de donnee. Toutes les informations de statistiques anterieures a la date que vous choisisirez seront perdues Si vous souhaitez reutiliser ces informations, faites d'abord un export de votre base.");
	
	$first_date = @mysql_result(mysql_query("SELECT date FROM ".$sps_config['db_prefix']."statistiques ORDER BY date ASC LIMIT 0,1;"),0,"date");
	
	$date_debut = explode("-",$first_date);
	$mois_debut = $date_debut[1];
	$year_debut = $date_debut[0];
	
	if($first_date)
	{
	
	echo "<form onsubmit=\"return false;\" ><p>";
	echo _("Purger les donnees plus anciennes que");
	
	echo "</p><p><select id=\"purge\" name=\"purge\">";
	for ($ia = $year_debut; $ia <= date("Y"); $ia++)
		{
//		echo "anneee : $ia<br />";
		if($ia != date("Y"))
			{
			for ($im = $mois_debut; $im <= 12; $im++)
				{
				if(strlen($im) == 1) $im="0".$im;
				echo "<option value=\"".$ia."-".$im."\">";
				echo $im." / ".$ia;
				echo "</option>";
				}
			$previous=1;
			}
		else
			{
			if($previous==1) $mois_debut=1;
			for ($im = $mois_debut; $im <= date("m"); $im++)
				{
				if(strlen($im) == 1) $im="0".$im;
				echo "<option value=\"".$ia."-".$im."\">";
				echo $im." / ".$ia;
				echo "</option>";
				}
			}
		}

	
	echo "</select>";
	
	echo "<input type=\"button\" value=\""._("Valider")."\" id=\"ok\" class=\"lien_go_purge\" /></p></form>";
	}
	else
	{
	echo"<p>"._("Aucune donnee a purger")."</p>";
	}
	echo "</div>";
	
	echo "<a name=\"export-db\"></a>";
	echo "<h3>"._("Exporter les donnees")."</h3>";
	
	echo "<div>";
	
	echo _("L'export de donnee vous permet de faire une sauvegarde complete de vos donnees SpongeStats. L'export est au format .sql, vous pourrez ensuite reimporter ce fichier dans votre base de donnee a l'aide de votre outil de gestion de base MySQL (PHPMyAdmin, Eskuel, mysqladmin, etc...)");
	
	echo "<p><a href=\"includes/admin_export-datas.php\">"._("Exporter les donnees")."</a></p>";
	echo "</div>";
	}
mysql_close($connect_db);
?></div>