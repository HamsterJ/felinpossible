<?php
function get_facebook_contents($id_album)    
{
    $chats_adopt_fb=array();
    $nb_chats_adopt_fb=0;
 
    // lancement de la requete FQL
    $fql_query_url = 'http://graph.facebook.com/fql?q=SELECT+caption+FROM+photo+WHERE+aid+=+%22'.$id_album.'%22+ORDER+BY+caption+limit+500';

    //On récupère le résultat et le décode (json)
    $fql_query_result = file_get_contents($fql_query_url);
    $fql_query_obj = json_decode($fql_query_result, true);

    //On récupère l'élément "data"
    $fql_query_data = $fql_query_obj["data"];
    
    // On garde chaque premier mot des éléments de DATA
    for ($i=0;$i<count($fql_query_data);$i++)
    {
            $nom = explode (' ',utf8_decode($fql_query_data[$i]["caption"]));
            $chats_adopt_fb[$nb_chats_adopt_fb++] = FPUtils::getNomSansAccentsHTML($nom[0]);        
    }

    //on trie par nom de chat (by value)
    asort($chats_adopt_fb);
    return $chats_adopt_fb;
}  
?>
