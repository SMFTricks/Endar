<?php
// Version: 1.1.12; Display

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	// Show the anchor for the top and for the first message.  If the first message is new, say so.
	echo '
<a name="top"></a>
<a name="msg', $context['first_message'], '"></a>', $context['first_new_message'] ? '<a name="new"></a>' : '';

	// Show the linktree as well as the "Who's Viewing" information.
	echo '
', theme_linktree(), '';


	// Show the topic information - icon, subject, etc.
	echo '
	<div class="border-marg">
	<h2 class="title" id="top_subject">';
	
	// Show a "go down" link?
	if (!empty($modSettings['topbottomEnable']))
		echo $context['menu_separator'], '<span class="float-r-collapse"><a href="#bot">', $settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/go_down.gif" alt="' . $txt['topbottom5'] . '" border="0" align="top" />' : $txt['topbottom5'], '</a></span>';
	  echo'', $txt[118], ': ', $context['subject'], ' &nbsp;(', $txt[641], ' ', $context['num_views'], ' ', $txt[642], ')
	</h2>
	  ';
	
	// Is this topic also a poll?
	if ($context['is_poll'])
	{
		echo '
<div class="subtitle"><strong> ', $txt['smf43'], ' ', $txt['smf21'], ':', $context['poll']['question'], '</strong></div>
	 <div class="row1">';
		if (!empty($context['poll']['expire_time']))
			echo '
			<p><em>(', ($context['poll']['is_expired'] ? $txt['poll_expired_on'] : $txt['poll_expires_on']), ': ', $context['poll']['expire_time'], ')</em></p>';

		// Are they not allowed to vote but allowed to view the options?
		if ($context['poll']['show_results'] || !$context['allow_vote'])
		{
			echo '
			<ul class="row-list" style="margin:3px 0;">';

			// Show each option with its corresponding percentage bar.
			foreach ($context['poll']['options'] as $option)
				echo '
								<li><div class="formleft" style="padding-right: 2ex;', $option['voted_this'] ? 'font-weight: bold;' : '', '">', $option['option'], '</div><div class="formright">', $context['allow_poll_view'] ? '
						<span class="poll-bar">' . $option['bar'] . '</span> ' . $option['votes'] . ' (' . $option['percent'] . '%)</div>
		<div class="clear"><!--no content--></div>				
						</li>' : '', '
						';

			echo '
						</ul></div>
						<div class="row1">
						<div class="buttons" style="margin:5px 0;">';

			// If they are allowed to revote - show them a link!
			if ($context['allow_change_vote'])
				echo '
						<strong><a href="', $scripturl, '?action=vote;topic=', $context['current_topic'], '.', $context['start'], ';poll=', $context['poll']['id'], ';sesc=', $context['session_id'], '">', $txt['poll_change_vote'], '</a></strong>';

			// If we're viewing the results... maybe we want to go back and vote?
			if ($context['poll']['show_results'] && $context['allow_vote'])
				echo '<a href="', $scripturl, '?topic=', $context['current_topic'], '.', $context['start'], '">', $txt['poll_return_vote'], '</a>';

			// If they're allowed to lock the poll, show a link!
			if ($context['poll']['lock'])
				echo '<a href="', $scripturl, '?action=lockVoting;topic=', $context['current_topic'], '.', $context['start'], ';sesc=', $context['session_id'], '">', !$context['poll']['is_locked'] ? $txt['smf30'] : $txt['smf30b'], '</a>';

			// If they're allowed to edit the poll... guess what... show a link!
			if ($context['poll']['edit'])
				echo '<a href="', $scripturl, '?action=editpoll;topic=', $context['current_topic'], '.', $context['start'], '">', $txt['smf39'], '</a>';

			echo '
					', $context['allow_poll_view'] ? '
				' . $txt['smf24'] . ': ' . $context['poll']['total_votes'] . '
				' : '', '</div></div>';
		}
		// They are allowed to vote!  Go to it!
		else
		{
			echo '
			<form action="', $scripturl, '?action=vote;topic=', $context['current_topic'], '.', $context['start'], ';poll=', $context['poll']['id'], '" method="post" accept-charset="', $context['character_set'], '" style="margin: 0px;">
				';

			// Show a warning if they are allowed more than one option.
			if ($context['poll']['allowed_warning'])
				echo '
							<em>', $context['poll']['allowed_warning'], '</em>';
			echo '<ul class="row-list" style="margin:3px 0;">';
			// Show each option with its button - a radio likely.
			foreach ($context['poll']['options'] as $option)
				echo '
							<li><label for="', $option['id'], '">', $option['vote_button'], ' ', $option['option'], '</label></li>';

			echo '
						</ul>
					  </div>';

echo '<div class="row1" style="text-align:right;">
		<div class="buttons" id="poll-buttons">';
			// Allowed to view the results? (without voting!)
			if ($context['allow_poll_view'])
				echo '<a href="', $scripturl, '?topic=', $context['current_topic'], '.', $context['start'], ';viewResults">', $txt['smf29'], '</a>';

			// Show a link for locking the poll as well...
			if ($context['poll']['lock'])
				echo '<a href="', $scripturl, '?action=lockVoting;topic=', $context['current_topic'], '.', $context['start'], ';sesc=', $context['session_id'], '">', (!$context['poll']['is_locked'] ? $txt['smf30'] : $txt['smf30b']), '</a>';

			// Want to edit it?  Click right here......
			if ($context['poll']['edit'])
				echo '<a href="', $scripturl, '?action=editpoll;topic=', $context['current_topic'], '.', $context['start'], '">', $txt['smf39'], '</a>';

			echo '</div>
			&nbsp;<input type="submit" class="button" value="', $txt['smf23'], '" />
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
			</div></form>';
		}

	}
		echo'<div class="block-foot"><!--no content--></div></div>';
		
	// Does this topic have some events linked to it?
	if (!empty($context['linked_calendar_events']))
	{
			echo '
		 <div class="border-mod">
		<div class="subtitle">', $txt['calendar_linked_events'], '</div>
		<div class="row1">
			<ul class="row-list">';
	foreach ($context['linked_calendar_events'] as $event)
	{
		echo '
				<li>
					<strong>', ($event['can_edit'] ? '<a href="' . $event['modify_href'] . '" style="color: red;">*</a> ' : ''), $event['title'], ': ', $event['start_date'], ($event['start_date'] != $event['end_date'] ? ' - ' . $event['end_date'] : ''), '</strong>';
		echo '
				</li>';
	}
	echo '
			</ul>
		</div></div><br />';
	}

	// Show the page index... "Pages: [1]".
	echo '
