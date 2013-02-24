<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href="http://felinpossible.fr/site/public/css/felinpossible.css" media="screen" rel="stylesheet" type="text/css" />
        <title>Les chats à l'adoption</title>
    </head>
    <body>
	<div id="main-content" class="span12">
            <div><h1>Chats à l'adoption</h1></div></br>
            <!--<div id="corps"><div class="centre_principal">-->
            <table class="thumbnail">
                <th width="150"><div><h3>Nom</h3></div></th>
                <th width="150"><div><h3>Photo fbk</h3></div></th>
                <th width="150"><div><h3>Photo Forum</h3></div></th>
                <th width="150"><div><h3>Photo Site</h3></div></th>
            
                <?php

                    include 'FPUtils.php';
                    include 'fbphotos.php'; 
                    include 'sitephotos.php';
                     include 'forumphotos.php';

                    $photos_fb=get_facebook_photos("44926746491_56236");
                    $photos_site=get_site_contents();
                    $photos_forum=get_forum_contents();

                    foreach ($photos_fb as $nom_chat_fb=>$photo_fb)
                    {
                        echo "<tr bgcolor=\"EEEEEE\" height=\"150\"><td align='middle'>".$nom_chat_fb."</td>";
                        //facebook
                        echo "<td align='middle' border-width='1'><a href='".str_replace("_s.jpg", "_n.jpg", $photo_fb[0])."'><img class='img-chat' src='".$photo_fb[0]."' alt='".$nom_chat_fb."' height=\"100\"/></a></br><font size='1'>".$photo_fb[1]."</font></td>";
                        
                        //forum
                        if ((array_key_exists($nom_chat_fb,$photos_forum)))
                        {
                            echo "<td border-width='1' align='middle'><img class='img-chat' src='"
                            .$photos_forum[$nom_chat_fb][0]
                            ."' alt='".$nom_chat_fb."' height=\"100\"/></br><font size='1'>".$photos_forum[$nom_chat_fb][1]."</font></td>";
                        } 
                        else
                        {
                            echo "<td></td>";
                        }
                        
                        //site
                        if (array_key_exists($nom_chat_fb,$photos_site))
                        {
                            echo "<td align='middle'><img class='img-chat' src='"
                            .$photos_site[$nom_chat_fb]
                            ."' alt='".$nom_chat_fb."' height=\"100\"/></td>";
                        } 
                        else if (array_key_exists($nom_chat_fb.' 2',$photos_site))
                        {
                            echo "<td align='middle'><img class='img-chat' src='"
                            .$photos_site[$nom_chat_fb.' 2']
                            ."' alt='".$nom_chat_fb."' height=\"100\"/></td>";
                        } 
                        else
                        {
                            echo "<td></td>";
                        }
                        
                        
                        
                        
                        echo "</tr>";
                    }            
            ?>             
            </table>                      
       </div><!--</div>-->
    </body>
</html>
