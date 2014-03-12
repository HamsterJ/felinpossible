<?php

class FPUtils {   
    
    public static function getNomSansAccentsHTML($st) // Retourne le nom du chat en majuscule sans accent (enfin quand ca marche correctement!)
    {
        $st = htmlentities(strtoupper($st));
        
        $carspe = array( "&Agrave;","&Aacute;","&Acirc;","&Atilde;","&Auml;"
                        ,"&Egrave;","&Eacute;","&Ecirc;","&Euml;"
                        ,"&Igrave;","&Iacute;","&Icirc;","&Iuml;"
                        ,"&Ograve;","&Oacute;","&Ocirc;","&Otilde;","&Ouml;"
                        ,"&Ugrave;","&Uacute;","&Ucirc;"
                        ,"&Ntilde;","&Ccedil;","&nbsp;");
        
        $replace = array("A","A","A","A","A","E","E","E","E","I","I","I","I","O","O","O","O","O","U","U","U","N","C","");
        $st_ss_acc = str_replace($carspe, $replace, $st);
        
        return $st_ss_acc;
    }
         
    
    public static function getNomSansAccentsUTF8($st)  // ne marche pas bien :(
    {   
        $st_ss_acc = strtr(strtoupper($st),'ÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','AAAAACEEEEIIIINOOOOOUUUUY'); 
        return $st_ss_acc;
    }

}

?>
