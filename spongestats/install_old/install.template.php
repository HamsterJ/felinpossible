<?php

//Parametres par defaut
// Liste des adresses IP à filtrer
// List of excluded IP addresses
$sps_config['excluded_ip'] = array("64.68.82.","192.168.","127.0.0.1","82.99.30");

// Liste des nom d'hôtes à filtrer
// List of excluded hostnames
$sps_config['excluded_host'] = array("inktomi","search","teoma","robot","crawl","exabot","speedy");

// Liste des clients web à filtrer
// List of excluded user agents
$sps_config['excluded_user_agent'] = array("bot","pompos","grub");

// Liste des referers à filtrer
// List of excluded referer
$sps_config['excluded_referers'] = array("whois.sc","ya.ru","poker","casino","vegas","gambl");

############## Apparence et Affichage ##############
############## Layout & Display ##############

$sps_config['site_folder'] = "/";
$sps_config['sponge_folder'] = "spongestats";

// Langue de l'interface
// Interface language
// fr_FR / en_US
$sps_config['language'] = "fr_FR";

// Affiche l'aide
// Display help
$sps_config['aide'] = 1;

// Thème par défaut (nom du répertoire)
// Default template (folder name)
$sps_config['default_theme'] = "citron-vert";

// Derniers visiteurs à afficher
// Last visitors to display
$sps_config['display_visiteurs'] = 20;

// Pages les plus vues à afficher
// Most seen pages to display
$sps_config['display_pages_vues'] = 40;

// Pages d'entrée sur le site  à afficher
// Entrance pages to display
$sps_config['display_pages_entree'] = 40;

// Pages référentes à afficher
// Backlinks (by page) to display
$sps_config['display_pages_referers'] = 40;

// Sites référents à afficher
// Backlinks (by site) to display
$sps_config['display_domains_referers'] = 40;

// Nombre de mots clé à afficher
// Displaied keywords
$sps_config['display_mots_cles'] = 50;

// Nombre d'occurences à partir duquel les mots clés seront affichés
// Minimal instances number to display a specific keyword
$sps_config['display_mots_cles_occurences'] = 1;

// IP
$sps_config['display_ip'] = 40;

// Hôtes à afficher
// Hosts to display
$sps_config['display_hotes'] = 40;

// Evolution des mots clés
// Keyword evolutions
$sps_config['display_historique'] = 10;

// Afficher les favicon pour les referers et les hotes
// Display favicon for referers and hosts
$sps_config['display_icones'] = 1;
$sps_config['excluded_domaines_icones'] = array("completel.net");


// Tableau des user agents

$sps_config['navigateurs'] = array("MSIE","Epiphany","Firefox","Konqueror","Opera","Safari","Netscape","Wget","SeaMonkey","Lynx","Links","Minimo","Flock","Iceweasel");
$sps_config['agregateurs'] = array("FeedFetcher-Google","Sharpreader","RSSreader","Thunderbird","KMail","Feedreader","Liferea","RSSOwl","Google Desktop","RssBandit","Avant Browser","Bloglines","NewsGator","Straw","Netvibes","Gregarius","Live.com","Akregator","FeedBurner","Feedshow","Vienna","NetNewsWire","Sage");
$sps_config['plateformes'] = array("Windows","Macintosh","Linux","BSD","Sun","PlayStation","Nokia","SmartPhone","OS/2","BlackBerry","Java","PalmOS","SonyEricsson");

?>