<?php

class FP_fb_photos {

    static function get_facebook_photos($id_album)    
    {
        $chats_photos_fb=array();

        // lancement de la requete FQL
        $fql_query_url = 'http://graph.facebook.com/fql?q=SELECT+caption,src,created+FROM+photo+WHERE+aid+=+%22'.$id_album.'%22+ORDER+BY+caption+limit+500';

        //On récupère le résultat et le décode (json)
        $fql_query_result = file_get_contents($fql_query_url);

        $fql_query_obj = json_decode($fql_query_result, true);

        //On récupère l'élément "data"
        $fql_query_data = $fql_query_obj["data"];

        // On garde chaque premier mot des éléments de DATA
        for ($i=0;$i<count($fql_query_data);$i++)
        {
                $nom = explode (' ',utf8_decode($fql_query_data[$i]["caption"]));           
                $chats_photos_fb[FPUtils::getNomSansAccentsHTML($nom[0])]['src'] = $fql_query_data[$i]["src"];
                $chats_photos_fb[FPUtils::getNomSansAccentsHTML($nom[0])]['dateM'] = date('d/m/Y', $fql_query_data[$i]["created"]);
                $chats_photos_fb[FPUtils::getNomSansAccentsHTML($nom[0])]['desc'] = substr($fql_query_data[$i]["caption"],0,120);
        }

        //on trie par nom de chat
        ksort($chats_photos_fb);

        return $chats_photos_fb;
    }  


    function print_fb_contents($photo,$nom_chat)    
    {
        return "<td align='middle' border-width='1'>
                <a href='".str_replace("_s.jpg", "_n.jpg", $photo['src'])
                ."'><img class='img-chat' src='".$photo['src']
                ."' alt='".$nom_chat
                ."' height=\"100\"/></a></br><font size='1'><b>Edit:"
                .$photo['dateM']."</b></br></br></br></br></br>".$photo['desc']."</font></td>";
    }
    

    
}
?>
