<?php
/**
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
* @ignore
*/
define('IN_PHPBB', true); // we tell the page that it is going to be using phpBB, this is important.
$phpbb_root_path = './'; // See phpbb_root_path documentation
$phpEx = substr(strrchr(__FILE__, '.'), 1); // Set the File extension for page-wide usage.
include($phpbb_root_path . 'common.' . $phpEx); // include the common.php file, this is important, especially for database connects.
include($phpbb_root_path . 'includes/functions_calendar.' . $phpEx); // contains the functions that "do the work".

// Start session management -- This will begin the session for the user browsing this page.
$user->session_begin();
$auth->acl($user->data);

// Language file (see documentation related to language files)
$user->setup('calendar');



/**
* All of your coding will be here, setting up vars, database selects, inserts, etc...
*/
$view_mode = request_var('view', 'month');

switch( $view_mode )
{
	case "event":
		// display a single event
		$template_body = "calendar_view_event.html";
		calendar_display_event();
		break;

	case "day":
		// display all of the events on this day
		$template_body = "calendar_view_day.html";
		calendar_display_day();
		break;

	case "week":
		// display the entire week
		// viewing the week is a lot like viewing the month...
		$template_body = "calendar.html";
		calendar_display_week( 0 );
		break;

	case "month":
		// display the entire month
		$template_body = "calendar.html";
		calendar_display_month();
		break;
}


// Output the page
page_header($user->lang['PAGE_TITLE']); // Page title, this language variable should be defined in the language file you setup at the top of this page.


// Set the filename of the template you want to use for this file.
$template->set_filenames(array(
	'body' => $template_body) // template file name -- See Templates Documentation
);

// Finish the script, display the page
page_footer();


?>
