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
header('Content-type: text/html; charset=utf-8');
@include_once("../sps.configuration.php");
@include_once("../sps.connect.inc.php");
@include_once("../locale.php");
include("functions.php");
echo "<h3>"._("Statistiques pour l'annee")." $annee</h3>";



$nb_jours = 12;
$i_bar = 1;
$i_day = 1;

$table_i_bar = $i_bar;


if(strlen($mois) == 1)
	{$mois_date = "0".$mois;}
else
	{$mois_date=$mois;}
$req_total = @mysql_query("SELECT SUM(visiteurs) as sum_visiteurs,SUM(pages) as sum_pages FROM ".$sps_config['db_prefix']."archives WHERE '$annee-01-01' <= date AND date <= '$annee-12-31';");
$res_total = @mysql_result($req_total,0,"sum_pages");
$res_ip_total = @mysql_result($req_total,0,"sum_visiteurs");


echo "<h2>"._("Pages vues et Visiteurs uniques")."</h2>\n";

echo "<div>";
while($i_bar < $nb_jours+1)
	{
	if(strlen($i_bar) == 1) {$i_bar_var = "0".$i_bar;} else {$i_bar_var = $i_bar;}
	$req = @mysql_query("SELECT SUM(visiteurs) as sum_visiteurs,SUM(pages) as sum_pages FROM ".$sps_config['db_prefix']."archives WHERE '$annee-$i_bar_var-01' <= date AND date <= '$annee-$i_bar_var-31';");
	if($res_total != 0)
	{
	$res = @mysql_result($req,0,"sum_pages");
	$res_ip = @mysql_result($req,0,"sum_visiteurs");

	if(!empty($res)) 	$table_h[$i_bar] = $res;
	if(!empty($res_ip))	$table_v[$i_bar] = $res_ip;

$long = $res;
$long_ip = $res_ip;

	}
	$i_bar++;
}

$day_h = count($table_h);
$day_v = count($table_v);

$table_sort_h = $table_h;
$table_sort_v = $table_v;

  @sort($table_sort_h);
  @reset($table_sort_h);
  while (list ($key, $val) = @each ($table_sort_h)) {
    $h_max = $val;
  }
  @sort($table_sort_v);
  @reset($table_sort_v);
  while (list ($key, $val) = @each ($table_sort_v)) {
    $v_max = $val;
  }

@  $avg_v = round($res_ip_total / $day_v);
@  $avg_h = round($res_total / $day_h);

@ $avg_total = round($res_total/$res_ip_total,2);

$size = 160;
@ $coef_h = $size / $h_max;
@ $coef_v = $size / $v_max;


affiche_table(_("Nombre de visiteurs total"),$res_ip_total,0,0,0);
affiche_table(_("Nombre de pages vues total"),$res_total,0,0,1);
affiche_table(_("Nombre de visiteurs maximum"),$v_max,0,0,0);
affiche_table(_("Nombre de pages vues maximum"),$h_max,0,0,1);
affiche_table(_("Moyenne du nombre de visiteurs"),$avg_v,0,0,0);
affiche_table(_("Moyenne du nombre de pages vues par visiteur"),$avg_total,0,0,1);



echo "<p>&nbsp;</p>";
echo "<div id=\"details\" style=\"position:absolute;\" class=\"survol\"></div>";

echo "<table cellpadding=\"0\" cellspacing=\"0\" style=\"margin-left:auto;margin-right:auto;\"><tr>";

while($table_i_bar < $nb_jours+1)
	{
	$long_h = round($table_h[$table_i_bar] * $coef_h);
	$long_v = round($table_v[$table_i_bar] * $coef_v);
	
	if(strlen($table_i_bar) == 1) {$mois_i = "0".$table_i_bar;} else {$mois_i = $table_i_bar;}
	
	echo "<td style=\"vertical-align:bottom;\" ";
	if($long_h != 0) echo "onmouseover=\"affiche_details('$table_i_bar','$mois_date','$annee','".$table_h[$table_i_bar]."','".$table_v[$table_i_bar]."');\" onmouseout=\"cache_details();\"";
	echo ">";
	echo "<div id=\"stats_mois-$mois_i-$annee-$table_i_bar\" class=\"barre-hits-annee lien_mois\" style=\"height:".$long_h."px;cursor:pointer;\"></div>\n";

	echo "</td><td style=\"vertical-align:bottom;\" ";
	if($long_v != 0) echo "onmouseover=\"affiche_details('$table_i_bar','$mois_date','$annee','".$table_h[$table_i_bar]."','".$table_v[$table_i_bar]."');\" onmouseout=\"cache_details();\"";
	echo ">";
	echo "<div id=\"stats_mois-$mois_i-$annee-".($table_i_bar+1)."\" class=\"barre-visiteurs-annee lien_mois\" style=\"height:".$long_v."px;cursor:pointer;\"></div>\n";

	echo "</td>";

	$table_i_bar++;
	}

	echo "</tr><tr>";
while($i_day < $nb_jours+1)
	{
	if(strlen($i_day) == 1) {$i_day = "0".$i_day;}

	echo "<td colspan=\"2\" style=\"text-align:center;\">";
	echo "<span style=\"font-size:9px;text-align:center;width:18px;\"><a href=\"#\" class=\"lien_mois\" id=\"stats_mois-$i_day-$annee\">$i_day</a></span>";
	echo "</td>\n";
	$i_day++;
	}

	echo "</tr></table></div>\n";
	echo "\n";



echo "<div style=\"margin-bottom:20px;margin-top:-20px;margin-left:220px;width:200px;\"><span style=\"width:10px;height:10px;float:left;margin-right:7px;\" class=\"barre-visiteurs-annee\"></span><span style=\"float:left;margin-right:30px;\">"._("Visiteurs")."</span><span style=\"width:10px;height:10px;float:left;margin-right:7px;\" class=\"barre-hits-annee\"></span><span style=\"float:left;\">"._("Pages vues")."</span></div>";



mysql_close($connect_db);
?>

<script type="text/javascript">
$(document).ready(
	function()
	{
	$(".lien_mois").each(function(){
			$(this).click(
					function()
					{
					$("#spongestats").html('');
					$('#ajax').ajaxStart(function(){$(this).show();}).ajaxStop(function(){$(this).hide();});
					$.get("includes/"+this.id.split('-')[0]+".php",{mois:this.id.split('-')[1],annee:this.id.split('-')[2]},function(txt){$("#spongestats").html(txt);});
					});
				}
			);
	}
);
</script>