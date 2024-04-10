<?php
// Version: 1.1; Help

function template_popup()
{
	global $context, $settings, $options, $txt;

	// Since this is a popup of its own we need to start the html, etc.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
		<title>', $context['page_title'], '</title>
		<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css" />
		<style type="text/css">';

	// Internet Explorer 4/5 and Opera 6 just don't do font sizes properly. (they are bigger...)
	if ($context['browser']['needs_size_fix'])
		echo '
			@import(', $settings['default_theme_url'], '/fonts-compat.css);';

	// Just show the help text and a "close window" link.
	echo '
		</style>
	</head>
	<body style="margin: 1ex;background-color: #E2DBBF;">
		<div class="border">
		<div class="subtitle"><!--no content--></div>
		<div class="row1-center">
		<div class="popuptext">
		<p>', $context['help_text'], '</p>
		<br />
		<p class="buttons"><a href="javascript:self.close();">', $txt[1006], '</a></p>
		</div>
		</div>
		<div class="block-foot"><!--no content--></div>
		</div>
	</body>
</html>';
}

function template_find_members()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<title>', $txt['find_members'], '</title>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
		<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css" />
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/script.js"></script>
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
			var membersAdded = [];
			function addMember(name)
			{
				var theTextBox = window.opener.document.getElementById("', $context['input_box_name'], '");

				if (typeof(membersAdded[name]) != "undefined")
					return;
				membersAdded[name] = true;

				if (theTextBox.value.length < 1)
					theTextBox.value = ', $context['quote_results'] ? '"\"" + name + "\""' : 'name', ';
				else
					theTextBox.value += "', $context['delimiter'], '" + ', $context['quote_results'] ? '"\"" + name + "\""' : 'name', ';

				window.focus();
			}
		// ]]></script>
	</head>
	<body style="margin: 1ex;background-color: #E2DBBF;">
		<form action="', $scripturl, '?action=findmember;sesc=', $context['session_id'], '" method="post" accept-charset="', $context['character_set'], '">
			<div class="border">
			<h1 class="title">', $txt['find_members'], '</h1>
			<h2 class="subtitle">', $txt['find_username'], ':</h2>
			<div class="row1-center">
			<input type="text" name="search" id="search" value="', isset($context['last_search']) ? $context['last_search'] : '', '" style="margin-top: 4px; width: 90%;" /><br />';

	// Only offer to search for buddies if we have some!
	if (!empty($context['show_buddies']))
		echo '<p class="smalltext"><label for="buddies"><input type="checkbox" class="check" name="buddies" id="buddies"', !empty($context['buddy_search']) ? ' checked="checked"' : '', ' /> ', $txt['find_buddies'], '</label></p>';
	else
		echo '';

	echo '
						<p class="smalltext"><em>', $txt['find_wildcards'], '</em></p>
					</div>
					<div class="subtitle" style="text-align:center;">
						<input type="submit" class="button" value="', $txt[182], '" />
						<input type="button" class="button" value="', $txt['find_close'], '" onclick="window.close();" />
					</div>
					</div>

			<br />

			<div class="border">
				<h3 class="title">', $txt['find_results'], '</h3>
				<div class="row1">';

	if (empty($context['results']))
		echo '
				<p>', $txt['find_no_results'], '</p>
				</div>';
	else
	{
	echo '<ul class="bullit-list">';
		$alternate = true;
		foreach ($context['results'] as $result)
		{
			echo '
				<li>
					<span class="float-r">
					<a href="', $result['href'], '" target="_blank"><img src="' . $settings['images_url'] . '/icons/profile_sm.gif" alt="' . $txt[27] . '" title="' . $txt[27] . '" border="0" /></a> 
					</span>
					<a href="javascript:void(0);" onclick="addMember(this.title); return false;" title="', $result['username'], '">', $result['name'], '</a>
					</li>';

			$alternate = !$alternate;
		}

		echo '	
		</ul>
		</div>
		<div class="subtitle">', $txt[139], ': ', $context['page_index'], '</div>';
	}

	echo '
			<div class="block-foot"><!--no content--></div>
			</div>
			<input type="hidden" name="input" value="', $context['input_box_name'], '" />
			<input type="hidden" name="delim" value="', $context['delimiter'], '" />
			<input type="hidden" name="quote" value="', $context['quote_results'] ? '1' : '0', '" />
		</form>';

	if (empty($context['results']))
		echo '
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
			document.getElementById("search").focus();
		// ]]></script>';

	echo '
	</body>
</html>';
}

// Top half of the help template.
function template_manual_above()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<div id="helpmenu">
		<ul class="help-menu">';

	$menu_items = array();
	foreach ($context['all_pages'] as $page_url => $page_txt)
	{
		if ($page_url == $context['current_page'])
			$menu_items[] = '<li class="help-menu-active"><strong>' . $txt['manual_index_' . $page_txt] . '</strong></li>';
		else
			$menu_items[] = '<li><a href="' . $scripturl . '?action=help;page=' . $page_url . '">' . $txt['manual_index_' . $page_txt] . '</a></li>';
	}
	echo implode('', $menu_items);

	echo '
		</ul>
		<div class="help-menu-border"><!--no content--></div>
		</div>';
}

function template_manual_below()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<div id="helpmenu-bottom">
		<ul class="help-menu">';

	$menu_items = array();
	foreach ($context['all_pages'] as $page_url => $page_txt)
	{
		if ($page_url == $context['current_page'])
			$menu_items[] = '<li class="help-menu-active"><strong>' . $txt['manual_index_' . $page_txt] . '</strong></li>';
		else
			$menu_items[] = '<li><a href="' . $scripturl . '?action=help;page=' . $page_url . '">' . $txt['manual_index_' . $page_txt] . '</a></li>';
	}
	echo implode('', $menu_items);

	echo '
		</ul>
		<div class="help-menu-border"><!--no content--></div>
		</div>';
}

// The introduction help page.
function template_manual_intro()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_index_you_have_arrived_part1'], '<a href="http://www.simplemachines.org/">', $txt['manual_index_you_have_arrived_link_site0'], '</a>', $txt['manual_index_you_have_arrived_part2'], '<a href="', $scripturl, '?action=help;page=index#board">', $txt['manual_index_you_have_arrived_link_site0_board'], '</a>', $txt['manual_index_you_have_arrived_part3'], '</p>
	<p>', $txt['manual_index_guest_permit_read_part1'], '<a href="', $scripturl, '?action=help;page=registering">', $txt['manual_index_guest_permit_read_link_registering'], '</a>', $txt['manual_index_guest_permit_read_part2'], '</p>
	<ol>
		<li><a href="', $scripturl, '?action=help;page=index#main">', $txt['manual_index_main_menu'], '</a></li>
		<li><a href="', $scripturl, '?action=help;page=index#board">', $txt['manual_index_sec_board_index'], '</a></li>
		<li><a href="', $scripturl, '?action=help;page=index#message">', $txt['manual_index_sec_msg_index'], '</a></li>
		<li><a href="', $scripturl, '?action=help;page=index#topic">', $txt['manual_index_sec_topic'], '</a></li>
	</ol>
	<h2 class="help-title"id="main">', $txt['manual_index_main_menu'], '</h2>
	<p>', $txt['manual_index_suppossing_guest'], '</p>
	<ul class="bullit-list">
		<li>', $txt['manual_index_home_desc_part1'], '<a href="', $scripturl, '?action=help;page=index#board">', $txt['manual_index_home_desc_link_board'], '</a>', $txt['manual_index_home_desc_part2'], '</li>
		<li>', $txt['manual_index_help_desc'], '</li>
		<li>', $txt['manual_index_search_desc_part1'], '<a href="', $scripturl, '?action=help;page=searching">', $txt['manual_index_search_desc_link_searching'], '</a>', $txt['manual_index_search_desc_part2'], '</li>
		<li>', $txt['manual_index_calendar_desc_part1'], '<a href="', $scripturl, '?action=help;page=post#calendar">', $txt['manual_index_calendar_desc_link_posting_calendar'], '</a>', $txt['manual_index_calendar_desc_part2'], '</li>
		<li>', $txt['manual_index_login_desc_part1'], '<a href="', $scripturl, '?action=help;page=loginout">', $txt['manual_index_login_desc_link_loginout'], '</a>', $txt['manual_index_login_desc_part2'], '</li>
		<li>', $txt['manual_index_register_desc_part1'], '<a href="', $scripturl, '?action=help;page=registering">', $txt['manual_index_register_desc_link_registering'], '</a>', $txt['manual_index_register_desc_part2'], '</li>
	</ul>
	<p>', $txt['manual_index_once_registered'], '</p>
	<ul class="bullit-list">
		<li>', $txt['manual_index_home_reg'], '</li>
		<li>', $txt['manual_index_help_reg'], '</li>
		<li>', $txt['manual_index_search_reg'], '</li>
		<li>', $txt['manual_index_profile_reg_part1'], '<a href="', $scripturl, '?action=help;page=profile">', $txt['manual_index_profile_reg_link_profile'], '</a>', $txt['manual_index_profile_reg_part2'], '</li>
		<li>', $txt['manual_index_calendar_reg'], '</li>
		<li>', $txt['manual_index_logout_reg_part1'], '<a href="', $scripturl, '?action=help;page=loginout#logout">', $txt['manual_index_logout_reg_link_loginout_logout'], '</a>', $txt['manual_index_logout_reg_part2'], '</li>
	</ul>
	<p>', $txt['manual_index_forum_admins_note_presentation'], '</p>
	<h2 class="help-title"id="board">', $txt['manual_index_sec_board_index'], '</h2>
	<p>', $txt['manual_index_sec_board_index_def'], '</p>
	
	
	<div class="help-example">
		<div class="bread-nav"><p class="subtitle"><strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_index_forum_name'], '</a></strong></p></div>
		
		<script language="JavaScript1.2" type="text/javascript">
//<![CDATA[
			var collapseExpand = false;
			function collapseExpandCategory()
			{
					document.getElementById("collapseArrow").src = smf_images_url + "/" + (collapseExpand ? "collapse.gif" : "expand.gif");
					document.getElementById("collapseArrow").alt = collapseExpand ? "-" : "+";
					document.getElementById("collapseCategory").style.display = collapseExpand ? "" : "none";
					collapseExpand = !collapseExpand;
			}
			function markBoardRead()
			{
					document.getElementById("board-new-or-not").src = smf_images_url + "/" + "off.gif";
					document.getElementById("board-new-or-not").alt = "', $txt['manual_index_no_new'], '";
			}
//]]>
</script>
		
			<div class="border">
			<h3 class="title">
			<span class="float-r-collapse"><a href="javascript:collapseExpandCategory();"><img src="', $settings['images_url'], '/collapse.gif" alt="-" border="0" id="collapseArrow" name="collapseArrow" /></a></span><a href="javascript:collapseExpandCategory();" class="board">', $txt['manual_index_cat_name'], '</a></h3>
			<dl id="collapseCategory">
			<dt>
			<strong><a href="', $scripturl, '?action=help;page=index#message" class="board">', $txt['manual_index_board_name'], '</a></strong>
			</dt>
			<dd>
			<div class="left">
			<img src="', $settings['images_url'], '/on.gif" class="index-topic-icon" id="board-new-or-not" alt="', $txt['manual_index_new_posts'], '" name="board-new-or-not" />
			', $txt['manual_index_board_desc'], '<br />
			<em>', $txt['manual_index_topics_posts'], '</em>
			</div>
			<div class="right">', $txt['manual_index_date_time'], '</div>
			</dd>
			</dl>
			<div class="block-foot"><!--no content--></div>
			</div><br />';

	// This changes dependant on theme really...
	$mark_read_button = array('markread' => array('text' => 452, 'image' => 'markread.gif', 'lang' => true, 'url' => 'javascript:markBoardRead();'));
	if (!empty($settings['use_tabs']))
	{
		echo '
		<div class="topic-buttons-bot" style="margin-bottom: 2ex;">
			<span class="float-r"><a href="javascript:markBoardRead();"><img src="', $settings['images_url'], '/' . $context['user']['language'] . '/markread.gif" alt="', $txt[452], '"/></a></span>
			<img src="' . $settings['images_url'] . '/new_some.gif" alt="" align="middle" /> ', $txt['manual_index_new_posts'], '
			<img src="' . $settings['images_url'] . '/new_none.gif" alt="" align="middle" style="margin-left: 4ex;" /> ', $txt['manual_index_no_new'], '</div>';
	}
	else
	{
		echo '
		<div class="topic-buttons-bot" style="margin-bottom: 2ex;">
			<span class="float-r"><a href="javascript:markBoardRead();"><img src="', $settings['images_url'], '/' . $context['user']['language'] . '/markread.gif" alt="', $txt[452], '"/></a></span>
			<img src="' . $settings['images_url'] . '/new_some.gif" alt="" align="middle" /> ', $txt['manual_index_new_posts'], '
			<img src="' . $settings['images_url'] . '/new_none.gif" alt="" align="middle" style="margin-left: 4ex;" /> ', $txt['manual_index_no_new'], '</div>';
	}

	echo '
		</div>

		
		
		<ul class="bullit-list">
		<li>', $txt['manual_index_f_name'], '</li>
		<li>', $txt['manual_index_cat'], '</li>
		<li>', $txt['manual_index_b_name_part1'], '<a href="', $scripturl, '?action=help;page=index#message">', $txt['manual_index_b_name_link_message'], '</a>', $txt['manual_index_b_name_part2'], '</li>
		<li>', $txt['manual_index_b_desc'], '</li>
		<li>', $txt['manual_index_n_no_n_posts'], '</li>
		<li>', $txt['manual_index_m_read'], '</li>
	</ul>
	<h2 class="help-title"id="message">', $txt['manual_index_sec_msg_index'], '</h2>
	<p>', $txt['manual_index_sec_msg_index_def'], '</p>
	
	
	<div class="help-example">
			<script language="JavaScript1.2" type="text/javascript">
