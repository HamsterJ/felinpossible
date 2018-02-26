<?php
    try{
            //Connexion à la base depuis les infos du fichier application.ini
            $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"); 
            $appliParams = parse_ini_file("../site/application/configs/application.ini"); 
            $pdo = new PDO('mysql:host=localhost;dbname='.$appliParams['resources.db.params.dbname'], $appliParams['resources.db.params.username'], $appliParams['resources.db.params.password'], $arrExtraParam); 

            $q = $pdo->prepare("UPDATE fp_cat_fiche SET okChats = ?,okChiens = ?,okApparts = ?,okEnfants = ? WHERE id = ?");  
            $q->execute(array($_POST['Sokchats'],$_POST['Sokchiens'],$_POST['Sokapparts'],$_POST['Sokenfants'],$_POST["idc"]));  
            //echo $q;

         }
    catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
         }

        // on ferme la connexion à mysql 
        $pdo = null; 
        
        header("Location: ./Preferences.php");
        exit();
?>