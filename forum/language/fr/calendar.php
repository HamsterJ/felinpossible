<?php
/**
*
* calendar [Standard french]
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

/*** DO NOT CHANGE*/
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
	'ALL_DAY'				=> 'Toute la Journée',
	'ALL_DAY_STARTING'		=> 'Toute la Journée de Départ',
	'AM'					=> 'AM',
	'CALENDAR_TITLE'		=> 'Agenda',
	'DAY'					=> 'Jour',
	'DAY_OF'				=> 'Journée du ',
	'EVENT_CREATED_BY'		=> 'Evénement posté par',
	'EVERYONE'				=> 'Tout le monde',
	'FROM_TIME'				=> 'Du',
	'INVALID_EVENT'			=> 'L\'événement que vous tentez d\'afficher n\'existe pas.',
	'INVITE_INFO'			=> 'Qui peut voir cet événement',
	'LOCAL_DATE_FORMAT'		=> '%1$s %2$s, %3$s',
	'MONTH'					=> 'Mois',
	'MONTH_OF'				=> 'Mois de ',
	'NEW_EVENT'				=> 'Nouvel Evénement',
	'NO_EVENTS_TODAY'		=> 'Il n\'y a pas d\'événement prévu pour cette journée.',
	'PAGE_TITLE'			=> 'Agenda',
	'PM'					=> 'PM',
	'PRIVATE_EVENT'			=> 'Cet événement est privé. Vous devez être connecté et invité pour voir cet événement.',
	'TO_TIME'				=> 'au',
	'UPCOMING_EVENTS'		=> 'Evénements à Venir',
	'USER_CANNOT_VIEW_EVENT'=> 'Vous n\'êtes pas autorisé à afficher cette manifestation.',
	'WEEK'					=> 'Semaine',
	'WEEK_OF'				=> 'Semaine du ',
));

?>