//<![CDATA[
			var currentSort = false;
			function sortLastPost()
			{
					document.getElementById("sort-arrow").src = smf_images_url + "/" + (currentSort ? "sort_down.gif" : "sort_up.gif");
					document.getElementById("sort-arrow").alt = "";
					currentSort = !currentSort;
			}
			function markMessageRead()
			{
					document.getElementById("message-new-or-not").style.display = "none";
			}
//]]>
</script>
		
		<div class="bread-nav"><p class="subtitle"><img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_index_forum_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_index_cat_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#message" class="nav">', $txt['manual_index_board_name'], '</a></strong>
				</p></div>';

	// Create the buttons we need here...
	$mindex_buttons = array(
		'markmread' => array('text' => 'mark_read_short', 'image' => 'markread.gif', 'lang' => true, 'url' => 'javascript:markMessageRead();'),
		'notify' => array('text' => 'manual_index_notify', 'image' => 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['manual_index_ru_sure_notify'] . '\');"', 'url' => $scripturl . '?action=help;page=index#message'),
		'topic' => array('text' => 'manual_index_start_new', 'image' => 'new_topic.gif', 'lang' => true, 'url' => $scripturl . '?action=help;page=post#newtopic'),
		'poll' => array('text' => 'manual_index_new_poll', 'image' => 'new_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=help;page=post#newpoll'),
	);

	if (!empty($settings['use_tabs']))
	{
		echo '
			<div class="clearfix" id="topic-buttons-top" style="margin-bottom: 1ex; text-align: right;">

			<span class="float-l">', $txt['manual_index_pages'], ': [<strong>1</strong>]</span>
			', template_button_strip($mindex_buttons, 'bottom'), '
			</div>';
	}
	else
	{
		echo '
			<div class="clearfix" id="topic-buttons-top" style="margin-bottom: 1ex; text-align: right;">

			<span class="float-l"><strong>', $txt['manual_index_pages'], '</strong>: [<strong>1</strong>]</span>
			', template_button_strip($mindex_buttons, 'bottom', false, 'align="right" nowrap="nowrap" style="font-size: smaller;"'), '
			</div>';
	}
	echo '
			<div class="border">
			<h3 class="title">', $txt['manual_index_board_name'], '</h3>
			<dl>
			<dt><a href="', $scripturl, '?action=help;page=index#topic" class="board">', $txt['manual_index_topic_subject'], '</a></dt>
				<dd>
				<div class="left">
				<img src="', $settings['images_url'], '/topic/my_normal_poll.gif" alt="" class="topic-icon" />
				<a href="', $scripturl, '?action=help;page=profile" class="board">', $txt['manual_index_topic_starter'], '</a> <img src="', $settings['images_url'], '/post/xx.gif" alt="" align="middle" /><a href="', $scripturl, '?action=help;page=index#topic"><img id="message-new-or-not" src="', $settings['images_url'], '/', $context['user']['language'], '/new.gif" border="0" alt="', $txt['manual_index_new'], '" name="message-new-or-not" /></a><br />
				<em>', $txt['manual_index_replies'], ', ', $txt['manual_index_views'], '</em>
				
				</div>
				<div class="right">
				', $txt['manual_index_last_poster'], '
				</div>
				</dd>
				</dl>
				<div class="block-foot"><!--no content--></div>
				</div>
				<br />
				';

	if (!empty($settings['use_tabs']))
	{
		echo '
			<div class="clearfix" id="topic-buttons-bot" style="margin-bottom: 1ex; text-align: right;">

			<span class="float-l">', $txt['manual_index_pages'], ': [<strong>1</strong>]</span>
			', template_button_strip($mindex_buttons, 'top'), '
			</div>';
	}
	else
	{
		echo '
			<div class="clearfix" id="topic-buttons-bot" style="margin-bottom: 1ex; text-align: right;">

			<span class="float-l"><strong>', $txt['manual_index_pages'], '</strong>: [<strong>1</strong>]</span>
			', template_button_strip($mindex_buttons, 'bottom', false, 'align="right" nowrap="nowrap" style="font-size: smaller;"'), '
			</div>
			';
	}
	echo '
			<div class="border" style="border-width: 1px 1px 0px;"><p class="title"> Sort by: <a href="', $scripturl, '?action=help;page=index#message">', $txt['manual_index_subject'], '</a>, <a href="', $scripturl, '?action=help;page=index#message">', $txt['manual_index_started_by'], '</a>, <a href="', $scripturl, '?action=help;page=index#message">', $txt['manual_index_replies'], '</a>, <a href="', $scripturl, '?action=help;page=index#message">', $txt['manual_index_views'], '</a>, <a href="javascript:sortLastPost();">', $txt['manual_index_last_post'], ' &nbsp; <img id="sort-arrow" src="', $settings['images_url'], '/sort_down.gif" alt="" border="0" name="sort-arrow" /></a></p></div>
			<div class="border-mod">
			<div class="bread-nav"><p class="subtitle"><strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_index_forum_name'], '</a></strong></p></div>
			<div class="row1-center">
			<div class="smalltext">
			<form action="', $scripturl, '?action=help;page=index" method="get" accept-charset="', $context['character_set'], '">
							<label for="jumpto">', $txt['manual_index_jump_to'], '</label>: <select name="jumpto" id="jumpto" onchange="if (this.options[this.selectedIndex].value) window.location.href=\'', $scripturl, '?action=help;page=index\' + this.options[this.selectedIndex].value;">
								<option value="">
									', $txt['manual_index_destination'], ':
								</option>
								<option value="">
									-----------------------------
								</option>
								<option value="#board">
									', $txt['manual_index_cat_name'], '
								</option>
								<option value="">
									-----------------------------
								</option>
								<option value="#message">
									=&gt; ', $txt['manual_index_board_name'], '
								</option>
								<option value="#message">
									=&gt; ', $txt['manual_index_another_board'], '
								</option>
							</select>&nbsp; <input type="button" class="button" onclick="if (this.form.jumpto.options[this.form.jumpto.selectedIndex].value) window.location.href = \'', $scripturl, '?action=help;page=index\' + this.form.jumpto.options[this.form.jumpto.selectedIndex].value;" value="', $txt['manual_index_go'], '" />
						</form>
			</div>
			<ul id="topiclegend" class="smalltext">
			<li><img src="', $settings['images_url'], '/topic/my_normal_post.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_normal_post'], '</li>
			<li><img src="', $settings['images_url'], '/topic/normal_post.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_normal_topic'], '</li>
			<li><img src="', $settings['images_url'], '/topic/hot_post.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_hot_post'], '</li>
			<li><img src="', $settings['images_url'], '/topic/veryhot_post.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_very_hot_post'], '</li>
			<li><img src="', $settings['images_url'], '/topic/normal_post_locked.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_locked'], '</li>
			<li><img src="', $settings['images_url'], '/topic/normal_post_sticky.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_sticky'], '</li>
			<li><img src="', $settings['images_url'], '/topic/normal_poll.gif" alt="" align="middle" />&nbsp; ', $txt['manual_index_poll'], '</li>
			</ul>
			<div class="clear"><!--no content--></div>

	</div>
	<div class="block-foot"><!--no content--></div>
	</div>
	
	</div>
	
	
	<ul class="bullit-list">
		<li>', $txt['manual_index_nav_tree'], '</li>
		<li>', $txt['manual_index_page_number'], '</li>
		<li>', $txt['manual_index_mark_read_button'], '</li>
		<li>', $txt['manual_index_notify_button'], '</li>
		<li>', $txt['manual_index_new_topic_poll_button_part1'], '<a href="', $scripturl, '?action=help;page=post">', $txt['manual_index_new_topic_poll_button_link_posting'], '</a>', $txt['manual_index_new_topic_poll_button_part2'], '</li>
		<li>', $txt['manual_index_subject_replies_etc'], '</li>
		<li>', $txt['manual_index_topic_icons'], '</li>
		<li>', $txt['manual_index_post_icons'], '</li>
		<li>', $txt['manual_index_topic_subject_links_part1'], '<a href="', $scripturl, '?action=help;page=index#topic">', $txt['manual_index_topic_subject_links_link_topic'], '</a>', $txt['manual_index_topic_subject_links_part2'], '</li>
		<li>', $txt['manual_index_where_topic_part1'], '<a href="', $scripturl, '?action=help;page=profile">', $txt['manual_index_where_topic_link_profile'], '</a>', $txt['manual_index_where_topic_part2'], '</li>
		<li>', $txt['manual_index_jump_to_menu'], '</li>
	</ul>
	<h2 class="help-title"id="topic">', $txt['manual_index_sec_topic'], '</h2>
	<p>', $txt['manual_index_ref_thread'], '</p>';

	// The buttons...
	$display_buttons = array(
		'reply' => array('text' => 'manual_index_reply', 'image' => 'reply.gif', 'lang' => true, 'url' => $scripturl . '?action=help;page=post#reply'),
		'notify' => array('text' => 'manual_index_notify', 'image' => 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['manual_index_ru_sure_enable_notify'] . '\');"', 'url' => $scripturl . '?action=help;page=post#topic'),
		'markunread' => array('text' => 'manual_index_mark_unread', 'image' => 'markunread.gif', 'lang' => true, 'url' => $scripturl . '?action=help;page=post#topic'),
		'sendtopic' => array('text' => 'manual_index_send_topic', 'image' => 'sendtopic.gif', 'lang' => true, 'url' => $scripturl . '?action=help;page=post#topic'),
		'print' => array('text' => 'manual_index_print', 'image' => 'print.gif', 'lang' => true, 'url' => $scripturl . '?action=help;page=post#topic'),
	);

	
	
	echo '
	<div class="help-example">
			<div class="bread-nav"><p class="subtitle">
			<img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_index_forum_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_index_cat_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#message" class="nav">', $txt['manual_index_board_name'], '</a></strong><br />
					<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#topic" class="nav">', $txt['manual_index_topic_subject'], '</a></strong>
				</p></div>';

	if (!empty($settings['use_tabs']))
	{
		echo '
			<div class="topic-buttons-top" style="margin-bottom: 1ex; text-align: right;">
			<span class="float-l">', $txt['manual_index_pages'], ': [<strong>1</strong>]</span>
			', template_button_strip($display_buttons, 'bottom'), '
			</div>';
			
	}
	else
	{
		echo '
			<div class="topic-buttons-top" style="margin-bottom: 1ex; text-align: right;">
			<span class="float-l"><strong>', $txt['manual_index_pages'], '</strong>: [<strong>1</strong>]</span>
			', template_button_strip($display_buttons, 'bottom', false, 'align="right" style="font-size: smaller;"'), '
			</div>
			';
	}
	echo '
			<div class="border">
<h2 class="title">', $txt['manual_index_topic'], ': ', $txt['manual_index_topic_subject'], ' &nbsp;(', $txt['manual_index_read_x_times'], ')</h2>
<div class="block-foot"><!--no content--></div>
</div>
<br />
<div class="border-post">
<h3 class="title">
<span class="float-r"><img src="', $settings['images_url'], '/star.gif" alt="*" border="0" /></span>
', $txt['manual_index_author'], '
</h3>
<h4 class="subtitle">
<span class="float-r"><strong> on:</strong> ', $txt['manual_index_post_date_time'], '</span>
<a href="', $scripturl, '?action=help;page=index#topic" class="board">', $txt['manual_index_topic_subject'], '</a>
</h4>
<div class="post-top"><div class="post-top-left"><!-- no content--> </div></div>

<div class="post-bg">
<div class="post-left">
<ul>
<li>', $txt['manual_index_member_group'], '</li>
<li>', $txt['manual_index_post_group'], '</li>
<li>', $txt['manual_index_post_count'], '</li>
<li><img src="', $settings['images_url'], '/ip.gif" alt="" border="0" />&nbsp; ', $txt['manual_index_logged'], '</li>
</ul>
</div>
<div class="post-right">
<div class="post" style="height:80px;">
', $txt['manual_index_topic_text'], '
&nbsp;<img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/smiley.gif" border="0" alt="', $txt['manual_index_smiley'], '" />
</div>						
</div>

<div class="post-foot">
<div class="float-l">
<a href="', $scripturl, '?action=help;page=profile" title="', $txt['manual_index_view_profile'], '"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/profile.gif" border="0" alt="', $txt['manual_index_view_profile'], '" /></a>
<a href="mailto:author@some.address" title="', $txt['manual_index_email'], '"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/email.gif" border="0" alt="', $txt['manual_index_email'], '" /></a>
<a href="', $scripturl, '?action=help;page=pm" title="', $txt['manual_index_personal_msg'], '"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/pm-off.gif" border="0" alt="', $txt['manual_index_personal_msg'], '" /></a>
</div>

