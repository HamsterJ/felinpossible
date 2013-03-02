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
                        echo'<th width="150"><div><h6>Nom</h6></div></th>
                        <th width="150"><div><h6>Photo fbk</h6></div></th>
                        <th width="150"><div><h6>Photo Forum</h6></div></th>
                        <th width="150"><div><h6>Photo Site</h6></div></th>';
                        
                        echo "<tr bgcolor=\"EEEEEE\" height=\"250\" valign=\"top\"><td align='middle'>".$nom_chat_fb."</td>";
                        //facebook
                        echo "<td align='middle' border-width='1'><a href='".str_replace("_s.jpg", "_n.jpg", $photo_fb[0])."'><img class='img-chat' src='".$photo_fb[0]."' alt='".$nom_chat_fb."' height=\"100\"/></a></br><b><font size='1'>Edit:".$photo_fb[1]."</font></b></td>";
                        
                        //forum
                        if ((array_key_exists($nom_chat_fb,$photos_forum)))
                        {
                            echo "<td border-width='1' align='middle'><a href=\"http://felinpossible.fr/forum/viewtopic.php?f=10&t=".$photos_forum[$nom_chat_fb]['topic_id']."\"><img class='img-chat' src='"
                            .$photos_forum[$nom_chat_fb]['pic']
                            ."' alt=".$nom_chat_fb." height=\"100\"/></a>"
                                    ."</br><p style='font-size:10px'><b>Edit:".$photos_forum[$nom_chat_fb]['edit_time']."</b></br>"
                                            .$photos_forum[$nom_chat_fb]['identif']."</br>"
                                            .$photos_forum[$nom_chat_fb]['tests']."</br>"
                                            .$photos_forum[$nom_chat_fb]['vaccin']."</br>"
                                            .$photos_forum[$nom_chat_fb]['dateNaissance']."</br>"                    
                                    ."</p></td>";
                        } 
                        else
                        {
                            echo "<td></td>";
                        }
                        
                        //site
                        if (array_key_exists($nom_chat_fb,$photos_site))
                        {
                            echo "<td align='middle'><img class='img-chat' src='"
                            .$photos_site[$nom_chat_fb]['pic']
                            ."' alt='".$nom_chat_fb."' height=\"100\"/>
                                    </br><p style='font-size:10px'></br>"
                                    .$photos_site[$nom_chat_fb]['identif']."</br>"
                                            .$photos_site[$nom_chat_fb]['tests']."</br>"
                                            .$photos_site[$nom_chat_fb]['vaccin']."</br>"
                                            .$photos_site[$nom_chat_fb]['dateNaissance']."</br>" 
                                    ."</p></td>";
                        } 
                        else if (array_key_exists($nom_chat_fb.' 2',$photos_site))
                        {
                            echo "<td align='middle'><img class='img-chat' src='"
                            .$photos_site[$nom_chat_fb.' 2']['pic']
                            ."' alt='".$nom_chat_fb."' height=\"100\"/>
                            </br><p style='font-size:10px'></br>"
                                    .$photos_site[$nom_chat_fb.' 2']['identif']."</br>"
                                            .$photos_site[$nom_chat_fb.' 2']['tests']."</br>"
                                            .$photos_site[$nom_chat_fb.' 2']['vaccin']."</br>"
                                            .$photos_site[$nom_chat_fb.' 2']['dateNaissance']."</br>" 
                                    ."</p></td>";
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
