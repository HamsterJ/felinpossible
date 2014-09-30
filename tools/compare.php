<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href="http://felinpossible.fr/site/public/css/felinpossible.css" media="screen" rel="stylesheet" type="text/css" />
        <title>Les chats à l'adoption</title>
    </head>
    <body>
<?php
include '../site/application/utils/ChatUtil.php';

  function compute($forum,$fb,$site,$res_fb)    
    {
        $computed = array();
        
        foreach ($forum as $nom_chat=>$c)
        {
            if ($c['forum_id'] =='10')
            {$computed[$nom_chat]['forum'] = 'A'.$c['topic_id'];}
            elseif ($c['forum_id'] =='54')
            {$computed[$nom_chat]['forum'] = 'R'.$c['topic_id'];}
            elseif ($c['forum_id'] =='108')
            {$computed[$nom_chat]['forum'] = 'S'.$c['topic_id'];}
        }
      
        foreach ($site as $nom_chat=>$c)
        {
            $n = explode(' ',$nom_chat);
            $computed[$n[0]]['site'] = $c['reserve'];
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
    include 'facebook.php'; 
    include 'forumphotos.php'; 
    include 'sitephotos.php';

    $chats_forum=get_forum_contents();// On récupère les chats à l'adoption du forum
    $chats_adopt_fb=get_facebook_contents("44926746491_56236"); // On récupère les chats à l'adoption du facebook
    $chats_resa_fb=get_facebook_contents("44926746491_1073741831"); // Chats réservés facebook
    $chats_site=get_site_contents();

    $computed = compute($chats_forum,$chats_adopt_fb,$chats_site,$chats_resa_fb);

    echo '</br><table align="center"><th width="220">Nom chat</th><th width="180">Forum</th><th width="180">Site</th><th width="180">Facebook</th>';

    foreach ($computed as $key=>$value)
    {
        echo "<tr><td class='centre_principal'>".$key
                ."</td><td class='centre_principal' align = 'center'>".(isset($value['forum'])?"<a href=\"http://felinpossible.fr/forum/viewtopic.php?t=".substr($value["forum"],1)."\">Voir fiche".(substr($value["forum"],0,1)=='A'?'':(substr($value["forum"],0,1)=='R'?' (Réservé)':' (Soins)'))."</a>":'')
                ."</td><td class='centre_principal' align = 'center'>".(isset($value['site'])?($value['site']=='0'?'Ad.':'Réservé'):'')
                ."</td><td class='centre_principal' align = 'center'>".(isset($value['fb'])?($value['fb']=='A'
                                                                                                ?(isset($value["forum"])?(substr($value["forum"],0,1)!='R'?'Ad.':'<b>Ad.</b>'):'Ad')
                                                                                                :(isset($value["forum"])?(substr($value["forum"],0,1)=='A'?'<b>Réservé</b>':'Réservé'):'Réservé')
                                  )
                                :'')
            ."</td></tr>";
    }

    echo "</div></table></br></br>";
?>
    </body>
</html>