<div class="clearfix" id="topic-buttons-top" style="margin-bottom: 2ex;text-align:right;">
<div class="float-l"><strong>', $txt[139], ':</strong> ', $context['page_index'] ,'</div>';


	$buttonArray = array();
	if ($context['can_reply'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';num_replies=' . $context['num_replies'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/reply.gif" alt="' . $txt[146] . '" border="0" />' : $txt[146]) . '</a>';
	if ($context['can_mark_notify'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=notify;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '" onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_topic'] : $txt['notification_enable_topic']) . '\');">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/notify.gif" alt="' . $txt[131] . '" border="0" />' : $txt[131]) . '</a>';

	// This is a special case; if they can see mark unread, put it at the top... otherwise show add poll.
	if ($context['user']['is_logged'] && $settings['show_mark_read'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=markasread;sa=topic;t=' . $context['mark_unread_time'] . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markunread.gif" alt="' . $txt['mark_unread'] . '" border="0" />' : $txt['mark_unread']) . '</a>';
	elseif ($context['can_add_poll'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;poll;msg=' . $context['topic_first_message'] . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/addpoll.gif" alt="' . $txt['add_poll'] . '" border="0" />' : $txt['add_poll']) . '</a>';

	if ($context['can_send_topic'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=sendtopic;topic=' . $context['current_topic'] . '.0">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/sendtopic.gif" alt="' . $txt[707] . '" border="0" />' : $txt[707]) . '</a>';
	$buttonArray[] = '<a href="' . $scripturl . '?action=printpage;topic=' . $context['current_topic'] . '.0" target="_blank">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/print.gif" alt="' . $txt[465] . '" border="0" />' : $txt[465]) . '</a>';

	echo implode($context['menu_separator'], $buttonArray);

	echo '</div>';


	//	if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $context['can_remove_post'])
		echo '
<form action="', $scripturl, '?action=quickmod2;topic=', $context['current_topic'], '.', $context['start'], '" method="post" name="quickModForm" accept-charset="', $context['character_set'], '" id="quickModForm" style="margin: 0;" onsubmit="return in_edit_mode == 1 ? modify_save(\'' . $context['session_id'] . '\') : confirm(\'' . $txt['quickmod_confirm'] . '\');">';

	echo '
<div>';

	// Get all the messages...
	while ($message = $context['get_message']())
	{
		echo '';

		// Show the message anchor and a "new" anchor if this message is new.
		if ($message['id'] != $context['first_message'])
			echo '
		<a name="msg', $message['id'], '"></a>', $message['first_new'] ? '<a name="new"></a>' : '';

		echo '';

		// Show information about the poster of this message.
		echo '
				<div class="border-post">
			<h3 class="title">';
		// Don't show these things for guests.
		if (!$message['member']['is_guest'])
		{
		echo '<span class="float-r">', $message['member']['group_stars'], '</span>';
		}
		echo '<strong>', $message['member']['link'], '</strong></h3>
			<h4 class="subtitle">
			<span class="float-r">';
			
		 // If this is the first post, (#0) just say when it was posted - otherwise give the reply #.
		echo '
			  <strong>', !empty($message['counter']) ? $txt[146] . ' #' . $message['counter'] : '', ' ', $txt[30], ':</strong> ', $message['time'], '';
			
		echo '
			</span><!--a href="', $message['href'], '"><img src="', $message['icon_url'] . '" alt="" border="0" /></a-->
			<span style="font-weight: bold;" id="subject_', $message['id'], '">
			<a href="', $message['href'], '">', $message['subject'], '</a>
			</span>
			</h4>
			<div class="post-top"><div class="post-top-left"><!-- no content--> </div></div>
			<div class="post-bg">
			<div class="post-left">
		 <ul>';

		// Show the member's primary group (like 'Administrator') if they have one.
		if (isset($message['member']['group']) && $message['member']['group'] != '')
			echo '
								<li>', $message['member']['group'], '</li>';
	  
	  // Show the member's custom title, if they have one.
		if (isset($message['member']['title']) && $message['member']['title'] != '')
			echo '
								<li>', $message['member']['title'], '</li>';

		// Don't show these things for guests.
		if (!$message['member']['is_guest'])
		{
			// Show avatars, images, etc.?
			if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']) && !empty($message['member']['avatar']['image']))
				echo '
								<li><div style="overflow: auto; width: 100%; max-height:200px;">', $message['member']['avatar']['image'], '</div></li>';
		 
		 // Show the post group if and only if they have no other group or the option is on, and they are in a post group.
			if ((empty($settings['hide_post_group']) || $message['member']['group'] == '') && $message['member']['post_group'] != '')
				echo '
								<li>', $message['member']['post_group'], '</li>';

			// Is karma display enabled?  Total or +/-?
			if ($modSettings['karmaMode'] == '1')
				echo '
								<li>
								', $modSettings['karmaLabel'], ' ', $message['member']['karma']['good'] - $message['member']['karma']['bad'], '</li>';
			elseif ($modSettings['karmaMode'] == '2')
				echo '
								<li>
								', $modSettings['karmaLabel'], ' +', $message['member']['karma']['good'], '/-', $message['member']['karma']['bad'], '</li>';

			// Is this user allowed to modify this member's karma?
			if ($message['member']['karma']['allow'])
				echo '
								<li><a href="', $scripturl, '?action=modifykarma;sa=applaud;uid=', $message['member']['id'], ';topic=', $context['current_topic'], '.' . $context['start'], ';m=', $message['id'], ';sesc=', $context['session_id'], '">', $modSettings['karmaApplaudLabel'], '</a>
								<a href="', $scripturl, '?action=modifykarma;sa=smite;uid=', $message['member']['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';m=', $message['id'], ';sesc=', $context['session_id'], '">', $modSettings['karmaSmiteLabel'], '</a></li>';

			// Show online and offline buttons?
			if (!empty($modSettings['onlineEnable']) && !$message['member']['is_guest'])
				echo '
								<li>', $context['can_send_pm'] ? '<a href="' . $message['member']['online']['href'] . '" title="' . $message['member']['online']['label'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $message['member']['online']['image_href'] . '" alt="' . $message['member']['online']['text'] . '" border="0" align="middle" />' : $message['member']['online']['text'], $context['can_send_pm'] ? '</a>' : '', $settings['use_image_buttons'] ? ' ' . $message['member']['online']['text'] . '' : '', '</li>';

			// Show the member's gender icon?
			if (!empty($settings['show_gender']) && $message['member']['gender']['image'] != '')
				echo '
								<li>', $txt[231], ': ', $message['member']['gender']['image'], '</li>';

			// Show how many posts they have made.
			echo '
								<li>', $txt[26], ': ', $message['member']['posts'], '</li>';


			// Show their personal text?
			if (!empty($settings['show_blurb']) && $message['member']['blurb'] != '')
				echo '
								<li>', $message['member']['blurb'], '</li>';

			// Show the IP to this user for this post - because you can moderate?
		if ($context['can_moderate_forum'] && !empty($message['member']['ip']))
			echo '
									<li><img src="', $settings['images_url'], '/ip.gif" alt="" border="0" /> <a href="', $scripturl, '?action=trackip;searchip=', $message['member']['ip'], '">', $message['member']['ip'], '</a> <a href="', $scripturl, '?action=helpadmin;help=see_admin_ip" onClick="return reqWin(this.href);" class="help">(?)</a></li>';
		// Or, should we show it because this is you?
		elseif ($message['can_see_ip'])
			echo '
									<li><img src="', $settings['images_url'], '/ip.gif" alt="" border="0" /> <a href="', $scripturl, '?action=helpadmin;help=see_member_ip" onClick="return reqWin(this.href);" class="help">', $message['member']['ip'], '</a></li>';
		// Okay, are you at least logged in?  Then we can show something about why IPs are logged...
		elseif (!$context['user']['is_guest'])
			echo '
								  <li><img src="', $settings['images_url'], '/ip.gif" alt="" border="0" /> <a href="', $scripturl, '?action=helpadmin;help=see_member_ip" onClick="return reqWin(this.href);" class="help">', $txt[511], '</a></li>';
		// Otherwise, you see NOTHING!
		else
			echo '
								  <li><img src="', $settings['images_url'], '/ip.gif" alt="" border="0" /> ', $txt[511] ,'</li>';
		 
		 
}

		// Done with the information about the poster... on to the post itself.
		echo '</ul>';
		// Show a checkbox for quick moderation?
		if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $message['can_remove'])
			echo '
									<input type="checkbox" name="msgs[]" value="', $message['id'], '" class="check" onClick="document.getElementById(\'quickmodSubmit\').style.display = \'\';" />';					
		echo '</div>
		<div class="post-right">
		';

  
		// Show the post itself, finally!
		echo '<div class="post"', $message['can_modify'] ? ' id="msg_' . $message['id'] . '"' : '', '>', $message['body'], '</div>', $message['can_modify'] ? '
							<img src="' . $settings['images_url'] . '/icons/modify_inline.gif" alt="" align="right" id="modify_button_' . $message['id'] . '" style="cursor: pointer;" onclick="modify_msg(\'' . $message['id'] . '\', \'' . $context['session_id'] . '\')" />' : '' , '
						';

		// Now for the attachments, signature, ip logged, etc...
	 

		// Assuming there are attachments...
		if (!empty($message['attachment']))
		{
			echo '<div><div class="post-overflow">';
			foreach ($message['attachment'] as $attachment)
			{
				if ($attachment['is_image'])
				{
					if ($attachment['thumbnail']['has_thumb'])
						echo '
									<a href="', $attachment['href'], ';image" id="link_', $attachment['id'], '" onClick="', $attachment['thumbnail']['javascript'], '"><img src="', $attachment['thumbnail']['href'], '" alt="" id="thumb_', $attachment['id'], '" border="0" /></a><br />';
					else
						echo '
									<img src="' . $attachment['href'] . ';image" alt="" width="' . $attachment['width'] . '" height="' . $attachment['height'] . '" border="0" /><br />';
				}
				echo '
										<a href="' . $attachment['href'] . '"><img src="' . $settings['images_url'] . '/icons/clip.gif" align="middle" alt="*" border="0" />&nbsp;' . $attachment['name'] . '</a> (', $attachment['size'], ($attachment['is_image'] ? ', ' . $attachment['real_width'] . 'x' . $attachment['real_height'] . ' - ' . $txt['attach_viewed'] : ' - ' . $txt['attach_downloaded']) . ' ' . $attachment['downloads'] . ' ' . $txt['attach_times'] . '.)<br />';
			}

			echo '
									</div></div>';
		}
		
		echo'<div id="modified_', $message['id'], '">';

		// Show "Last Edit: Time by Person" if this post was edited.
		if ($settings['show_modify'] && !empty($message['modified']['name']))
			echo '<p class="edittime"><em>', $txt[211], ': ', $message['modified']['time'], ' ', $txt[525], ' ', $message['modified']['name'], '</em></p>';

		echo '</div></div>';

		// Show the member's signature?
		if (!empty($message['member']['signature']) && empty($options['show_no_signatures']))
			echo '
							<div class="signature"><div class="sig-text">', $message['member']['signature'], '</div></div>';

	
			  echo '<div class="post-foot"><div class="float-l">';
		
		// Don't show these things for guests.
		if (!$message['member']['is_guest'])
		{
		
			 // Show the profile, website, email address, and personal message buttons.
			if ($settings['show_profile_buttons'])
			{
				// Don't show the profile button if you're not allowed to view the profile.
				if ($message['member']['can_view_profile'])
					echo '<a href="', $message['member']['href'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/profile.gif" alt="' . $txt[27] . '" title="' . $txt[27] . '" border="0" />' : $txt[27]), '</a>';

				// Don't show an icon if they haven't specified a website.
				if ($message['member']['website']['url'] != '')
					echo '<a href="', $message['member']['website']['url'], '" title="' . $message['member']['website']['title'] . '" target="_blank">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/website.gif" alt="' . $txt[515] . '" border="0" />' : $txt[515]), '</a>';

				// Don't show the email address if they want it hidden.
				if (empty($message['member']['hide_email']))
					echo '<a href="mailto:', $message['member']['email'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/email.gif" alt="' . $txt[69] . '" title="' . $txt[69] . '" border="0" />' : $txt[69]), '</a>';

				// Since we know this person isn't a guest, you *can* message them.
				if ($context['can_send_pm'])
					echo '<a href="', $scripturl, '?action=pm;sa=send;u=', $message['member']['id'], '" title="', $message['member']['online']['label'], '">', $settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/pm-' . ($message['member']['online']['is_online'] ? 'on' : 'off') . '.gif" alt="' . $message['member']['online']['label'] . '" border="0" />' : $message['member']['online']['label'], '</a>';
			}
		}
	  
		// Otherwise, show the guest's email.
		elseif (empty($message['member']['hide_email']))
			echo '<a href="mailto:', $message['member']['email'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/email.gif" alt="' . $txt[69] . '" title="' . $txt[69] . '" border="0" />' : $txt[69]), '</a>';
			
		
		echo '</div>';
		
			// Can they reply?  Have they turned on quick reply?
		if ($context['can_reply'] && !empty($options['display_quick_reply']))
			echo '
									<a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';num_replies=', $context['num_replies'], ';sesc=', $context['session_id'], '" onclick="doQuote(', $message['id'], ', \'', $context['session_id'], '\'); return false;">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/quote.gif" alt="' . $txt[145] . '" border="0" />' : $txt[145]), '</a>';
		
		// So... quick reply is off, but they *can* reply?
		elseif ($context['can_reply'])
			echo '<a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';num_replies=', $context['num_replies'], ';sesc=', $context['session_id'], '" title="' . $txt[145] . '" >', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/quote.gif" alt="' . $txt[145] . '" border="0" />' : $txt[145]), '</a>';

		// Can the user modify the contents of this post?
		if ($message['can_modify'])
			echo '<a href="', $scripturl, '?action=post;msg=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';sesc=', $context['session_id'], '" title="' . $txt[66] . '" class="pinkbut">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/modify.gif" alt="' . $txt[66] . '" border="0" />' : $txt[17]), '</a>';

		// How about... even... remove it entirely?!
		if ($message['can_remove'])
			echo '<a href="', $scripturl, '?action=deletemsg;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';sesc=', $context['session_id'], '" onClick="return confirm(\'', $txt[154], '?\');" title="' . $txt[121] . '" class="pinkbut">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/delete.gif" alt="' . $txt[121] . '" border="0" />' : $txt[31]), '</a>';

		// What about splitting it off the rest of the topic?
		if ($context['can_split'])
			echo '<a href="', $scripturl, '?action=splittopics;topic=', $context['current_topic'], '.0;at=', $message['id'], '" title="' . $txt['smf251'] . '" class="pinkbut">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/split.gif" alt="' . $txt['smf251'] . '" border="0" />' : $txt['smf251']), '</a>';
		 
	// Maybe they want to report this post to the moderator(s)?
		if ($context['can_report_moderator'])
			echo '<a href="', $scripturl, '?action=reporttm;topic=', $context['current_topic'], '.', $message['counter'], ';msg=', $message['id'], '" class="pinkbut">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/report.gif" title="' . $txt['rtm1'] . '" alt="' . $txt['rtm1'] . '" border="0" />' : $txt['rtm1']), '</a>';

		echo '	
			</div>
				</div>
				<div class="block-foot"><!--no content--></div>
				</div>
				<br />';
	}
	echo '
</div>
<a name="lastPost"></a>';

// Show the page index... "Pages: [1]".
	echo '
<div class="clearfix" id="topic-buttons-bot" style="margin-bottom: 2ex;text-align:right;">
<div class="float-l"><strong>', $txt[139], ':</strong> ', $context['page_index'] ,'</div>';


	$buttonArray = array();
	if ($context['can_reply'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';num_replies=' . $context['num_replies'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/reply.gif" alt="' . $txt[146] . '" border="0" />' : $txt[146]) . '</a>';
	if ($context['can_mark_notify'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=notify;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '" onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_topic'] : $txt['notification_enable_topic']) . '\');">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/notify.gif" alt="' . $txt[131] . '" border="0" />' : $txt[131]) . '</a>';

	// Another special case, similar to above but reversed.  Show "add poll" unless they can't - then show unread here too.
	if ($context['can_add_poll'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=editpoll;add;topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/addpoll.gif" alt="' . $txt['add_poll'] . '" border="0" />' : $txt['add_poll']) . '</a>';
	elseif ($context['user']['is_logged'] && $settings['show_mark_read'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=markasread;sa=topic;t=' . $context['mark_unread_time'] . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markunread.gif" alt="' . $txt['mark_unread'] . '" border="0" />' : $txt['mark_unread']) . '</a>';

	if ($context['can_send_topic'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=sendtopic;topic=' . $context['current_topic'] . '.0">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/sendtopic.gif" alt="' . $txt[707] . '" border="0" />' : $txt[707]) . '</a>';
	$buttonArray[] = '<a href="' . $scripturl . '?action=printpage;topic=' . $context['current_topic'] . '.0" target="_blank">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/print.gif" alt="' . $txt[465] . '" border="0" />' : $txt[465]) . '</a>';

	echo implode($context['menu_separator'], $buttonArray);

	echo '</div>';
	

	if ($context['show_spellchecking'])
			echo '
<script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/spellcheck.js"></script>';

echo '
<script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/xml_topic.js"></script>
<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
	quickReplyCollapsed = ', !empty($options['display_quick_reply']) && $options['display_quick_reply'] == 2 ? 'false' : 'true', ';

	smf_topic = ', $context['current_topic'], ';
	smf_start = ', $context['start'], ';
	smf_show_modify = ', $settings['show_modify'] ? '1' : '0', ';

	// On quick modify, this is what the body will look like.
	var smf_template_body_edit = \'<div id="error_box" style="padding: 4px; color: red;"></div><div style="width:100%;"><textarea class="editor" name="message" rows="12">%body%</textarea></div><br /><input type="hidden" name="sc" value="', $context['session_id'], '" /><input type="hidden" name="topic" value="', $context['current_topic'], '" /><input type="hidden" name="msg" value="%msg_id%" /><div style="text-align: center;"><input type="submit" class="button" name="post" value="', $txt[10], '" onclick="return modify_save(\\\'' . $context['session_id'] . '\\\');" accesskey="s" />&nbsp;&nbsp;', $context['show_spellchecking'] ? '<input type="button" class="button" value="' . $txt['spell_check'] . '" onclick="spellCheck(\\\'quickModForm\\\', \\\'message\\\');" />&nbsp;&nbsp;' : '', '<input type="submit" class="button" name="cancel" value="', $txt['modify_cancel'], '" onclick="return modify_cancel();" /></div>\';

	// And this is the replacement for the subject.
	var smf_template_subject_edit = \'<input type="text" name="subject" value="%subject%" size="60" style="width: 50%;"  maxlength="80" />\';

	// Restore the message to this after editing.
	var smf_template_body_normal = \'%body%\';
	var smf_template_subject_normal = \'<a href="', $scripturl, '?topic=', $context['current_topic'], '.msg%msg_id%#msg%msg_id%">%subject%</a>\';
	var smf_template_top_subject = "', $txt[118], ': %subject% &nbsp;(', $txt[641], ' ', $context['num_views'], ' ', $txt[642], ')"

	if (window.XMLHttpRequest)
		showModifyButtons();
// ]]></script>';
	echo '
</form>';


	
	if ($settings['linktree_inline'])
		echo '
		', theme_linktree(), '';
	echo '
	<div class="border">
	<div class="title">
	<a name="bot"></a>';
	// Show a "go down" link?
	if (!empty($modSettings['topbottomEnable']))
		echo $context['menu_separator'], '<span class="float-r-collapse"><a href="#top">', $settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/go_up.gif" alt="' . $txt['topbottom4'] . '" border="0" align="top" />' : $txt['topbottom4'], '</a></span>';
	  echo'', $context['previous_next'], '</div>';
	
	if ($context['can_reply'] && !empty($options['display_quick_reply']))
	{

		echo '

<div class="subtitle">
<a name="quickreply"></a>
<a href="javascript:swapQuickReply();"><img src="', $settings['images_url'], '/', $options['display_quick_reply'] == 2 ? 'collapse' : 'expand', '.gif" alt="+" border="0" class="subtitle-collapse-icon" id="quickReplyExpand" /></a> <a href="javascript:swapQuickReply();">', $txt['quick_reply_1'], '</a>
</div>
<div class="row1-center" id="quickReplyOptions"', $options['display_quick_reply'] == 2 ? '' : ' style="display: none"', '>
		<p>', $txt['quick_reply_2'], $context['is_locked'] ? '</p><p><strong>' . $txt['quick_reply_warning'] . '</strong>' : '', '</p>
			<form action="', $scripturl, '?action=post2" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" onsubmit="submitonce(this);" style="margin: 0;">
				<input type="hidden" name="topic" value="' . $context['current_topic'] . '" />
				<input type="hidden" name="subject" value="' . $context['response_prefix'] . $context['subject'] . '" />
				<input type="hidden" name="icon" value="xx" />
				<input type="hidden" name="notify" value="', $context['is_marked_notify'] || !empty($options['auto_notify']) ? '1' : '0', '" />
				<input type="hidden" name="goback" value="', empty($options['return_to_post']) ? '0' : '1', '" />
				<input type="hidden" name="num_replies" value="', $context['num_replies'], '" />
				<textarea cols="75" rows="7" style="width: 60%; height: 100px;" name="message" tabindex="1"></textarea><br />
				<input type="submit" class="button" name="post" value="' . $txt[105] . '" onclick="return submitThisOnce(this);" accesskey="s" tabindex="2" />
				<input type="submit" class="button" name="preview" value="' . $txt[507] . '" onclick="return submitThisOnce(this);" accesskey="p" tabindex="4" />';
		if ($context['show_spellchecking'])
			echo '
				<input type="button" class="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'postmodify\', \'message\');" />';
		echo '
				<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
				<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
			</form>
		</div>';
	}
	
	echo '
	<div class="row1-center">
	<div id="topic-buttons-mod" style="margin:0 auto;">
	', theme_show_mod_buttons(), '
	</div>';
	if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $context['can_remove_post'])
		echo '
	<input type="hidden" name="sc" value="', $context['session_id'], '" />
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		document.getElementById("quickmodSubmit").style.display = "none";
	// ]]></script>';
	if (!empty($settings['display_who_viewing']))
	{

		// Show just numbers...?
		if ($settings['display_who_viewing'] == 1)
			echo count($context['view_members']), ' ', count($context['view_members']) == 1 ? $txt['who_member'] : $txt[19];
		// Or show the actual people viewing the topic?
		else
			echo empty($context['view_members_list']) ? '0 ' . $txt[19] : implode(', ', $context['view_members_list']) . ((empty($context['view_num_hidden']) or $context['can_moderate_forum']) ? '' : ' (+ ' . $context['view_num_hidden'] . ' ' . $txt['hidden'] . ')');

		// Now show how many guests are here too.
		echo $txt['who_and'], $context['view_num_guests'], ' ', $context['view_num_guests'] == 1 ? $txt['guest'] : $txt['guests'], $txt['who_viewing_topic'], '';
	}
		echo '
	<div style="margin:0 auto;">
	<form action="', $scripturl, '" method="get" accept-charset="', $context['character_set'], '">
		<span class="smalltext">' . $txt[160] . ':</span>
		<select name="jumpto" id="jumpto" onchange="if (this.selectedIndex > 0 &amp;&amp; this.options[this.selectedIndex].value) window.location.href = smf_scripturl + this.options[this.selectedIndex].value.substr(smf_scripturl.indexOf(\'?\') == -1 || this.options[this.selectedIndex].value.substr(0, 1) != \'?\' ? 0 : 1);">
			<option value="">' . $txt[251] . ':</option>';
	foreach ($context['jump_to'] as $category)
	{
		echo '
			<option value="" disabled="disabled">-----------------------------</option>
			<option value="#', $category['id'], '">', $category['name'], '</option>
			<option value="" disabled="disabled">-----------------------------</option>';
		foreach ($category['boards'] as $board)
			echo '
			<option value="?board=', $board['id'], '.0"', $board['is_current'] ? ' selected="selected"' : '', '> ' . str_repeat('==', $board['child_level']) . '=> ' . $board['name'] . '</option>';
	}
	echo '
		</select>&nbsp;
		<input type="button" class="button" value="', $txt[161], '" onclick="if (this.form.jumpto.options[this.form.jumpto.selectedIndex].value) window.location.href = \'', $scripturl, '\' + this.form.jumpto.options[this.form.jumpto.selectedIndex].value;" />
	</form>
</div></div>
<div class="block-foot"><!--no content--></div>
</div><br />';

	
			if ($context['show_spellchecking'])
		echo '
<form action="', $scripturl, '?action=spellcheck" method="post" accept-charset="', $context['character_set'], '" name="spell_form" id="spell_form" target="spellWindow"><input type="hidden" name="spellstring" value="" /></form>';
}
		


function theme_show_mod_buttons()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	$moderationButtons = array();

	if ($context['can_move'])
		$moderationButtons[] = '<a href="' . $scripturl . '?action=movetopic;topic=' . $context['current_topic'] . '.0">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/admin_move.gif" alt="' . $txt[132] . '" border="0" />' : $txt[132]) . '</a>';
	if ($context['can_delete'])
		$moderationButtons[] = '<a href="' . $scripturl . '?action=removetopic2;topic=' . $context['current_topic'] . '.0;sesc=' . $context['session_id'] . '" onclick="return confirm(\'' . $txt[162] . '\');">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/admin_rem.gif" alt="' . $txt[63] . '" border="0" />' : $txt[63]) . '</a>';
	if ($context['can_lock'])
		$moderationButtons[] = '<a href="' . $scripturl . '?action=lock;topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/admin_lock.gif" alt="' . (empty($context['is_locked']) ? $txt['smf279'] : $txt['smf280']) . '" border="0" />' : (empty($context['is_locked']) ? $txt['smf279'] : $txt['smf280'])) . '</a>';
	if ($context['can_sticky'])
		$moderationButtons[] = '<a href="' . $scripturl . '?action=sticky;topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/admin_sticky.gif" alt="' . (empty($context['is_sticky']) ? $txt['smf277'] : $txt['smf278']) . '" border="0" />' : (empty($context['is_sticky']) ? $txt['smf277'] : $txt['smf278'])) . '</a>';
	if ($context['can_merge'])
		$moderationButtons[] = '<a href="' . $scripturl . '?action=mergetopics;board=' . $context['current_board'] . '.0;from=' . $context['current_topic'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/merge.gif" alt="' . $txt['smf252'] . '" border="0" />' : $txt['smf252']) . '</a>';
	if ($context['can_remove_poll'])
		$moderationButtons[] = '<a href="' . $scripturl . '?action=removepoll;topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '" onclick="return confirm(\'' . $txt['poll_remove_warn'] . '\');">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/admin_remove_poll.gif" alt="' . $txt['poll_remove'] . '" border="0" />' : $txt['poll_remove']) . '</a>';

	if ($context['calendar_post'])
		$moderationButtons[] = '<a href="' . $scripturl . '?action=post;calendar;msg=' . $context['topic_first_message'] . ';topic=' . $context['current_topic'] . '.0;sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/linktocal.gif" alt="' . $txt['calendar37'] . '" border="0" />' : $txt['calendar37']) . '</a>';

	if ($context['can_remove_post'] && !empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1)
		$moderationButtons[] = $settings['use_image_buttons'] ? '<input type="image" name="submit" id="quickmodSubmit" src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/delete_selected.gif" alt="' . $txt['quickmod_delete_selected'] . '" />' : '<a href="javascript:document.forms.quickModForm.submit();" id="quickmodSubmit">' . $txt['quickmod_delete_selected'] . '</a>';

	return implode($context['menu_separator'], $moderationButtons);
}

?>