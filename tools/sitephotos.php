<?php

class FP_site_photos {
    
    function get_site_contents()    
    {
        $chats_site=array();

        try{
            $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"); 
            $appliParams = parse_ini_file("../site/application/configs/application.ini"); 
            $pdo = new PDO('mysql:host=localhost;dbname='.$appliParams['resources.db.params.dbname'], $appliParams['resources.db.params.username'], $appliParams['resources.db.params.password'], $arrExtraParam); 


            // on crée la requête SQL 
            $sql = "SELECT id,nom,miniature,DATE_FORMAT(date,'%d/%m/%Y') date,tests,vaccins,tatouage,caractere,topic_id,okChats,okChiens,okApparts,okEnfants FROM fp_cat_fiche WHERE adopte=0 and reserve=0 and disparu=0 and to_check=0"; 

            // on fait une boucle qui va faire un tour pour chaque enregistrement 
            foreach  ($pdo->query($sql) as $data) 
            { 
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['pic']=  $data["miniature"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['dateNaissance']=  $data["date"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['tests']=  $data["tests"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['vaccin']=  $data["vaccins"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['identif']=  $data["tatouage"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['caractere']=  $data["caractere"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['topic_id']=  $data["topic_id"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['okChats']=  $data["okChats"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['okChiens']=  $data["okChiens"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['okApparts']=  $data["okApparts"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['okEnfants']=  $data["okEnfants"];
                 $chats_site[FPUtils::getNomSansAccentsHTML($data["nom"])]['id']=  $data["id"];
            } 
            }
            catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }

        //on trie par nom de chat
        ksort($chats_site);

        return $chats_site;
    }

    function print_site_contents($photo,$nom_chat,$chat_forum)    
    {
         $chat = "<td align='middle'><img class='img-chat' src='";
         $chat = $chat.$photo['pic']."' alt='".$nom_chat."' height=\"100\"/></br><p style='font-size:10px'></br>";
         
         if ($photo['identif'] === $chat_forum['identif'])
            {$chat = $chat.$photo['identif']."</br>";}
         else
            {$chat = $chat."<font color='red'>".$photo['identif']."</font></br>";} 
            
         if (strtoupper(str_replace(CHR(32),'',strtr($photo['tests'],'//',' '))) === strtoupper(str_replace(CHR(32),'',strtr($chat_forum['tests'],'//',' '))))   
            $chat = $chat.$photo['tests']."</br>";
         else
            {$chat = $chat."<font color='red'>".$photo['tests']."</font></br>";} 
         
         if ($photo['vaccin'] === $chat_forum['vaccin'])   
            $chat = $chat.$photo['vaccin']."</br>";
         else
            {$chat = $chat."<font color='red'>".$photo['vaccin']."</font></br>";} 
         
        if ($photo['dateNaissance'] === $chat_forum['dateNaissance'])   
            $chat = $chat.$photo['dateNaissance']."</br>";
         else
            {$chat = $chat."<font color='orange'>".$photo['dateNaissance']."</font></br></br>";} 

         $chat = $chat.substr($photo['caractere'],0,120)."</br>";
         $chat = $chat."</p></td>";
                
        return $chat;
    }
}
?>
