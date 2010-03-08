<?php
/**
*
* @package acp
* @version $Id: phpbb_calendar_version.php
* @copyright (c) 2008 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package phpbb_calendar
*/
class phpbb_calendar_version
{
	function version()
	{
		return array(
			'author'	=> 'alightner',
			'title'		=> 'phpbb Calendar',
			'tag'		=> 'phpbb_calendar',
			'version'	=> '0.0.8',
			'file'		=> array('phpbbcalendarmod.com', 'updatecheck', 'mods.xml'),
		);
	}
}

?>