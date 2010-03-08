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
if (!defined('IN_PHPBB'))
{
	exit;
}


$date = array();
$month_names = array();
$available_etype_count = 0;
$available_etype_ids = array();
$available_etype_full_names = array();
$available_etype_display_names = array();
$available_etype_colors = array();
$available_etype_images = array();
$month_sel_code = "";
$day_sel_code = "";
$year_sel_code = "";
$mode_sel_code = "";


function calendar_display_month()
{
	global $auth, $db, $user, $config, $template, $date, $available_etype_colors, $available_etype_images, $available_etype_display_names, $month_sel_code, $day_sel_code, $year_sel_code, $mode_sel_code;
	global $phpEx, $phpbb_root_path;

	init_calendar_data();
	init_view_selection_code("month");


	//create next and prev links
	set_date_prev_next( "month" );
	$prev_link = append_sid("{$phpbb_root_path}calendar.$phpEx", "calM=".$date['prev_month']."&amp;calY=".$date['prev_year']);
	$next_link = append_sid("{$phpbb_root_path}calendar.$phpEx", "calM=".$date['next_month']."&amp;calY=".$date['next_year']);

	//find the first day of the week
	$first_day_of_week = get_calendar_config_value("first_day_of_week", 0);
	get_weekday_names( $first_day_of_week, $sunday, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday );

	//get the first day of the month
	$date['num'] = "01";
	$date['fday'] = get_fday( $date['num'], $date['month_no'], $date['year'], $first_day_of_week );

	$number_days = gmdate("t", gmmktime( 0,0,0,$date['month_no'], $date['day'], $date['year']));

	$calendar_header_txt = $user->lang['MONTH_OF'] . sprintf($user->lang['LOCAL_DATE_FORMAT'], $user->lang['datetime'][$date['month']], $date['day'], $date['year'] );
	$subject_limit = get_calendar_config_value("display_truncated_name", 0);

	// Is the user able to view ANY events?
	$user_can_view_events = false;
	if ( $auth->acl_get('u_calendar_view_events') )
	{
		$user_can_view_events = true;

		/* find the group options here so we do not have to look them up again for each day */
		$group_options = get_sql_group_options($user->data['user_id']);
	}

	$counter = 0;
	for ($j = 1; $j < $number_days+1; $j++, $counter++)
	{
		// if it is the first week
		if ($j == 1)
		{
			// find how many place holders we need before day 1
			if ($date['fday'] < 7)
			{
				$date['fday'] = $date['fday']+1;
				for ($i = 1; $i < $date['fday']; $i++, $counter++)
				{
					// create dummy days (place holders)
					if( $i == 1 )
					{
						$calendar_days['START_WEEK'] = true;
					}
					else
					{
						$calendar_days['START_WEEK'] = false;
					}
					$calendar_days['END_WEEK'] = false;
					$calendar_days['HEADER_CLASS'] = '';
					$calendar_days['DAY_CLASS'] = '';
					$calendar_days['NUMBER'] = 0;
					$calendar_days['DUMMY_DAY'] = true;
					$calendar_days['ADD_LINK'] = '';
					$calendar_days['BIRTHDAYS'] = '';
					$template->assign_block_vars('calendar_days', $calendar_days);
				}
			}
		}
		// start creating the data for the real days
		$calendar_days['START_WEEK'] = false;
		$calendar_days['END_WEEK'] = false;
		$calendar_days['DUMMY_DAY'] = false;
		$calendar_days['HEADER_CLASS'] = '';
		$calendar_days['DAY_CLASS'] = '';
		$calendar_days['NUMBER'] = 0;
		$calendar_days['ADD_LINK'] = '';
		$calendar_days['BIRTHDAYS'] = '';

		if($counter % 7 == 0)
		{
			$calendar_days['START_WEEK'] = true;
		}
		if($counter % 7 == 6 )
		{
			$calendar_days['END_WEEK'] = true;
		}
		$calendar_days['NUMBER'] = $j;
		if( $auth->acl_get('u_calendar_create_events') )
		{
			$calendar_days['ADD_LINK'] = append_sid("{$phpbb_root_path}calendarpost.$phpEx", "mode=post&amp;calD=".$j."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
		}
		$calendar_days['DAY_VIEW_URL'] = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=day&amp;calD=".$j."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
		$calendar_days['WEEK_VIEW_URL'] = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=week&amp;calD=".$j."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);

		//highlight selected day
		if( $j == $date['day'] )
		{
			$calendar_days['DAY_CLASS'] = 'highlight';
		}

		//highlight current day
		$test_start_hi_time = mktime( 0,0,0,$date['month_no'], $j, $date['year']) + date('Z');
		$test_end_hi_time = $test_start_hi_time + 86399;
		$test_hi_time = time() + $user->timezone + $user->dst;

		if( ($test_start_hi_time <= $test_hi_time) &&
		    ($test_end_hi_time >= $test_hi_time))
		{
			$calendar_days['HEADER_CLASS'] = 'highlight';
			$calendar_days['DAY_CLASS'] = 'highlight';
		}


		if ( $user_can_view_events && $auth->acl_get('u_viewprofile') )
		{
			// find birthdays
			$calendar_days['BIRTHDAYS'] = generate_birthday_list( $j, $date['month_no'], $date['year'] );
		}

		$template->assign_block_vars('calendar_days', $calendar_days);

		if ( $user_can_view_events )
		{
			//find any events on this day
			$start_temp_date = gmmktime(0,0,0,$date['month_no'], $j, $date['year'])  - $user->timezone - $user->dst;
			$end_temp_date = $start_temp_date + 86399;

			$sql = 'SELECT * FROM ' . CALENDAR_EVENTS_TABLE . '
					WHERE ( (event_access_level = 2) OR
						(event_access_level = 0 AND poster_id = '.$db->sql_escape($user->data['user_id']).' ) OR
						(event_access_level = 1 AND ('.$db->sql_escape($group_options).') ) ) AND
					((( event_start_time >= '.$db->sql_escape($start_temp_date).' AND event_start_time <= '.$db->sql_escape($end_temp_date).' ) OR
					 ( event_end_time > '.$db->sql_escape($start_temp_date).' AND event_end_time <= '.$db->sql_escape($end_temp_date).' ) OR
					 ( event_start_time < '.$db->sql_escape($start_temp_date).' AND event_end_time > '.$db->sql_escape($end_temp_date)." )) OR
					 ((event_all_day = 1) AND (event_day LIKE '" . $db->sql_escape(sprintf('%2d-%2d-%4d', $j, $date['month_no'], $date['year'])) . "'))) ORDER BY event_start_time ASC";


			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$event_output['COLOR'] = $available_etype_colors[$row['etype_id']];
				$event_output['IMAGE'] = $available_etype_images[$row['etype_id']];
				$event_output['EVENT_URL'] = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=event&amp;calEid=".$row['event_id']);

				// if the event was created by this user
				// display it in bold
				if( $user->data['user_id'] == $row['poster_id'] )
				{
					$event_output['DISPLAY_BOLD'] = true;
				}
				else
				{
					$event_output['DISPLAY_BOLD'] = false;
				}

				$event_output['ETYPE_DISPLAY_NAME'] = $available_etype_display_names[$row['etype_id']];

				$event_output['FULL_SUBJECT'] = censor_text($row['event_subject']);
				$event_output['EVENT_SUBJECT'] = $event_output['FULL_SUBJECT'];
				if( $subject_limit > 0 )
				{
					if(utf8_strlen($event_output['EVENT_SUBJECT']) > $subject_limit)
					{
						$event_output['EVENT_SUBJECT'] = truncate_string($event_output['EVENT_SUBJECT'], $subject_limit) . '...';
					}
				}
				$template->assign_block_vars('calendar_days.events', $event_output);
			}
			$db->sql_freeresult($result);
		}

	}
	$counter--;
	$dummy_end_day_count = 6 - ($counter % 7);
	for ($i = 1; $i <= $dummy_end_day_count; $i++)
	{
		// create dummy days (place holders)
		$calendar_days['START_WEEK'] = false;
		if( $i == $dummy_end_day_count )
		{
			$calendar_days['END_WEEK'] = true;
		}
		else
		{
			$calendar_days['END_WEEK'] = false;
		}
		$calendar_days['HEADER_CLASS'] = '';
		$calendar_days['DAY_CLASS'] = '';
		$calendar_days['NUMBER'] = 0;
		$calendar_days['DUMMY_DAY'] = true;
		$calendar_days['ADD_LINK'] = '';
		$calendar_days['BIRTHDAYS'] = '';
		$template->assign_block_vars('calendar_days', $calendar_days);
	}





	// A typical usage for sending your variables to your template.
	$template->assign_vars(array(
		'CALENDAR_HEADER'	=> $calendar_header_txt,
		'DAY_IMG'			=> $user->img('button_calendar_day', 'DAY'),
		'WEEK_IMG'			=> $user->img('button_calendar_week', 'WEEK'),
		'CALENDAR_PREV'		=> $prev_link,
		'CALENDAR_NEXT'		=> $next_link,
		'CALENDAR_VIEW_OPTIONS' => $mode_sel_code.' '.$month_sel_code.' '.$day_sel_code.' '.$year_sel_code,
		'SUNDAY'			=> $sunday,
		'MONDAY'			=> $monday,
		'TUESDAY'			=> $tuesday,
		'WEDNESDAY'			=> $wednesday,
		'THURSDAY'			=> $thursday,
		'FRIDAY'			=> $friday,
		'SATURDAY'			=> $saturday,
		'S_POST_ACTION'		=> append_sid("{$phpbb_root_path}calendar.$phpEx" ),
	));



}

function calendar_display_week( $index_display )
{
	global $auth, $db, $user, $config, $template, $date, $month_names, $available_etype_colors, $available_etype_images, $available_etype_display_names, $month_sel_code, $day_sel_code, $year_sel_code, $mode_sel_code;
	global $phpEx, $phpbb_root_path;

	init_calendar_data();
	init_view_selection_code("week");
	$index_display_var = request_var('indexWk', 0);

	// create next and prev links
	set_date_prev_next( "week" );
	$prev_link = "";
	$next_link = "";

	//find the first day of the week
	if( $index_display == 0 && $index_display_var == 0)
	{
		$first_day_of_week = get_calendar_config_value("first_day_of_week", 0);
		$prev_link = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=week&amp;calD=".$date['prev_day']."&amp;calM=".$date['prev_month']."&amp;calY=".$date['prev_year']);
		$next_link = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=week&amp;calD=".$date['next_day']."&amp;calM=".$date['next_month']."&amp;calY=".$date['next_year']);
	}
	else
	{
		/* get current weekday so we show this upcoming week's events */
		$temp_date = time() + $user->timezone + $user->dst;
		$first_day_of_week = gmdate("w", $temp_date);

		$prev_link = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=week&amp;calD=".$date['prev_day']."&amp;calM=".$date['prev_month']."&amp;calY=".$date['prev_year']."&amp;indexWk=1");
		$next_link = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=week&amp;calD=".$date['next_day']."&amp;calM=".$date['next_month']."&amp;calY=".$date['next_year']."&amp;indexWk=1");
	}
	get_weekday_names( $first_day_of_week, $sunday, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday );

	$date['fday'] = get_fday($date['day'], $date['month_no'], $date['year'], $first_day_of_week);

	$number_days = 7;
	$calendar_header_txt = $user->lang['WEEK_OF'] . sprintf($user->lang['LOCAL_DATE_FORMAT'], $user->lang['datetime'][$date['month']], $date['day'], $date['year'] );
	$subject_limit = get_calendar_config_value("display_truncated_name", 0);

	$counter = 0;
	$j_start = $date['day'];
	if( $date['fday']<7 )
	{
		$j_start = $date['day']-$date['fday'];
	}
	$prev_month_no = $date['month_no'] - 1;
	$prev_year_no = $date['year'];
	if( $prev_month_no == 0 )
	{
		$prev_month_no = 12;
		$prev_year_no--;
	}
	$prev_month_day_count = date("t",mktime( 0,0,0,$prev_month_no, 25, $prev_year_no));
	// how many days are in this month?
	$month_day_count = date("t",mktime(0,0,0,$date['month_no'], 25, $date['year']));
	$next_month_no = $date['month_no'] + 1;
	$next_year_no = $date['year'];
	if( $next_month_no == 13 )
	{
		$next_month_no = 1;
		$next_year_no++;
	}


	// Is the user able to view ANY events?
	$user_can_view_events = false;
	if ( $auth->acl_get('u_calendar_view_events') )
	{
		$user_can_view_events = true;

		/* find the group options here so we do not have to look them up again for each day */
		$group_options = get_sql_group_options($user->data['user_id']);
	}

	for ($j = $j_start; $j < $j_start+7; $j++, $counter++)
	{
		if( $j < 1 )
		{
			$true_j = $prev_month_day_count + $j;
			$true_m = $prev_month_no;
			$true_y = $prev_year_no;
		}
		else if ($j > $month_day_count )
		{
			$true_j = $j - $month_day_count;
			$true_m = $next_month_no;
			$true_y = $next_year_no;
		}
		else
		{
			$true_j = $j;
			$true_m = $date['month_no'];
			$true_y = $date['year'];
		}

		// start creating the data for the real days
		$calendar_days['START_WEEK'] = false;
		$calendar_days['END_WEEK'] = false;
		$calendar_days['DUMMY_DAY'] = false;
		$calendar_days['HEADER_CLASS'] = '';
		$calendar_days['DAY_CLASS'] = '';
		$calendar_days['NUMBER'] = 0;
		$calendar_days['ADD_LINK'] = '';
		$calendar_days['BIRTHDAYS'] = '';

		if($counter % 7 == 0)
		{
			$calendar_days['START_WEEK'] = true;
		}
		if($counter % 7 == 6 )
		{
			$calendar_days['END_WEEK'] = true;
		}
		$calendar_days['NUMBER'] = $true_j;
		if( $auth->acl_get('u_calendar_create_events') )
		{
			$calendar_days['ADD_LINK'] = append_sid("{$phpbb_root_path}calendarpost.$phpEx", "mode=post&amp;calD=".$true_j."&amp;calM=".$true_m."&amp;calY=".$true_y);
		}
		$calendar_days['DAY_VIEW_URL'] = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=day&amp;calD=".$true_j."&amp;calM=".$true_m."&amp;calY=".$true_y);
		$calendar_days['MONTH_VIEW_URL'] = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=month&amp;calD=".$true_j."&amp;calM=".$true_m."&amp;calY=".$true_y);

		if( ($true_j == $date['day']) &&
		    ($true_m == $date['month_no']) &&
		    ($true_y == $date['year']) )
		{
			$calendar_days['DAY_CLASS'] = 'highlight';
		}

		//highlight current day
		$test_start_hi_time = mktime( 0,0,0,$true_m, $true_j, $true_y) + date('Z');
		$test_end_hi_time = $test_start_hi_time + 86399;
		$test_hi_time = time() + $user->timezone + $user->dst;

		if( ($test_start_hi_time <= $test_hi_time) &&
		    ($test_end_hi_time >= $test_hi_time))
		{
			$calendar_days['HEADER_CLASS'] = 'highlight';
			$calendar_days['DAY_CLASS'] = 'highlight';
		}
		if ( $user_can_view_events && $auth->acl_get('u_viewprofile') )
		{
			// find birthdays
			$calendar_days['BIRTHDAYS'] = generate_birthday_list( $true_j, $true_m, $true_y );
		}

		$template->assign_block_vars('calendar_days', $calendar_days);

		if ( $user_can_view_events )
		{
			//find any events on this day
			$start_temp_date = gmmktime(0,0,0,$true_m, $true_j, $true_y)  - $user->timezone - $user->dst;

			$end_temp_date = $start_temp_date + 86399;

			$sql = 'SELECT * FROM ' . CALENDAR_EVENTS_TABLE . '
					WHERE ( (event_access_level = 2) OR
							(event_access_level = 0 AND poster_id = '.$db->sql_escape($user->data['user_id']).' ) OR
							(event_access_level = 1 AND ('.$db->sql_escape($group_options).') ) ) AND
						((( event_start_time >= '.$db->sql_escape($start_temp_date).' AND event_start_time <= '.$db->sql_escape($end_temp_date).' ) OR
						 ( event_end_time > '.$db->sql_escape($start_temp_date).' AND event_end_time <= '.$db->sql_escape($end_temp_date).' ) OR
						 ( event_start_time < '.$db->sql_escape($start_temp_date).' AND event_end_time > '.$db->sql_escape($end_temp_date)." )) OR
						 ((event_all_day = 1) AND (event_day LIKE '" . $db->sql_escape(sprintf('%2d-%2d-%4d', $true_j, $true_m, $true_y)) . "'))) ORDER BY event_start_time ASC";


			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$event_output['COLOR'] = $available_etype_colors[$row['etype_id']];
				$event_output['IMAGE'] = $available_etype_images[$row['etype_id']];
				$event_output['EVENT_URL'] = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=event&amp;calEid=".$row['event_id']);

				// if the event was created by this user
				// display it in bold
				if( $user->data['user_id'] == $row['poster_id'] )
				{
					$event_output['DISPLAY_BOLD'] = true;
				}
				else
				{
					$event_output['DISPLAY_BOLD'] = false;
				}
				$event_output['ETYPE_DISPLAY_NAME'] = $available_etype_display_names[$row['etype_id']];


				$event_output['FULL_SUBJECT'] = censor_text($row['event_subject']);
				$event_output['EVENT_SUBJECT'] = $event_output['FULL_SUBJECT'];
				if( $subject_limit > 0 )
				{
					if(utf8_strlen($event_output['EVENT_SUBJECT']) > $subject_limit)
					{
						$event_output['EVENT_SUBJECT'] = truncate_string($event_output['EVENT_SUBJECT'], $subject_limit) . '...';
					}
				}

				$event_output['SHOW_TIME'] = true;
				if( $row['event_all_day'] == 1 )
				{
					$event_output['ALL_DAY'] = true;
				}
				else
				{
					$event_output['ALL_DAY'] = false;
					$event_output['START_TIME'] = $user->format_date($row['event_start_time']);
					$event_output['END_TIME'] = $user->format_date($row['event_end_time']);
				}

				$template->assign_block_vars('calendar_days.events', $event_output);
			}
			$db->sql_freeresult($result);
		}

	}


	// A typical usage for sending your variables to your template.
	$template->assign_vars(array(
			'CALENDAR_HEADER'	=> $calendar_header_txt,
			'DAY_IMG'			=> $user->img('button_calendar_day', 'DAY'),
			'MONTH_IMG'			=> $user->img('button_calendar_month', 'MONTH'),
			'CALENDAR_PREV'		=> $prev_link,
			'CALENDAR_NEXT'		=> $next_link,
			'CALENDAR_VIEW_OPTIONS' => $mode_sel_code.' '.$month_sel_code.' '.$day_sel_code.' '.$year_sel_code,
			'SUNDAY'			=> $sunday,
			'MONDAY'			=> $monday,
			'TUESDAY'			=> $tuesday,
			'WEDNESDAY'			=> $wednesday,
			'THURSDAY'			=> $thursday,
			'FRIDAY'			=> $friday,
			'SATURDAY'			=> $saturday,
			'S_POST_ACTION'		=> append_sid("{$phpbb_root_path}calendar.$phpEx" ),
	));

}

function calendar_display_day()
{
	global $auth, $db, $user, $config, $template, $date, $available_etype_colors, $available_etype_images, $available_etype_display_names, $month_sel_code, $day_sel_code, $year_sel_code, $mode_sel_code;
	global $phpEx, $phpbb_root_path;

	init_calendar_data();
	init_view_selection_code("day");

	// create next and prev links
	set_date_prev_next( "day" );
	$prev_link = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=day&amp;calD=".$date['prev_day']."&amp;calM=".$date['prev_month']."&amp;calY=".$date['prev_year']);
	$next_link = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=day&amp;calD=".$date['next_day']."&amp;calM=".$date['next_month']."&amp;calY=".$date['next_year']);

	$calendar_header_txt = $user->lang['DAY_OF'] . sprintf($user->lang['LOCAL_DATE_FORMAT'], $user->lang['datetime'][$date['month']], $date['day'], $date['year'] );
	$subject_limit = get_calendar_config_value("display_truncated_name", 0);

	$hour_mode = get_calendar_config_value('hour_mode', '12');
	if( $hour_mode == 12 )
	{
		for( $i = 0; $i < 24; $i++ )
		{
			$time_header['TIME'] = $i % 12;
			if( $time_header['TIME'] == 0 )
			{
				$time_header['TIME'] = 12;
			}
			$time_header['AM_PM'] = $user->lang['PM'];
			if( $i < 12 )
			{
				$time_header['AM_PM'] = $user->lang['AM'];
			}
			$template->assign_block_vars('time_headers', $time_header);
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
			$time_header['TIME'] = $o . $i;
			$time_header['AM_PM'] = "";
			$template->assign_block_vars('time_headers', $time_header);
		}
	}

    $event_counter = 0;
	// Is the user able to view ANY events?
	if ( $auth->acl_get('u_calendar_view_events') )
	{
		// find birthdays
		if( $auth->acl_get('u_viewprofile') )
		{
			$birthday_list = generate_birthday_list( $date['day'], $date['month_no'], $date['year'] );
			if( $birthday_list != "" )
			{
				$events['PRE_PADDING'] = "";
				$events['PADDING'] = "96";
				$events['DATA'] = $birthday_list;
				$events['POST_PADDING'] = "";
				$template->assign_block_vars('events', $events);
				$event_counter++;
			}
		}


		//find any events on this day
		$start_temp_date = gmmktime(0,0,0,$date['month_no'], $date['day'], $date['year'])  - $user->timezone - $user->dst;
		$end_temp_date = $start_temp_date + 86399;


		$group_options = get_sql_group_options($user->data['user_id']);
		$sql = 'SELECT * FROM ' . CALENDAR_EVENTS_TABLE . '
				WHERE ( (event_access_level = 2) OR
				        (event_access_level = 0 AND poster_id = '.$db->sql_escape($user->data['user_id']).' ) OR
				        (event_access_level = 1 AND ('.$db->sql_escape($group_options).') ) ) AND
				((( event_start_time >= '.$db->sql_escape($start_temp_date).' AND event_start_time <= '.$db->sql_escape($end_temp_date).' ) OR
				 ( event_end_time > '.$db->sql_escape($start_temp_date).' AND event_end_time <= '.$db->sql_escape($end_temp_date).' ) OR
				 ( event_start_time < '.$db->sql_escape($start_temp_date).' AND event_end_time > '.$db->sql_escape($end_temp_date)." )) OR
				 ((event_all_day = 1) AND (event_day LIKE '" . $db->sql_escape(sprintf('%2d-%2d-%4d', $date['day'], $date['month_no'], $date['year'])) . "'))) ORDER BY event_start_time ASC";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$pre_padding = 0;
			$padding = 0;
			$post_padding = 0;
			$events['PRE_PADDING'] = "";
			$events['PADDING'] = "";
			$events['POST_PADDING'] = "";
			$events['COLOR'] = $available_etype_colors[$row['etype_id']];
			$events['IMAGE'] = $available_etype_images[$row['etype_id']];
			$events['EVENT_URL'] = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=event&amp;calEid=".$row['event_id']);
			// if the event was created by this user
			// display it in bold
			if( $user->data['user_id'] == $row['poster_id'] )
			{
				$events['DISPLAY_BOLD'] = true;
			}
			else
			{
				$events['DISPLAY_BOLD'] = false;
			}

			$events['ETYPE_DISPLAY_NAME'] = $available_etype_display_names[$row['etype_id']];

			$events['FULL_SUBJECT'] = censor_text($row['event_subject']);
			$events['EVENT_SUBJECT'] = $events['FULL_SUBJECT'];
			if( $subject_limit > 0 )
			{
				if(utf8_strlen($events['EVENT_SUBJECT']) > $subject_limit)
				{
					$events['EVENT_SUBJECT'] = truncate_string($events['EVENT_SUBJECT'], $subject_limit) . '...';
				}
			}

			if( $row['event_all_day'] == 1 )
			{
				$events['ALL_DAY'] = true;
				$events['PADDING'] = "96";
			}
			else
			{
				$events['ALL_DAY'] = false;
				$events['START_TIME'] = $user->format_date($row['event_start_time']);
				$events['END_TIME'] = $user->format_date($row['event_end_time']);

				if( $row['event_start_time'] > $start_temp_date )
				{
					// find pre-padding value...
					$start_diff = $row['event_start_time'] - $start_temp_date;
					$pre_padding = round($start_diff/900);
					if( $pre_padding > 0 )
					{
						$events['PRE_PADDING'] = $pre_padding;
					}
				}
				if( $row['event_end_time'] < $end_temp_date )
				{
					// find pre-padding value...
					$end_diff = $end_temp_date - $row['event_end_time'];
					$post_padding = round($end_diff/900);
					if( $post_padding > 0 )
					{
						$events['POST_PADDING'] = $post_padding;
					}
				}
				$events['PADDING'] = 96 - $pre_padding - $post_padding;

			}
			$template->assign_block_vars('events', $events);
			$event_counter++;
		}
		$db->sql_freeresult($result);
	}

	$week_view_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=week&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
	$month_view_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=month&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
	$add_event_url = "";
	if( $auth->acl_get('u_calendar_create_events') )
	{
		$add_event_url = append_sid("{$phpbb_root_path}calendarpost.$phpEx", "mode=post&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
	}

	// A typical usage for sending your variables to your template.
	$template->assign_vars(array(
		'CALENDAR_HEADER'	=> $calendar_header_txt,
		'WEEK_IMG'			=> $user->img('button_calendar_week', 'WEEK'),
		'MONTH_IMG'			=> $user->img('button_calendar_month', 'MONTH'),
		'ADD_LINK'			=> $add_event_url,
		'WEEK_VIEW_URL'		=> $week_view_url,
		'MONTH_VIEW_URL'	=> $month_view_url,
		'CALENDAR_PREV'		=> $prev_link,
		'CALENDAR_NEXT'		=> $next_link,
		'CALENDAR_VIEW_OPTIONS' => $mode_sel_code.' '.$month_sel_code.' '.$day_sel_code.' '.$year_sel_code,
		'S_POST_ACTION'		=> append_sid("{$phpbb_root_path}calendar.$phpEx" ),
		'EVENT_COUNT'		=> $event_counter,
	));


}

function calendar_display_event()
{
	global $auth, $db, $user, $config, $template, $date, $available_etype_colors, $available_etype_images, $available_etype_display_names;
	global $phpEx, $phpbb_root_path;

	init_calendar_data();

	$event_id = request_var('calEid', 0);
	$event_display_name = "";
	$event_color = "";
	$event_image = "";
	$event_details = "";
	$all_day = 1;
	$start_date_txt = "";
	$end_date_txt = "";
	$subject="";
	$message="";
	$back_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year'] );
	if( $event_id > 0 )
	{
		$sql = 'SELECT * FROM ' . CALENDAR_EVENTS_TABLE . '
				WHERE event_id = '.$db->sql_escape($event_id);
		$result = $db->sql_query($sql);
		$event_data = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if( !$event_data )
		{
			trigger_error( 'INVALID_EVENT' );
		}

		// Is the user able to view ANY events?
		if ( !$auth->acl_get('u_calendar_view_events') )
		{
			trigger_error( 'USER_CANNOT_VIEW_EVENT' );
		}
		// Is user authorized to view THIS event?
		$user_auth_for_event = is_user_authorized_to_view_event( $user->data['user_id'], $event_data);
		if( $user_auth_for_event == 0 )
		{
			trigger_error( 'PRIVATE_EVENT' );
		}



		$start_date_txt = $user->format_date($event_data['event_start_time']);
		$end_date_txt = $user->format_date($event_data['event_end_time']);

		// translate event start and end time into user's timezone
		$event_start = $event_data['event_start_time'] + $user->timezone + $user->dst;
		$event_end = $event_data['event_end_time'] + $user->timezone + $user->dst;

		if( $event_data['event_all_day'] == 1 )
		{
			// All day event - find the string for the event day
			if ($event_data['event_day'])
			{
				list($eday['eday_day'], $eday['eday_month'], $eday['eday_year']) = explode('-', $event_data['event_day']);

				$event_days_time = gmmktime(0,0,0,$eday['eday_month'], $eday['eday_day'], $eday['eday_year'])- $user->timezone - $user->dst;
				$start_date_txt = $user->format_date($event_days_time);
				$date['day'] = $eday['eday_day'];
				$date['month_no'] = $eday['eday_month'];
				$date['year'] = $eday['eday_year'];
			}
			else
			{
				// We should never get here
				// (this would be an all day event with no specified day for the event)
				$start_date_txt = "";
			}
		}
		else
		{
			$all_day = 0;
			$date['day'] = gmdate("d", $event_start);
			$date['month_no'] = gmdate("n", $event_start);
			$date['year']	=	gmdate('Y', $event_start);
		}
		$back_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year'] );

		$event_body = $event_data['event_body'];
		$event_data['bbcode_options'] = (($event_data['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +    (($event_data['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) +     (($event_data['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);

		$message = generate_text_for_display($event_body, $event_data['bbcode_uid'], $event_data['bbcode_bitfield'], $event_data['bbcode_options']);
		$event_display_name = $available_etype_display_names[$event_data['etype_id']];
		$event_color = $available_etype_colors[$event_data['etype_id']];
		$event_image = $available_etype_images[$event_data['etype_id']];

		$subject = censor_text($event_data['event_subject']);

		$poster_url = '';
		$invite_list = '';
		get_event_invite_list_and_poster_url($event_data, $poster_url, $invite_list );

		$edit_url = "";
		if( $user->data['is_registered'] && $auth->acl_get('u_calendar_edit_events') &&
		    (($user->data['user_id'] == $event_data['poster_id'])|| $auth->acl_get('m_calendar_edit_other_users_events') ))
		{
			$edit_url = append_sid("{$phpbb_root_path}calendarpost.$phpEx", "mode=edit&amp;calEid=".$event_id."&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
		}
		$delete_url = "";
		if( $user->data['is_registered'] && $auth->acl_get('u_calendar_delete_events') &&
		    (($user->data['user_id'] == $event_data['poster_id'])|| $auth->acl_get('m_calendar_delete_other_users_events') ))

		{
			$delete_url = append_sid("{$phpbb_root_path}calendarpost.$phpEx", "mode=delete&amp;calEid=".$event_id."&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
		}

		$add_event_url = "";
		if( $auth->acl_get('u_calendar_create_events') )
		{
			$add_event_url = append_sid("{$phpbb_root_path}calendarpost.$phpEx", "mode=post&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
		}
		$day_view_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=day&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
		$week_view_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=week&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);
		$month_view_url = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=month&amp;calD=".$date['day']."&amp;calM=".$date['month_no']."&amp;calY=".$date['year']);

		$template->assign_vars(array(
			'U_CALENDAR'		=> $back_url,
			'DAY_IMG'			=> $user->img('button_calendar_day', 'DAY'),
			'WEEK_IMG'			=> $user->img('button_calendar_week', 'WEEK'),
			'MONTH_IMG'			=> $user->img('button_calendar_month', 'MONTH'),
			'ETYPE_DISPLAY_NAME'=> $event_display_name,
			'EVENT_COLOR'		=> $event_color,
			'EVENT_IMAGE'		=> $event_image,
			'SUBJECT'			=> $subject,
			'MESSAGE'			=> $message,
			'START_DATE'		=> $start_date_txt,
			'END_DATE'			=> $end_date_txt,
			'POSTER'			=> $poster_url,
			'ALL_DAY'			=> $all_day,
			'INVITED'			=> $invite_list,
			'U_EDIT'			=> $edit_url,
			'U_DELETE'			=> $delete_url,
			'DAY_IMG'			=> $user->img('button_calendar_day', 'DAY'),
			'WEEK_IMG'			=> $user->img('button_calendar_week', 'WEEK'),
			'MONTH_IMG'			=> $user->img('button_calendar_month', 'MONTH'),
			'ADD_LINK'			=> $add_event_url,
			'DAY_VIEW_URL'		=> $day_view_url,
			'WEEK_VIEW_URL'		=> $week_view_url,
			'MONTH_VIEW_URL'	=> $month_view_url,

			)
		);
	}
}

function calendar_display_calendar_on_index()
{
	global $auth, $db, $user, $config, $template;

	$user->setup('calendar');

	//find the first day of the week
	$index_display_week = get_calendar_config_value( "index_display_week", 0 );
	if( $index_display_week === "1" )
	{
		$template->assign_vars(array(
			'S_CALENDAR_WEEK'	=> true,
		));
		calendar_display_week( 1 );
	}
	else
	{
		//see if we should display X number of upcoming events
		$index_display_next_events = get_calendar_config_value( "index_display_next_events", 0 );
		$s_next_events = false;
		if( $index_display_next_events > 0 )
		{
			$s_next_events = true;
		}

		$template->assign_vars(array(
			'S_CALENDAR_WEEK'	=> false,
			'S_CALENDAR_NEXT_EVENTS'	=> $s_next_events,
		));
		display_next_events( $index_display_next_events );
	}
}

function display_next_events( $x )
{
	global $auth, $db, $user, $config, $template, $date, $available_etype_colors, $available_etype_images, $available_etype_display_names, $month_sel_code, $day_sel_code, $year_sel_code, $mode_sel_code;

	global $phpEx, $phpbb_root_path;

	// Is the user able to view ANY events?
	$user_can_view_events = false;
	if ( $auth->acl_get('u_calendar_view_events') )
	{

		init_calendar_data();
		$subject_limit = get_calendar_config_value("display_truncated_name", 0);
		$group_options = get_sql_group_options($user->data['user_id']);

		$start_temp_date = time();
		$end_temp_date = $start_temp_date + 31536000;
		// find all day events that are still taking place
		$sort_timestamp_cutoff = $start_temp_date - 86400+1;


		// don't list events that are more than 1 year in the future
		$sql = 'SELECT * FROM ' . CALENDAR_EVENTS_TABLE . '
				WHERE ( (event_access_level = 2) OR
					(event_access_level = 0 AND poster_id = '.$db->sql_escape($user->data['user_id']).' ) OR
					(event_access_level = 1 AND ('.$db->sql_escape($group_options).') ) ) AND
				((( event_start_time >= '.$db->sql_escape($start_temp_date).' AND event_start_time <= '.$db->sql_escape($end_temp_date).' ) OR
				 ( event_end_time > '.$db->sql_escape($start_temp_date).' AND event_end_time <= '.$db->sql_escape($end_temp_date).' ) OR
				 ( event_start_time < '.$db->sql_escape($start_temp_date).' AND event_end_time > '.$db->sql_escape($end_temp_date)." )) OR (sort_timestamp > ".$db->sql_escape($sort_timestamp_cutoff)." AND event_all_day = 1) ) ORDER BY sort_timestamp ASC";
		$result = $db->sql_query_limit($sql, $x, 0);
		while ($row = $db->sql_fetchrow($result))
		{
			$events['EVENT_URL'] = append_sid("{$phpbb_root_path}calendar.$phpEx", "view=event&amp;calEid=".$row['event_id']);
			$events['IMAGE'] = $available_etype_images[$row['etype_id']];
			$events['COLOR'] = $available_etype_colors[$row['etype_id']];
			$events['ETYPE_DISPLAY_NAME'] = $available_etype_display_names[$row['etype_id']];

			$events['FULL_SUBJECT'] = censor_text($row['event_subject']);
			$events['SUBJECT'] = $events['FULL_SUBJECT'];
			if( $subject_limit > 0 )
			{
				if(utf8_strlen($events['SUBJECT']) > $subject_limit)
				{
					$events['SUBJECT'] = truncate_string($events['SUBJECT'], $subject_limit) . '...';
				}
			}

			$poster_url = '';
			$invite_list = '';
			get_event_invite_list_and_poster_url($row, $poster_url, $invite_list );
			$events['POSTER'] = $poster_url;
			$events['INVITED'] = $invite_list;
			if( $row['event_all_day'] == 1 )
			{
				list($eday['eday_day'], $eday['eday_month'], $eday['eday_year']) = explode('-', $row['event_day']);
				$row['event_start_time'] = gmmktime(0,0,0,$eday['eday_month'], $eday['eday_day'], $eday['eday_year'])- $user->timezone - $user->dst;
				$row['event_end_time'] = $row['event_start_time']+86399;
			}
			$events['START_TIME'] = $user->format_date($row['event_start_time']);
			$events['END_TIME'] = $user->format_date($row['event_end_time']);
			$template->assign_block_vars('events', $events);
		}
		$db->sql_freeresult($result);
	}
}

function is_user_authorized_to_view_event($user_id, $event_data)
{
	global $auth, $db;
	$user_auth_for_event = 0;

	switch( $event_data['event_access_level'] )
	{
		case 0:
			// personal event... only event creator is invited
			if( $user_id === $event_data['poster_id'] )
			{
				$user_auth_for_event = 1;
			}
			break;
		case 1:
			// group event... only members of specified group are invited
			// is this user a member of the group?
			$sql = 'SELECT g.group_id
					FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . ' ug
					WHERE ug.user_id = '.$db->sql_escape($user_id).'
						AND g.group_id = ug.group_id
						AND g.group_id = '.$db->sql_escape($event_data['group_id']).'
						AND ug.user_pending = 0';
			$result = $db->sql_query($sql);
			if( $result )
			{
				$group_data = $db->sql_fetchrow($result);
				if( $group_data['group_id'] == $event_data['group_id'] )
				{
					$user_auth_for_event = 1;
				}
			}
			$db->sql_freeresult($result);
			break;
		case 2:
			// public event... everyone is invited
			$user_auth_for_event = 1;
			break;
	}
	return $user_auth_for_event;
}

function generate_birthday_list( $day, $month, $year )
{
	global $db, $user, $config;

	$birthday_list = "";
	if ($config['load_birthdays'] && $config['allow_birthdays'])
	{
		$sql = 'SELECT user_id, username, user_colour, user_birthday
				FROM ' . USERS_TABLE . "
				WHERE user_birthday LIKE '" . $db->sql_escape(sprintf('%2d-%2d-', $day, $month)) . "%'
				AND user_type IN (" . USER_NORMAL . ', ' . USER_FOUNDER . ')';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			// TBD TRANSLATION ISSUE HERE!!!
			$birthday_list .= (($birthday_list != '') ? ', ' : '') . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);
			if ($age = (int) substr($row['user_birthday'], -4))
			{
				// TBD TRANSLATION ISSUE HERE!!!
				$birthday_list .= ' (' . ($year - $age) . ')';
			}
		}
		if( $birthday_list != "" )
		{
			// TBD TRANSLATION ISSUE HERE!!!
			$birthday_list = $user->lang['BIRTHDAYS'].": ". $birthday_list;
		}
		$db->sql_freeresult($result);
	}

	return $birthday_list;
}

/* we need to find out what group this user is a member of,
   and create a list of or options for an sql command so we can
   find events for all of the groups this user is a member of.
*/
function get_sql_group_options($user_id)
{
	global $auth, $db;

	// What groups is this user a member of?
	// Do the SQL thang
	$disp_hidden_groups = get_calendar_config_value("display_hidden_groups", 0);
	if( $disp_hidden_groups == 1 )
	{
		$sql = 'SELECT g.group_id, g.group_name, g.group_type
				FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . ' ug
				WHERE ug.user_id = '.$db->sql_escape($user_id).'
					AND g.group_id = ug.group_id
					AND ug.user_pending = 0
				ORDER BY g.group_type, g.group_name';
	}
	else
	{
		$sql = 'SELECT g.group_id, g.group_name, g.group_type
				FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . ' ug
				WHERE ug.user_id = '.$db->sql_escape($user_id).'
					AND g.group_id = ug.group_id' . ((!$auth->acl_gets('a_group', 'a_groupadd', 'a_groupdel')) ? ' AND g.group_type <> ' . GROUP_HIDDEN : '') . '
					AND ug.user_pending = 0
				ORDER BY g.group_type, g.group_name';
	}


	$result = $db->sql_query($sql);

	//$group_options = "group_id = 1 OR group_id = 2 OR group_id = 3"
	$group_options = '';
	while ($row = $db->sql_fetchrow($result))
	{
		if( $group_options != "" )
		{
			$group_options .= " OR ";
		}
		$group_options .= "group_id = ".$row['group_id'];
	}
	$db->sql_freeresult($result);
	return $group_options;
}

/* get the the invite list for an event and the poster url
*/
function get_event_invite_list_and_poster_url($event_data, &$poster_url, &$invite_list )
{
	global $auth, $db, $user, $config;
	global $phpEx, $phpbb_root_path;

	$sql = 'SELECT user_id, username, user_colour FROM ' . USERS_TABLE . '
			WHERE user_id = '.$db->sql_escape($event_data['poster_id']);
	$result = $db->sql_query($sql);
	$poster_data = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$poster_url = get_username_string( 'full', $event_data['poster_id'], $poster_data['username'], $poster_data['user_colour'] );

	$invite_list = "";

	switch( $event_data['event_access_level'] )
	{
		case 0:
			// personal event... only event creator is invited
			$invite_list = $poster_url;
			break;
		case 1:
			// group event... only members of specified group are invited
			$sql = 'SELECT group_name, group_type, group_colour FROM ' . GROUPS_TABLE . '
					WHERE group_id = '.$db->sql_escape($event_data['group_id']);
			$result = $db->sql_query($sql);
			$group_data = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$temp_list = (($group_data['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $group_data['group_name']] : $group_data['group_name']);
			$temp_url = append_sid("{$phpbb_root_path}memberlist.$phpEx", "mode=group&amp;g=".$event_data['group_id']);
			$temp_color_start = "";
			$temp_color_end = "";
			if( $group_data['group_colour'] !== "" )
			{
				$temp_color_start = "<span style='color:#".$group_data['group_colour']."'>";
				$temp_color_end = "</span>";
			}
			$invite_list = "<a href='".$temp_url."'>".$temp_color_start.$temp_list.$temp_color_end."</a>";
			break;
		case 2:
			// public event... everyone is invited
			$invite_list = $user->lang['EVERYONE'];
			break;
	}

}

/* initialize global variables used throughout
   all of the calendar functions
*/
function init_calendar_data()
{
	global $auth, $db, $user, $config, $date, $month_names, $available_etype_count, $available_etype_ids, $available_etype_full_names, $available_etype_colors, $available_etype_images, $available_etype_display_names;

	/* check to see if we have already initialized things */
	if( count($month_names) == 0 )
	{
		$month_names[1] = "January";
		$month_names[2] = "February";
		$month_names[3] = "March";
		$month_names[4] = "April";
		$month_names[5] = "May";
		$month_names[6] = "June";
		$month_names[7] = "July";
		$month_names[8] = "August";
		$month_names[9] = "September";
		$month_names[10] = "October";
		$month_names[11] = "November";
		$month_names[12] = "December";


		//find the available event types:
		$sql = 'SELECT * FROM ' . CALENDAR_EVENT_TYPES_TABLE . ' ORDER BY etype_index';
		$result = $db->sql_query($sql);
		$available_etype_count = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$available_etype_ids[$available_etype_count] = $row['etype_id'];
			$available_etype_full_names[$available_etype_count] = $row['etype_full_name'];
			$available_etype_colors[$row['etype_id']] = $row['etype_color'];
			$available_etype_images[$row['etype_id']] = $row['etype_image'];
			$available_etype_display_names[$row['etype_id']] = $row['etype_display_name'];
			$available_etype_count++;
		}
		$db->sql_freeresult($result);
	}

	// always refresh the date...

	//get the current date and set it into an array
	$date['day'] = request_var('calD', '');
	$date['month'] = request_var('calM', '');
	$date['month_no'] = request_var('calM', '');
	$date['year'] = request_var('calY', '');

	$temp_now_time = time() + $user->timezone + $user->dst;

	if( $date['day'] == "" )
	{
		$date['day'] = gmdate("d", $temp_now_time);
	}

	if( $date['month'] == "" )
	{
		$date['month'] = gmdate("F", $temp_now_time);
		$date['month_no'] = gmdate("n", $temp_now_time);
		$date['prev_month'] = gmdate("n", $temp_now_time) - 1;
		$date['next_month'] = gmdate("n", $temp_now_time) + 1;

	}
	else
	{
		$date['month'] = $month_names[$date['month']];
		$date['prev_month'] = $date['month'] - 1;
		$date['next_month'] = $date['month'] + 1;
	}

	if( $date['year'] == "" )
	{
		$date['year']	=	gmdate('Y', $temp_now_time);
	}
	// make sure this day exists - ie there is no February 31st.
	$number_days = gmdate("t", gmmktime( 0,0,0,$date['month_no'], 1, $date['year']));
	if( $number_days < $date['day'] )
	{
	    $date['day'] = $number_days;
	}
}

/* read the calendar configuration value for given variable name
*/
function get_calendar_config_value( $config_name, $default_val )
{
	global $auth, $db, $user;

	$config_val = $default_val;
	$sql = 'SELECT * FROM ' . CALENDAR_CONFIG_TABLE ."
			WHERE config_name ='".$db->sql_escape($config_name)."'";
	$result = $db->sql_query($sql);
	if($row = $db->sql_fetchrow($result))
	{
		$config_val = $row['config_value'];
	}
	$db->sql_freeresult($result);
	return $config_val;
}

/* "shift" names of weekdays depending on which day we want to display as the first day of the week
*/
function get_weekday_names( $first_day_of_week, &$sunday, &$monday, &$tuesday, &$wednesday, &$thursday, &$friday, &$saturday )
{
	global $user;
	switch( $first_day_of_week )
	{
		case 0:
			$sunday = $user->lang['datetime']['Sunday'];
			$monday = $user->lang['datetime']['Monday'];
			$tuesday = $user->lang['datetime']['Tuesday'];
			$wednesday = $user->lang['datetime']['Wednesday'];
			$thursday = $user->lang['datetime']['Thursday'];
			$friday = $user->lang['datetime']['Friday'];
			$saturday = $user->lang['datetime']['Saturday'];
			break;
		case 1:
			$saturday = $user->lang['datetime']['Sunday'];
			$sunday = $user->lang['datetime']['Monday'];
			$monday = $user->lang['datetime']['Tuesday'];
			$tuesday = $user->lang['datetime']['Wednesday'];
			$wednesday = $user->lang['datetime']['Thursday'];
			$thursday = $user->lang['datetime']['Friday'];
			$friday = $user->lang['datetime']['Saturday'];
			break;
		case 2:
			$friday = $user->lang['datetime']['Sunday'];
			$saturday = $user->lang['datetime']['Monday'];
			$sunday = $user->lang['datetime']['Tuesday'];
			$monday = $user->lang['datetime']['Wednesday'];
			$tuesday = $user->lang['datetime']['Thursday'];
			$wednesday = $user->lang['datetime']['Friday'];
			$thursday = $user->lang['datetime']['Saturday'];
			break;
		case 3:
			$thursday = $user->lang['datetime']['Sunday'];
			$friday = $user->lang['datetime']['Monday'];
			$saturday = $user->lang['datetime']['Tuesday'];
			$sunday = $user->lang['datetime']['Wednesday'];
			$monday = $user->lang['datetime']['Thursday'];
			$tuesday = $user->lang['datetime']['Friday'];
			$wednesday = $user->lang['datetime']['Saturday'];
			break;
		case 4:
			$wednesday = $user->lang['datetime']['Sunday'];
			$thursday = $user->lang['datetime']['Monday'];
			$friday = $user->lang['datetime']['Tuesday'];
			$saturday = $user->lang['datetime']['Wednesday'];
			$sunday = $user->lang['datetime']['Thursday'];
			$monday = $user->lang['datetime']['Friday'];
			$tuesday = $user->lang['datetime']['Saturday'];
			break;
		case 5:
			$tuesday = $user->lang['datetime']['Sunday'];
			$wednesday = $user->lang['datetime']['Monday'];
			$thursday = $user->lang['datetime']['Tuesday'];
			$friday = $user->lang['datetime']['Wednesday'];
			$saturday = $user->lang['datetime']['Thursday'];
			$sunday = $user->lang['datetime']['Friday'];
			$monday = $user->lang['datetime']['Saturday'];
			break;
		case 6:
			$monday = $user->lang['datetime']['Sunday'];
			$tuesday = $user->lang['datetime']['Monday'];
			$wednesday = $user->lang['datetime']['Tuesday'];
			$thursday = $user->lang['datetime']['Wednesday'];
			$friday = $user->lang['datetime']['Thursday'];
			$saturday = $user->lang['datetime']['Friday'];
			$sunday = $user->lang['datetime']['Saturday'];
			break;
	}
}

/* used to find info about the previous and next [day, week, or month]
*/
function set_date_prev_next( $view_mode )
{
	global $date;
	if( $view_mode === "month" )
	{
		$date['prev_year'] = $date['year'];
		$date['next_year'] = $date['year'];
		$date['prev_month'] = $date['month_no'] - 1;
		$date['next_month'] = $date['month_no'] + 1;
		if( $date['prev_month'] == 0 )
		{
			$date['prev_month'] = 12;
			$date['prev_year']--;
		}
		if( $date['next_month'] == 13 )
		{
			$date['next_month'] = 1;
			$date['next_year']++;
		}
	}
	else
	{
		$delta_time = 0;
		if( $view_mode === "week" )
		{
			// delta = 7 days
			$delta_time = 604800;
		}
		else if( $view_mode === "day" )
		{
			// delta = 1 day
			$delta_time = 86400;
		}
		// get timestamp of current view date:
		$display_day = gmmktime(0,0,0, $date['month_no'], $date['day'], $date['year']);
		$prev_day = $display_day - $delta_time;
		$next_day = $display_day + $delta_time;

		$date['prev_day'] = gmdate("d", $prev_day);
		$date['next_day'] = gmdate("d", $next_day);
		$date['prev_month'] = gmdate("n", $prev_day);
		$date['next_month'] = gmdate("n", $next_day);

		$date['prev_year'] = gmdate("Y", $prev_day);
		$date['next_year'] = gmdate("Y", $next_day);
	}
}

/* fday is used to determine in what day we are starting with */
function get_fday($day, $month, $year, $first_day_of_week)
{
	$fday = 0;

	// what day of the week are we starting on?
	if (phpversion() < '5.1')
	{
		switch(gmdate("l",gmmktime(0,0,0, $month, $day, $year)))
		{
			case "Monday":
				$fday = 1;
				break;
			case "Tuesday":
				$fday = 2;
				break;
			case "Wednesday":
				$fday = 3;
				break;
			case "Thursday":
				$fday = 4;
				break;
			case "Friday":
				$fday = 5;
				break;
			case "Saturday":
				$fday = 6;
				break;
			case "Sunday":
				$fday = 7;
				break;
		}
	}
	else
	{
		$fday = gmdate("N",gmmktime(0,0,0, $month, $day, $year));
	}
	$fday = $fday - $first_day_of_week;
	if( $fday < 0 )
	{
		$fday = $fday + 7;
	}
	return $fday;
}

/* Initialize the pulldown menus that allow the user
   to jump from one calendar display mode/time to another
*/
function init_view_selection_code( $view_mode )
{
	global $auth, $db, $user, $config, $date, $month_names, $month_sel_code, $day_sel_code, $year_sel_code, $mode_sel_code;

	// create CALENDAR_VIEW_OPTIONS
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


	$temp_year	=	gmdate('Y');

	$year_sel_code  = "<select name='calY' id='calY'>\n";
	for( $i = $temp_year-1; $i < ($temp_year+5); $i++ )
	{
		$year_sel_code .= "<option value='".$i."'>".$i."</option>\n";
	}
	$year_sel_code .= "</select>\n";

	$temp_find_str = "value='".$date['month_no']."'>";
	$temp_replace_str = "value='".$date['month_no']."' selected='selected'>";
	$month_sel_code = str_replace( $temp_find_str, $temp_replace_str, $month_sel_code );
	$temp_find_str = "value='".(int)$date['day']."'>";
	$temp_replace_str = "value='".(int)$date['day']."' selected='selected'>";
	$day_sel_code = str_replace( $temp_find_str, $temp_replace_str, $day_sel_code );
	$temp_find_str = "value='".$date['year']."'>";
	$temp_replace_str = "value='".$date['year']."' selected='selected'>";
	$year_sel_code = str_replace( $temp_find_str, $temp_replace_str, $year_sel_code );

	$mode_sel_code = "<select name='view' id='view'>\n";
	$mode_sel_code .= "<option value='month'>".$user->lang['MONTH']."</option>\n";
	$mode_sel_code .= "<option value='week'>".$user->lang['WEEK']."</option>\n";
	$mode_sel_code .= "<option value='day'>".$user->lang['DAY']."</option>\n";
	$mode_sel_code .= "</select>\n";
	$temp_find_str = "value='".$view_mode."'>";
	$temp_replace_str = "value='".$view_mode."' selected='selected'>";
	$mode_sel_code = str_replace( $temp_find_str, $temp_replace_str, $mode_sel_code );
}



function get_event_data( $id, &$event_data )
{
	global $auth, $db, $user;
	if( $id < 1 )
	{
		trigger_error('NO_EVENT');
	}
	$sql = 'SELECT * FROM ' . CALENDAR_EVENTS_TABLE . '
			WHERE event_id = '.$db->sql_escape($id);
	$result = $db->sql_query($sql);
	$event_data = $db->sql_fetchrow($result);
	if( !$event_data )
	{
		trigger_error('NO_EVENT');
	}

    $db->sql_freeresult($result);
}

/**
* Do the various checks required for removing event as well as removing it
* Note the caller of this function must make sure that the user has
* permission to delete the event before calling this function
*/
function handle_event_delete($event_id, &$event_data)
{
	global $user, $db, $auth, $date;
	global $phpbb_root_path, $phpEx;

	$s_hidden_fields = build_hidden_fields(array(
			'calEid'=> $event_id,
			'mode'	=> 'delete')
	);


	if (confirm_box(true))
	{
		// Delete event
		$sql = 'DELETE FROM ' . CALENDAR_EVENTS_TABLE . '
				WHERE event_id = '.$db->sql_escape($event_id);
		$db->sql_query($sql);
		$meta_info = append_sid("{$phpbb_root_path}calendar.$phpEx", "calM=".$date['month_no']."&amp;calY=".$date['year']);
		$message = $user->lang['EVENT_DELETED'];

		meta_refresh(3, $meta_info);
		$message .= '<br /><br />' . sprintf($user->lang['RETURN_CALENDAR'], '<a href="' . $meta_info . '">', '</a>');
		trigger_error($message);
	}
	else
	{
		confirm_box(false, $user->lang['DELETE_EVENT'], $s_hidden_fields);
	}
}

/* generates the selection code necessary for group selection when making new calendar posts
   by default no group is selected and the entire form item is disabled
*/
function posting_generate_group_selection_code( $user_id )
{
	global $auth, $db, $user, $config;

	$disp_hidden_groups = get_calendar_config_value("display_hidden_groups", 0);
	if( $disp_hidden_groups == 1 )
	{
		$sql = 'SELECT g.group_id, g.group_name, g.group_type
				FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . " ug
				WHERE ug.user_id = ". $db->sql_escape($user_id).'
					AND g.group_id = ug.group_id
					AND ug.user_pending = 0
				ORDER BY g.group_type, g.group_name';
	}
	else
	{
		$sql = 'SELECT g.group_id, g.group_name, g.group_type
				FROM ' . GROUPS_TABLE . ' g, ' . USER_GROUP_TABLE . " ug
				WHERE ug.user_id = ". $db->sql_escape($user_id)."
					AND g.group_id = ug.group_id" . ((!$auth->acl_gets('a_group', 'a_groupadd', 'a_groupdel')) ? ' 	AND g.group_type <> ' . GROUP_HIDDEN : '') . '
					AND ug.user_pending = 0
				ORDER BY g.group_type, g.group_name';
	}

	$result = $db->sql_query($sql);

	$group_sel_code = "<select name='calGroupId' id='calGroupId' disabled='disabled'>\n";
	while ($row = $db->sql_fetchrow($result))
	{
		$group_sel_code .= "<option value='" . $row['group_id'] . "'>" . (($row['group_type'] == GROUP_SPECIAL) ? $user->lang['G_' . $row['group_name']] : $row['group_name']) . "</option>\n";
	}
	$db->sql_freeresult($result);
	$group_sel_code .= "</select>\n";
	return $group_sel_code;
}

function prune_calendar()
{
	global $auth, $db, $user, $config, $phpEx, $phpbb_root_path;
	$prune_limit = get_calendar_config_value("prune_limit", 0);

	$config_name = "last_prune";
	$sql = 'UPDATE ' . CALENDAR_CONFIG_TABLE . '
			SET ' . $db->sql_build_array('UPDATE', array(
			'config_name'	=> $config_name,
			'config_value'	=> time() )) . "
			WHERE config_name = '".$config_name."'";
	$db->sql_query($sql);

	// delete events that have been over for $prune_limit seconds.
	$end_temp_date = time() - $prune_limit;

	// find all day events that finished before the prune limit
	$sort_timestamp_cutoff = $end_temp_date - 86400;

	$sql = 'DELETE FROM ' . CALENDAR_EVENTS_TABLE . '
				WHERE ( (event_all_day = 1 AND sort_timestamp < '.$db->sql_escape($sort_timestamp_cutoff).')
				OR (event_all_day = 0 AND event_end_time < '.$db->sql_escape($end_temp_date).') )';
	$db->sql_query($sql);
}

/**
* Fill smiley templates (or just the variables) with smilies, either in a window or inline
*/
function generate_calendar_smilies($mode)
{
	global $auth, $db, $user, $config, $template;
	global $phpEx, $phpbb_root_path;

	if ($mode == 'window')
	{
		page_header($user->lang['SMILIES']);

		$template->set_filenames(array(
			'body' => 'posting_smilies.html')
		);
	}

	$display_link = false;
	if ($mode == 'inline')
	{
		$sql = 'SELECT smiley_id
			FROM ' . SMILIES_TABLE . '
			WHERE display_on_posting = 0';
		$result = $db->sql_query_limit($sql, 1, 0, 3600);

		if ($row = $db->sql_fetchrow($result))
		{
			$display_link = true;
		}
		$db->sql_freeresult($result);
	}

	$last_url = '';

	$sql = 'SELECT *
		FROM ' . SMILIES_TABLE .
		(($mode == 'inline') ? ' WHERE display_on_posting = 1 ' : '') . '
		ORDER BY smiley_order';
	$result = $db->sql_query($sql, 3600);

	$smilies = array();
	while ($row = $db->sql_fetchrow($result))
	{
		if (empty($smilies[$row['smiley_url']]))
		{
			$smilies[$row['smiley_url']] = $row;
		}
	}
	$db->sql_freeresult($result);

	if (sizeof($smilies))
	{
		foreach ($smilies as $row)
		{
			$template->assign_block_vars('smiley', array(
				'SMILEY_CODE'	=> $row['code'],
				'A_SMILEY_CODE'	=> addslashes($row['code']),
				'SMILEY_IMG'	=> $phpbb_root_path . $config['smilies_path'] . '/' . $row['smiley_url'],
				'SMILEY_WIDTH'	=> $row['smiley_width'],
				'SMILEY_HEIGHT'	=> $row['smiley_height'],
				'SMILEY_DESC'	=> $row['emotion'])
			);
		}
	}

	if ($mode == 'inline' && $display_link)
	{
		$template->assign_vars(array(
			'S_SHOW_SMILEY_LINK' 	=> true,
			'U_MORE_SMILIES' 		=> append_sid("{$phpbb_root_path}calendarpost.$phpEx", 'mode=smilies'))
		);
	}

	if ($mode == 'window')
	{
		page_footer();
	}
}

?>
