<?php
session_start();
@include_once("../sps.configuration.php");
@include_once("../sps.connect.inc.php");
@include_once("../locale.php");


$res_auth = mysql_result(mysql_query("SELECT valeur FROM ".$sps_config['db_prefix']."config WHERE param='sps_admin_pass';"),0,"valeur");

if($_SESSION['sps_passwd'] == $res_auth)
	{
	foreach($_POST as $param => $valeur )
		{
		$valeur = trim(str_replace(chr(10),",",str_replace(chr(13),"",$valeur)));
		if($param == "excluded_ip" || $param == "excluded_host" || $param == "excluded_user_agent" || $param == "excluded_referers" || $param == "excluded_domaines_icones" || $param =="navigateurs" || $param == "agregateurs" || $param == "plateformes") {$valeur = serialize(explode(",",$valeur));}
		$mysql_update="UPDATE ".$sps_config['db_prefix']."config SET valeur='".$valeur."' WHERE param='$param';";
		mysql_query($mysql_update);
//		setcookie ("style", "/", time() - 3600);
		header("Location:../");
		}
	}
	else
	{
	echo "auth failed :";
	}
?>