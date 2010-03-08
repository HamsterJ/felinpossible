<?php
session_start();
@include_once("../sps.configuration.php");
@include_once("../sps.connect.inc.php");
@include_once("../locale.php");


$res_auth = mysql_result(mysql_query("SELECT valeur FROM ".$sps_config['db_prefix']."config WHERE param='sps_admin_pass';"),0,"valeur");

if($_SESSION['sps_passwd'] == $res_auth)
	{


//Fonction d'export par torvald17 : http://www.developpez.net/forums/showpost.php?p=132888&postcount=3

	
		header("Content-Disposition: attachment; filename=\"SpongeStats-export.".date("Y-m-d").".sql\"");
		header('Content-Type: .sql');
	 	header('Content-Type: application/force-download'); 
	 	header('Content-Transfer-Encoding: Binary');
		header('Pragma: no-cache');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');


	
		echo "-- ----------------------\n";
		echo "-- Export SpongeStats 3.0 - ".date("d-M-Y")."\n";
		echo "-- ----------------------\n\n\n";
		echo "";
		echo "\n\n";
		
		$listeTables = mysql_query("show tables");
		while($table = mysql_fetch_array($listeTables))
		{
		if($table[0] == $sps_config['db_prefix']."config" || $table[0] == $sps_config['db_prefix']."statistiques" || $table[0] == $sps_config['db_prefix']."archives" || eregi($sps_config['db_prefix']."stats",$table[0]))
			{
            echo "-- -----------------------------\n";
            echo "-- creation de la table ".$table[0]."\n";
            echo "-- -----------------------------\n";
            $listeCreationsTables = mysql_query("show create table ".$table[0]);
            while($creationTable = mysql_fetch_array($listeCreationsTables))
            {
              echo $creationTable[1].";\n\n";
            }


		
           $donnees = mysql_unbuffered_query("SELECT * FROM ".$table[0]);
           echo "-- -----------------------------\n";
           echo "-- insertions dans la table ".$table[0]."\n";
           echo "-- -----------------------------\n";
		    
			while ($nuplet= mysql_fetch_row($donnees))
			{
			echo "INSERT INTO ".$table[0]." VALUES(";
                for($i=0; $i < mysql_num_fields($donnees); $i++)
                {
                  if($i != 0)
                     echo ", ";
                  if(mysql_field_type($donnees, $i) == "string" || mysql_field_type($donnees, $i) == "blob" || mysql_field_type($donnees, $i) == "date")
                     echo "'";
                  echo addslashes($nuplet[$i]);
                  if(mysql_field_type($donnees, $i) == "string" || mysql_field_type($donnees, $i) == "blob" || mysql_field_type($donnees, $i) == "date" )
                    echo "'";
				}
				echo ");\n";
			}
			echo "\n";
			}				
		}
	}
	else
	{
	echo "auth failed :";
	}
?>