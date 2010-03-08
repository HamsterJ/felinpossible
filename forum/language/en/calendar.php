<?php
/**
*
* calendar [English]
*
* @author alightner alightner@hotmail.com
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
	'ALL_DAY'				=> 'All Day Event',
	'AM'					=> 'AM',
	'CALENDAR_TITLE'		=> 'Calendar',
	'DAY'					=> 'Day',
	'DAY_OF'				=> 'Day of ',
	'EVENT_CREATED_BY'		=> 'Event Posted By',
	'EVERYONE'				=> 'Everyone',
	'FROM_TIME'				=> 'From',
	'INVALID_EVENT'			=> 'The event you are trying to view does not exist.',
	'INVITE_INFO'			=> 'Invited',
	'LOCAL_DATE_FORMAT'		=> '%1$s %2$s, %3$s',
	'MONTH'					=> 'Month',
	'MONTH_OF'				=> 'Month of ',
	'NEW_EVENT'				=> 'New Event',
	'NO_EVENTS_TODAY'		=> 'There are no events scheduled for this day.',
	'PAGE_TITLE'			=> 'Calendar',
	'PM'					=> 'PM',
	'PRIVATE_EVENT'			=> 'This event is private.  You must be invited and logged in to view this event.',
	'TO_TIME'				=> 'To',
	'UPCOMING_EVENTS'		=> 'Upcoming Events',
	'USER_CANNOT_VIEW_EVENT'=> 'You do not have permission to view this event.',
	'WEEK'					=> 'Week',
	'WEEK_OF'				=> 'Week of ',
));

?>
