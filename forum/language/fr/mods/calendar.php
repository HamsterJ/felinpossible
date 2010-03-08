<?php
/**
*
* calendar [FrenchStandard]
*
* @author alightner alightner@hotmail.com
* @author SpaceDoG spacedog@hypermutt.com
*
* @package phpBB Calendar
* @version CVS/SVN: $Id: $
* @copyright (c) 2008 alightner
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
$lang = array_merge($lang, array(
    '12_HOURS'								=> '12 heures',
    '24_HOURS'								=> '24 heures',
    'AUTO_PRUNE_EVENT_FREQUENCY'			=> 'Effacer Automatiquement les Evénements Passés',
    'AUTO_PRUNE_EVENT_FREQUENCY_EXPLAIN'	=> 'Quelle est la fréquence (en jour) de suppression des événements passés. Remarque : si vous sélectionnez 0, les événements passés ne seront jamais supprimés. Il faudra le faire manuellement.',
    'AUTO_PRUNE_EVENT_LIMIT'				=> 'Configurations des Suppresions Automatiques',
    'AUTO_PRUNE_EVENT_LIMIT_EXPLAIN'		=> 'Au bout de combien de jours après un événement souhaitez vous ajouter celui-ci dans la liste des éléments à effacer automatiquement ?  C\'est à dire, pendant combien de jours souhaitez vous conserver l\'événement après que la date soit passée : 0, 30 ou 45 jours ?',
    'CALENDAR_ETYPE_NAME'					=> 'Nom du Type d\'Evénement',
    'CALENDAR_ETYPE_COLOR'					=> 'Couleur du Type d\'Evénement',
    'CALENDAR_ETYPE_ICON'					=> 'Icône du Type d\'Evénement',
    'CALENDAR_SETTINGS_EXPLAIN'				=> 'Réglez les paramètres de l\'agenda ici.',
    'CHANGE_EVENTS_TO'						=> 'Changer tous les événements de ce type en',
    'COLOR'									=> 'Couleur',
    'CREATE_EVENT_TYPE'						=> 'Créer un nouveau type d\'événement',
    'DELETE'								=> 'Supprimer',
    'DELETE_ALL_EVENTS'						=> 'Supprimez tous les événements existants de ce type',
    'DELETE_ETYPE'							=> 'Supprimer le type d\'événement',
    'DELETE_ETYPE_EXPLAIN'					=> 'Êtes-vous sûr de vouloir supprimer ce type d\'événement?',
    'DELETE_LAST_EVENT_TYPE'				=> 'Attention: c\'est le dernier type d\'événement.',
    'DELETE_LAST_EVENT_TYPE_EXPLAIN'		=> 'Supprimer ce type d\'événement va entraîner la suppression de tous les événements dans l\'agenda. La création de nouveaux événements sera désactivée jusqu\'à ce que de nouveaux types d\'événements soient créés.',
    'DISPLAY_12_OR_24_HOURS'				=> 'Format des heures',
    'DISPLAY_12_OR_24_HOURS_EXPLAIN'		=> 'Souhaitez-vous afficher les heures avec le format 12 heures AM/PM ou le format 24 heures ? Ceci n\'affecte pas le format de date sélectionné par l\'utilisateur dans son profil. Cette option n\'affecte que le menu déroulant permettant la sélection des heures lors de la création / modification des événements et l\'affichage de l\'heure dans l\'agenda.',
    'DISPLAY_HIDDEN_GROUPS'					=> 'Afficher les groupes cachés',
    'DISPLAY_HIDDEN_GROUPS_EXPLAIN'			=> 'Voulez-vous que les utilisateurs puissent voir et inviter des membres des groupes cachés ? Si activé, seulement le groupe des administrateurs pourra voir et inviter des membres des groupes cachés.',
    'DISPLAY_NAME'							=> 'Nom affiché sur l\'agenda (peut être vide)',
    'DISPLAY_FIRST_WEEK'					=> 'Afficher la Semaine en Cours',
    'DISPLAY_FIRST_WEEK_EXPLAIN'			=> 'Souhaitez-vous afficher la semaine en cours sur l\'index du forum ?',
    'DISPLAY_NEXT_EVENTS'					=> 'Afficher les Prochains Evénements',
    'DISPLAY_NEXT_EVENTS_EXPLAIN'			=> 'Spécifiez le nombre d\'événements courants que vous souhaitez afficher sur la page d\'index. Note : Ce nombre sera ignoré si vous avez coché la case « Afficher la semaine en cour ».',
    'DISPLAY_TRUNCATED_SUBJECT'				=> 'Tronquer les Sujets',
    'DISPLAY_TRUNCATED_SUBJECT_EXPLAIN'		=> 'De longs sujets peuvent prendre beaucoup de place sur l\'agenda. Combien de caractères souhaitez vous afficher dans le sujet sur l\'agenda ? (Entrez la valeur 0 si vous ne voulez pas tronquer les sujets)',
    'EDIT'									=> 'Editer',
    'EDIT_ETYPE'							=> 'Modifier le type d\'événement',
    'EDIT_ETYPE_EXPLAIN'					=> 'Spécifiez les paramètres d\'affichage de ce type d\'événement.',
    'FIRST_DAY'								=> 'Premier Jour',
    'FIRST_DAY_EXPLAIN'						=> 'Quel jour doit être affiché comme le premier jour de la semaine ?',
    'FRIDAY'								=> 'Vendredi',
    'ICON_URL'								=> 'URL de l\'icône',
    'MANAGE_ETYPES'							=> 'Gérer les types d\'événements',
    'MANAGE_ETYPES_EXPLAIN'					=> 'Les types d\'événements sont utilisées pour aider à organiser l\'agenda. Vous pouvez ajouter, modifier, supprimer ou réorganiser les types d\'événements ici.',
    'MONDAY'								=> 'Lundi',
    'FULL_NAME'								=> 'Nom complet',
    'NO_EVENT_TYPE_ERROR'					=> 'Impossible de trouver le type d\'événement spécifié.',
    'SATURDAY'								=> 'Samedi',
    'SUNDAY'								=> 'Dimanche',
    'THURSDAY'								=> 'Jeudi',
    'TUESDAY'								=> 'Mardi',
    'USER_CANNOT_MANAGE_CALENDAR'			=> 'Vous n\'avez pas l\'autorisation de gérer les paramètres de l\'agenda ou des types d\'événements.',
    'WEDNESDAY'								=> 'Mercredi',

));

?>
