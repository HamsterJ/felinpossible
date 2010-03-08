<?php
/**
*
* calendarpost [Standard french]
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
if (empty($lang) || !is_array($lang))
{
	$lang = array();
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
	'ALL_DAY'					=> 'Toute la Journée',
	'ALL_DAY_STARTING'			=> 'Toute la Journée de Départ',
	'AM'						=> 'AM',
	'CALENDAR_POST_EVENT'		=> 'Créer un Evénement',
	'CALENDAR_EDIT_EVENT'		=> 'Modifier l\'Evénement',
	'CALENDAR_TITLE'			=> 'Agenda',
	'DELETE_EVENT'				=> 'Supprimer l\'Evénement',
	'EMPTY_EVENT_MESSAGE'		=> 'Vous devez entrer un message lors de la saisie de l\'événements.',
	'EMPTY_EVENT_SUBJECT'		=> 'Vous devez entrer un sujet lors de la saisie de l\'événements.',
	'END_DATE'					=> 'Date de Fin',
	'END_TIME'					=> 'Heure de Fin',
	'EVENT_ACCESS_LEVEL'			=> 'Qui peut voir cet événement ?',
	'EVENT_ACCESS_LEVEL_GROUP'		=> 'Groupe',
	'EVENT_ACCESS_LEVEL_PERSONAL'	=> 'Personnel',
	'EVENT_ACCESS_LEVEL_PUBLIC'		=> 'Public',
	'EVENT_CREATED_BY'			=> 'Evénement posté par',
	'EVENT_DELETED'				=> 'Cet événement a été supprimé avec succès.',
	'EVENT_EDITED'				=> 'Cet événement a été édité avec succès.',
	'EVENT_GROUP'				=> 'Quel groupe peut voir cet événement?',
	'EVENT_STORED'				=> 'Cet événement a été créé avec succès.',
	'EVENT_TYPE'				=> 'Type d\'Evénement',
	'EVERYONE'					=> 'Tout le monde',
	'FROM_TIME'					=> 'Du',
	'INVITE_INFO'				=> 'Invité',
	'LOGIN_EXPLAIN_POST_EVENT'	=> 'Vous devez vous identifier pour ajouter/modifier/supprimer un événement.',
	'MESSAGE_BODY_EXPLAIN'		=> 'Entrez votre message ici, il ne peut contenir plus de <strong>%d</strong> caractères.',
	'NEGATIVE_LENGTH_EVENT'		=> 'L\'événement ne peut pas se terminer avant d\'avoir commencé.',
	'NEW_EVENT'					=> 'Nouvel Evénement',
	'NO_EVENT'					=> 'L\'événement demandé n\'existe pas.',
	'NO_EVENT_TYPES'			=> 'L\'administrateur du site n\'a pas configuré de type d\'événement dans cette agenda. La création d\'événement a été désactivée.',
	'NO_POST_EVENT_MODE'		=> 'Aucun mode de poste spécifié.',
	'PM'						=> 'PM',
	'RETURN_CALENDAR'			=> '%sRetour à l\'agenda%s',
	'START_DATE'				=> 'Date de Début',
	'START_TIME'				=> 'Heure de Début',
	'TO_TIME'					=> 'Au',
	'USER_CANNOT_DELETE_EVENT'	=> 'Vous n\'avez pas la permission de supprimer des événements.',
	'USER_CANNOT_EDIT_EVENT'	=> 'Vous n\'êtes pas autorisé à modifier les événements.',
	'USER_CANNOT_POST_EVENT'	=> 'Vous n\'avez pas la permission de créer des événements.',
	'USER_CANNOT_VIEW_EVENT'	=> 'Vous n\'avez pas l\'autorisation d\'afficher des événements.',
	'VIEW_EVENT'				=> '%sVisualisez votre événement%s',
	'WEEK'						=> 'Semaine',
	'ZERO_LENGTH_EVENT'			=> 'L\'événement ne peut pas se terminer en même temps qu\'il commence.',
));

?>
