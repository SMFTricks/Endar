<?php
// Version: 1.1; BoardIndex

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	// Show some statistics next to the link tree if SP1 info is off.
	echo '
 ', theme_linktree(), '';
	if (!$settings['show_sp1_info'])
		echo '
			', $txt[19], ': ', $context['common_stats']['total_members'], ' &nbsp;&#8226;&nbsp; ', $txt[95], ': ', $context['common_stats']['total_posts'], ' &nbsp;&#8226;&nbsp; ', $txt[64], ': ', $context['common_stats']['total_topics'], '
			', ($settings['show_latest_member'] ? '<br />' . $txt[201] . ' <strong>' . $context['common_stats']['latest_member']['link'] . '</strong>' . $txt[581] : '');

	// Show the news fader?  (assuming there are things to show...)
	if ($settings['show_newsfader'] && !empty($context['fader_news_lines']))
	{
		echo '
<div class="border-mod" style="margin-bottom:2ex;text-align:center;">
<h2 class="subtitle">', $txt[102], '</h2>
<div class="row1-center" style="height:30px;overflow:auto;">';

		// Prepare all the javascript settings.
		echo '
				<div id="smfFadeScroller" style="margin:0 15px; padding: 2px;">', $context['news_lines'][0], '</div>
				<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
					// The fading delay (in ms.)
					var smfFadeDelay = ', empty($settings['newsfader_time']) ? 5000 : $settings['newsfader_time'], ';
					// Fade from... what text color?  To which background color?
					var smfFadeFrom = {"r": 0, "g": 0, "b": 0}, smfFadeTo = {"r": 255, "g": 255, "b": 255};
					// Surround each item with... anything special?
					var smfFadeBefore = "", smfFadeAfter = "";

					var foreColor, backEl, backColor;

					if (typeof(document.getElementById(\'smfFadeScroller\').currentStyle) != "undefined")
					{
						foreColor = document.getElementById(\'smfFadeScroller\').currentStyle.color.match(/#([\da-f][\da-f])([\da-f][\da-f])([\da-f][\da-f])/);
						smfFadeFrom = {"r": parseInt(foreColor[1]), "g": parseInt(foreColor[2]), "b": parseInt(foreColor[3])};

						backEl = document.getElementById(\'smfFadeScroller\');
						while (backEl.currentStyle.backgroundColor == "transparent" && typeof(backEl.parentNode) != "undefined")
							backEl = backEl.parentNode;

						backColor = backEl.currentStyle.backgroundColor.match(/#([\da-f][\da-f])([\da-f][\da-f])([\da-f][\da-f])/);
						smfFadeTo = {"r": eval("0x" + backColor[1]), "g": eval("0x" + backColor[2]), "b": eval("0x" + backColor[3])};
					}
					else if (typeof(window.opera) == "undefined" && typeof(document.defaultView) != "undefined")
					{
						foreColor = document.defaultView.getComputedStyle(document.getElementById(\'smfFadeScroller\'), null).color.match(/rgb\((\d+), (\d+), (\d+)\)/);
						smfFadeFrom = {"r": parseInt(foreColor[1]), "g": parseInt(foreColor[2]), "b": parseInt(foreColor[3])};

						backEl = document.getElementById(\'smfFadeScroller\');
						while (document.defaultView.getComputedStyle(backEl, null).backgroundColor == "transparent" && typeof(backEl.parentNode) != "undefined" && typeof(backEl.parentNode.tagName) != "undefined")
							backEl = backEl.parentNode;

						backColor = document.defaultView.getComputedStyle(backEl, null).backgroundColor.match(/rgb\((\d+), (\d+), (\d+)\)/);
						smfFadeTo = {"r": parseInt(backColor[1]), "g": parseInt(backColor[2]), "b": parseInt(backColor[3])};
					}

					// List all the lines of the news for display.
					var smfFadeContent = new Array(
						"', implode('",
						"', $context['fader_news_lines']), '"
					);
				// ]]></script>
				<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/fader.js"></script>
			</div>
		<div class="block-foot"><!--no content--></div>
</div>';
	}

	/* Each category in categories is made up of:
		id, href, link, name, is_collapsed (is it collapsed?), can_collapse (is it okay if it is?),
		new (is it new?), collapse_href (href to collapse/expand), collapse_image (up/down iamge),
		and boards. (see below.) */
	foreach ($context['categories'] as $category)
	{
		echo '
<div class="border">
<h3 class="title', $category['new'] ? '2' : '', '">';

		// If this category even can collapse, show a link to collapse it.
		if ($category['can_collapse'])
			echo '
			<span class="float-r-collapse"><a href="', $category['collapse_href'], '">', $category['collapse_image'], '</a></span>';

		echo '
			', $category['link'], '
		</h3>
	  <dl>';

		// Assuming the category hasn't been collapsed...
		if (!$category['is_collapsed'])
		{
			/* Each board in each category's boards has:
				new (is it new?), id, name, description, moderators (see below), link_moderators (just a list.),
				children (see below.), link_children (easier to use.), children_new (are they new?),
				topics (# of), posts (# of), link, href, and last_post. (see below.) */
			foreach ($category['boards'] as $board)
			{
				echo '
	 <dt><strong><a href="', $board['href'], '" name="b', $board['id'], '">', $board['name'], '</a></strong></dt>
		<dd><div class="left"><a href="', $scripturl, '?action=unread;board=', $board['id'], '.0">';

				// If the board is new, show a strong indicator.
				if ($board['new'])
					echo '<img src="', $settings['images_url'], '/on.gif" alt="', $txt[333], '" title="', $txt[333], '" border="0" class="index-topic-icon" />';
				// This board doesn't have new posts, but its children do.
				elseif ($board['children_new'])
					echo '<img src="', $settings['images_url'], '/on2.gif" alt="', $txt[333], '" title="', $txt[333], '" border="0" class="index-topic-icon" />';
				// No new posts at all!  The agony!!
				else
					echo '<img src="', $settings['images_url'], '/off.gif" alt="', $txt[334], '" title="', $txt[334], '" border="0" class="index-topic-icon" />';

				echo '</a>
			
			', $board['description'];
		 
		 echo '<br /><em>';

				// Show the "Child Boards: ". (there's a link_children but we're going to bold the new ones...)
				if (!empty($board['children']))
				{
					// Sort the links into an array with new boards bold so it can be imploded.
					$children = array();
					/* Each child in each board's children has:
						id, name, description, new (is it new?), topics (#), posts (#), href, link, and last_post. */
					foreach ($board['children'] as $child)
					{
						$child['link'] = '<a href="' . $child['href'] . '" title="' . ($child['new'] ? $txt[333] : $txt[334]) . ' (' . $txt[330] . ': ' . $child['topics'] . ', ' . $txt[21] . ': ' . $child['posts'] . ')">' . $child['name'] . '</a>';
						$children[] = $child['new'] ? '<strong>' . $child['link'] . '</strong>' : $child['link'];
					}

					echo '
			', $txt['parent_boards'], ': ', implode(', ', $children), '';
				}

				// Show some basic information about the number of posts, etc.
				echo '<br />
		  
			', $board['posts'], ' ', $txt[21], ' ', $txt['smf88'], '
			', $board['topics'], ' ', $txt[330], '. ';
	  
	  // Show the "Moderators: ".  Each has name, href, link, and id. (but we're gonna use link_moderators.)
				if (!empty($board['moderators']))


					echo '
		 ', count($board['moderators']) == 1 ? $txt[298] : $txt[299], ': ', implode(', ', $board['link_moderators']), '';
echo '</em></div>';

				/* The board's and children's 'last_post's have:
					time, timestamp (a number that represents the time.), id (of the post), topic (topic id.),
					link, href, subject, start (where they should go for the first unread post.),
					and member. (which has id, name, link, href, username in it.) */
				
				if (!empty($board['last_post']['id']))
					echo '<div class="right">
			', $txt[22], ' ', $txt[30], '<br /><em> ', $board['last_post']['time'], '<br />
			', $txt['smf88'], ' ', $board['last_post']['link'], ' ', $txt[525], ' ', $board['last_post']['member']['link'], '</em></div>';
			
		else
			echo '<div class="clear"><!--no content--></div>';

				echo '
		</dd>';
			}
		}

		echo '
	 </dl>
	 <div class="block-foot"><!--no content--></div>
</div>
<br />';
	}

	if ($context['user']['is_logged'])
	{
		echo '
<div class="clearfix" id="topic-buttons-bot" style="margin-bottom:2ex;">';
 
// Show the mark all as read button?
		if ($settings['show_mark_read'] && !empty($context['categories']))
			echo '
			<span class="float-r">
			<a href="', $scripturl, '?action=markasread;sa=all;sesc=' . $context['session_id'] . '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[452] . '" border="0" />' : $txt[452]), '</a></span>';


			echo '<img src="' . $settings['images_url'] . '/new_some.gif" alt="" border="0" align="middle" /> ', $txt[333], '
			<img src="' . $settings['images_url'] . '/new_none.gif" alt="" border="0" align="middle" style="margin-left: 4ex;" /> ', $txt[334], '
	  </div>';
	}

	// Here's where the "Info Center" starts...
	echo '
<br />
<div class="border">
<h4 class="title">', $txt[685], '</h4>';

	// This is the "Recent Posts" bar.
	if (!empty($settings['number_recent_posts']))
	{
		echo '
	<h5 class="subtitle"><a href="', $scripturl, '?action=recent">', $txt[214], '</a></h5>
	<div class="row1">';

		// Only show one post.
		if ($settings['number_recent_posts'] == 1)
		{
			// latest_post has link, href, time, subject, short_subject (shortened with...), and topic. (its id.)
			echo '
			<strong><p class="smalltext"><a href="', $scripturl, '?action=recent">', $txt[214], '</a></p></strong>
			<p class="smalltext">
				', $txt[234], ' &quot;', $context['latest_post']['link'], '&quot; ', $txt[235], ' (', $context['latest_post']['time'], ')
			</p>';
		}
		// Show lots of posts.
		elseif (!empty($context['latest_posts']))
		{
			echo '
			<ul class="bullit-list">';
			/* Each post in latest_posts has:
				board (with an id, name, and link.), topic (the topic's id.), poster (with id, name, and link.),
				subject, short_subject (shortened with...), time, link, and href. */
			foreach ($context['latest_posts'] as $post)
				echo '
				<li><p class="smalltext"><strong>', $post['link'], '</strong> ', $txt[525], ' ', $post['poster']['link'], ' [', $post['board']['link'], '] ', $post['time'], '</p></li>';
			echo '
			</ul>';
		}
		echo '
		</div>';
	}

	// Show information about events, birthdays, and holidays on the calendar.
	if ($context['show_calendar'])
	{
		echo '
	<h5 class="subtitle"><a href="', $scripturl, '?action=calendar">', $context['calendar_only_today'] ? $txt['calendar47b'] : $txt['calendar47'], '</a></h5>
	<div class="row1">
	<div id="icon-calendar">
		<p class="smalltext">';

		// Holidays like "Christmas", "Chanukah", and "We Love [Unknown] Day" :P.
		if (!empty($context['calendar_holidays']))
			echo '
				<span style="color: #', $modSettings['cal_holidaycolor'], ';">', $txt['calendar5'], ' ', implode(', ', $context['calendar_holidays']), '</span><br />';

		// People's birthdays.  Like mine.  And yours, I guess.  Kidding.
		if (!empty($context['calendar_birthdays']))
		{
			echo '
				<span style="color: #', $modSettings['cal_bdaycolor'], ';">', $context['calendar_only_today'] ? $txt['calendar3'] : $txt['calendar3b'], '</span> ';
			/* Each member in calendar_birthdays has:
				id, name (person), age (if they have one set?), is_last. (last in list?), and is_today (birthday is today?) */
			foreach ($context['calendar_birthdays'] as $member)
				echo '
				<a href="', $scripturl, '?action=profile;u=', $member['id'], '">', $member['is_today'] ? '<strong>' : '', $member['name'], $member['is_today'] ? '</strong>' : '', isset($member['age']) ? ' (' . $member['age'] . ')' : '', '</a>', $member['is_last'] ? '<br />' : ', ';
		}
		// Events like community get-togethers.
		if (!empty($context['calendar_events']))
		{
			echo '
				<span style="color: #', $modSettings['cal_eventcolor'], ';">', $context['calendar_only_today'] ? $txt['calendar4'] : $txt['calendar4b'], '</span> ';
			/* Each event in calendar_events should have:
				title, href, is_last, can_edit (are they allowed?), modify_href, and is_today. */
			foreach ($context['calendar_events'] as $event)
				echo '
				', $event['can_edit'] ? '<a href="' . $event['modify_href'] . '" style="color: #FF0000;">*</a> ' : '', $event['href'] == '' ? '' : '<a href="' . $event['href'] . '">', $event['is_today'] ? '<strong>' . $event['title'] . '</strong>' : $event['title'], $event['href'] == '' ? '' : '</a>', $event['is_last'] ? '<br />' : ', ';

			// Show a little help text to help them along ;).
			if ($context['calendar_can_edit'])
				echo '
				(<a href="', $scripturl, '?action=helpadmin;help=calendar_how_edit" onclick="return reqWin(this.href);">', $txt['calendar_how_edit'], '</a>)';
		}
		echo '
			</p>
		</div></div>';
	}

	// Show YaBB SP1 style information...
	if ($settings['show_sp1_info'])
	{
		echo '
	<h5 class="subtitle"><a href="', $scripturl, '?action=stats">', $txt[645], '</a></h5>
	<div class="row1">
	<div id="icon-stats">
		<div class="left"><p class="smalltext">', $txt[490], ': <strong>', $context['common_stats']['total_topics'], '</strong> &nbsp;', $txt[489], ': <strong>', $context['common_stats']['total_posts'], '</strong><br />', !empty($context['latest_post']) ? '
					' . $txt[659] . ': &quot;' . $context['latest_post']['link'] . '&quot;  (' . $context['latest_post']['time'] . ')<br />' : '', '
					<a href="', $scripturl, '?action=recent">', $txt[234], '</a>', $context['show_stats'] ? ' 
					<a href="' . $scripturl . '?action=stats">' . $txt['smf223'] . '</a>' : '', '
				</p></div>
				<div class="right"><p class="smalltext">
					', $txt[488], ': <strong><a href="', $scripturl, '?action=mlist">', $context['common_stats']['total_members'], '</a></strong><br />
					', $txt[656], ': <strong>', $context['common_stats']['latest_member']['link'], '</strong><br />';
		// If they are logged in, show their unread message count, etc..
		if ($context['user']['is_logged'])
			echo '
					', $txt['smf199'], ': <strong><a href="', $scripturl, '?action=pm">', $context['user']['messages'], '</a></strong> ', $txt['newmessages3'], ': <strong><a href="', $scripturl, '?action=pm">', $context['user']['unread_messages'], '</a></strong>';
		echo '
				&nbsp;</p>
			</div></div></div>
		 ';
	}

	// "Users online" - in order of activity.
	echo '
	<h5 class="subtitle"><a href="' . $scripturl . '?action=who">', $txt[158], '</a></h5>
	<div class="row1">
	<div id="icon-online">';

	if ($context['show_who'])
		echo '
			<p><a href="', $scripturl, '?action=who">';

	echo $context['num_guests'], ' ', $context['num_guests'] == 1 ? $txt['guest'] : $txt['guests'], ', ' . $context['num_users_online'], ' ', $context['num_users_online'] == 1 ? $txt['user'] : $txt['users'];

	// Handle hidden users and buddies.
	if (!empty($context['num_users_hidden']) || ($context['show_buddies'] && !empty($context['show_buddies'])))
	{
		echo ' (';

		// Show the number of buddies online?
		if ($context['show_buddies'])
			echo $context['num_buddies'], ' ', $context['num_buddies'] == 1 ? $txt['buddy'] : $txt['buddies'];

		// How about hidden users?
		if (!empty($context['num_users_hidden']))
			echo $context['show_buddies'] ? ', ' : '', $context['num_users_hidden'] . ' ' . $txt['hidden'];

		echo ')';
	}

	if ($context['show_who'])
		echo '</a></p>';

	echo '
			<p class="smalltext">';

	// Assuming there ARE users online... each user in users_online has an id, username, name, group, href, and link.
	if (!empty($context['users_online']))
		echo '
				', $txt[140], ':<br />', implode(', ', $context['list_users_online']);

	echo '
				<br />', $context['show_stats'] && !$settings['show_sp1_info'] ? '
				<a href="' . $scripturl . '?action=stats">' . $txt['smf223'] . '</a>' : '', '
			</p>
		</div></div>';

	// If they are logged in, but SP1 style information is off... show a personal message bar.
	if ($context['user']['is_logged'] && !$settings['show_sp1_info'])
	{
		echo '
	<h5 class="subtitle"><a href="', $scripturl, '?action=pm">', $txt[159], '</a></h5>
	<div class="row1">
	<div id="icon-pm">
			', $context['allow_pm'] ? '' : '', '
		
			<p><strong><a href="', $scripturl, '?action=pm">', $txt[159], '</a></strong></p>
			<p class="smalltext">
				', $txt[660], ' ', $context['user']['messages'], ' ', $context['user']['messages'] == 1 ? $txt[471] : $txt[153], '.... ', $txt[661], ' <a href="', $scripturl, '?action=pm">', $txt[662], '</a> ', $txt[663], '
			</p>
	</div>
	</div>';
	}

	echo '
<div class="block-foot"><!--no content--></div>
</div>';
}

?>