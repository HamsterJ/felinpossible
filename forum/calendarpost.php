<?php
/**
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
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_calendar.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);


// Start session management
$user->session_begin();
$auth->acl($user->data);

// Language file (see documentation related to language files)
$user->setup('posting');
$user->setup('calendarpost');

// Grab only parameters needed here
//----------------------------
$event_id	= request_var('calEid', 0);
$lastclick	= request_var('lastclick', 0);
$submit		= (isset($_POST['post'])) ? true : false;
$preview	= (isset($_POST['preview'])) ? true : false;
$delete		= (isset($_POST['delete'])) ? true : false;
$cancel		= (isset($_POST['cancel'])) ? true : false;

// mode: post, edit, delete, or smilies
$mode		= ($delete && !$preview && $submit) ? 'delete' : request_var('mode', '');

$error = array();

init_calendar_data();

// are there any event types defined?
if( $available_etype_count < 1 )
{
	trigger_error('NO_EVENT_TYPES');
}


$current_time = time();

// Was cancel pressed? If so then redirect to the appropriate page
if ($cancel || ($current_time - $lastclick < 2 && $submit))
{
	$redirect = append_sid("{$phpbb_root_path}calendar.$phpEx", "calM=".$date['month_no']."&amp;calY=".$date['year']);
	redirect($redirect);
}

$event_data = array();
if( $event_id !== 0 )
{
	get_event_data( $event_id, $event_data );
}
else
{
	$event_data['event_id'] = 0;
	$event_data['event_start_time'] = 0;
	$event_data['event_end_time'] = 0;
	$event_data['etype_id'] = 0;
	$event_data['event_subject'] = "";
	$event_data['event_body'] = "";
	$event_data['poster_id'] = $user->data['user_id'];
	$event_data['event_all_day'] = 0;
	$event_data['event_day'] = "00-00-0000";
}
if( $mode == 'smilies' )
{
	generate_calendar_smilies('window');
	trigger_error('NO_POST_EVENT_MODE');
}


// Check permissions
if ($user->data['is_bot'])
{
	redirect(append_sid("{$phpbb_root_path}index.$phpEx"));
}


// Is the user able to view events?
if ( !$auth->acl_get('u_calendar_view_events') )
{
	if ($user->data['user_id'] != ANONYMOUS)
	{
		trigger_error('USER_CANNOT_VIEW_EVENT');
	}
	trigger_error('LOGIN_EXPLAIN_POST_EVENT');
}

// Permission to do the action asked?
$is_authed = false;

switch ($mode)
{
	case 'post':
		if ( $auth->acl_get('u_calendar_create_events') )
		{
			$is_authed = true;
		}
	break;

	case 'edit':
		if ($user->data['is_registered'] && $auth->acl_get('u_calendar_edit_events') )
		{
			$is_authed = true;
		}
	break;

	case 'delete':
		if ($user->data['is_registered'] && $auth->acl_get('u_calendar_delete_events') )
		{
			$is_authed = true;
		}
	break;
}

if (!$is_authed)
{
	if ($user->data['is_registered'])
	{
		if( strtoupper($mode) == "" )
		{
			$error_string = 'USER_CANNOT_POST_EVENT';
		}
		else
		{
			$error_string = 'USER_CANNOT_' . strtoupper($mode) . '_EVENT';
		}
		trigger_error($error_string);
	}

	login_box('', $user->lang['LOGIN_EXPLAIN_POST_EVENT']);
}

// Can we edit this post ... if we're a moderator with rights then always yes
// else it depends on editing times, lock status and if we're the correct user
if ($mode == 'edit' && !$auth->acl_get('m_calendar_edit_other_users_events'))
{
	if ($user->data['user_id'] != $event_data['poster_id'])
	{
		trigger_error('USER_CANNOT_EDIT_EVENT');
	}
}
if ($mode == 'delete' && !$auth->acl_get('m_calendar_delete_other_users_events'))
{
	if ($user->data['user_id'] != $event_data['poster_id'])
	{
		trigger_error('USER_CANNOT_DELETE_EVENT');
	}
}


// Handle delete mode...
if ($mode == 'delete')
{
	handle_event_delete($event_id, $event_data);
	exit;
}

// HTML, BBCode, Smilies, Images and Flash status
$bbcode_status	= ($config['allow_bbcode']) ? true : false;
$smilies_status	= ($bbcode_status && $config['allow_smilies']) ? true : false;
$img_status		= ($bbcode_status) ? true : false;
$url_status		= ($config['allow_post_links']) ? true : false;
$flash_status	= ($bbcode_status && $config['allow_post_flash']) ? true : false;
$quote_status	= false;


if ($submit || $preview)
{
	$event_data['event_subject']		= utf8_normalize_nfc(request_var('subject', '', true));
	$event_data['event_body']	= utf8_normalize_nfc(request_var('message', '', true));
	$event_data['etype_id']		= request_var('calEType', 0);
	$event_data['group_id']			= request_var('calGroupId', 0);
	$event_data['event_access_level']		= request_var('calELevel', 0);

	if( request_var('calAllDay', '') == "ON" )
	{
		$event_start_date = 0;
		$event_end_date = 0;
		$event_data['event_all_day'] = 1;
		$event_data['event_day'] = sprintf('%2d-%2d-%4d', $date['day'], $date['month_no'], $date['year']);
		$sort_timestamp = gmmktime( 0,0,0,$date['month_no'], $date['day'], $date['year']);
	}
	else
	{
		$start_hr = request_var('calHr', 0);
		$start_mn = request_var('calMn', 0);
		$event_start_date = gmmktime($start_hr, $start_mn, 0, $date['month_no'], $date['day'], $date['year'] ) - $user->timezone - $user->dst;
		$sort_timestamp = $event_start_date;
		$end_m = request_var('calMEnd', 0);
		$end_d = request_var('calDEnd', 0);
		$end_y = request_var('calYEnd', 0);
		$end_hr = request_var('calHrEnd', 0);
		$end_mn = request_var('calMnEnd', 0);
		$event_end_date = gmmktime($end_hr, $end_mn, 0, $end_m, $end_d, $end_y ) - $user->timezone - $user->dst;
		$event_data['event_all_day'] = 0;
		$event_data['event_day'] = '';

		// validate start and end times
		if( $event_end_date < $event_start_date )
		{
			$error[] = $user->lang['NEGATIVE_LENGTH_EVENT'];
		}
		else if( $event_end_date == $event_start_date )
		{
			$error[] = $user->lang['ZERO_LENGTH_EVENT'];
		}
	}
	$event_data['event_start_time'] = $event_start_date;
	$event_data['event_end_time'] = $event_end_date;
	$event_all_day = $event_data['event_all_day'];
	$event_day = $event_data['event_day'];

	// Parse subject
	if (!$preview && !utf8_clean_string($event_data['event_subject']) && ($mode == 'post' || ($mode == 'edit')))
	{
		$error[] = $user->lang['EMPTY_EVENT_SUBJECT'];
	}

	// DNSBL check
	if ($config['check_dnsbl'] )
	{
		if (($dnsbl = $user->check_dnsbl('post')) !== false)
		{
			$error[] = sprintf($user->lang['IP_BLACKLISTED'], $user->ip, $dnsbl[1]);
		}
	}


	// Store message/event
	if (!sizeof($error) && $submit)
	{
		if ($submit)
		{
			$etype_id = $event_data['etype_id'];
			$event_body = $event_data['event_body'];
			$poster_id = $event_data['poster_id'];

			$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
			$allow_bbcode = $allow_urls = $allow_smilies = true;
			generate_text_for_storage($event_body, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);


			$event_subject = $event_data['event_subject'];
			//$event_subject = trim(htmlspecialchars($event_subject));
			$event_subject = str_replace("\'", "''", $event_subject);

			$event_group_id = $event_data['group_id'];
			$event_access_level = $event_data['event_access_level'];


			if( $event_id > 0 )
			{
				$sql = 'UPDATE ' . CALENDAR_EVENTS_TABLE . '
					SET ' . $db->sql_build_array('UPDATE', array(
						'etype_id'				=> (int) $etype_id,
						'sort_timestamp'		=> (int)$sort_timestamp,
						'event_start_time'		=> (int) $event_start_date,
						'event_end_time'		=> (int) $event_end_date,
						'event_all_day'			=> (int) $event_all_day,
						'event_day'				=> $event_day,
						'event_subject'			=> (string) $event_subject,
						'event_body'			=> (string) $event_body,
						'poster_id'				=> (int) $poster_id,
						'event_access_level'	=> (int) $event_access_level,
						'group_id'				=> (int) $event_group_id,
						'bbcode_uid'			=> $uid,
						'bbcode_bitfield'		=> $bitfield,
						'enable_bbcode'			=> $allow_bbcode,
						'enable_magic_url'		=> $allow_urls,
						'enable_smilies'		=> $allow_smilies,
						)) . "
					WHERE event_id = $event_id";
				$db->sql_query($sql);
			}
			else
			{
				$sql = 'INSERT INTO ' . CALENDAR_EVENTS_TABLE . ' ' . $db->sql_build_array('INSERT', array(
						'etype_id'				=> (int) $etype_id,
						'sort_timestamp'		=> (int)$sort_timestamp,
						'event_start_time'		=> (int) $event_start_date,
						'event_end_time'		=> (int) $event_end_date,
						'event_all_day'			=> (int) $event_all_day,
						'event_day'				=> $event_day,
						'event_subject'			=> (string) $event_subject,
						'event_body'			=> (string) $event_body,
						'poster_id'				=> (int) $poster_id,
						'event_access_level'	=> (int) $event_access_level,
						'group_id'				=> (int) $event_group_id,
						'bbcode_uid'			=> $uid,
						'bbcode_bitfield'		=> $bitfield,
						'enable_bbcode'			=> $allow_bbcode,
						'enable_magic_url'		=> $allow_urls,
						'enable_smilies'		=> $allow_smilies,
						)
					);
				$db->sql_query($sql);
				$event_id = $db->sql_nextid();
			}

			$redirect_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "calM=".$date['month_no']."&amp;calY=".$date['year']);
			meta_refresh(3, $redirect_url);

			$view_event_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=event&amp;calEid=".$event_id."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);

			if( $mode == 'edit' )
			{
				$message = $user->lang['EVENT_EDITED'] . '<br /><br />' . sprintf($user->lang['VIEW_EVENT'], '<a href="' . $view_event_url . '">', '</a>');

			}
			else
			{
				$message = $user->lang['EVENT_STORED'] . '<br /><br />' . sprintf($user->lang['VIEW_EVENT'], '<a href="' . $view_event_url . '">', '</a>');
			}

			$message .= '<br /><br />' . sprintf($user->lang['RETURN_CALENDAR'], '<a href="' . $redirect_url . '">', '</a>');
			trigger_error($message);
		}
	}
}

// Preview
if (!sizeof($error) && $preview)
{
	// Get the date/time info in the user display format
	$start_date_txt = $user->format_date($event_data['event_start_time']);
	$end_date_txt = $user->format_date($event_data['event_end_time']);

	// translate event start and end time into user's timezone
	$user_event_start = $event_data['event_start_time'] + $user->timezone + $user->dst;
	$user_event_end = $event_data['event_end_time'] + $user->timezone + $user->dst;

	$preview_all_day = 0;
	if( $event_data['event_all_day'] == 1 )
	{
		$preview_all_day = 1;
		// All day event - find the string for the event day
		if ($event_data['event_day'])
		{
			list($eday['eday_day'], $eday['eday_month'], $eday['eday_year']) = explode('-', $event_data['event_day']);
			$event_days_time = gmmktime(0,0,0,$eday['eday_month'], $eday['eday_day'], $eday['eday_year']) - $user->timezone - $user->dst;
			$start_date_txt = $user->format_date($event_days_time);
		}
		else
		{
			// We should never get here
			// (this would be an all day event with no specified day for the event)
			$start_date_txt = "";
		}
	}



	// Convert event comment into preview version with bbcode and all
	$event_body = $event_data['event_body'];
	$uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
	$allow_bbcode = $allow_urls = $allow_smilies = true;
	generate_text_for_storage($event_body, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
	$preview_message = generate_text_for_display($event_body, $uid, $bitfield, $options);

	$preview_etype_display_name = $available_etype_display_names[$event_data['etype_id']];
	$preview_event_color = $available_etype_colors[$event_data['etype_id']];
	$preview_event_image = $available_etype_images[$event_data['etype_id']];
	$preview_subject = censor_text($event_data['event_subject']);

	$poster_url = '';
	$invite_list = '';
	get_event_invite_list_and_poster_url($event_data, $poster_url, $invite_list );

	if (!sizeof($error))
	{
		$template->assign_vars(array(
			'PREVIEW_SUBJECT'		=> $preview_subject,
			'PREVIEW_ETYPE_DISPLAY_NAME'=> $preview_etype_display_name,
			'PREVIEW_EVENT_COLOR'	=> $preview_event_color,
			'PREVIEW_EVENT_IMAGE'	=> $preview_event_image,
			'PREVIEW_MESSAGE'		=> $preview_message,
			'PREVIEW_START_DATE'	=> $start_date_txt,
			'PREVIEW_END_DATE'		=> $end_date_txt,
			'PREVIEW_POSTER'		=> $poster_url,
			'PREVIEW_INVITED'		=> $invite_list,
			'ALL_DAY'				=> $preview_all_day,
			'S_DISPLAY_PREVIEW'		=> true)
		);
	}
}


// MAIN POSTING PAGE BEGINS HERE

// Generate smiley listing
generate_calendar_smilies('inline');

// action URL, include session_id for security purpose
$s_action = append_sid("{$phpbb_root_path}calendarpost.$phpEx", "mode=$mode", true, $user->session_id);

// Page title
switch ($mode)
{
	case 'post':
		$page_title = $user->lang['CALENDAR_POST_EVENT'];
	break;

	case 'delete':
	case 'edit':
		$page_title = $user->lang['CALENDAR_EDIT_EVENT'];
		// Decode text for message editing
		decode_message($event_data['event_body'], $event_data['bbcode_uid']);
	break;
}

$temp_find_str = "<br />";
$temp_replace_str = "\n";
$event_data['event_body'] = str_replace( $temp_find_str, $temp_replace_str, $event_data['event_body'] );


//-----------------------------------------
// populate form options...
//-----------------------------------------

$month_sel_code  = "<select name='calM' id='calM'>\n";
for( $i = 1; $i <= 12; $i++ )
{
	$month_sel_code .= "<option value='".$i."'>".$user->lang['datetime'][$month_names[$i]]."</option>\n";
}
$month_sel_code .= "</select>\n";

$day_sel_code  = "<select name='calD' id='calD'>\n";
for( $i = 1; $i <= 31; $i++ )
{
	$day_sel_code .= "<option value='".$i."'>".$i."</option>\n";
}
$day_sel_code .= "</select>\n";

$year_sel_code  = "<select name='calY' id='calY'>\n";
for( $i = $date['year']; $i < ($date['year']+5); $i++ )
{
	$year_sel_code .= "<option value='".$i."'>".$i."</option>\n";
}
$year_sel_code .= "</select>\n";

$hour_sel_code = "<select name='calHr' id='calHr'>\n";
$hour_mode = get_calendar_config_value('hour_mode', '12');
if( $hour_mode == 12 )
{
	for( $i = 0; $i < 24; $i++ )
	{
		$mod_12 = $i % 12;
		if( $mod_12 == 0 )
		{
			$mod_12 = 12;
		}
		$am_pm = $user->lang['PM'];
		if( $i < 12 )
		{
			$am_pm = $user->lang['AM'];
		}
		$hour_sel_code .= "<option value='".$i."'>".$am_pm." ".$mod_12."</option>\n";
	}
}
else
{
	for( $i = 0; $i < 24; $i++ )
	{
		$o = "";
		if($i < 10 )
		{
			$o="0";
		}
		$hour_sel_code .= "<option value='".$i."'>".$o.$i."</option>\n";
	}
}
$hour_sel_code .= "</select>\n";

$min_sel_code = "<select name='calMn' id='calMn'>\n";
for( $i = 0; $i < 4; $i++ )
{
	$t = $i * 15;
	$o = "";
	if($t < 10 )
	{
		$o="0";
	}
	$min_sel_code .= "<option value='".$t."'>".$o.$t."</option>\n";
}
$min_sel_code .= "</select>\n";

$e_type_sel_code  = "<select name='calEType' id='calEType'>\n";
for( $i = 0; $i < $available_etype_count; $i++ )
{
	$e_type_sel_code .= "<option value='".$available_etype_ids[$i]."'>".$available_etype_full_names[$i]."</option>\n";
}
$e_type_sel_code .= "</select>\n";

// Find what groups this user is a member of and add them to the list of groups to invite
$group_sel_code = posting_generate_group_selection_code( $event_data['poster_id'] );

$level_sel_code  = "<select name='calELevel' id='calELevel' onchange='update_group_id_state();'>\n";
$level_sel_code .= "<option value='0'>".$user->lang['EVENT_ACCESS_LEVEL_PERSONAL']."</option>\n";
$level_sel_code .= "<option value='1'>".$user->lang['EVENT_ACCESS_LEVEL_GROUP']."</option>\n";
$level_sel_code .= "<option value='2'>".$user->lang['EVENT_ACCESS_LEVEL_PUBLIC']."</option>\n";
$level_sel_code .= "</select>\n";


$all_day_check = "<input type='checkbox' name='calAllDay' value='ON' checked='checked' onclick='toggle_all_day_event()' />";
$cancel_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "m=".$date['month_no']."&amp;y=".$date['year']);


// check to see if we're editing an existing event
if( sizeof($error) || $preview || $event_id > 0 )
{

	// translate event start and end time into user's timezone
	$event_start = $event_data['event_start_time'] + $user->timezone + $user->dst;
	$event_end = $event_data['event_end_time'] + $user->timezone + $user->dst;

	$all_day = 0;
	if( $event_data['event_all_day'] == 1 )
	{
		$all_day = 1;
		list($eday['eday_day'], $eday['eday_month'], $eday['eday_year']) = explode('-', $event_data['event_day']);
		$event_start = gmmktime(0,0,0,$eday['eday_month'], $eday['eday_day'], $eday['eday_year']);
	}
	else
	{
		$temp_find_str = "value='ON' checked='checked'";
		$temp_replace_str = "value='ON'";
		$all_day_check = str_replace( $temp_find_str, $temp_replace_str, $all_day_check );
	}


	$cancel_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "m=".gmdate('n', $event_start)."&amp;y=".gmdate('Y', $event_start) );

	//-----------------------------------------
	// month selection data
	//-----------------------------------------
	if( $all_day == 0 )
	{
		$temp_find_str = "value='".gmdate('n', $event_end)."'";
		$temp_replace_str = "value='".gmdate('n', $event_end)."' selected='selected'";
		$end_month_sel_code = str_replace( $temp_find_str, $temp_replace_str, $month_sel_code );
		$temp_find_str = "name='calM' id='calM'";
		$temp_replace_str = "name='calMEnd' id='calMEnd'";
		$end_month_sel_code = str_replace( $temp_find_str, $temp_replace_str, $end_month_sel_code );
	}
	else
	{
		$temp_find_str = "value='".gmdate('n', $event_start)."'";
		$temp_replace_str = "value='".gmdate('n', $event_start)."' selected='selected'";
		$end_month_sel_code = str_replace( $temp_find_str, $temp_replace_str, $month_sel_code );
		$temp_find_str = "name='calM' id='calM'";
		$temp_replace_str = "name='calMEnd' id='calMEnd' disabled='disabled'";
		$end_month_sel_code = str_replace( $temp_find_str, $temp_replace_str, $end_month_sel_code );
	}
	$temp_find_str = "value='".gmdate('n', $event_start)."'";
	$temp_replace_str = "value='".gmdate('n', $event_start)."' selected='selected'";
	$month_sel_code = str_replace( $temp_find_str, $temp_replace_str, $month_sel_code );


	//-----------------------------------------
	// day selection data
	//-----------------------------------------
	if( $all_day == 0 )
	{
		$temp_find_str = "value='".gmdate('j', $event_end)."'";
		$temp_replace_str = "value='".gmdate('j', $event_end)."' selected='selected'";
		$end_day_sel_code = str_replace( $temp_find_str, $temp_replace_str, $day_sel_code );
		$temp_find_str = "name='calD' id='calD'";
		$temp_replace_str = "name='calDEnd' id='calDEnd'";
		$end_day_sel_code = str_replace( $temp_find_str, $temp_replace_str, $end_day_sel_code );
	}
	else
	{
		$temp_find_str = "value='".gmdate('j', $event_start)."'";
		$temp_replace_str = "value='".gmdate('j', $event_start)."' selected='selected'";
		$end_day_sel_code = str_replace( $temp_find_str, $temp_replace_str, $day_sel_code );
		$temp_find_str = "name='calD' id='calD'";
		$temp_replace_str = "name='calDEnd' id='calDEnd' disabled='disabled'";
		$end_day_sel_code = str_replace( $temp_find_str, $temp_replace_str, $end_day_sel_code );
	}
	$temp_find_str = "value='".gmdate('j', $event_start)."'";
	$temp_replace_str = "value='".gmdate('j', $event_start)."' selected='selected'";
	$day_sel_code = str_replace( $temp_find_str, $temp_replace_str, $day_sel_code );

	//-----------------------------------------
	// year selection data
	//-----------------------------------------
	if( $all_day == 0 )
	{
		$temp_find_str = "value='".gmdate('Y', $event_end)."'";
		$temp_replace_str = "value='".gmdate('Y', $event_end)."' selected='selected'";
		$end_year_sel_code = str_replace( $temp_find_str, $temp_replace_str, $year_sel_code );
		$temp_find_str = "name='calY' id='calY'";
		$temp_replace_str = "name='calYEnd' id='calYEnd'";
		$end_year_sel_code = str_replace( $temp_find_str, $temp_replace_str, $end_year_sel_code );
	}
	else
	{
		$temp_find_str = "value='".gmdate('Y', $event_start)."'";
		$temp_replace_str = "value='".gmdate('Y', $event_start)."' selected='selected'";
		$end_year_sel_code = str_replace( $temp_find_str, $temp_replace_str, $year_sel_code );
		$temp_find_str = "name='calY' id='calY'";
		$temp_replace_str = "name='calYEnd' id='calYEnd' disabled='disabled'";
		$end_year_sel_code = str_replace( $temp_find_str, $temp_replace_str, $end_year_sel_code );
	}
	$temp_find_str = "value='".gmdate('Y', $event_start)."'";
	$temp_replace_str = "value='".gmdate('Y', $event_start)."' selected='selected'";
	$year_sel_code = str_replace( $temp_find_str, $temp_replace_str, $year_sel_code );


	//-----------------------------------------
	// hour selection data
	//-----------------------------------------
	if( $all_day == 0 )
	{
		$temp_find_str = "value='".gmdate('G', $event_end)."'";
		$temp_replace_str = "value='".gmdate('G', $event_end)."' selected='selected'";
		$end_hour_code = str_replace( $temp_find_str, $temp_replace_str, $hour_sel_code );
		$temp_find_str = "name='calHr' id='calHr'";
		$temp_replace_str = "name='calHrEnd' id='calHrEnd'";
		$end_hour_code = str_replace( $temp_find_str, $temp_replace_str, $end_hour_code );
	}
	else
	{
		$temp_find_str = "value='".gmdate('G', $event_start)."'";
		$temp_replace_str = "value='".gmdate('G', $event_start)."' selected='selected'";
		$end_hour_code = str_replace( $temp_find_str, $temp_replace_str, $hour_sel_code );
		$temp_find_str = "name='calHr' id='calHr'";
		$temp_replace_str = "name='calHrEnd' id='calHrEnd' disabled='disabled'";
		$end_hour_code = str_replace( $temp_find_str, $temp_replace_str, $end_hour_code );
		$temp_find_str = "name='calHr' id='calHr'";
		$temp_replace_str = "name='calHr' id='calHr' disabled='disabled'";
		$hour_sel_code = str_replace( $temp_find_str, $temp_replace_str, $hour_sel_code );
	}
	$temp_find_str = "value='".gmdate('G', $event_start)."'";
	$temp_replace_str = "value='".gmdate('G', $event_start)."' selected='selected'";
	$start_hour_code = str_replace( $temp_find_str, $temp_replace_str, $hour_sel_code );

	//-----------------------------------------
	// minute selection data
	//-----------------------------------------
	if( $all_day == 0 )
	{
		$temp_find_str = "value='".gmdate('i', $event_end)."'";
		$temp_replace_str = "value='".gmdate('i', $event_end)."' selected='selected'";
		$end_min_code = str_replace( $temp_find_str, $temp_replace_str, $min_sel_code );
		$temp_find_str = "name='calMn' id='calMn'";
		$temp_replace_str = "name='calMnEnd' id='calMnEnd'";
		$end_min_code = str_replace( $temp_find_str, $temp_replace_str, $end_min_code );
	}
	else
	{
		$temp_find_str = "value='".gmdate('i', $event_start)."'";
		$temp_replace_str = "value='".gmdate('i', $event_start)."' selected='selected'";
		$end_min_code = str_replace( $temp_find_str, $temp_replace_str, $min_sel_code );
		$temp_find_str = "name='calMn' id='calMn'";
		$temp_replace_str = "name='calMnEnd' id='calMnEnd' disabled='disabled'";
		$end_min_code = str_replace( $temp_find_str, $temp_replace_str, $end_min_code );
		$temp_find_str = "name='calMn' id='calMn'";
		$temp_replace_str = "name='calMn' id='calMn' disabled='disabled'";
		$min_sel_code = str_replace( $temp_find_str, $temp_replace_str, $min_sel_code );
	}
	$temp_find_str = "value='".gmdate('i', $event_start)."'";
	$temp_replace_str = "value='".gmdate('i', $event_start)."' selected='selected'";
	$start_min_code = str_replace( $temp_find_str, $temp_replace_str, $min_sel_code );

	//-----------------------------------------
	// event type data
	//-----------------------------------------
	$temp_find_str = "value='".$event_data['etype_id']."'";
	$temp_replace_str = "value='".$event_data['etype_id']."' selected='selected'";
	$e_type_sel_code = str_replace( $temp_find_str, $temp_replace_str, $e_type_sel_code );


	//-----------------------------------------
	// event levels
	//-----------------------------------------
	$temp_find_str = "value='".$event_data['event_access_level']."'";
	$temp_replace_str = "value='".$event_data['event_access_level']."' selected='selected'";
	$level_sel_code = str_replace( $temp_find_str, $temp_replace_str, $level_sel_code );

	$temp_find_str = "value='".$event_data['group_id']."'";
	$temp_replace_str = "value='".$event_data['group_id']."' selected='selected'";
	$group_sel_code = str_replace( $temp_find_str, $temp_replace_str, $group_sel_code );

	if( $event_data['event_access_level'] == 1 )
	{
		$temp_find_str = "disabled='disabled'";
		$temp_replace_str = "";
		$group_sel_code = str_replace( $temp_find_str, $temp_replace_str, $group_sel_code );
	}




}
else // we're creating a new event
{
	//-----------------------------------------
	// month selection data
	//-----------------------------------------
	$temp_find_str = "value='".$date['month_no']."'>";
	$temp_replace_str = "value='".$date['month_no']."' selected='selected'>";
	$month_sel_code = str_replace( $temp_find_str, $temp_replace_str, $month_sel_code );

	$temp_find_str = "name='calM' id='calM'";
	$temp_replace_str = "name='calMEnd' id='calMEnd' disabled='disabled'";
	$end_month_sel_code = str_replace( $temp_find_str, $temp_replace_str, $month_sel_code );


	//-----------------------------------------
	// day selection data
	//-----------------------------------------
	$temp_find_str = "value='".$date['day']."'>";
	$temp_replace_str = "value='".$date['day']."' selected='selected'>";
	$day_sel_code = str_replace( $temp_find_str, $temp_replace_str, $day_sel_code );

	$temp_find_str = "name='calD' id='calD'";
	$temp_replace_str = "name='calDEnd' id='calDEnd' disabled='disabled'";
	$end_day_sel_code = str_replace( $temp_find_str, $temp_replace_str, $day_sel_code );


	//-----------------------------------------
	// year selection data
	//-----------------------------------------
	$temp_find_str = "value='".$date['year']."'>";
	$temp_replace_str = "value='".$date['year']."' selected='selected'>";
	$year_sel_code = str_replace( $temp_find_str, $temp_replace_str, $year_sel_code );

	$temp_find_str = "name='calY' id='calY'";
	$temp_replace_str = "name='calYEnd' id='calYEnd' disabled='disabled'";
	$end_year_sel_code = str_replace( $temp_find_str, $temp_replace_str, $year_sel_code );

	//-----------------------------------------
	// hour selection data
	//-----------------------------------------
	$temp_find_str = "id='calHr'";
	$temp_replace_str = "id='calHr' disabled='disabled'";
	$hour_sel_code = str_replace( $temp_find_str, $temp_replace_str, $hour_sel_code );

	$start_hour_code = $hour_sel_code;
	$end_hour_code = $hour_sel_code;

	$temp_find_str = "name='calHr' id='calHr'";
	$temp_replace_str = "name='calHrEnd' id='calHrEnd'";
	$end_hour_code = str_replace( $temp_find_str, $temp_replace_str, $end_hour_code );

	//-----------------------------------------
	// minute selection data
	//-----------------------------------------
	$temp_find_str = "id='calMn'";
	$temp_replace_str = "id='calMn' disabled='disabled'";
	$min_sel_code = str_replace( $temp_find_str, $temp_replace_str, $min_sel_code );

	$start_min_code = $min_sel_code;
	$end_min_code = $min_sel_code;

	$temp_find_str = "name='calMn' id='calMn'";
	$temp_replace_str = "name='calMnEnd' id='calMnEnd'";
	$end_min_code = str_replace( $temp_find_str, $temp_replace_str, $end_min_code );

}


// Build Navigation Links
generate_forum_nav($post_data);

$s_hidden_fields = '<input type="hidden" name="calEid" value="' . $event_data['event_id'] . '" />';
$s_hidden_fields .= '<input type="hidden" name="lastclick" value="' . $current_time . '" />';

$day_view_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=day&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
$week_view_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=week&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
$month_view_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=month&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);

$allow_delete = false;
if( ($mode == 'edit') &&
	( $user->data['is_registered'] && $auth->acl_get('u_calendar_delete_events')) &&
	(($user->data['user_id'] == $event_data['poster_id'])|| $auth->acl_get('m_calendar_delete_other_users_events') ))
{
	$allow_delete = true;
}

// Start assigning vars for main posting page ...
$template->assign_vars(array(
	'L_POST_A'					=> $page_title,
	'L_MESSAGE_BODY_EXPLAIN'	=> (intval($config['max_post_chars'])) ? sprintf($user->lang['MESSAGE_BODY_EXPLAIN'], intval($config['max_post_chars'])) : '',
	'SUBJECT'					=> $event_data['event_subject'],
	'MESSAGE'					=> $event_data['event_body'],
	'BBCODE_STATUS'				=> ($bbcode_status) ? sprintf($user->lang['BBCODE_IS_ON'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>') : sprintf($user->lang['BBCODE_IS_OFF'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>'),
	'IMG_STATUS'				=> ($img_status) ? $user->lang['IMAGES_ARE_ON'] : $user->lang['IMAGES_ARE_OFF'],
	'FLASH_STATUS'				=> ($flash_status) ? $user->lang['FLASH_IS_ON'] : $user->lang['FLASH_IS_OFF'],
	'SMILIES_STATUS'			=> ($smilies_status) ? $user->lang['SMILIES_ARE_ON'] : $user->lang['SMILIES_ARE_OFF'],
	'URL_STATUS'				=> ($bbcode_status && $url_status) ? $user->lang['URL_IS_ON'] : $user->lang['URL_IS_OFF'],
	'MINI_POST_IMG'				=> $user->img('icon_post_target', $user->lang['POST']),
	'ERROR'						=> (sizeof($error)) ? implode('<br />', $error) : '',
	'U_CALENDAR'				=> append_sid("{$phpbb_root_path}calendar.$phpEx"),
	'MONTH_SEL'					=> $month_sel_code,
	'DAY_SEL'					=> $day_sel_code,
	'YEAR_SEL'					=> $year_sel_code,
	'START_HOUR_SEL'			=> $start_hour_code,
	'START_MIN_SEL'				=> $start_min_code,
	'ALL_DAY_CHECK'				=> $all_day_check,
	'END_MONTH_SEL'				=> $end_month_sel_code,
	'END_DAY_SEL'				=> $end_day_sel_code,
	'END_YEAR_SEL'				=> $end_year_sel_code,
	'END_HOUR_SEL'				=> $end_hour_code,
	'END_MIN_SEL'				=> $end_min_code,
	'EVENT_TYPE_SEL'			=> $e_type_sel_code,
	'EVENT_ACCESS_LEVEL_SEL'			=> $level_sel_code,
	'EVENT_GROUP_SEL'			=> $group_sel_code,

	'DAY_VIEW_URL'				=> $day_view_url,
	'WEEK_VIEW_URL'				=> $week_view_url,
	'MONTH_VIEW_URL'			=> $month_view_url,

	'S_DELETE_ALLOWED'			=> $allow_delete,
	'S_BBCODE_ALLOWED'			=> $bbcode_status,
	'S_SMILIES_ALLOWED'			=> $smilies_status,
	'S_LINKS_ALLOWED'			=> $url_status,
	'S_BBCODE_IMG'			=> $img_status,
	'S_BBCODE_URL'			=> $url_status,
	'S_BBCODE_FLASH'		=> $flash_status,
	'S_BBCODE_QUOTE'		=> $quote_status,

	'S_POST_ACTION'			=> $s_action,
	'S_HIDDEN_FIELDS'		=> $s_hidden_fields)
);


// Build custom bbcodes array
display_custom_bbcodes();


// Output page ...
page_header($page_title);

$template->set_filenames(array(
	'body' => 'calendar_post_body.html')
);

make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));

page_footer();




?>
