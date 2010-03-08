<div id="test_params">
<?php
include("../includes/functions.php");
include("functions.php");
if (isset($_GET['action']))
{
	switch ($_GET['action']) {
		//Test des parametres de connexion a la base de donnees
		case "test_params":
			@$connect_db = mysql_connect($_GET['sps_server'], $_GET['sps_user'], $_GET['sps_pass']);
			@mysql_select_db($_GET['sps_base'],$connect_db);
			if(!@mysql_select_db($_GET['sps_base'],$connect_db))
				{
				$connexion_bdd=false;
				effectuer_test($connexion_bdd,"Connexion a la base de donnees");
				break;
				}
			else
				{
				$connexion_bdd=true;
				effectuer_test($connexion_bdd,"Connexion a la base de donnees");
				echo "<input id='bouton_install_spongestats' type='button' value='"._("Installer Spongestats")."'>";
				}
			break;
		//Parametres OK => on cree les tables
		case "install_spongestats":
			@$connect_db = mysql_connect($_GET['sps_server'], $_GET['sps_user'], $_GET['sps_pass']);
			@mysql_select_db($_GET['sps_base'],$connect_db);
			creer_tables($_GET['db_prefix']);
			creer_fichier_config();
			break;
		default:
			break;
	}
}
?>
</div>