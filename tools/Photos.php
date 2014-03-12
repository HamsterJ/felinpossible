<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href="http://felinpossible.fr/site/public/css/felinpossible.css" media="screen" rel="stylesheet" type="text/css" />
        <title>Les chats à l'adoption</title>
    </head>
    <body>
	<div class="span12">
            <div><h1>Chats à l'adoption</h1></div></br>
            <table cellpadding="20">
                <?php
                
                    include 'FPUtils.php';
                    include 'ChatUtil.php';
                    include 'fbphotos.php'; 
                    include 'sitephotos.php';
                    include 'forumphotos.php';

                    $photos_fb=FP_fb_photos::get_facebook_photos("44926746491_56236");
                    $photos_site=get_site_contents();
                    $photos_forum=get_forum_contents();
               
                    foreach ($photos_forum as $nom_chat_forum=>$photo_forum)
                    {
                        echo'<th width="150"><div><h6>Nom</h6></div></th>
                        <th width="150"><div><h6>Photo Forum</h6></div></th>
                        <th width="150"><div><h6>Photo Site</h6></div></th>
                        <th width="150"><div><h6></h6>Photo fbk</div></th>';
                        
                        echo "<tr bgcolor=\"EEEEEE\" height=\"250\" valign=\"top\"><td align='middle'>".$nom_chat_forum."</td>";
                        
                        //forum
                         echo "<td border='1' align='middle'><a href=\"http://felinpossible.fr/forum/viewtopic.php?f=10&t=".$photos_forum[$nom_chat_forum]['topic_id']."\"><img class='img-chat' src='"
                            .$photos_forum[$nom_chat_forum]['pic']
                            ."' alt=".$nom_chat_forum." height=\"100\"/></a>"
                                    ."</br><p style='font-size:10px'><b>Edit:".$photos_forum[$nom_chat_forum]['edit_time']."</b></br>"
                                            .$photos_forum[$nom_chat_forum]['identif']."</br>"
                                            .$photos_forum[$nom_chat_forum]['tests']."</br>"
                                            .$photos_forum[$nom_chat_forum]['vaccin']."</br>"
                                            .$photos_forum[$nom_chat_forum]['dateNaissance']."</br></br>"
                                            .$photos_forum[$nom_chat_forum]['caractere']."</br></br>"
                                    ."</p></td>";

                        //site
                        if (array_key_exists($nom_chat_forum,$photos_site))
                        {
                            echo print_site_contents($photos_site[$nom_chat_forum],$nom_chat_forum,$photos_forum[$nom_chat_forum]);
                        } 
                        else if (array_key_exists($nom_chat_forum.' 2',$photos_site))
                        {
                            echo print_site_contents($photos_site[$nom_chat_forum.' 2'],$nom_chat_forum.' 2',$photos_forum[$nom_chat_forum]);
                        } 
                        else
                        {
                            echo "<td></td>";
                        }
  
                        //facebook
                        if ((array_key_exists($nom_chat_forum,$photos_fb)))
                        {
                          echo FP_fb_photos::print_fb_contents($photos_fb[$nom_chat_forum],$nom_chat_forum);
                        } 
                        else
                        {
                            echo "<td></td>";
                        }
                        echo "</tr>";
                    }            
            ?>             
            </table>                      
       </div>
    </body>
</html>
