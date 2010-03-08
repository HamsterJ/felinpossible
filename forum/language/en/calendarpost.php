<?php
/**
*
* calendarpost [English]
*
* @author alightner alightner@hotmail.com
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
	'ALL_DAY'					=> 'All Day Event',
	'AM'						=> 'AM',
	'CALENDAR_POST_EVENT'		=> 'Create New Event',
	'CALENDAR_EDIT_EVENT'		=> 'Edit Event',
	'CALENDAR_TITLE'			=> 'Calendar',
	'DELETE_EVENT'				=> 'Delete event',
	'EMPTY_EVENT_MESSAGE'		=> 'You must enter a message when posting events.',
	'EMPTY_EVENT_SUBJECT'		=> 'You must enter a subject when posting events.',
	'END_DATE'					=> 'End Date',
	'END_TIME'					=> 'End Time',
	'EVENT_ACCESS_LEVEL'			=> 'Who can see this event?',
	'EVENT_ACCESS_LEVEL_GROUP'		=> 'Group',
	'EVENT_ACCESS_LEVEL_PERSONAL'	=> 'Personal',
	'EVENT_ACCESS_LEVEL_PUBLIC'		=> 'Public',
	'EVENT_CREATED_BY'			=> 'Event Posted By',
	'EVENT_DELETED'				=> 'This event has been deleted successfully.',
	'EVENT_EDITED'				=> 'This event has been edited successfully.',
	'EVENT_GROUP'				=> 'Which group can see this event?',
	'EVENT_STORED'				=> 'This event has been created successfully.',
	'EVENT_TYPE'				=> 'Event Type',
	'EVERYONE'					=> 'Everyone',
	'FROM_TIME'					=> 'From',
	'INVITE_INFO'				=> 'Invited',
	'LOGIN_EXPLAIN_POST_EVENT'	=> 'You need to login in order to add/edit/delete events.',
	'MESSAGE_BODY_EXPLAIN'		=> 'Enter your message here, it may contain no more than <strong>%d</strong> characters.',
	'NEGATIVE_LENGTH_EVENT'		=> 'The event cannot end before it starts.',
	'NEW_EVENT'					=> 'New Event',
	'NO_EVENT'					=> 'The requested event does not exist.',
	'NO_EVENT_TYPES'			=> 'The site administrator has not set up event types for this calendar.  Calendar event creation has been disabled.',
	'NO_POST_EVENT_MODE'		=> 'No post mode specified.',
	'PM'						=> 'PM',
	'RETURN_CALENDAR'			=> '%sReturn to the calendar%s',
	'START_DATE'				=> 'Start Date',
	'START_TIME'				=> 'Start Time',
	'TO_TIME'					=> 'To',
	'USER_CANNOT_DELETE_EVENT'	=> 'You do not have permission to delete events.',
	'USER_CANNOT_EDIT_EVENT'	=> 'You do not have permission to edit events.',
	'USER_CANNOT_POST_EVENT'	=> 'You do not have permission to create events.',
	'USER_CANNOT_VIEW_EVENT'	=> 'You do not have permission to view events.',
	'VIEW_EVENT'				=> '%sView your submitted event%s',
	'WEEK'						=> 'Week',
	'ZERO_LENGTH_EVENT'			=> 'The event cannot end at the same time it starts.',
));

?>
