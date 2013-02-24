<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href="http://felinpossible.fr/site/public/css/felinpossible.css" media="screen" rel="stylesheet" type="text/css" />
        <title>Mise à jour des ages des chats à l'adoption</title>
    </head>
    <body></br>
        <div id="main-content">
            </br><h5>Mise à jour des ages effectuées...</h5></br>
            
            <table cellpadding="5" cellspacing="2" bgcolor="#EEEEEE">
            <tr bgcolor="#CCCCCC" align="center"><th>Nom</th><th>Date naissance</th><th>Titre initial</th><th>Nouveau titre</th></tr>
        
            <?php
                try{
                    //Connexion à la base depuis les infos du fichier application.ini
                    $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"); 
                    $appliParams = parse_ini_file("../site/application/configs/application.ini"); 
                    $pdo = new PDO('mysql:host=localhost;dbname='.$appliParams['resources.db.params.dbname'], $appliParams['resources.db.params.username'], $appliParams['resources.db.params.password'], $arrExtraParam); 
                    
                    //Selection d'un chat sur le site et de son topic associé sur le forum
                    $sql = "SELECT  ch.nom  nom
                        , ch.date           dateNaissance
                        , t.topic_title     titre
                        , t.topic_id        topic_id
                        , co.name           couleur
                        , (DATEDIFF(CURRENT_DATE(),ch.date)/365)  annees
                        FROM 	phpbb_topics t, fp_cat_fiche ch, fp_cat_color co
                        WHERE 	t.topic_id  = ch.topic_id
                        AND	ch.idCouleur = co.id
                        AND	ch.adopte   = 0 
                        AND 	ch.disparu  = 0
                        ORDER BY ch.nom"; 

                    foreach  ($pdo->query($sql) as $data) 
                    {   
                        //on split sur les tirets (en gérant au passage le bug des tirets word et autres ...)
                        $arr=preg_split('#[\x{2D}\x{AD}\x{E2}]+#',preg_replace('#[\x{80}\x{93}]#','',$data['titre'])); 
                                                
                        $annees=floor($data['annees']);
                        $fraction=$data['annees']-floor($data['annees']);
                        
                        if($fraction>0.95 && $annees>0)//on arrondi si c'est N + 11mois et demi (pas pour les juniors)
                        {
                            $annees=$annees+1;
                            $fraction=0;
                        }
                        
                        // Si le titre semble mal formé ou qu'on n'a pas de date de naissance sur le site, on ne fait rien
                        // titre formé de 3 parties pour un chat classique, et 4 pour les FIV+/FELV+
                        if ((count($arr)===3 || count($arr) ===4) && !($data['dateNaissance']==null)) 
                        {                           
                            $age=(($annees==0)&&($fraction<=0.5))
                                    ?'chaton'
                                    :((($annees==0)&&($fraction>0.5))
                                        ?'junior'
                                        :(
                                            $annees.($annees=='1'?' an':' ans')
                                            .(
                                                ($fraction<0.4) //apres 5 mois on met 'et demi'
                                                     ?''
                                                     :' et demi'
                                              )
                                         )
                                      );
                        
                            //nouveau titre = ancien en remplacant la partie3
                            $nv_titre = count($arr)===3
                                    ?($arr[0].'-'.$arr[1].'- '.$age)
                                    :($arr[0].'-'.$arr[1].'- '.$age.' -'.$arr[3]);                  
                        
                            // on update et affiche si mis à jour
                            if($nv_titre!==$data['titre'])
                           { 
                                $q = $pdo->prepare("UPDATE phpbb_topics SET topic_title = ? WHERE topic_id = ?");  
                                //$q->execute(array($nv_titre,$data['topic_id']));  

                                echo '<tr><td><b><a href="http://felinpossible.fr/forum/viewtopic.php?t='.$data['topic_id'].'">'.$data['nom'].'</a>'
                                    .'</td><td>'.$data['dateNaissance']
                                    .'</b></td><td>'.$data['titre']
                                    .'</td><td>'.$nv_titre
                                    .'</td></tr>';
                            }
                        }
                        else // Mise a jour pas possible car titre mal formé ou age non present
                        {
                           echo '<tr><td><b><a href="http://felinpossible.fr/forum/viewtopic.php?t='.$data['topic_id'].'">'.$data['nom'].'</a>'
                                    .'</td><td>'.$data['dateNaissance']
                                    .'</b></td><td>'.$data['titre']
                                    .'</td><td>'.'<b><font color="red">MISE A JOUR AUTO IMPOSSIBLE</font></b>'
                                    .'</td></tr>'; 
                        }
                    } 
                    }
                    catch (PDOException $e) {
                        print "Error!: " . $e->getMessage() . "<br/>";
                        die();
                    }
                    
                // on ferme la connexion à mysql 
                $pdo = null; 
            ?>
            </table>
        </div>
        </br></br><a href="./index.php">Retour</a>
    </body>
</html>