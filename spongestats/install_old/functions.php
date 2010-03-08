<?php

function creer_tables($prefixe)
{
$sps_mois = date('m');
$sps_annee = date('Y');
$sps_table = $prefixe."stats_".$sps_annee."_".$sps_mois;
$sps_table_stats = $prefixe."statistiques";
$sps_table_archive = $prefixe."archives";
$sps_table_config = $prefixe."config";

//Creation table statistiques
$result=mysql_query("CREATE TABLE IF NOT EXISTS `$sps_table_stats` (
  `id` int(10) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `heure` int(2) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `nb_pages` int(4) NOT NULL default '0',
  `url_page` varchar(255) NOT NULL default '',
  `referer` varchar(255) NOT NULL default '',
  `host` varchar(150) NOT NULL,
  `domaine_referer` varchar(255) NOT NULL default '',
  `mot_cle` varchar(255) NOT NULL,
  `logiciel` varchar(30) NOT NULL,
  `version` varchar(10) NOT NULL,
  `plateforme` varchar(30) NOT NULL,
  `type_logiciel` int(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `date` (`date`)
)");
effectuer_test($result,_("Creation table statistiques"));

//Creation table archives
	$result=mysql_query("CREATE TABLE IF NOT EXISTS `$sps_table_archive` (
  `id` int(4) NOT NULL auto_increment,
  `date` date NOT NULL,
  `visiteurs` int(8) NOT NULL,
  `pages` int(8) NOT NULL,
  PRIMARY KEY  (`id`)
)");
effectuer_test($result,_("Creation table archives"));
  	// Table du nombre de pages vue pour chaque mois
	// Monthly seen pages table
mysql_query("CREATE TABLE IF NOT EXISTS $sps_table(
	`url` VARCHAR(255) NOT NULL,
	`nb_vu` INT(10) NOT NULL
	);");
effectuer_test($result,_("Creation table statistiques du mois"));
//Creation table configuration
	$result=mysql_query("CREATE TABLE IF NOT EXISTS `$sps_table_config` (
  `id` int(4) NOT NULL auto_increment,
  `param` varchar(100) NOT NULL,
  `valeur` text NOT NULL,
  PRIMARY KEY  (`id`)
)");
effectuer_test($result,_("Creation table configuration"));
include("install.template.php");
$sps_config['sps_admin_pass']=md5($_GET['sps_admin_pass']);
foreach ($sps_config as $cle_config => $param_config)
	{
	if (!mysql_query("SELECT `$cle_config` FROM `$sps_table_config`"))
		{

		if (is_array($param_config))
			{
			//Cas des variables sous forme de tableaux
			$param_config=serialize($param_config);
			}
		//Alimentation table de configuration
		$req_if_field_exists = mysql_query("SELECT param FROM `$sps_table_config` WHERE param='$cle_config';");
		if(mysql_num_rows($req_if_field_exists) == 0)
			{
			mysql_query("INSERT INTO `$sps_table_config`(param,valeur) VALUES('$cle_config','$param_config')");
			}
		}
	}
}
function creer_fichier_config()
	{
	//Ecriture fichier config

	$chemin_fichier_config="../sps.configuration.php";
	if (!file_exists($chemin_fichier_config))
		{
		$inF=@fopen($chemin_fichier_config,"x");


		$configuration = "define('sps_server','".$_GET['sps_server']."');\n";
		$configuration .= "define('sps_user','".$_GET['sps_user']."');\n";
		$configuration .= "define('sps_pass','".$_GET['sps_pass']."');\n";
		$configuration .= "define('sps_base','".$_GET['sps_base']."');\n";
		$configuration .= "define('db_prefix','".$_GET['db_prefix']."');\n";

		@fwrite($inF,"<?php \n");
		@fwrite($inF,$configuration) or die("Impossible d'&eacute;crire dans le fichier de configuration<br /> Veuillez copier ceci dans un fichier nomm&eacute; sps.configuration.php &agrave; la racine du r&eacute;pertoire d'installation de SpongeStats : <br /><br /><pre style=\"text-align:left;padding-left:30px;\">&lt;?php\n".$configuration."?&gt;</pre>");
		@fwrite($inF,"?>");
		fclose($inF);
		}

	echo "<ul id=\"liens_post_install\"><li><a href=\"../\">Acc&eacute;der &agrave; l'interface de SpongeStats</a></li><li style=\"padding-top:10px;\"><a href=\"http://spongestats.sourceforge.net/?reference=yes\">S'enregistrer en tant qu'utilisateur sur le site du projet</a></li>";
	}
?>