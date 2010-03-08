<form action="" method="get" name="formtheme" id="formtheme">
<select name="selecteur" id="selecteur" >
<?php


 
$dir = "themes/";
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
    $liste_rep[] = $filename;
}

sort($liste_rep);

 $i = 2;
 $num = count($liste_rep);
 while($i < $num)
 {
 if($liste_rep[$i] == $sps_config['default_theme']) { $select = "selected='selected'"; } else { $select = "";}
	echo "	<option value=\"$liste_rep[$i]\" $select>$liste_rep[$i]</option>\n";
 $i++;
 } 
?>
</select>
</form>