<a href="', $scripturl, '?action=help;page=post#quote">', create_button('quote.gif', 'manual_index_reply_quote', 'smf240', ''), '</a>
<a href="', $scripturl, '?action=help;page=index#topic" class="board" style="font-size: x-small;"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/report.gif" title="' . $txt['rtm1'] . '" alt="' . $txt['rtm1'] . '" border="0" /></a>
</div>
</div>
<div class="block-foot"><!--no content--></div>
</div>
				<br />
				<a name="lastPost" id="lastPost"></a>';
				
	if (!empty($settings['use_tabs']))
	{
		echo '
			<div class="topic-buttons-bot" style="margin-bottom: 1ex; text-align: right;">
			<span class="float-l">
			<strong>', $txt['manual_index_pages'], ':</strong> [<strong>1</strong>]</span>
			', template_button_strip($display_buttons, 'top', false), '
			</div>';
	}
	else
	{
		echo '
			<div class="topic-buttons-bot" style="margin-bottom: 1ex; text-align: right;">
			<span class="float-l">
			<strong>', $txt['manual_index_pages'], ':</strong> [<strong>1</strong>]
			</span>
			', template_button_strip($display_buttons, 'top', false, 'align="right" style="font-size: smaller;"'), '
			</div>';
	}
	echo '
			<div style="padding-top: 4px; padding-bottom: 4px;"></div>
			<div align="right" style="float: right; margin-bottom: 1ex;">
				<form action="', $scripturl, '?action=help;page=index" method="get" accept-charset="', $context['character_set'], '">
					<label for="jump2">', $txt['manual_index_jump_to'], '</label>: <select name="jump2" id="jump2" onchange="if (this.options[this.selectedIndex].value) window.location.href=\'', $scripturl, '?action=help;page=index\' + this.options[this.selectedIndex].value;">
						<option value="">
							', $txt['manual_index_destination'], ':
						</option>
						<option value="">
							-----------------------------
						</option>
						<option value="#board">
							', $txt['manual_index_cat_name'], '
						</option>
						<option value="">
							-----------------------------
						</option>
						<option value="#message">
							=&gt; ', $txt['manual_index_board_name'], '
						</option>
						<option value="#message">
							=&gt; ', $txt['manual_index_another_board'], '
						</option>
					</select>&nbsp; <input type="button" class="button" onclick="if (this.form.jump2.options[this.form.jump2.selectedIndex].value) window.location.href = \'', $scripturl, '?action=help;page=index\' + this.form.jump2.options[this.form.jump2.selectedIndex].value;" value="', $txt['manual_index_go'], '" />
				</form>
			</div><br />
			<br clear="all" />
	</div><br />
	
	
	
	<ul class="bullit-list">
		<li>', $txt['manual_index_navigation_tree'], '</li>
		<li>', $txt['manual_index_prev_next'], '</li>
		<li>', $txt['manual_index_page_no_link'], '</li>
		<li>', $txt['manual_index_reply_button_part1'], '<a href="', $scripturl, '?action=help;page=post#reply">', $txt['manual_index_reply_button_link_posting_reply'], '</a>', $txt['manual_index_reply_button_part2'], '</li>
		<li>', $txt['manual_index_notify_button_enables'], '</li>
		<li>', $txt['manual_index_mark_unread_button'], '</li>
		<li>', $txt['manual_index_send_topic_button'], '</li>
		<li>', $txt['manual_index_print_button'], '</li>
		<li>', $txt['manual_index_author_name_link_part1'], '<a href="', $scripturl, '?action=help;page=profile">', $txt['manual_index_author_name_link_link_profile'], '</a></li>
		<li>', $txt['manual_index_author_details'], '</li>
		<li>', $txt['manual_index_topic_subject_links_start'], '</li>
		<li>', $txt['manual_index_quote_button_part1'], '<a href="', $scripturl, '?action=help;page=post#quote">', $txt['manual_index_quote_button_link_posting_quote'], '</a>', $txt['manual_index_quote_button_part2'], '</li>
		<li>', $txt['manual_index_modify_delete_part1'], '<a href="', $scripturl, '?action=help;page=post#modify">', $txt['manual_index_modify_delete_link_posting_modify'], '</a>', $txt['manual_index_modify_delete_part2'], '</li>
		<li>', $txt['manual_index_report_to_moderator'], '</li>
		<li>', $txt['manual_index_logged_IP'], '</li>
		<li>', $txt['manual_index_jump_to_menu_provides'], '</li>
	</ul>';
}

// Logging in and out page.
function template_manual_login()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
		<p>', $txt['manual_loginout_complete_reg_part1'], '<a href="', $scripturl, '?action=help;page=registering">', $txt['manual_loginout_complete_reg_link_registering'], '</a>', $txt['manual_loginout_complete_reg_part2'], '</p>
	<ol>
		<li>
			<a href="', $scripturl, '?action=help;page=loginout#login">', $txt['manual_loginout_sec_login'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=loginout#screen">', $txt['manual_loginout_login_screen'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=loginout#quick">', $txt['manual_loginout_sub_quick_login'], '</a></li>
			</ol>
		</li>
		<li><a href="', $scripturl, '?action=help;page=loginout#logout">', $txt['manual_loginout_logout'], '</a></li>
		<li><a href="', $scripturl, '?action=help;page=loginout#reminder">', $txt['manual_loginout_sec_reminder'], '</a></li>
	</ol>
	<h2 class="help-title" id="login">', $txt['manual_loginout_sec_login'], '</h2>
	<p>', $txt['manual_loginout_login_desc'], '</p>
	<h3 class="help-subtitle" id="screen" class="help-subtitle">', $txt['manual_loginout_login_screen'], '</h3>
	<p>', $txt['manual_loginout_login_screen_desc_part1'], '<a href="', $scripturl, '?action=help;page=index#main">', $txt['manual_loginout_login_screen_desc_link_index_main'], '</a>', $txt['manual_loginout_login_screen_desc_part2'], '</p>
	
	<div class="help-example">
	<form action="', $scripturl, '?action=help;page=loginout" method="post" accept-charset="', $context['character_set'], '" style="margin-top: 4ex;">
	<div class="border">
	<h3 class="title"><img src="', $settings['images_url'], '/icons/login_sm.gif" alt="" align="top" />&nbsp; ', $txt['manual_loginout_login'], '</h3>
	<div class="row1">
	<span class="formleft"><strong>', $txt['manual_loginout_username'], ':</strong></span>
	<span class="formright"><input type="text" size="20" value="" /></span>
	<span class="formleft"><strong>', $txt['manual_loginout_password'], ':</strong></span>
	<span class="formright"><input type="password" value="" size="20" /></span>
	<span class="formleft"><strong>', $txt['manual_loginout_how_long'], ':</strong></span>
	<span class="formright"><input name="cookielength" type="text" size="4" maxlength="4" value="60" /></span>
	<span class="formleft"><strong>', $txt['manual_loginout_always'], ':</strong></span>
	<span class="formright"><input type="checkbox" class="check" onclick="this.form.cookielength.disabled = this.checked;" /></span>
	</div>
	<div class="row1-center">
	<p></strong><a href="', $scripturl, '?action=help;page=loginout#reminder" style="font-size: x-small;" class="board">', $txt['manual_loginout_forgot'], '?</a></strong></p>
	</div>
	<div class="subtitle" style="text-align:center;">
	<input type="button" class="button" value="Login" />
	</div>
	</div>
	</form>
	</div><br />
	
	
	<p>', $txt['manual_loginout_login_screen_explanation'], '</p>
	<h3 class="help-subtitle" id="quick" class="help-subtitle">', $txt['manual_loginout_sub_quick_login'], '</h3>
	<p>', $txt['manual_loginout_although_many_forums_part1'], '<a href="', $scripturl, '?action=help;page=index#main">', $txt['manual_loginout_although_many_forums_link_index_main'], '</a>', $txt['manual_loginout_although_many_forums_part2'], '</p>
	
	<div class="help-example">
	<div class="border" style="margin:0 auto; width:40%;text-align:right;">
							<form action="', $scripturl, '?action=help;page=loginout" method="post" accept-charset="', $context['character_set'], '" style="margin: 3px 1ex 1px 0; text-align:right;">
								<input type="text" size="10" /> <input type="password" size="10" /> <select>
									<option>
										', $txt['manual_loginout_hour'], '
									</option>
									<option>
										', $txt['manual_loginout_day'], '
									</option>
									<option>
										', $txt['manual_loginout_week'], '
									</option>
									<option>
										', $txt['manual_loginout_mo'], '
									</option>
									<option selected="selected">
										', $txt['manual_loginout_forever'], '
									</option>
								</select> <input class="button" style="margin-right:0;" type="button" value="Login" /><br />
								', $txt['manual_loginout_login_all'], '
							</form>
					</div>
					</div>
		<br />
		
	<p>', $txt['manual_loginout_use_quick_login'], '</p>
	<h2 class="help-title"id="logout">', $txt['manual_loginout_logout'], '</h2>
	<p>', $txt['manual_loginout_logout_desc_part1'], '<a href="', $scripturl, '?action=help;page=index#main">', $txt['manual_loginout_logout_desc_link_index_main'], '</a>', $txt['manual_loginout_logout_desc_part2'], '</p>
	<h2 class="help-title"id="reminder">', $txt['manual_loginout_sec_reminder'], '</h2>
	<p>', $txt['manual_loginout_reminder_desc_part1'], '<a href="', $scripturl, '?action=help;page=loginout#screen">', $txt['manual_loginout_reminder_desc_link_screen'], '</a>', $txt['manual_loginout_reminder_desc_part2'], '</p>
	
	<div class="help-example">
	<form action="', $scripturl, '?action=help;page=loginout" method="post" accept-charset="', $context['character_set'], '">
		<div class="border">
		<h3 class="title">', $txt['manual_loginout_password_reminder'], '</h3>
		<div class="row1-center">
		<p><em>', $txt['manual_loginout_q_explanation'], '</em></p>
		</div>
		<div class="row1-center">
		<p>', $txt['manual_loginout_username_email'], ':&nbsp; <input type="text" size="30" /></p>
		<p style="margin-top: 10px;"><label for="secret"><input type="checkbox" name="sa" value="secret" id="secret" class="check" /> ', $txt['manual_loginout_ask_q'], '.</label></p>
		</div>
		<div class="subtitle" style="text-align:center;"><input type="button" class="button" value="', $txt['manual_loginout_send'], '" /></div>
		</div>
	</form>
	</div>
	<p>', $txt['manual_loginout_reminder_explanation'], '</p>';

}

