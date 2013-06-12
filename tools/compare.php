<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href="http://felinpossible.fr/site/public/css/felinpossible.css" media="screen" rel="stylesheet" type="text/css" />
        <title>Les chats à l'adoption</title>
    </head>
    <body>
<?php
  function compute($forum_ad,$forum_soins,$fb,$site,$res_fb,$res_forum)    
    {
        $computed = array();
        
        foreach ($forum_ad as $id_post=>$nom_chat)
        {
            $n = explode(' ',$nom_chat);
            $computed[$n[0]]['forum'] = 'A'.$id_post;
        }
        
        foreach ($forum_soins as $id_post=>$nom_chat)
        {
            $n = explode(' ',$nom_chat);
            $computed[$n[0]]['forum'] = 'S'.$id_post;
        }
        
        foreach ($res_forum as $id_post=>$nom_chat)
        {
            $n = explode(' ',$nom_chat);
            $computed[$n[0]]['forum'] = 'R'.$id_post;
        }
      
        foreach ($site as $nom_chat=>$c)
        {
            $n = explode(' ',$nom_chat);
            $computed[$n[0]]['site'] = 1;
        }
        
        foreach ($fb as $id=>$nom_chat)
        {
            $n = explode(' ',$nom_chat);
            $computed[$n[0]]['fb'] = 'A';
        }
        
        foreach ($res_fb as $id=>$nom_chat)
        {
            $n = explode(' ',$nom_chat);
            $computed[$n[0]]['fb'] = 'R';
        }
        
        ksort($computed);        
        
        return $computed;
    }
    
    include 'FPUtils.php';
    include 'forum.php'; 
    include 'facebook.php'; 
    include 'sitephotos.php';

    $chats_adopt_forum=get_forum_contents("10","200");// On récupère les chats à l'adoption du forum
    $chats_soins_forum=get_forum_contents("108","40"); // On récupère les chats en cours de soin du forum
    $chats_adopt_fb=get_facebook_contents("44926746491_56236"); // On récupère les chats à l'adoption du facebook
    $chats_site=FP_site_photos::get_site_contents();
    $chats_resa_fb=get_facebook_contents("44926746491_440397"); // Chats réservés facebook
    $chats_resa_forum=get_forum_contents("54","44"); // Chats réservés forum
    
    $computed = compute($chats_adopt_forum,$chats_soins_forum,$chats_adopt_fb,$chats_site,$chats_resa_fb,$chats_resa_forum);

    echo '</br><table align="center"><th width="220">Nom chat</th><th width="180">Forum</th><th width="180">Site</th><th width="180">Facebook</th>';

    foreach ($computed as $key=>$value)
    {
        echo "<tr><td class='centre_principal'>".$key
                ."</td><td class='centre_principal' align = 'center'>".($value['forum']?"<a href=\"http://felinpossible.fr/forum/viewtopic.php?t=".substr($value["forum"],1)."\">Voir fiche".(substr($value["forum"],0,1)=='A'?'':(substr($value["forum"],0,1)=='R'?' (Réservé)':' (Soins)'))."</a>":'')
                ."</td><td class='centre_principal' align = 'center'>".($value['site']?'OK':'')
                ."</td><td class='centre_principal' align = 'center'>".($value['fb']?($value['fb']=='A'?'OK':'Réservé'):'')
            ."</td></tr>";
    }

    echo "</div></table></br></br>";
?>
    </body>
</html>