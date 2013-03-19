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
            <div id="corps">
                <table>
                    <th width="220"><div><h3>Facebook</h3></div></th>
                    <th width="220"><div><h3>Forum</h3></div></th>
                    <tr>
                        <td valign="top">
                            <div class="centre_principal">
                            <?php
                                    include 'FPUtils.php';
                                    include 'forum.php'; 
                                    include 'facebook.php'; 
                                    
                                    $chats_adopt_forum=get_forum_contents("10","200");// On récupère les chats à l'adoption du forum
                                    $chats_soins_forum=get_forum_contents("108","40"); // On récupère les chats en cours de soin du forum
                                    $chats_adopt_fb=get_facebook_contents("44926746491_56236"); // On récupère les chats à l'adoption du facebook
                                    
                                    echo "<div><b>Adopt. Facebook : ".count($chats_adopt_fb)."</b></div></br>";
                                    
                                    for ($j=0;$j<count($chats_adopt_fb);$j++)
                                    {
                                        //Pour chaque chat facebook, on regarde s'il existe sur le forum, si NON, on met le nom en gras
                                        if (in_array($chats_adopt_fb[$j],$chats_adopt_forum)||in_array($chats_adopt_fb[$j],$chats_soins_forum))
                                        {
                                            echo $chats_adopt_fb[$j]."</br>";
                                        }
                                        else
                                        {
                                            echo "<b>".$chats_adopt_fb[$j]."</b></br>";
                                        }
                                    }
                            ?>
                            </div>
                        </td>
                        <td valign="top">
                            <div class="centre_principal"> 
                            	<?php  // chats du forum
                                
                                    $nb =  count($chats_adopt_forum)+count($chats_soins_forum);                              
                                    echo "<div><b>Adopt. Forum : ".$nb."</b></div></br>";
                                    
                                    foreach ($chats_adopt_forum as $id_chat_af=>$chat_af)//chats à l'adoption
                                    {
                                        
                                        if (in_array($chat_af,$chats_adopt_fb))
                                        {
                                            echo "<a href=\"http://felinpossible.fr/forum/viewtopic.php?f=10&t=".$id_chat_af."\">".$chat_af."</a></br>";
                                        }
                                        else
                                        {
                                           echo "<b><a href=\"http://felinpossible.fr/forum/viewtopic.php?f=10&t=".$id_chat_af."\">".$chat_af."</a></b></br>"; 
                                        }
                                    }
                                    echo '---------</br>';
                                    foreach ($chats_soins_forum as $id_chat_sf=>$chat_sf)//chats en cours de soin
                                    {
                                        
                                        if (in_array($chat_sf,$chats_adopt_fb))
                                        {
                                            echo "<a href=\"http://felinpossible.fr/forum/viewtopic.php?f=108&t=".$id_chat_sf."\">".$chat_sf."</a></br>";
                                        }
                                        else
                                        {
                                           echo "<b><a href=\"http://felinpossible.fr/forum/viewtopic.php?f=108&t=".$id_chat_sf."\">".$chat_sf."</a></b></br>"; 
                                        }
                                    }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
                <table>
                    <th width="220"><div><h3>Facebook</h3></div></th>
                    <th width="220"><div><h3>Forum</h3></div></th>
                    <tr>
                        <td valign="top">
                            <div class="centre_principal">                         
                                <?php //Chats réservés facebook
                                
                                    $chats_resa_fb=get_facebook_contents("44926746491_440397"); // Chats réservés facebook
                                    $chats_resa_forum=get_forum_contents("54","44"); // Chats réservés forum
                                  
                                    echo "<div><b>Rés. Facebook : ".count($chats_resa_fb)."</b></div></br>";

                                    for ($j=0;$j<count($chats_resa_fb);$j++)// Parcours des réservés facebook, et mise en gras s'il n'existe pas dans les réservés forum
                                    {
                                        if (in_array($chats_resa_fb[$j],$chats_resa_forum))
                                        {
                                            echo $chats_resa_fb[$j]."</br>";
                                        }
                                        else
                                        {
                                            echo "<b>".$chats_resa_fb[$j]."</b></br>";
                                        }
                                   }
                                  ?>

                           </div>  

                        </td>
                        <td valign="top">
                            <div class="centre_principal">
                                <?php //Chats réservés forum
                                    echo "<div><b>Rés. Forum : ".count($chats_resa_forum)."</b></div></br>";

                                    foreach ($chats_resa_forum as $id_chat_af=>$chat_af)
                                    {
                                        if (in_array($chat_af,$chats_resa_forum))
                                        {
                                            echo "<a href=\"http://felinpossible.fr/forum/viewtopic.php?f=54&t=".$id_chat_af."\">".$chat_af."</a></br>";
                                        }
                                        else
                                        {
                                           echo "<b><a href=\"http://felinpossible.fr/forum/viewtopic.php?f=54&t=".$id_chat_af."\">".$chat_af."</a></b></br>"; 
                                        }
                                    }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table> 
          </div>
    </body>
</html>

  