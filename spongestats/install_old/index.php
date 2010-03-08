<?php if (file_exists("../sps.configuration.php"))
	{
	header("Location: ../");
	exit();
	}
?>
<?php
include("install.template.php");
global $sps_config;
include("../locale.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="../themes/citron-vert/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery-latest.js"></script>
<script type="text/javascript" src="../js/spongestats.js.php"></script>
<script type="text/javascript" src="../js/outils.js.php?default_theme=<?php echo $sps_config['default_theme']; ?>"></script>
<script type="text/javascript" src="../js/livequery/jquery.livequery.pack.js"></script>

<title>Installation SpongeStats</title>
</head>
<body>
<div id="conteneur">
	<div id="intitule">
		<h1 style="position:absolute;text-indent:-5000px;overflow:hidden;">Installation SpongeStats 3.0</h1>
	</div>
	<div id="bas">
		<div id="installation">
		<h2>Bienvenue sur la page d'installation de SpongeStats 3.0</h2><br /><br />
		Merci de saisir ci-dessous les informations permettant la connexion &agrave; la base de donn&eacute;es de votre h&eacute;bergeur :<br /><br />
		
		<form name="formulaire_install">
			<p><label for="sps_server">Serveur MySQL</label><input type="text" name="sps_server" value="" id="sps_server" /></p>
			<p><label for="sps_user">Nom d'utilisateur</label><input type="text"  name="sps_user" value="" id="sps_user" /></p>
			<p><label for="sps_pass">Mot de passe</label><input type="password"  name="sps_pass" value="" id="sps_pass" /></p>
			<p><label for="sps_base">Nom de la base</label><input type="text"  name="sps_base" value="" id="sps_base" /></p>
			<p><label for="sps_prefix">Pr&eacute;fixe des tables</label><input type="text"  name="db_prefix" value="sps_" id="sps_prefix" /></p>
			<p><label for="sps_admin_pass">Mot de passe d'administration</label><input type="text"  name="sps_admin_pass" value="" id="sps_admin_pass" /></p>
			<p class="bouton"><input type="button" value="Tester les param&egrave;tres" id="bouton_verif_parametres" class="bouton" /></p>
		</form>
			<div id="test_params">

			</div>
		</div>
	</div>
</div>
</body>
</html>

