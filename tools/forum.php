<?php
function get_forum_contents($id_topic,$nb_max)    
{
    $chats_adopt_forum=array();
    $page_forum_adopt=0;
    $save_last_cat="";
    $have_a_break=0;
    
    for ($page_forum_adopt=0;$page_forum_adopt <$nb_max;$page_forum_adopt=$page_forum_adopt+45) // on va volontairement aller trop loin, ca ramenera des doublons, mais on les gèrera plus loin
    {   
        //url forum des "chats_forum_adopt à l'adoption" // page N
        $url_forum_adopt_html = "http://felinpossible.fr/forum/viewforum.php?f=".$id_topic."&start=".$page_forum_adopt;
        //$url_forum_adopt_html = "C:\\Users\\Max\\Desktop\\coding\\template_forum.txt";// BOUCHON

        //On récupère le résultat
        $html_query_result = utf8_decode(file_get_contents($url_forum_adopt_html));
        
        // On recherche les titres de topics
        $int= preg_match_all("/t=([0-9]*).*class=\"topictitle\">([^\s-]*).*</", $html_query_result,$forum_contents,PREG_PATTERN_ORDER);
        
        for ($i=0;$i<count($forum_contents[0]);$i++)
        {
            if (    ($forum_contents[1][$i]!=("7"))       //post-it
                    && ($forum_contents[1][$i]!=("193"))  //post-it
                    && ($forum_contents[1][$i]!=("246"))  //post-it
                    && ($forum_contents[1][$i]!=("1414")) //post-it
                    && ($forum_contents[1][$i]!=("3902")) //post-it
                    && ($forum_contents[1][$i]!=("6524")) //post-it
                    && ($have_a_break != 1)               //détection de doublon : pas la peine d'aller plus loin)
               )
            {   
                if ($forum_contents[2][$i] != $save_last_cat)// Si on tombe 2 fois sur le même chat d'affilée c'est le signe qu'on a été une page trop loin, on va flagger et s'arrêter au prochain tour
                {
                    //on stocke le nom du chat à l'indice correspondant au numero de post
                    $chats_adopt_forum[$forum_contents[1][$i]]=  FPUtils::getNomSansAccentsHTML($forum_contents[2][$i]);
                    $save_last_cat = $forum_contents[2][$i];
                }
                else 
                {
                    $have_a_break=1;
                }
            }        
        }
    }
    
    //on trie par nom de chat
    asort($chats_adopt_forum);
    return $chats_adopt_forum;
}
?>
