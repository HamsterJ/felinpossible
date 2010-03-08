<?php
$language= $sps_config['language'];

/*$lang_association = array('fr' => 'fr_FR', 'en' => 'en_EN', 'en-gb' => 'en_EN', 'en-us' => 'en_US', 'nl' => 'nl_NL', 'es' => 'es_ES');
$language = $lang_association[$language];*/

//putenv("LANG=$language");
setlocale(LC_ALL, $language,$language.".utf8",$language."@euro",$language.".utf8@euro");

$domain = 'messages';
$language_folder=str_replace(basename($_SERVER['SCRIPT_FILENAME']),"",$_SERVER['SCRIPT_FILENAME']);
//Si on se trouve dans un sous-dossier, on repart de la racine du dossier d'installation de Spongestats
$language_folder= (file_exists($language_folder."../sps.configuration.php")) ? $language_folder."../" : $language_folder;
bindtextdomain("$domain", $language_folder."locale");
bind_textdomain_codeset("messages", "UTF-8");
textdomain("$domain");

?>