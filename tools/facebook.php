<?php
function get_facebook_flow($id_album)    
{
    require_once __DIR__.'/FACEBOOK/src/Facebook/autoload.php';
    
    $fb = new Facebook\Facebook([
      'app_id' => '166998373734276', // Replace {app-id} with your app id
      'app_secret' => '2ed0bc5e469d3374194dc73922656000',
      'default_graph_version' => 'v2.7',
      ]);

    $at =  new Facebook\Authentication\AccessToken('166998373734276|2ed0bc5e469d3374194dc73922656000');

    $request = $fb->request('GET', $id_album.'/photos',array(),$at); //10153511024966492
    $response = $fb->getClient()->sendRequest($request);

    $contents =  $response->getDecodedBody();

    return $contents['data'];
}

function get_facebook_contents($id_album)    
{   
    $chats_adopt_fb=array();
    $nb_chats_adopt_fb=0;

    //On récupère l'élément "data"
    $query_data = get_facebook_flow($id_album);
    
    // On garde chaque premier mot des éléments de DATA
    for ($i=0;$i<count($query_data);$i++)
    {
            //$nom = explode (' ',utf8_decode($fql_query_data[$i]["caption"]));
            $nom = explode (' ',utf8_decode($query_data[$i]["name"]));
            $chats_adopt_fb[$nb_chats_adopt_fb++] = FPUtils::getNomSansAccentsHTML($nom[0]); 
            //get_facebook_photo('10152622755866492');
    }

    //on trie par nom de chat (by value)
    asort($chats_adopt_fb);
    return $chats_adopt_fb;
}  

    function get_facebook_photo($id_photo)    
    {
    
require_once __DIR__.'/FACEBOOK/src/Facebook/autoload.php';
    
    $fb = new Facebook\Facebook([
      'app_id' => '166998373734276', // Replace {app-id} with your app id
      'app_secret' => '2ed0bc5e469d3374194dc73922656000',
      'default_graph_version' => 'v2.7',
      ]);

    $at =  new Facebook\Authentication\AccessToken('166998373734276|2ed0bc5e469d3374194dc73922656000');

    $request = $fb->request('GET', '/'.$id_photo,array(),$at); 
    $response = $fb->getClient()->sendRequest($request);

    $contents =  $response->getDecodedBody();
    
    }

?>
