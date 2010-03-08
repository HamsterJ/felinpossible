<?php
@include_once("../sps.configuration.php");
@include_once("../sps.connect.inc.php");
include("functions.php");
if (isset($_GET['top_id']))
{
	$req = @mysql_query("SELECT id,host,nb_pages,heure,date FROM ".$sps_config['db_prefix']."statistiques WHERE id>".$_GET['top_id']." ORDER BY id DESC");
	//$res_all_request = @mysql_num_rows($req);
	//echo "SELECT id FROM ".$sps_config['db_prefix']."statistiques WHERE id>".$_GET['top_id']." ORDER BY id DESC LIMIT 0,".$sps_config['display_visiteurs'].";";
	//print_r(mysql_fetch_assoc($req));
	if (mysql_num_rows($req)>0)
	{
	$res_all_request = @mysql_num_rows($req);
	echo "<span class=\"refresh\" style=\"display:none;\">";
	while($i != $res_all_request)
		{
		$id = mysql_result($req,$i,"id");
		if(mysql_result($req,$i,"date") == date("Y-m-d"))
			{
			$timeday = mysql_result($req,$i,"heure")."h";
			}
		else
			{
			$timeday = mysql_result($req,$i,"date");
			}
		$gauche = "<a href=\"javascript:void(0);\" id=\"plus_details-$id-last\" class=\"plus_details\" ><img src=\"themes/".$sps_config['default_theme']."/plus-details.png\" alt=\""._("Plus de details")."\"/></a>"."<span style=\"font-weight:bold;padding-right:20px;\">$timeday</span>".mysql_result($req,$i,"host");
		
		$droite = mysql_result($req,$i,"nb_pages");


		affiche_table($gauche,$droite,0,0,$id);
		echo "<span id=\"details-$id\" style=\"display:none;\" class=\"details\"></span>";
		
		$i++;
		}
		echo "</div>\n";
			//Fermeture refresh-$_GET['top_id']
	echo "</span>";
	}
}
mysql_close($connect_db);
?>