// Sending personal messages.
function template_manual_pm()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
		<p>', $txt['manual_pm_community'], '</p>
	<ol>
		<li>
			<a href="', $scripturl, '?action=help;page=pm#pm">', $txt['manual_pm_sec_pm'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=pm#description">', $txt['manual_pm_pm_desc'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=pm#reading">', $txt['manual_pm_reading'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;page=pm#interface">', $txt['manual_pm_sec_pm2'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=pm#starting">', $txt['manual_pm_start_reply'], '</a></li>
			</ol>
		</li>
	</ol>
	<h2 class="help-title"id="pm">', $txt['manual_pm_sec_pm'], '</h2>
	<h3 class="help-subtitle" id="description">', $txt['manual_pm_pm_desc'], '</h3>
	<p>', $txt['manual_pm_pm_desc_1'], '</p>
	<p>', $txt['manual_pm_pm_desc_2'], '</p>
	<p>', $txt['manual_pm_pm_desc_3'], '</p>
	<h3 class="help-subtitle" id="reading">', $txt['manual_pm_reading'], '</h3>
	<p>', $txt['manual_pm_reading_desc_part1'], '<a href="', $scripturl, '?action=help;page=loginout">', $txt['manual_pm_reading_desc_link_loginout'], '</a>', $txt['manual_pm_reading_desc_part2'], '<a href="', $scripturl, '?action=help;page=pm#interface">', $txt['manual_pm_reading_desc_link_loginout_interface'], '</a>', $txt['manual_pm_reading_desc_part3'], '</p>
	<h2 class="help-title"id="interface">', $txt['manual_pm_sec_pm2'], '</h2>
	<p>', $txt['manual_pm_pm_desc2_part1'], '<a href="', $scripturl, '?action=help;page=index#message">', $txt['manual_pm_pm_desc2_link_index_message'], '</a>', $txt['manual_pm_pm_desc2_part2'], '</p>
	
	
	<div class="help-example">
			<script language="JavaScript1.2" type="text/javascript">
//<![CDATA[
			var currentSort = false;
			function sortLastPM()
			{
					document.getElementById("sort-arrow").src = smf_images_url + "/" + (currentSort ? "sort_up.gif" : "sort_down.gif");
					document.getElementById("sort-arrow").alt = "";
					currentSort = !currentSort;
			}
//]]>
</script>
			<form action="', $scripturl, '?action=help;page=pm" method="post" accept-charset="', $context['character_set'], '">
				<div class="bread-nav"><p class="subtitle"><img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_pm_forum_name'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=pm#interface" class="nav">', $txt['manual_pm_personal_msgs'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=pm#interface" class="nav">', $txt['manual_pm_inbox'], '</a></strong></p></div>
				
				
	<div id="messages">
	<div id="user-cp-left">
	<div class="border">
	<div class="title">&nbsp;</div>
	<h3 class="subtitle" style="clear: none;">' , $txt['manual_pm_messages'] , '</h3>
	<ul>
	<li>
	<a href="', $scripturl, '?action=help;page=pm#interface">' , $txt['manual_pm_new_msg'] , '</a></li>
	<li id="selected"><a href="', $scripturl, '?action=help;page=pm#interface" class="nav">' , $txt['manual_pm_inbox'] , '</a></li>
	<li><a href="', $scripturl, '?action=help;page=pm#interface">' , $txt['manual_pm_outbox'] , '</a></li>
	</ul>
	</div>
	</div>
	
	<div id="user-cp-right">
	<div class="border">
	<div class="title" style="text-align: right;">
	<span class="float-l"><input type="checkbox" onclick="invertAll(this, this.form);" class="check" /></span>
	<strong>', $txt['manual_pm_pages'], ':</strong> [<strong>1</strong>]
	</div>

	<div class="subtitle">
Sort by : 
<a href="javascript:sortLastPM();">', $txt['manual_pm_date'], '&nbsp; <img id="sort-arrow" src="', $settings['images_url'], '/sort_up.gif" alt="" border="0" name="sort-arrow" /></a> &middot; 
<a href="', $scripturl, '?action=help;page=pm#interface">', $txt['manual_pm_subject2'], '</a> &middot; 
<a href="', $scripturl, '?action=help;page=pm#interface">', $txt['manual_pm_from'], '</a>
	</div>
	<div class="row2">

	<dl>
		<dt>
		<span class="float-r"><img src="', $settings['images_url'], '/icons/pm_read.gif" alt="Read" border="0"></span>
		<input type="checkbox" class="check" />
		<a href="', $scripturl, '?action=help;page=pm#interface" class="board">', $txt['manual_pm_subject'], '</a>
		</dt>
		<dd>
		<p><em>', $txt['manual_pm_from'], ': ', $txt['manual_pm_another_member'], '. ', $txt['manual_pm_date_and_time'], '</em></p>
		</dd>
		</dl>
		</div>
		<div class="subtitle">
	<input type="button" class="button" value="', $txt['manual_pm_delete_selected'], '" />
				</div>
				<div class="block-foot"><!--no content--></div>
				</div>
				</div>
				</div>
				
				</div>
				
				
				
		<br />
	<ul class="bullit-list">
		<li>', $txt['manual_pm_nav_tree'], '</li>
		<li>', $txt['manual_pm_delete_button'], '</li>
		<li>', $txt['manual_pm_outbox_button'], '</li>
		<li>', $txt['manual_pm_new_msg2_part1'], '<a href="', $scripturl, '?action=help;page=post#newtopic">', $txt['manual_pm_new_msg2_link_posting_newtopic'], '</a>', $txt['manual_pm_new_msg2_part2'], '</li>
		<li>', $txt['manual_pm_reload'], '</li>
		<li>', $txt['manual_pm_sort_by'], '</li>
		<li>', $txt['manual_pm_main_subject'], '</li>
		<li>', $txt['manual_pm_page_nos'], '</li>
	</ul>
	<h3 class="help-subtitle" id="starting">', $txt['manual_pm_start_reply'], '</h3>
	<p>', $txt['manual_pm_how_to_start_reply_part1'], '<a href="', $scripturl, '?action=help;page=loginout">', $txt['manual_pm_how_to_start_reply_link_loginout'], '</a>', $txt['manual_pm_how_to_start_reply_part2'], '</p>
	<ul class="bullit-list">
		<li>', $txt['manual_pm_msg_link_part1'], '<a href="', $scripturl, '?action=help;page=pm#interface">', $txt['manual_pm_msg_link_link_interface'], '</a>', $txt['manual_pm_msg_link_part2'], '</li>
		<li>', $txt['manual_pm_click_name_part1'], '<a href="', $scripturl, '?action=help;page=profile#info-all">', $txt['manual_pm_click_name_link_profile_info-all'], '</a>', $txt['manual_pm_click_name_part2'], '</li>
		<li>', $txt['manual_pm_click_im_icon'], '</li>
		<li>', $txt['manual_pm_click_pm_icon_part1'], '<a href="', $scripturl, '?action=help;page=profile#info-all">', $txt['manual_pm_click_pm_icon_link_profile_info-all'], '</a>', $txt['manual_pm_click_pm_icon_part2'], '</li>
		<li>', $txt['manual_pm_reply_msg_part1'], '<a href="', $scripturl, '?action=help;page=post#reply">', $txt['manual_pm_reply_msg_link_posting_reply'], '</a>', $txt['manual_pm_reply_msg_part2'], '</li>
	</ul>';
}

function template_manual_profile()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_profile_profile_screen'], '</p>
	<p>', $txt['manual_profile_edit_profile_part1'], '<a href="', $scripturl, '?action=help;page=index#main">', $txt['manual_profile_edit_profile_link_index_main'], '</a>', $txt['manual_profile_edit_profile_part2'], '</p>
	<ol>
		<li>
			<a href="', $scripturl, '?action=help;page=profile#all">', $txt['manual_profile_available_to_all'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=profile#info-all">', $txt['manual_profile_profile_info'], '</a></li>
			</ol>
		</li>
				<li>
			<a href="', $scripturl, '?action=help;page=profile#owners">', $txt['manual_profile_sec_normal'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=profile#edit-owners">', $txt['manual_profile_modify_profile'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=profile#actions-owners">', $txt['manual_profile_actions'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;page=profile#admins">', $txt['manual_profile_sec_settings'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=profile#info-admins">', $txt['manual_profile_profile_info'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=profile#edit-admins">', $txt['manual_profile_modify_profile'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=profile#actions-admins">', $txt['manual_profile_actions'], '</a></li>
			</ol>
		</li>
	</ol>
	<h2 class="help-title"id="all">', $txt['manual_profile_available_to_all'], '</h2>
	<h3 class="help-subtitle" id="info-all">', $txt['manual_profile_profile_info'], '</h3>
	
	
	<div class="help-example">
	
	<div class="border">
	<h4 class="title">', $txt['manual_profile_username'], ':&nbsp;', $txt['manual_profile_login_name'], '</h4>
	<h5 class="subtitle">blah</h5>
	<div class="row2">
	<div id="cp-avatar">
	', $txt['manual_profile_pic_text'], '
	</div>

	<ul class="bullit-list">
	<li><strong>', $txt['manual_profile_name'], ':</strong> ', $txt['manual_profile_screen_name'], '</li>
	<li><strong>', $txt['manual_profile_posts'], ':</strong> ', $txt['manual_profile_member_posts'], '</li>
	<li><strong>', $txt['manual_profile_position'], ':</strong> ', $txt['manual_profile_membergroup'], '</li>
	<li><strong>', $txt['manual_profile_date_reg'], ':</strong> ', $txt['manual_profile_date_time_reg'], '</li>
	<li><strong>', $txt['manual_profile_last_active'], ':</strong> ', $txt['manual_profile_date_time_active'], '</li>
	<ul>
	</div>
	
	<div class="row2">
	<ul class="bullit-list">
	<li><strong>ICQ:</strong></li>
	<li><strong>AIM:</strong></li>
	<li><strong>MSN:</strong></li>
	<li><strong>YIM:</strong></li>
	<li><strong>', $txt['manual_profile_email'], ':</strong> <a href="mailto:', $txt['manual_profile_email_user'], '" class="board">', $txt['manual_profile_email_user'], '</a></li>
	<li><strong>', $txt['manual_profile_website'], ':</strong> <a href="http://www.simplemachines.org/" target="_blank"></a></li>
	<li><strong>', $txt['manual_profile_status'], ':</strong> <em><a href="', $scripturl, '?action=help;page=pm" title="', $txt['manual_profile_pm'], ' (', $txt['manual_profile_online'], ')  "><img src="', $settings['images_url'], '/useron.gif" border="0" align="middle" alt="', $txt['manual_profile_online'], '" /></a> <span class="smalltext">', $txt['manual_profile_online'], '</span></em></li>
	</ul>
	</div>
	<div class="row2">
	<ul class="bullit-list">
	<li><strong>', $txt['manual_profile_gender'], ':</strong></li>
	<li><strong>', $txt['manual_profile_age'], ':</strong> ', $txt['manual_profile_n_a'], '</li>
	<li><strong>', $txt['manual_profile_location'], ':</strong></li>
	<li><strong>', $txt['manual_profile_local_time'], ':</strong> ', $txt['manual_profile_current_date_time'], '</li>
	<li><strong>', $txt['manual_profile_language'], ':</strong></li>
	</ul>
	</div>
	<h5 class="subtitle">', $txt['manual_profile_sig'], ':</h5>
	<div class="row2">
	<p><em>', $txt['manual_profile_sig'], '</em></p>
	</div>
	<h5 class="subtitle">', $txt['manual_profile_other_info'], ':</h5>
	<div class="row2">
	<ul class="bullit-list">
	<li><a href="', $scripturl, '?action=help;page=profile#all" class="board">', $txt['manual_profile_send_pm'], '</a></li>
	<li><a href="', $scripturl, '?action=help;page=profile#all" class="board">', $txt['manual_profile_show_member_posts'], '</a></li>
	<li><a href="', $scripturl, '?action=help;page=profile#all" class="board">', $txt['manual_profile_show_member_stats'], '</a></li>
	</ul>
	</div>
	<div class="block-foot"><!--no content--></div>
	</div>
	</div><br />
	
	
	
	<ul class="bullit-list">
		<li>', $txt['manual_profile_summary_part1'], '<a href="', $scripturl, '?action=help;page=profile#owners">', $txt['manual_profile_summary_link_owners'], '</a>', $txt['manual_profile_summary_part2'], '</li>
		<li>', $txt['manual_profile_hide_email'], '</li>
		<li>', $txt['manual_profile_empty_part1'], '<a href="', $scripturl, '?action=help;page=profile#owners">', $txt['manual_profile_empty_link_owners'], '</a>', $txt['manual_profile_empty_part2'], '</li>
		<li>', $txt['manual_profile_send_member_pm_part1'], '<a href="', $scripturl, '?action=help;page=pm">', $txt['manual_profile_send_member_pm_link_pm'], '</a>', $txt['manual_profile_send_member_pm_part2'], '</li>
		<li>', $txt['manual_profile_show_last_posts'], '</li>
		<li>', $txt['manual_profile_show_member_stats2'], '</li>
	</ul>
	<h2 class="help-title"id="owners">', $txt['manual_profile_sec_normal'], '</h2>
	<p>', $txt['manual_profile_normal_desc'], '</p>
	<h3 class="help-subtitle" id="edit-owners">', $txt['manual_profile_modify_profile'], '</h3>
	<ul class="bullit-list">
		<li>', $txt['manual_profile_account_related'], '</li>
		<li>', $txt['manual_profile_forum_profile_info'], '</li>
		<li>', $txt['manual_profile_look_layout'], '</li>
	</ul>
	
	
		<div class="help-example">

<div id="user-cp-left">
<div class="border">
<div class="title">&nbsp;</div>
<h3 class="subtitle">', $txt['manual_profile_profile_info2'], '</h3>
<ul>
<li><a href="', $scripturl, '?action=help;page=profile#owners" style="font-size: x-small;" class="board">', $txt['manual_profile_summary2'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#owners" style="font-size: x-small;" class="board">', $txt['manual_profile_show_stats'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#owners" style="font-size: x-small;" class="board">', $txt['manual_profile_show_posts'], '</a></li>
</ul>
<h3 class="subtitle">', $txt['manual_profile_modify_own_profile'], '</h3>
<ul>
<li><a href="', $scripturl, '?action=help;page=profile#owners" style="font-size: x-small;" class="board">', $txt['manual_profile_acct_settings'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#owners" style="font-size: x-small;" class="board">', $txt['manual_profile_forum_profile'], '</a></li>
<li id="selected"><a href="', $scripturl, '?action=help;page=profile#owners" style="font-size: x-small;" class="board">', $txt['manual_profile_look_and_layout'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#owners" style="font-size: x-small;" class="board">', $txt['manual_profile_notify_email'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#owners" style="font-size: x-small;" class="board">', $txt['manual_profile_pm_options1'], '</a></li>
</ul>
<h3 class="subtitle">', $txt['manual_profile_actions'], '</h3>
<ul>
<li><a href="', $scripturl, '?action=help;page=profile#owners" style="font-size: x-small;" class="board">', $txt['manual_profile_delete_account'], '</a></li>
</ul>
</div>
</div>


<div id="user-cp-right">
<form action="', $scripturl, '?action=help;page=profile" method="post" accept-charset="', $context['character_set'], '">
<div class="border">
<h4 class="title">', $txt['manual_profile_edit_profile1'], '</h4>
<div class="row2"><p>', $txt['manual_profile_look_layout_explanation'], '</p></div>
<h5 class="subtitle">', $txt['manual_profile_current_theme'], ':</h5>
<div class="row2">
', $txt['manual_profile_board_default'], '&nbsp;<a href="', $scripturl, '?action=help;page=profile#owners" class="board">(', $txt['manual_profile_change'], ')</a>
</div>
<h5 class="subtitle">', $txt['manual_profile_time_format'], ':</h5>
<div class="row2">
<a href="', $scripturl, '/index.php?action=helpadmin;help=time_format" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['manual_profile_help'], '" border="0" align="left" style="padding-right: 1ex;" /></a> 
', $txt['manual_profile_caption_date'], '<br />
<select style="margin-bottom: 4px;">
													<option selected="selected">
														(', $txt['manual_profile_date_option_select'], ')
													</option>
													<option>
														', $txt['manual_profile_date_option_1'], '
													</option>
													<option>
														', $txt['manual_profile_date_option_2'], '
													</option>
													<option>
														', $txt['manual_profile_date_option_3'], '
													</option>
													<option>
														', $txt['manual_profile_date_option_4'], '
													</option>
													<option>
														', $txt['manual_profile_date_option_5'], '
													</option>
												</select><br />
												<input type="text" value="" size="30" />
</div>
<h5 class="subtitle">', $txt['manual_profile_time_offset'], ':</h5>
<div class="row2">
<p>', $txt['manual_profile_offset_hours'], '</p>
<input type="text" size="5" maxlength="5" value="0" />
<p>(', $txt['manual_profile_forum_time'], ')</p>
</div>
<div class="row2">
<ul class="row-list">
<li><input type="checkbox" class="check" /> ', $txt['manual_profile_board_descriptions'], '</li>
<li><input type="checkbox" class="check" /> ', $txt['manual_profile_show_child'], '</li>
<li><input type="checkbox" class="check" /> ', $txt['manual_profile_no_ava'], '</li>
<li><input type="checkbox" class="check" /> ', $txt['manual_profile_no_sig'], '</li>
<li><input type="checkbox" class="check" /> ', $txt['manual_profile_return_to_topic'], '</li>
<li><input type="checkbox" class="check" /> ', $txt['manual_profile_recent_posts'], '</li>
<li><input type="checkbox" class="check" /> ', $txt['manual_profile_recent_pms'], '</li>
</ul>
</div>
<h5 class="subtitle">', $txt['manual_profile_first_day_week'], '</h5>
<div class="row2">
<select>
																<option selected="selected">
																	', $txt['manual_profile_sun'], '
																</option>
																<option>
																	', $txt['manual_profile_mon'], '
																</option>
															</select>
</div>
<h5 class="subtitle">', $txt['manual_profile_quick_reply'], ':</h5>
<div class="row2">
<option selected="selected">
																	', $txt['manual_profile_not_at_all'], '
																</option>
																<option>
																	', $txt['manual_profile_off_default'], '
																</option>
																<option>
																	', $txt['manual_profile_on_default'], '
																</option>
															</select>
</div>
<h5 class="subtitle">', $txt['manual_profile_quick_mod'], '</h5>
<div class="row2">
<select>
																<option selected="selected">
																	', $txt['manual_profile_no_quick_mod'], '.
																</option>
																<option>
																	', $txt['manual_profile_check_quick_mod'], '.
																</option>
																<option>
																	', $txt['manual_profile_icon_quick_mod'], '.
																</option>
															</select>
</div>
<div class="subtitle" style="text-align:center;"><input type="button" class="button" value="', $txt['manual_profile_change_profile'], '" /></div>
</div>
</form>
</div>
</div><br />


	<ul class="bullit-list">
		<li>', $txt['manual_profile_notify_email_prefs'], '</li>
		<li>', $txt['manual_profile_pm_options_part1'], '<a href="', $scripturl, '?action=help;page=pm">', $txt['manual_profile_pm_options_link_pm'], '</a>', $txt['manual_profile_pm_options_part2'], '</li>
	</ul>
	<h3 class="help-subtitle" id="actions-owners">', $txt['manual_profile_sub_actions'], '</h3>
	<ul class="bullit-list">
		<li>', $txt['manual_profile_confirm_delete_acct'], '</li>
	</ul>
	<h2 class="help-title"id="admins">', $txt['manual_profile_sec_settings'], '</h2>
	<p>', $txt['manual_profile_settings_desc'], '</p>

	<div class="user-cp-left">
<div class="border">
<div class="title">&nbsp;</div>
<h3 class="subtitle">', $txt['manual_profile_profile_info'], '</h3>
<ul>
<li id="selected"><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_summary2'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_show_stats'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_show_posts'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_track_user'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_track_ip'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_show_permissions'], '</a></li>
</ul>
<h3 class="subtitle">', $txt['manual_profile_sub_modify_profile'], '</h3>
<ul>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_acct_settings'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_forum_profile'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_look_and_layout'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_notify_email'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_pm_options1'], '</a></li>
</ul>
<h3 class="subtitle">', $txt['manual_profile_actions'], '</h3>
<ul>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_ban_user'], '</a></li>
<li><a href="', $scripturl, '?action=help;page=profile#admins" style="font-size: x-small;" class="board">', $txt['manual_profile_delete_account'], '</a></li>
</ul>
</div>
</div>
<div style="margin:0 0 0 240px;">

			<h3 class="help-subtitle" id="info-admins">', $txt['manual_profile_sub_profile_info'], '</h3>
			<ul class="bullit-list">
				<li>', $txt['manual_profile_sub_track_user'], '</li>
				<li>', $txt['manual_profile_sub_track_ip'], '</li>
				<li>', $txt['manual_profile_sub_show_permissions'], '</li>
			</ul>
			<h3 class="help-subtitle" id="edit-admins">', $txt['manual_profile_sub_modify_profile'], '</h3>
			<ul class="bullit-list">
				<li>', $txt['manual_profile_sub_acct_settings'], '</li>
				<li>', $txt['manual_profile_sub_forum_profile_info'], '</li>
			</ul>
			<h3 class="help-subtitle" id="actions-admins">', $txt['manual_profile_sub_actions2'], '</h3>
			<ul class="bullit-list">
				<li>', $txt['manual_profile_sub_ban_user'], '</li>
				<li>', $txt['manual_profile_sub_delete_acct'], '</li>
			</ul>


</div>
<div class="clear"><!--no content--></div>	
			';

}

function template_manual_posting()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_posting_forum_about_part1'], '<a href="', $scripturl, '?action=help;page=post#bbcref">', $txt['manual_posting_forum_about_link_bbcref'], '</a>', $txt['manual_posting_forum_about_part2'], '<a href="', $scripturl, '?action=help;page=post#smileysref">', $txt['manual_posting_forum_about_link_bbcref_smileysref'], '</a>', $txt['manual_posting_forum_about_part3'], '</p>
	<p>', $txt['manual_posting_please_note'], '</p>
	<ol>
		<li>
			<a href="', $scripturl, '?action=help;page=post#basics">', $txt['manual_posting_sec_posting_basics'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=post#newtopic">', $txt['manual_posting_starting_topic'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#newpoll">', $txt['manual_posting_start_poll'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#calendar">', $txt['manual_posting_post_event'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#reply">', $txt['manual_posting_replying'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#quote">', $txt['manual_posting_quote_post'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#modify">', $txt['manual_posting_modify_delete'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;page=post#standard">', $txt['manual_posting_sec_posting_options'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=post#messageicon">', $txt['manual_posting_sub_message_icon'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#bbc">', $txt['manual_posting_sub_bbc'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#smileys">', $txt['manual_posting_sub_smileys'], '</a></li>
			</ol>
		</li>
		<li><a href="', $scripturl, '?action=help;page=post#tags">', $txt['manual_posting_sec_tags'], '</a></li>
		<li>
			<a href="', $scripturl, '?action=help;page=post#additional">', $txt['manual_posting_sec_additional_options'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=post#notify">', $txt['manual_posting_notify'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#return">', $txt['manual_posting_return'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#nosmileys">', $txt['manual_posting_no_smiley'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#attachments">', $txt['manual_posting_sub_attach'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;page=post#references">', $txt['manual_posting_sec_references'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=post#bbcref">', $txt['manual_posting_sub_SMF_bbc'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=post#smileysref">', $txt['manual_posting_sub_help_smileys'], '</a></li>
			</ol>
		</li>
	</ol>
	<h2 class="help-title"id="basics">', $txt['manual_posting_sec_posting_basics'], '</h2>
	<h3 class="help-subtitle" id="newtopic">', $txt['manual_posting_starting_topic'], '</h3>
	<p>', $txt['manual_posting_starting_topic_desc_part1'], '<a href="', $scripturl, '?action=help;page=index#message">', $txt['manual_posting_starting_topic_desc_link_index_message'], '</a>', $txt['manual_posting_starting_topic_desc_part2'], '<a href="', $scripturl, '?action=help;page=post#standard">', $txt['manual_posting_starting_topic_desc_link_index_message_standard'], '</a>', $txt['manual_posting_starting_topic_desc_part3'], '</p>
	
	
	
	<div class="help-example">
		
			<form action="', $scripturl, '?action=help;page=post" method="post" accept-charset="', $context['character_set'], '" style="margin: 0;">
				
				<div class="bread-nav"><p class="subtitle"><img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_posting_forum_name'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_posting_cat_name'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#message" class="nav">', $txt['manual_posting_board_name'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_main.gif" alt="| " border="0" /> <img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><em>', $txt['manual_posting_start_topic'], '</em></strong></p></div>

				<div class="border">
				<h3 class="title">', $txt['manual_posting_start_topic'], '</h3>
				<div class="row1">
				<div class="row"><p><strong>', $txt['manual_posting_subject'], ':</strong> <input type="text" name="subject" size="80" maxlength="80" tabindex="1" /></p></div>
				</div>
				<div class="row1"><p><em><a href="', $scripturl, '?action=help;page=post#standard">', $txt['manual_posting_std_options'], '&nbsp;', $txt['manual_posting_omit_clarity'], '</a></em></p></div>
				<div class="row1-center">
									<textarea class="editor" name="message" rows="12" cols="60" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onchange="storeCaret(this);" tabindex="2">
</textarea>
<br />
<p><em><a href="', $scripturl, '?action=help;page=post#additional">', $txt['manual_posting_sec_additional_options'], '&nbsp;', $txt['manual_posting_omit_clarity'], '</a></em></p>
</div>
<div class="row1-center">
<p class="smalltext">', $txt['manual_posting_shortcuts'], '</p>

</div>
<div class="subtitle" style="text-align:center;"><input type="button" class="button" accesskey="s" tabindex="3" value="', $txt['manual_posting_posts'], '" /> <input type="button" class="button" accesskey="p" tabindex="4" value="', $txt['manual_posting_preview'], '" /></div>
</div>
</form>
		</div>
<br />
	
	
	
	<ul class="bullit-list">
		<li>', $txt['manual_posting_nav_tree'], '</li>
		<li>', $txt['manual_posting_spell_check'], '</li>
	</ul>
	<h3 class="help-subtitle" id="newpoll">', $txt['manual_posting_start_poll'], '</h3>
	<p>', $txt['manual_posting_poll_desc_part1'], '<a href="', $scripturl, '?action=help;page=post#newtopic">', $txt['manual_posting_poll_desc_link_newtopic'], '</a>', $txt['manual_posting_poll_desc_part2'], '</p>
	<p>', $txt['manual_posting_poll_options'], '</p>
	<p>', $txt['manual_posting_poll_note'], '</p>
	<h3 class="help-subtitle" id="calendar">', $txt['manual_posting_post_event'], '</h3>
	<p>', $txt['manual_posting_event_desc_part1'], '<a href="', $scripturl, '?action=help;page=index#main">', $txt['manual_posting_event_desc_link_index_main'], '</a>', $txt['manual_posting_event_desc_part2'], '</p>
	<h3 class="help-subtitle" id="reply">', $txt['manual_posting_replying'], '</h3>
	<p>', $txt['manual_posting_replying_desc_part1'], '<a href="', $scripturl, '?action=help;page=post#newtopic">', $txt['manual_posting_replying_desc_link_newtopic'], '</a>', $txt['manual_posting_replying_desc_part2'], '</p>
	<p>', $txt['manual_posting_quick_reply_part1'], '<a href="', $scripturl, '?action=help;page=post#bbc">', $txt['manual_posting_quick_reply_link_bbc'], '</a>', $txt['manual_posting_quick_reply_part2'], '<a href="', $scripturl, '?action=help;page=post#smileys">', $txt['manual_posting_quick_reply_link_bbc_smileys'], '</a>', $txt['manual_posting_quick_reply_part3'], '</p>
	<h3 class="help-subtitle" id="quote">', $txt['manual_posting_quote_post'], '</h3>
	<p>', $txt['manual_posting_quote_desc'], '</p>
	<ul class="bullit-list">
		<li>', $txt['manual_posting_quote_both_part1'], '<a href="', $scripturl, '?action=help;page=post#bbc">', $txt['manual_posting_quote_both_link_bbc'], '</a>', $txt['manual_posting_quote_both_part2'], '</li>
		<li>', $txt['manual_posting_quote_independant_part1'], '<a href="', $scripturl, '?action=help;page=post#bbcref">', $txt['manual_posting_quote_independant_link_bbcref'], '</a>', $txt['manual_posting_quote_independant_part2'], '</li>
	</ul>
	<h3 class="help-subtitle" id="modify">', $txt['manual_posting_modify_delete'], '</h3>
	<p>', $txt['manual_posting_modify_desc'], '</p>
	<p>', $txt['manual_posting_delete_desc'], '</p>
	<h2 class="help-title"id="standard">', $txt['manual_posting_sec_posting_options'], '</h2>
	
	
	
	<div class="help-example">
			<script language="JavaScript1.2" type="text/javascript">
//<![CDATA[
			function showimage()
			{
					document.images.icons.src = "', $settings['images_url'], '/post/" + document.forms.postmodify.icon.options[document.forms.postmodify.icon.selectedIndex].value + ".gif";
					document.images.icons.src ="', $settings['images_url'], '/post/" + document.forms.postmodify.icon.options[document.forms.postmodify.icon.selectedIndex].value + ".gif";
			}
			var currentSwap = false;
			function swapOptions()
			{
					document.getElementById("postMoreExpand").src = smf_images_url + "/" + (currentSwap ? "collapse.gif" : "expand.gif");
					document.getElementById("postMoreExpand").alt = currentSwap ? "-" : "+";
					document.getElementById("postMoreOptions").style.display = currentSwap ? "" : "none";
					if (document.getElementById("postAttachment"))
								document.getElementById("postAttachment").style.display = currentSwap ? "" : "none";
					if (document.getElementById("postAttachment2"))
								document.getElementById("postAttachment2").style.display = currentSwap ? "" : "none";
					currentSwap = !currentSwap;
			}
//]]>
</script>
			<form action="', $scripturl, '?action=help;page=post" method="post" accept-charset="', $context['character_set'], '" name="postmodify" style="margin: 0;" id="postmodify">
				<div class="border">
				<div class="row1">
				
										<script language="JavaScript" type="text/javascript">
//<![CDATA[
										function bbc_highlight(something, mode)
										{
													something.style.backgroundImage = "url(" + smf_images_url + (mode ? "/bbc/bbc_hoverbg.gif)" : "/bbc/bbc_bg.gif)");
										}
//]]>
</script>
										<a href="javascript:void(0);" onclick="surroundText(\'[b]\', \'[/b]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/bold.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_bold_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[i]\', \'[/i]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/italicize.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_italicize_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[u]\', \'[/u]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/underline.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_underline_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[s]\', \'[/s]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/strike.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_strike_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[glow=red,2,300]\', \'[/glow]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/glow.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_glow_example'], '" /></a>
										<a href="javascript:void(0);" onclick="surroundText(\'[shadow=red,left]\', \'[/shadow]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/shadow.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_shadow_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[move]\', \'[/move]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/move.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_move_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[pre]\', \'[/pre]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/pre.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_pre_example'], '" /></a>
										<a href="javascript:void(0);" onclick="surroundText(\'[left]\', \'[/left]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/left.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_left_example'], '" /></a>
										<a href="javascript:void(0);" onclick="surroundText(\'[center]\', \'[/center]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/center.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_center_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[right]\', \'[/right]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/right.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_right_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[hr]\', \'\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/hr.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_hr_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[size=10pt]\', \'[/size]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/size.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_size_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[font=Verdana]\', \'[/font]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/face.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_face_example'], '" /></a>
										<select onchange="surroundText(\'[color=\'+this.options[this.selectedIndex].value+\']\', \'[/color]\', document.forms.postmodify.message); this.selectedIndex = 0;" style="margin-bottom: 1ex; margin-left: 2ex;">
											<option value="" selected="selected">
												', $txt['manual_posting_Change_Color'], '
											</option>
											<option value="Black">
												', $txt['manual_posting_color_black'], '
											</option>
											<option value="Red">
												', $txt['manual_posting_color_red'], '
											</option>
											<option value="Yellow">
												', $txt['manual_posting_color_yellow'], '
											</option>
											<option value="Pink">
												', $txt['manual_posting_color_pink'], '
											</option>
											<option value="Green">
												', $txt['manual_posting_color_green'], '
											</option>
											<option value="Orange">
												', $txt['manual_posting_color_orange'], '
											</option>
											<option value="Purple">
												', $txt['manual_posting_color_purple'], '
											</option>
											<option value="Blue">
												', $txt['manual_posting_color_blue'], '
											</option>
											<option value="Beige">
												', $txt['manual_posting_color_beige'], '
											</option>
											<option value="Brown">
												', $txt['manual_posting_color_brown'], '
											</option>
											<option value="Teal">
												', $txt['manual_posting_color_teal'], '
											</option>
											<option value="Navy">
												', $txt['manual_posting_color_navy'], '
											</option>
											<option value="Maroon">
												', $txt['manual_posting_color_maroon'], '
											</option>
											<option value="LimeGreen">
												', $txt['manual_posting_color_lime'], '
											</option>
										</select><br />
										<a href="javascript:void(0);" onclick="surroundText(\'[flash=200,200]\', \'[/flash]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/flash.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_flash_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[img]\', \'[/img]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/img.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_img_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[url]\', \'[/url]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/url.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_url_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[email]\', \'[/email]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/email.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_email_example'], '" /></a>
										<a href="javascript:void(0);" onclick="surroundText(\'[ftp]\', \'[/ftp]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/ftp.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_ftp_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[table]\', \'[/table]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/table.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_table_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[tr]\', \'[/tr]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/tr.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_tr_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[td]\', \'[/td]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/td.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_td_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[sup]\', \'[/sup]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/sup.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_sup_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[sub]\', \'[/sub]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/sub.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_sub_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[tt]\', \'[/tt]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/tele.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_tele_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" />
										<a href="javascript:void(0);" onclick="surroundText(\'[code]\', \'[/code]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/code.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_code_example'], '" /></a><a href="javascript:void(0);" onclick="surroundText(\'[quote]\', \'[/quote]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/quote.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_quote_example'], '" /></a><img src="', $settings['images_url'], '/bbc/divider.gif" alt="|" style="margin: 0 3px 0 3px;" /><a href="javascript:void(0);" onclick="surroundText(\'[list][li]\', \'[/li][li][/li][/list]\', document.forms.postmodify.message);"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/list.gif" align="bottom" width="23" height="22" border="0" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" alt="', $txt['manual_posting_list_example'], '" /></a> &nbsp;
									
										<a href="javascript:void(0);" onclick="replaceText(\' :)\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/smiley.gif" align="bottom" alt="', $txt['manual_posting_smiley_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' ;)\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/wink.gif" align="bottom" alt="', $txt['manual_posting_wink_code'], '" border="0" /></a> 
										<a href="javascript:void(0);" onclick="replaceText(\' :D\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/cheesy.gif" align="bottom" alt="', $txt['manual_posting_cheesy_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' ;D\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/grin.gif" align="bottom" alt="', $txt['manual_posting_grin_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' &gt;:(\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/angry.gif" align="bottom" alt="', $txt['manual_posting_angry_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :(\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/sad.gif" align="bottom" alt="', $txt['manual_posting_sad_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :o\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/shocked.gif" align="bottom" alt="', $txt['manual_posting_shocked_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' 8)\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/cool.gif" align="bottom" alt="', $txt['manual_posting_cool_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' ???\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/huh.gif" align="bottom" alt="', $txt['manual_posting_huh_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' ::)\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/rolleyes.gif" align="bottom" alt="', $txt['manual_posting_rolleyes_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :P\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/tongue.gif" align="bottom" alt="', $txt['manual_posting_tongue_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :-[\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/embarrassed.gif" align="bottom" alt="', $txt['manual_posting_embarrassed_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :-X\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/lipsrsealed.gif" align="bottom" alt="', $txt['manual_posting_lipsrsealed_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :-\\\\\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/undecided.gif" align="bottom" alt="', $txt['manual_posting_undecided_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :-*\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/kiss.gif" align="bottom" alt="', $txt['manual_posting_kiss_code'], '" border="0" /></a> <a href="javascript:void(0);" onclick="replaceText(\' :\\\'(\', document.forms.postmodify.message);"><img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/cry.gif" align="bottom" alt="', $txt['manual_posting_cry_code'], '" border="0" /></a>
										
										</div>
										<div class="row1-center">
									<textarea class="editor" name="message" rows="12" cols="60" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onchange="storeCaret(this);" tabindex="2">
</textarea></div></div>
			</form>
		
	</div><br />
	
	
	
	
	<h3 class="help-subtitle" id="bbc">', $txt['manual_posting_sub_bbc'], '</h3>
	<p>', $txt['manual_posting_bbc_desc'], '</p>
	<p>', $txt['manual_posting_bbc_ref_part1'], '<a href="', $scripturl, '?action=help;page=post#bbcref">', $txt['manual_posting_bbc_ref_link_bbcref'], '</a>', $txt['manual_posting_bbc_ref_part2'], '</p>
	<h3 class="help-subtitle" id="smileys">', $txt['manual_posting_sub_smileys'], '</h3>
	<p>', $txt['manual_posting_smiley_desc_part1'], '<a href="', $scripturl, '?action=help;page=post#nosmileys">', $txt['manual_posting_smiley_desc_link_nosmileys'], '</a>', $txt['manual_posting_smiley_desc_part2'], '</p>
	<p>', $txt['manual_posting_smiley_ref_part1'], '<a href="', $scripturl, '?action=help;page=post#smileysref">', $txt['manual_posting_smiley_ref_link_smileysref'], '</a>', $txt['manual_posting_smiley_ref_part2'], '</p>
	<h2 class="help-title"id="tags">', $txt['manual_posting_sec_tags'], '</h2>
	<p>', $txt['manual_posting_tags_desc_part1'], '<a href="', $scripturl, '?action=help;page=post#bbcref">', $txt['manual_posting_tags_desc_link_bbcref'], '</a>', $txt['manual_posting_tags_desc_part2'], '</p>
	<p>', $txt['manual_posting_note_tags'], '</p>
	<h2 class="help-title"id="additional">', $txt['manual_posting_sec_additional_options'], '</h2>
	<p>', $txt['manual_posting_sec_additional_options_desc'], '</p>
	
	
	
	<div class="help-example">
	
			<script language="JavaScript1.2" type="text/javascript">
//<![CDATA[
			var currentSwap = false;
			function swapOptions()
			{
						document.getElementById("postMoreExpand").src = smf_images_url + "/" + (currentSwap ? "collapse.gif" : "expand.gif");
						document.getElementById("postMoreExpand").alt = currentSwap ? "-" : "+";
						document.getElementById("postMoreOptions").style.display = currentSwap ? "" : "none";
						if (document.getElementById("postAttachment"))
								document.getElementById("postAttachment").style.display = currentSwap ? "" : "none";
						if (document.getElementById("postAttachment2"))
								document.getElementById("postAttachment2").style.display = currentSwap ? "" : "none";
						currentSwap = !currentSwap;
			}
//]]>
</script>
			<form action="', $scripturl, '?action=help;page=post" method="post" accept-charset="', $context['character_set'], '" style="margin: 0;">
			<div class="border">
			<h4 class="subtitle"><a href="javascript:swapOptions();"><img class="subtitle-collapse-icon" src="', $settings['images_url'], '/expand.gif" alt="+" border="0" id="postMoreExpand" name="postMoreExpand" /></a> <a href="javascript:swapOptions();" class="board">', $txt['manual_posting_sec_additional_options'], '...</a></h4>

										<div id="postMoreOptions">
										<div class="row1">
										<p><input type="checkbox" class="check" />&nbsp;', $txt['manual_posting_notify'], '</p>
										<p><input type="checkbox" class="check" />&nbsp;', $txt['manual_posting_return'], '</p>
										<p><input type="checkbox" class="check" />&nbsp;', $txt['manual_posting_no_smiley'], '</p>
										</div>
										</div>
										<div id="post', $txt['manual_posting_attach'], 'ment2">
										<h4 class="subtitle">', $txt['manual_posting_attach'], ':</h4>
										<div class="row1">
										<input type="file" size="48" name="attachment[]" /><br />
										<input type="file" size="48" name="attachment[]" /><br />
									<p>', $txt['manual_posting_allowed_types'], '</p>
									<p>', $txt['manual_posting_max_size'], '</p>
									</div>
									</div>
										<script language="JavaScript" type="text/javascript">
//<![CDATA[
										swapOptions();
//]]>
</script> 
<div class="row1-center"><p class="smalltext">', $txt['manual_posting_shortcuts'], '</p></div>
<div class="subtitle" style="text-align:center;"><input class="button" type="button" accesskey="s" tabindex="3" value="', $txt['manual_posting_posts'], '" /> <input class="button" type="button" accesskey="p" tabindex="4" value="', $txt['manual_posting_preview'], '" /></div>
</div>
										
			</form>
		</div>

	
	
	
	<h3 class="help-subtitle" id="notify">', $txt['manual_posting_sub_notify'], '</h3>
	<p>', $txt['manual_posting_notify_desc'], '</p>
	<h3 class="help-subtitle" id="return">', $txt['manual_posting_sub_return'], '</h3>
	<p>', $txt['manual_posting_return_desc'], '</p>
	<h3 class="help-subtitle" id="nosmileys">', $txt['manual_posting_sub_no_smiley'], '</h3>
	<p>', $txt['manual_posting_no_smiley_desc_part1'], '<a href="', $scripturl, '?action=help;page=post#smileysref">', $txt['manual_posting_no_smiley_desc_link_smileysref'], '</a>', $txt['manual_posting_no_smiley_desc_part2'], '</p>
	<h3 class="help-subtitle" id="attachments">', $txt['manual_posting_sub_attach'], '</h3>
	<p>', $txt['manual_posting_attach_desc_part1'], '<a href="', $scripturl, '?action=help;page=post#modify">', $txt['manual_posting_attach_desc_link_modify'], '</a>', $txt['manual_posting_attach_desc_part2'], '</p>
	<ul class="bullit-list">
		<li>', $txt['manual_posting_attach_desc2'], '</li>
		<li>', $txt['manual_posting_most_forums_attach'], '</li>
	</ul>
	<h2 class="help-title"id="references">', $txt['manual_posting_sec_references'], '</h2>
	<h3 class="help-subtitle" id="bbcref">', $txt['manual_posting_sub_SMF_bbc'], '</h3>
	<p>', $txt['manual_posting_sub_smf_bbc_desc'], '</p>
	
	
	
	<table id="reference1" class="bordercolor" cellspacing="1" cellpadding="4">
		<tr>
			<th>', $txt['manual_posting_header_name'], '</th>
			<th>', $txt['manual_posting_header_button'], '</th>
			<th>', $txt['manual_posting_header_code'], '</th>
			<th>', $txt['manual_posting_header_output'], '</th>
			<th>', $txt['manual_posting_header_comments'], '</th>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_bold'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/bold.gif" alt="', $txt['manual_posting_bbc_bold'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_bold_code'], '</td>
			<td><b>', $txt['manual_posting_bold_output'], '</b></td>
			<td>', $txt['manual_posting_bold_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_italic'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/italicize.gif" alt="', $txt['manual_posting_bbc_italic'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_italic_code'], '</td>
			<td><i>', $txt['manual_posting_italic_output'], '</i></td>
			<td>', $txt['manual_posting_italic_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_underline'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/underline.gif" alt="', $txt['manual_posting_bbc_underline'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_underline_code'], '</td>
			<td><u>', $txt['manual_posting_underline_output'], '</u></td>
			<td>', $txt['manual_posting_underline_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_strike'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/strike.gif" alt="', $txt['manual_posting_bbc_strike'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_strike_code'], '</td>
			<td><s>', $txt['manual_posting_strike_output'], '</s></td>
			<td>', $txt['manual_posting_strike_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_glow'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/glow.gif" alt="', $txt['manual_posting_bbc_glow'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_glow_code'], '</td>
			<td>
				<div style="filter: Glow(color=red, strength=2); width: 30px;">
					', $txt['manual_posting_glow_output'], '
				</div>
			</td>
			<td>', $txt['manual_posting_glow_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_shadow'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/shadow.gif" alt="', $txt['manual_posting_bbc_shadow'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_shadow_code'], '</td>
			<td>
				<div style="filter: Shadow(color=red, direction=240); width: 30px;">
					', $txt['manual_posting_shadow_output'], '
				</div>
			</td>
			<td>', $txt['manual_posting_shadow_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_move'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/move.gif" alt="', $txt['manual_posting_bbc_move'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_move_code'], '</td>
			<td><marquee>', $txt['manual_posting_move_output'], '</marquee></td>
			<td>', $txt['manual_posting_move_comment'], '</td>
		</tr>
				<tr>
			<td>', $txt['manual_posting_bbc_pre'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/pre.gif" alt="', $txt['manual_posting_bbc_pre'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>[pre]Simple<br />
			&nbsp;&nbsp;Machines<br />
			&nbsp;&nbsp;&nbsp;&nbsp;Forum[/pre]</td>
			<td>
				<pre>
Simple
  Machines
						Forum
</pre>
			</td>
			<td>', $txt['manual_posting_pre_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_left'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/left.gif" alt="', $txt['manual_posting_bbc_left'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_left_code'], '</td>
			<td>
				<p align="left">', $txt['manual_posting_left_output'], '</p>
			</td>
			<td>', $txt['manual_posting_left_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_centered'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/center.gif" alt="', $txt['manual_posting_bbc_centered'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_centered_code'], '</td>
			<td>
				<center>
					', $txt['manual_posting_centered_output'], '
				</center>
			</td>
			<td>', $txt['manual_posting_centered_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_right'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/right.gif" alt="', $txt['manual_posting_bbc_right'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_right_code'], '</td>
			<td>
				<p align="right">', $txt['manual_posting_right_output'], '</p>
			</td>
			<td>', $txt['manual_posting_right_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_rtl'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_rtl_code'], '</td>
			<td>
				<div dir="rtl">
					', $txt['manual_posting_rtl_output'], '
				</div>
			</td>
			<td>', $txt['manual_posting_rtl_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_ltr'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_ltr_code'], '</td>
			<td>
				<div dir="ltr">
					', $txt['manual_posting_ltr_output'], '
				</div>
			</td>
			<td>', $txt['manual_posting_ltr_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_hr'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/hr.gif" alt="', $txt['manual_posting_bbc_hr'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_hr_code'], '</td>
			<td>
				<hr />
			</td>
			<td>', $txt['manual_posting_hr_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_size'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/size.gif" alt="', $txt['manual_posting_bbc_size'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_size_code'], '</td>
			<td><span style="font-size: 10pt;">', $txt['manual_posting_size_output'], '</span></td>
			<td>', $txt['manual_posting_size_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_font'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/face.gif" alt="', $txt['manual_posting_bbc_font'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_font_code'], '</td>
			<td><span style="font-family: Verdana;">', $txt['manual_posting_font_output'], '</span></td>
			<td>', $txt['manual_posting_font_comment'], '</td>
		</tr>
				<tr>
			<td>', $txt['manual_posting_bbc_color'], '</td>
			<td><select>
				<option value="" selected="selected">
					', $txt['manual_posting_Change_Color'], '
				</option>
				<option value="Black">
					', $txt['manual_posting_color_black'], '
				</option>
				<option value="Red">
					', $txt['manual_posting_color_red'], '
				</option>
				<option value="Yellow">
					', $txt['manual_posting_color_yellow'], '
				</option>
				<option value="Pink">
					', $txt['manual_posting_color_pink'], '
				</option>
				<option value="Green">
					', $txt['manual_posting_color_green'], '
				</option>
				<option value="Orange">
					', $txt['manual_posting_color_orange'], '
				</option>
				<option value="Purple">
					', $txt['manual_posting_color_purple'], '
				</option>
				<option value="Blue">
					', $txt['manual_posting_color_blue'], '
				</option>
				<option value="Beige">
					', $txt['manual_posting_color_beige'], '
				</option>
				<option value="Brown">
					', $txt['manual_posting_color_brown'], '
				</option>
				<option value="Teal">
					', $txt['manual_posting_color_teal'], '
				</option>
				<option value="Navy">
					', $txt['manual_posting_color_navy'], '
				</option>
				<option value="Maroon">
					', $txt['manual_posting_color_maroon'], '
				</option>
				<option value="LimeGreen">
					', $txt['manual_posting_color_lime'], '
				</option>
			</select></td>
			<td>', $txt['manual_posting_color_code'], '</td>
			<td><span style="color: red;">', $txt['manual_posting_color_output'], '</span></td>
			<td>', $txt['manual_posting_color_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_flash'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/flash.gif" alt="', $txt['manual_posting_bbc_flash'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_flash_code'], '</td>
			<td><a href="http://somesite/somefile.swf" class="board" target="_blank">', $txt['manual_posting_flash_output'], '</a></td>
			<td>', $txt['manual_posting_flash_comment'], '</td>
		</tr>
		<tr>
			<td rowspan="2">', $txt['manual_posting_bbc_img'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/img.gif" alt="', $txt['manual_posting_bbc_img'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_img_top_code'], '</td>
			<td><img src="', $settings['images_url'], '/on.gif" alt="" /></td>
			<td rowspan="2">', $txt['manual_posting_img_top_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_img_bottom_code'], '</td>
			<td><img src="', $settings['images_url'], '/on.gif" width="48" height="48" alt="" /></td>
		</tr>
		<tr>
			<td rowspan="2">', $txt['manual_posting_bbc_url'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/url.gif" alt="', $txt['manual_posting_bbc_url'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_url_code'], '</td>
			<td><a href="http://somesite" class="board" target="_blank">', $txt['manual_posting_url_output'], '</a></td>
			<td rowspan="2">', $txt['manual_posting_url_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_url_bottom_code'], '</td>
			<td><a href="http://somesite" class="board" target="_blank">', $txt['manual_posting_url_bottom_output'], '</a></td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_email'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/email.gif" alt="', $txt['manual_posting_bbc_email'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_email_code'], '</td>
			<td><a href="mailto:someone@somesite" class="board">', $txt['manual_posting_email_output'], '</a></td>
			<td>', $txt['manual_posting_email_comment'], '</td>
		</tr>
		<tr>
			<td rowspan="2">', $txt['manual_posting_bbc_ftp'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/ftp.gif" alt="', $txt['manual_posting_bbc_ftp'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_ftp_code'], '</td>
			<td><a href="ftp://somesite/somefile" class="board" target="_blank">', $txt['manual_posting_ftp_output'], '</a></td>
			<td rowspan="2">', $txt['manual_posting_ftp_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_ftp_bottom_code'], '</td>
			<td><a href="ftp://somesite/somefile" class="board" target="_blank">', $txt['manual_posting_ftp_bottom_output'], '</a></td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_table'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/table.gif" alt="', $txt['manual_posting_bbc_table'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_table_code'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_table_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_row'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/tr.gif" alt="', $txt['manual_posting_bbc_row'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_row_code'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_row_comment'], '</td>
		</tr>
				<tr>
			<td rowspan="2">', $txt['manual_posting_bbc_column'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/td.gif" alt="', $txt['manual_posting_bbc_column'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_column_code'], '</td>
			<td>
				<table>
					<tr>
						<td valign="top">', $txt['manual_posting_column_output'], '</td>
					</tr>
				</table>
			</td>
			<td rowspan="2">', $txt['manual_posting_column_comment'], '</td>
		</tr>
		<tr>
			<td>[table][tr][td]SMF[/td]<br />
			[td]Bulletin[/td][/tr]<br />
			[tr][td]Board[/td]<br />
			[td]Code[/td][/tr][/table]</td>
			<td>
				<table>
					<tr>
						<td valign="top">SMF</td>
						<td valign="top">Bulletin</td>
					</tr>
					<tr>
						<td valign="top">Board</td>
						<td valign="top">Code</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_sup'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/sup.gif" alt="', $txt['manual_posting_bbc_sup'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_sup_code'], '</td>
			<td><sup>', $txt['manual_posting_sup_output'], '</sup></td>
			<td>', $txt['manual_posting_sup_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_sub'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/sub.gif" alt="', $txt['manual_posting_bbc_sub'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_sub_code'], '</td>
			<td><sub>', $txt['manual_posting_sub_output'], '</sub></td>
			<td>', $txt['manual_posting_sub_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_tt'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/tele.gif" alt="', $txt['manual_posting_bbc_tt'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_tt_code'], '</td>
			<td><tt>', $txt['manual_posting_tt_output'], '</tt></td>
			<td>', $txt['manual_posting_tt_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_code'], '</td>
			<td><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/code.gif" alt="', $txt['manual_posting_bbc_code'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_code_code'], '</td>
			<td>
				<div class="codeheader">
					Code:
				</div>
				<div class="code">
					<font color="#000000"><font color="#0000BB">&lt;?php phpinfo</font><font color="#007700">();</font> <font color="#0000BB">?&gt;</font></font>
				</div>
			</td>
			<td>', $txt['manual_posting_code_comment'], '</td>
		</tr>
		<tr>
			<td rowspan="2">', $txt['manual_posting_bbc_quote'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/quote.gif" alt="', $txt['manual_posting_bbc_quote'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_quote_code'], '</td>
			<td>
				<div class="', $txt['manual_posting_quote_output'], 'header">
					Quote
				</div>
				<div class="quote">
					', $txt['manual_posting_quote_output'], '
				</div>
			</td>
			<td rowspan="2">', $txt['manual_posting_quote_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_quote_buttom_code'], '</td>
			<td>
				<div class="', $txt['manual_posting_quote_buttom_output'], 'header">
					Quote from: author
				</div>
				<div class="quote">
					', $txt['manual_posting_quote_buttom_output'], '
				</div>
			</td>
		</tr>
		<tr>
			<td rowspan="2">', $txt['manual_posting_bbc_list'], '</td>
			<td rowspan="2"><img onmouseover="bbc_highlight(this, true);" onmouseout="bbc_highlight(this, false);" src="', $settings['images_url'], '/bbc/list.gif" alt="', $txt['manual_posting_bbc_list'], '" style="background-image: url(', $settings['images_url'], '/bbc/bbc_bg.gif); margin: 1px 2px 1px 1px;" /></td>
			<td>', $txt['manual_posting_list_code'], '</td>
			<td>', $txt['manual_posting_list_output'], '</td>
			<td rowspan="2">', $txt['manual_posting_list_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_list_buttom_code'], '</td>
			<td>', $txt['manual_posting_list_buttom_output'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_abbr'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_abbr_code'], '</td>
			<td><abbr title="exempli gratia">', $txt['manual_posting_abbr_output'], '</abbr></td>
			<td>', $txt['manual_posting_abbr_comment'], '</td>
		</tr>
		<tr>
			<td>', $txt['manual_posting_bbc_acro'], '</td>
			<td>*</td>
			<td>', $txt['manual_posting_acro_code'], '</td>
			<td><acronym title="Simple Machines Forum">', $txt['manual_posting_acro_output'], '</acronym></td>
			<td>', $txt['manual_posting_acro_comment'], '</td>
		</tr>
	</table><br />
	<h3 class="help-subtitle" id="smileysref">', $txt['manual_posting_sub_help_smileys'], '</h3>
	<p>', $txt['manual_posting_smileys_help_desc'], '</p>
	<div class="border">
	<div id="reference2" class="row1">
	<dl>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_smiley_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/smiley.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:)</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_wink_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/wink.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>;)</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_cheesy_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/cheesy.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:D</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_grin_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/grin.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>;D</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_angry_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/angry.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>&gt;:(</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_sad_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/sad.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:(</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_shocked_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/shocked.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:o</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_cool_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/cool.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>8)</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_huh_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/huh.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>???</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_rolleyes_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/rolleyes.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>::)</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_tongue_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/tongue.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:P</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_embarrassed_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/embarrassed.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:-[</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_lipsrsealed_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/lipsrsealed.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:-X</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_undecided_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/undecided.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:-\</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_kiss_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/kiss.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:-*</code></p>
	</dd>
	<dt><strong>', $txt['manual_posting_smileys_help_name'], ': ', $txt['manual_posting_cry_help_name'], '</strong></dt>
	<dd>
	<p>', $txt['manual_posting_smileys_help_img'], ': <img src="', $modSettings['smileys_url'], '/', $context['user']['smiley_set'], '/cry.gif" alt="" /></p>
	<p>', $txt['manual_posting_smileys_help_code'], ': <code>:\'(</code></p>
	</dd>
	</dl>
	</div>
	</div>
	<br />
	<p>', $txt['manual_posting_smiley_parse'], '</p>';
}

// The register help page.
function template_manual_register()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
		<p>', $txt['manual_registering_you_have_arrived_part1'], '<a href="', $scripturl, '?action=help;page=profile', $txt['manual_registering_you_have_arrived_link_profile'], '">profile</a>', $txt['manual_registering_you_have_arrived_part2'], '<a href="', $scripturl, '?action=help;page=pm">', $txt['manual_registering_you_have_arrived_link_profile_pm'], '</a>', $txt['manual_registering_you_have_arrived_part3'], '</p>
	<ol>
		<li><a href="', $scripturl, '?action=help;page=registering#how-to">', $txt['manual_registering_sec_register'], '</a></li>
		<li><a href="', $scripturl, '?action=help;page=registering#screen">', $txt['manual_registering_sec_reg_screen'], '</a></li>
	</ol>
	<h2 class="help-title"id="how-to">', $txt['manual_registering_sec_register'], '</h2>
	<p>', $txt['manual_registering_register_desc'], '</p>
	<ul class="bullit-list">
		<li>', $txt['manual_registering_select_register_part1'], '<a href="', $scripturl, '?action=help;page=index#main">', $txt['manual_registering_select_register_link_index_main'], '</a>', $txt['manual_registering_select_register_part2'], '</li>
		<li>', $txt['manual_registering_login_Scr_part1'], '<a href="', $scripturl, '?action=help;page=index#main">', $txt['manual_registering_login_Scr_link_index_main'], '</a>', $txt['manual_registering_login_Scr_part2'], '</li>
	</ul>
	
	<div class="help-example">
	<div class="border">
		<h3 class="title">', $txt['manual_registering_warning'], '</h3>
		<div class="row1">
		<p>', $txt['manual_registering_warning_desc_1'], '<br />
			', $txt['manual_registering_warning_desc_2'], '<a href="', $scripturl, '?action=help;page=registering#screen" class="board">', $txt['manual_registering_warning_desc_3'], '</a>', $txt['manual_registering_warning_desc_4'], '
			</p>
		</div>
	</div>
	</div>
	<br />
	
	<h2 class="help-title"id="screen">', $txt['manual_registering_sec_reg_screen'], '</h2>
	
	
	<div class="help-example">
			<form action="', $scripturl, '?action=help;page=registering" method="post" accept-charset="', $context['character_set'], '">
				<div class="border">
					<h3 class="title">', $txt['manual_registering_required_info'], '</h3>
					<div class="row1">
					<dl>
					<dt><strong>', $txt['manual_registering_choose_username'], ':</strong></dt>
					<dd><input type="text" size="20" maxlength="18" />
					<p><em>', $txt['manual_registering_caption_username'], '</em></p>
					</dd>
					<dt><strong>', $txt['manual_registering_email'], ':</strong></dt>
					<dd><input type="text" size="30" />
					<p><input type="checkbox" class="check" /> <label><em>', $txt['manual_registering_hide_email'], '</em></label> <em>', $txt['manual_registering_caption_email'], '</em></p>
					</dd>
					<dt><strong>', $txt['manual_registering_choose_pass'], ':</strong></dt>
					<dd><input type="password" size="30" /></dd>
					<dt><strong>', $txt['manual_registering_verify_pass'], ':</strong></dt>
					<dd><input type="password" size="30" /></dd>
					</dl>
					</div>
					<div class="row1-center">
					<p>', $txt['manual_registering_agreement'], '</p>
					<br />
					<label><input type="checkbox" class="check" /> <strong>', $txt['manual_registering_agree'], '</strong></label>
					</div>
					<div class="subtitle" style="text-align:center;">
					<input type="button" class="button" value="', $txt['manual_registering_register'], '" />
					</div>
				</div>
			</form>
		</div>
<br />


	<p>', $txt['manual_registering_reg_screen_requirements_part1'], '<a href="', $scripturl, '?action=help;page=loginout#screen">', $txt['manual_registering_reg_screen_requirements_link_loginout_screen'], '</a>', $txt['manual_registering_reg_screen_requirements_part2'], '</p>
	<ul class="bullit-list">
		<li>', $txt['manual_registering_email_activate'], '</li>
		<li>', $txt['manual_registering_admin_approve'], '</li>
	</ul>';
}

// The search help page.
function template_manual_search()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<p>', $txt['manual_searching_you_have_arrived'], '</p>
	<ol>
		<li><a href="', $scripturl, '?action=help;page=searching#starting">', $txt['manual_searching_sec_search'], '</a></li>
		<li>
			<a href="', $scripturl, '?action=help;page=searching#syntax">', $txt['manual_searching_sec_syntax'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=searching#quotes">', $txt['manual_searching_sub_quotes'], '</a></li>
			</ol>
		</li>
		<li>
			<a href="', $scripturl, '?action=help;page=searching#searching">', $txt['manual_searching_sec_simple_adv'], '</a>
			<ol class="la">
				<li><a href="', $scripturl, '?action=help;page=searching#simple">', $txt['manual_searching_sub_simple'], '</a></li>
				<li><a href="', $scripturl, '?action=help;page=searching#advanced">', $txt['manual_searching_sub_adv'], '</a></li>
			</ol>
		</li>
	</ol>
	<h2 class="help-title"id="starting">', $txt['manual_searching_sec_search'], '</h2>
	<p>', $txt['manual_searching_search_desc_part1'], '<a href="', $scripturl, '?action=help;page=index#main">', $txt['manual_searching_search_desc_link_index_main'], '</a>', $txt['manual_searching_search_desc_part2'], '</p>
	<h2 class="help-title"id="syntax">', $txt['manual_searching_sec_syntax'], '</h2>
	<p>', $txt['manual_searching_syntax_desc'], '</p>
	<h3 class="help-subtitle" id="quotes">', $txt['manual_searching_sub_quotes'], '</h3>
	<p>', $txt['manual_searching_quotes_desc'], '</p>
	<h2 class="help-title"id="searching">', $txt['manual_searching_sec_simple_adv'], '</h2>
	<h3 class="help-subtitle" id="simple">', $txt['manual_searching_sub_simple'], '</h3>
	<p>', $txt['manual_searching_simple_desc'], '</p>
	<h3 class="help-subtitle" id="advanced">', $txt['manual_searching_sub_adv'], '</h3>
	<p>', $txt['manual_searching_adv_desc'], '</p>
	<div class="help-example">
	
			<form action="', $scripturl, '?action=help;page=searching" method="post" accept-charset="', $context['character_set'], '">	
				<div class="bread-nav"><p class="subtitle"><img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=index#board" class="nav">', $txt['manual_searching_forum_name'], '</a></strong><br />
						<img src="', $settings['images_url'], '/icons/linktree_side.gif" alt="|-" border="0" /> <img src="', $settings['images_url'], '/icons/folder_open.gif" alt="+" border="0" />&nbsp; <strong><a href="', $scripturl, '?action=help;page=searching#advanced" class="nav">', $txt['manual_searching_search'], '</a></strong></p></div>
					
					<div class="border">
						<h3 class="title">', $txt['manual_searching_search_param'], '</h3>
						<h4 class="subtitle">', $txt['manual_searching_search_for'], ':</h4>
						<div class="row1">
						<input type="text" size="40" /> 
						<select>
						<option selected="selected">
						', $txt['manual_searching_match_all'], '
						</option>
						<option>
						', $txt['manual_searching_match_any'], '
						</option>
						</select>
						</div>
						<div class="row1">
						<div class="left">
						<p><strong>', $txt['manual_searching_by_user'], ':</strong></p>
						<input type="text" value="*" size="40" />
						</div>
						<div class="right">
						<p><strong>', $txt['manual_searching_search_order'], ':</strong></p>
						<select>
						<option selected="selected">
						', $txt['manual_searching_relevant_first'], '
						</option>
						<option>
						', $txt['manual_searching_big_first'], '
						</option>
						<option>
						', $txt['manual_searching_small_first'], '
						</option>
						<option>
						', $txt['manual_searching_recent_first'], '
						</option>
						<option>
						', $txt['manual_searching_oldest_first'], '
						</option>
						</select>
						</div>
						</div>
						<div class="row1">
						<div class="left">
						<p><strong>', $txt['manual_searching_msg_age'], ':</strong></p>
						', $txt['manual_searching_between'], '<input type="text" value="0" size="5" maxlength="5" />', $txt['manual_searching_and'], '<input type="text" value="9999" size="5" maxlength="5" />', $txt['manual_searching_days'], '.
						</div>
						<div class="right">
						<p><strong>', $txt['manual_searching_options'], ':</strong></p>
						<p><input type="checkbox" class="check" /> <label>', $txt['manual_searching_show_results'], '</label></p>
						<p><input type="checkbox" class="check" /> <label>', $txt['manual_searching_subject_only'], '</label></p>
						</div>
						</div>
						<h4 class="subtitle">', $txt['manual_searching_choose'], ':</h4>
						<div class="row1">
						<div class="float-l" style="width: 40%;"><span style="text-decoration: underline;">', $txt['manual_searching_cat'], '</span></div>
						<div class="float-l" style="width: 40%;"><input type="checkbox" id="brd2" name="brd[2]" value="2" checked="checked" class="check" /> <label for="brd2">', $txt['manual_searching_another_board'], '</label></div>
						<div class="float-l" style="width: 40%;"><input type="checkbox" id="brd1" name="brd[1]" value="1" checked="checked" class="check" /> <label for="brd1">', $txt['manual_searching_board_name'], '</label></div>
						<div class="clear"><!--no content--></div>
						<div class="row">
						<input type="checkbox" name="all" id="check_all" value="" checked="checked" onclick="invertAll(this, this.form, \'brd\');" class="check" /><em><label for="check_all">', $txt['manual_searching_check_all'], '</label></em>
						</div>
						</div>
						<div class="subtitle" style="text-align:center;">
						<input class="button" type="button" value="', $txt['manual_searching_search'], '" />
						</div>
						</div>
			</form>
		</div>
		<br />
	<ul class="bullit-list">
		<li>', $txt['manual_searching_nav_tree'], '</li>
		<li>', $txt['manual_searching_three_options_part1'], '<a href="', $scripturl, '?action=help;page=searching#syntax">', $txt['manual_searching_three_options_link_syntax'], '</a>', $txt['manual_searching_three_options_part2'], '</li>
		<li>', $txt['manual_searching_wildcard'], '</li>
		<li>', $txt['manual_searching_results_as_messages'], '</li>
		<li>', $txt['manual_searching_message_age'], '</li>
		<li>', $txt['manual_searching_which_board'], '</li>
		<li>', $txt['manual_searching_search_button'], '</li>
	</ul>';
}

?>