<?php
// Version: 1.1.2; Profile

// Template for the profile side bar - goes before any other profile template.
function template_profile_above()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// Assuming there are actually some areas the user can visit...
	if (!empty($context['profile_areas']))
	{
		echo '
		<div id="user-cp-left">
					<div class="border">
					<div class="title">&nbsp;</div>';

		// Loop through every area, displaying its name as a header.
		foreach ($context['profile_areas'] as $section)
		{
			echo '
						<h3 class="subtitle">', $section['title'], '</h3>
						<ul>
						';

			// For every section of the area display it, and bold it if it's the current area.
			foreach ($section['areas'] as $i => $area)
				if ($i == $context['menu_item_selected'])
					echo '
								<li id="selected">', $area, '</li>';
				else
					echo '
								<li>', $area, '</li>';
			echo '
								</ul>';
		}
		echo '
					</div>
				</div>
				<div id="user-cp-right">';
	}
	// If no areas exist just open up a containing table.
	else
	{
		echo '
		<div>';
	}

	// If an error occured whilst trying to save previously, give the user a clue!
	if (!empty($context['post_errors']))
	{
		echo '
					<div class="border">
						', template_error_message(), '
						</div>
					<br />';
	}
}

// Template for closing off table started in profile_above.
function template_profile_below()
{
	global $context, $settings, $options;

	echo '
		</div>
		<div class="clear"><!-- no content--></div>';
}

// This template displays users details without any option to edit them.
function template_summary()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// First do the containing table and table header.
	echo '
<div class="border">
	<h4 class="title">', $txt['summary'], ' - ', $context['member']['name'], '</h4>
	<h5 class="subtitle">', $context['member']['blurb'], '</h5>';


	echo '
	<div class="row2">
	<div id="cp-avatar">
			', $context['member']['avatar']['image'], '
			
		</div>

	<ul class="bullit-list">
		<li><strong>', $txt[68], ': </strong>', $context['member']['name'], '</li>';
	if (!empty($modSettings['titlesEnable']) && $context['member']['title'] != '')
	{
		echo '
				<li><strong>', $txt['title1'], ': </strong>', $context['member']['title'], '</li>';
	}
	echo '
		<li><strong>', $txt[86], ': </strong>', $context['member']['posts'], ' (', $context['member']['posts_per_day'], ' ', $txt['posts_per_day'], ')</li>
		<li><strong>', $txt[87], ': </strong>', (!empty($context['member']['group']) ? $context['member']['group'] : $context['member']['post_group']), '</li>';

	// If the person looking is allowed, they can check the members IP address and hostname.
	if ($context['can_see_ip'])
	{
		echo '
				<li><strong>', $txt[512], ': </strong><a href="', $scripturl, '?action=trackip;searchip=', $context['member']['ip'], '" target="_blank">', $context['member']['ip'], '</a></li>
				<li><strong>', $txt['hostname'], ': </strong>', $context['member']['hostname'], '</li>';
	}

	// If karma enabled show the members karma.
	if ($modSettings['karmaMode'] == '1')
		echo '
				<li><strong>', $modSettings['karmaLabel'], ' </strong>', ($context['member']['karma']['good'] - $context['member']['karma']['bad']), '</li>';
	elseif ($modSettings['karmaMode'] == '2')
		echo '
				<li><strong>', $modSettings['karmaLabel'], ' </strong>+', $context['member']['karma']['good'], '/-', $context['member']['karma']['bad'], '</li>';
	echo '
		<li><strong>', $txt[233], ': </strong>', $context['member']['registered'], '</li>
		<li><strong>', $txt['lastLoggedIn'], ': </strong>', $context['member']['last_login'], '</li>';
	echo'
	</ul>
	</div>';
	// Is this member requiring activation and/or banned?
	if (!empty($context['activate_message']) || !empty($context['member']['bans']))
	{
		echo '<div class="row2">';
	
	// If the person looking at the summary has permission, and the account isn't activated, give the viewer the ability to do it themselves.
		if (!empty($context['activate_message']))
			echo '
				<p><span style="color: red;">', $context['activate_message'], '</span>&nbsp;(<a href="' . $scripturl . '?action=profile2;sa=activateAccount;userID=' . $context['member']['id'] . ';sesc=' . $context['session_id'] . '" ', ($context['activate_type'] == 4 ? 'onclick="return confirm(\'' . $txt['profileConfirm'] . '\');"' : ''), '>', $context['activate_link_text'], '</a>)
					</p>';

		// If the current member is banned, show a message and possibly a link to the ban.
		if (!empty($context['member']['bans']))
		{
			echo '
				</div>
				<div class="row2">>
						<p><span style="color: red;">', $txt['user_is_banned'], '</span>&nbsp;[<a href="#" onclick="document.getElementById(\'ban_info\').style.display = document.getElementById(\'ban_info\').style.display == \'none\' ? \'\' : \'none\';return false;">' . $txt['view_ban'] . '</a>]</p>
				<div id="ban_info" style="display: none;">
					<p><strong>', $txt['user_banned_by_following'], ':</strong></p>
					<ul class="bullit-list">';

			foreach ($context['member']['bans'] as $ban)
				echo '
						<li><span class="smalltext">', $ban['explanation'], '</li>';

			echo '
					</ul>
				</div>';
		}
	}

	// Messenger type information.
	echo '
				<div class="row2">
				<ul class="bullit-list">
					<li><strong>', $txt[513], ':</strong>', $context['member']['icq']['link_text'], '</li>
					<li><strong>', $txt[603], ': </strong>', $context['member']['aim']['link_text'], '</li>
					<li><strong>', $txt['MSN'], ': </strong>', $context['member']['msn']['link_text'], '</li>
					<li><strong>', $txt[604], ': </strong>', $context['member']['yim']['link_text'], '</li>
					<li><strong>', $txt[69], ': </strong>';

	// Only show the email address if it's not hidden.
	if ($context['member']['email_public'])
		echo '
						<a href="mailto:', $context['member']['email'], '">', $context['member']['email'], '</a>';
	// ... Or if the one looking at the profile is an admin they can see it anyway.
	elseif (!$context['member']['hide_email'])
		echo '
						<em><a href="mailto:', $context['member']['email'], '">', $context['member']['email'], '</a></em>';
	else
		echo '
						<em>', $txt[722], '</em>';

	// Some more information.
	echo '
					</li>
					<li><strong>', $txt[96], ': </strong><a href="', $context['member']['website']['url'], '" target="_blank">', $context['member']['website']['title'], '</a></li>
					<li><strong>', $txt[113], ' </strong>
						<em>', $context['can_send_pm'] ? '<a href="' . $context['member']['online']['href'] . '" title="' . $context['member']['online']['label'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $context['member']['online']['image_href'] . '" alt="' . $context['member']['online']['text'] . '" border="0" align="middle" />' : $context['member']['online']['text'], $context['can_send_pm'] ? '</a>' : '', $settings['use_image_buttons'] ? '<span class="smalltext"> ' . $context['member']['online']['text'] . '</span>' : '', '</em></li>';

	// Can they add this member as a buddy?
	if (!empty($context['can_have_buddy']) && !$context['user']['is_owner'])
		echo '
						<li><a href="', $scripturl, '?action=buddy;u=', $context['member']['id'], ';sesc=', $context['session_id'], '">[', $txt['buddy_' . ($context['member']['is_buddy'] ? 'remove' : 'add')], ']</a></li>';

	echo '
					</ul>
				</div>
				<div class="row2">
				<ul class="bullit-list">
					<li><strong>', $txt[231], ': </strong>', $context['member']['gender']['name'], '</li>
					<li><strong>', $txt[420], ':</strong>', $context['member']['age'] . ($context['member']['today_is_birthday'] ? ' &nbsp; <img src="' . $settings['images_url'] . '/bdaycake.gif" width="40" alt="" />' : ''), '</li>
					<li><strong>', $txt[227], ':</strong>', $context['member']['location'], '</li>
					<li><strong>', $txt['local_time'], ':</strong>', $context['member']['local_time'], '</li>';

	if (!empty($modSettings['userLanguage']))
		echo '
					<li><strong>', $txt['smf225'], ':</strong>', $context['member']['language'], '</li>';

	echo '
					</ul>
					</div>';

	// Show the users signature.
	echo '
				<div class="subtitle"><strong>', $txt[85], ':</strong></div>
							<div class="row2"><div class="signature" style="clear: none; padding: 0;">', $context['member']['signature'], '</div></div>
							';


	// Finally, if applicable, span the bottom of the table with links to other useful member functions.
	echo '
	<div class="subtitle"><strong>', $txt[597], ':</strong></div>
	<div class="row2">
	<ul class="bullit-list">';
	if (!$context['user']['is_owner'] && $context['can_send_pm'])
		echo '
			<li><a href="', $scripturl, '?action=pm;sa=send;u=', $context['member']['id'], '">', $txt[688], '.</a></li>';
	echo '
			<li><a href="', $scripturl, '?action=profile;u=', $context['member']['id'], ';sa=showPosts">', $txt[460], ' ', $txt[461], '.</a></li>
			<li><a href="', $scripturl, '?action=profile;u=', $context['member']['id'], ';sa=statPanel">', $txt['statPanel_show'], '.</a></li>
		</ul>
	</div>
</div>';
}

// Template for showing all the posts of the user, in chronological order.
function template_showPosts()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<div class="border">
			<h4 class="title" style="text-align:right;">
			<span class="float-l">', $txt['showPosts'], '</span>&nbsp;';
		if (!empty($context['posts']))
		{
		echo '<strong>', $txt[139], ':</strong> ', $context['page_index'], '';
		}
			
		echo '	</h4>';

	// Only show posts if they have made some!
	if (!empty($context['posts']))
	{
		

		// Button shortcuts
		$quote_button = create_button('quote.gif', 145, 'smf240', 'align="middle"');
		$reply_button = create_button('reply_sm.gif', 146, 146, 'align="middle"');
		$remove_button = create_button('delete.gif', 121, 31, 'align="middle"');
		$notify_button = create_button('notify_sm.gif', 131, 125, 'align="middle"');
		
		// For every post to be displayed, give it its own subtable, and show the important details of the post.
		foreach ($context['posts'] as $post)
		{
			echo '
		<h5 class="subtitle">', $post['counter'], '&nbsp; <a href="', $scripturl, '#', $post['category']['id'], '">', $post['category']['name'], '</a> / <a href="', $scripturl, '?board=', $post['board']['id'], '.0">', $post['board']['name'], '</a> / <a href="', $scripturl, '?topic=', $post['topic'], '.', $post['start'], '#msg', $post['id'], '">', $post['subject'], '</a></h5>
		<div class="row2">
		', $post['body'], '
		<br />
		<p style="text-align:right;"><em>', $txt[30], ': ', $post['time'], '</em></p>
		</div>
		<div class="post-foot">';

			if ($post['can_delete'])
				echo '<a href="', $scripturl, '?action=profile;u=', $context['current_member'], ';sa=showPosts;start=', $context['start'], ';delete=', $post['id'], ';sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt[154], '?\');">', $remove_button, '</a>';
				
			if ($post['can_delete'] && ($post['can_mark_notify'] || $post['can_reply']))
				echo '
								', $context['menu_separator'];
			if ($post['can_reply'])
				echo '
					<a href="', $scripturl, '?action=post;topic=', $post['topic'], '.', $post['start'], '">', $reply_button, '</a>', $context['menu_separator'], '
					<a href="', $scripturl, '?action=post;topic=', $post['topic'], '.', $post['start'], ';quote=', $post['id'], ';sesc=', $context['session_id'], '">', $quote_button, '</a>';
			
			if ($post['can_reply'] && $post['can_mark_notify'])
				echo '
								', $context['menu_separator'];
			if ($post['can_mark_notify'])
				echo '
					<a href="' . $scripturl . '?action=notify;topic=' . $post['topic'] . '.' . $post['start'] . '">' . $notify_button . '</a>';

			echo '</div>
			<div class="block-foot"><!--no content--></div>';
		}

		// Show more page numbers.
		echo '
		<div class="subtitle"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</div>
		<div class="block-foot"><!--no content--></div>
		</div>';
	}
	// No posts? Just end the table with a informative message.
	else
		echo '
			<div class="row2">
				<p>', $txt[170], '</p>
			</div>
			<div class="block-foot"><!--no content--></div>
		</div>';
}

// Template for showing all the buddies of the current user.
function template_editBuddies()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<div class="border">
		<h3 class="title">', $txt['editBuddies'], '</h3>
		<div class="row2">
		<dl>';

	// If they don't have any buddies don't list them!
	if (empty($context['buddies']))
		echo '
		<dt><strong>', $txt['no_buddies'], '</strong></dt>
		<dd> --- </dd>';

	// Now loop through each buddy showing info on each.
	$alternate = false;
	foreach ($context['buddies'] as $buddy)
	{
		echo '
			<dt class="', $alternate ? 'placeholder' : 'placeholder', '">
			<span class="float-r">
			<a href="', $buddy['online']['href'], '"><img src="', $buddy['online']['image_href'], '" alt="', $buddy['online']['label'], '" title="', $buddy['online']['label'], '" border="0" /></a>
			', ($buddy['hide_email'] ? '' : '<a href="mailto:' . $buddy['email'] . '"><img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt[69] . '" title="' . $txt[69] . ' ' . $buddy['name'] . '" border="0" /></a>'), '
			', $buddy['icq']['link'], ' ', $buddy['aim']['link'], ' ', $buddy['yim']['link'], ' ', $buddy['msn']['link'], '</span>
				<strong>', $buddy['link'], '</strong>
				</dt>
				<dd><em><a href="', $scripturl, '?action=profile;u=', $context['member']['id'], ';sa=editBuddies;remove=', $buddy['id'], '">', $txt['buddy_remove'], '?</a></em></dd>';

		$alternate = !$alternate;
	}

	echo '
		</dl>
		</div></div>';

	// Add a new buddy?
	echo '
	<br />
	<form action="', $scripturl, '?action=profile;u=', $context['member']['id'], ';sa=editBuddies" method="post" accept-charset="', $context['character_set'], '">
		<div class="border">
		<h4 class="title">', $txt['buddy_add'], '</h4>
		<div class="row1-center">
		<strong>', $txt['who_member'], ':</strong> 
		<input type="text" name="new_buddy" id="new_buddy" size="25" /> 
		<a href="', $scripturl, '?action=findmember;input=new_buddy;quote=1;sesc=', $context['session_id'], '" onclick="return reqWin(this.href, 350, 400);"><img src="', $settings['images_url'], '/icons/assist.gif" border="0" alt="', $txt['find_members'], '" align="top" /></a>
		</div>
		<div class="subtitle" style="text-align:center;"><input type="submit" class="button" value="', $txt['buddy_add_button'], '" /></div>
		</div>
	</form>';
}


// This template shows an admin information on a users IP addresses used and errors attributed to them.
function template_trackUser()
{
	global $context, $settings, $options, $scripturl, $txt;

	// The first table shows IP information about the user.
	echo '
		<div class="border">
					<h4 class="title">', $txt['view_ips_by'], ' ', $context['member']['name'], '</h4>';

	// The last IP the user used.
	echo '
				<div class="row2"><p>
				', $txt['most_recent_ip'], ': <a href="', $scripturl, '?action=trackip;searchip=', $context['last_ip'], ';">', $context['last_ip'], '</a>
				</p></div>';

	// Lists of IP addresses used in messages / error messages.
	echo '
				<h4 class="subtitle">', $txt['ips_in_messages'], ':</h4>
					<div class="row2">
						', (count($context['ips']) > 0 ? implode(', ', $context['ips']) : '(' . $txt['none'] . ')'), '
					</div>
				<h4 class="subtitle">', $txt['ips_in_errors'], ':</h4>
					<div class="row2">
						', (count($context['error_ips']) > 0 ? implode(', ', $context['error_ips']) : '(' . $txt['none'] . ')'), '
					</div>';

	// List any members that have used the same IP addresses as the current member.
	echo '
				<h4 class="subtitle">', $txt['members_in_range'], ':</h4>
					<div class="row2">
						', (count($context['members_in_range']) > 0 ? implode(', ', $context['members_in_range']) : '(' . $txt['none'] . ')'), '
					</div>
				<div class="block-foot"><!--no content--></div>
		</div>
		<br />';

	// The second table lists all the error messages the user has caused/received.
	echo '
		<div class="border">
		<h5 class="title" style="text-align:right;"><span class="float-l">', $txt['errors_by'], ' ', $context['member']['name'], '</span> <strong>', $txt[139], ':</strong> ', $context['page_index'], '</h5>
				<div class="row2">
				<p>', $txt['errors_desc'], '</p>
				</div>
				<div class="row2">
				<dl>';

	// If there arn't any messages just give a message stating this.
	if (empty($context['error_messages']))
		echo '
				<dt><em>', $txt['no_errors_from_user'], '</em></dt>
				<dd> --- <dd>';

	// Otherwise print every error message out.
	else
		// For every error message print the IP address that caused it, the message displayed and the date it occurred.
		foreach ($context['error_messages'] as $error)
			echo '
				<dt><strong><a href="', $scripturl, '?action=trackip;searchip=', $error['ip'], ';">', $error['ip'], '</a> ', $error['time'], '</strong></dt>
				<dd>
						', $error['message'], '<br />
						<a href="', $error['url'], '">', $error['url'], '</a>
					</dd>';
	echo '
			</dl>
		</div>
		</div>';
}

// The template for trackIP, allowing the admin to see where/who a certain IP has been used.
function template_trackIP()
{
	global $context, $settings, $options, $scripturl, $txt;

	// This function always defaults to the last IP used by a member but can be set to track any IP.
	echo '
		<form action="', $scripturl, '?action=trackip" method="post" accept-charset="', $context['character_set'], '">';

	// The first table in the template gives an input box to allow the admin to enter another IP to track.
	echo '
			<div class="border">
				<h4 class="title">', $txt['trackIP'], '</h4>
					<div class="row2">
							', $txt['enter_ip'], ':&nbsp;&nbsp;<input type="text" name="searchip" value="', $context['ip'], '" />&nbsp;&nbsp;<input type="submit" class="button" value="', $txt['trackIP'], '" />
						</div>
					</div>
		</form>
		<br />';

	// The table inbetween the first and second table shows links to the whois server for every region.
	if ($context['single_ip'])
	{
		echo '
		<div class="border">
				<h4 class="title">', $txt['whois_title'], ' ', $context['ip'], '</h4>
				<div class="row2">
				<ul class="bullit-list">';
		foreach ($context['whois_servers'] as $server)
			echo '
						<li><a href="', $server['url'], '" target="_blank"', isset($context['auto_whois_server']) && $context['auto_whois_server']['name'] == $server['name'] ? ' style="font-weight: bold;"' : '', '>', $server['name'], '</a></li>';
		echo '
					</ul>
				</div>
			</div>
		<br />';
	}

	// The second table lists all the members who have been logged as using this IP address.
	echo '
		<div class="border">
				<h4 class="title">', $txt['members_from_ip'], ' ', $context['ip'], '
					</h4>
					<div class="row2">';
	if (empty($context['ips']))
		echo '
				<p>', $txt['no_members_from_ip'], '</p>';
	else
		// Loop through each of the members and display them.
		foreach ($context['ips'] as $ip => $memberlist)
			echo '', $txt['ip_address'], ': <a href="', $scripturl, '?action=trackip;searchip=', $ip, ';">', $ip, '</a><br />
					', implode(', ', $memberlist), '';
	echo '
			</div>
		</div>
		<br />';

	// The third table in the template displays a list of all the messages sent using this IP (can be quite long).
	echo '
		<div class="border">
				<h4 class="title" style="text-align:right;"><span class="float-l">', $txt['messages_from_ip'], ' ', $context['ip'], '</span> <strong>', $txt[139], ':</strong> ', $context['message_page_index'], '</h4>
				<div class="row2">
				<dl>';

	// No message means nothing to do!
	if (empty($context['messages']))
		echo '
				<dt>', $txt['no_messages_from_ip'], '</i></dt>
				<dd> --- </dd>';
	else
		// For every message print the IP, member who posts it, subject (with link) and date posted.
		foreach ($context['messages'] as $message)
			echo '
				<dt><strong>', $message['member']['link'], ' - <a href="', $scripturl, '?action=trackip;searchip=', $message['ip'], '">', $message['ip'], '</a></strong></dt>
				<dd>', $message['time'], '. <a href="', $scripturl, '?topic=', $message['topic'], '.msg', $message['id'], '#msg', $message['id'], '">', $message['subject'], '</a>.</dd>';
	echo '
			</dl>
		</div>
		</div>
		<br />';

	// The final table in the template lists all the error messages caused/received by anyone using this IP address.
	echo '
		<div class="border">
				<h4 class="title" style="text-align:right;"><span class="float-l">', $txt['errors_from_ip'], ' ', $context['ip'], '</span> ', $txt[139], ':</strong> ', $context['error_page_index'], '</h4>
				<div class="row2">
						<p>', $txt['errors_from_ip_desc'], '</p>
					</div>
				<div class="row2">
				<dl>';
	if (empty($context['error_messages']))
		echo '
				<dt>', $txt['no_errors_from_ip'], '</dt>
				<dd> -- </dd>';
	else
		// For each error print IP address, member, message received and date caused.
		foreach ($context['error_messages'] as $error)
			echo '
				<dt><strong>', $error['member']['link'], ' - ', $error['error_time'], ' - <a href="', $scripturl, '?action=trackip;searchip=', $error['ip'], '">', $error['ip'], '</a></strong></dt>
					<dd>
						<span style="color:#990000;"><em>', $error['message'], '</span></em><br />
						<a href="', $error['url'], '">', $error['url'], '</a>
					</dd>';
	echo '
			</dl>
		</div>
		</div>';
}

function template_showPermissions()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<div class="border">
				<h4 class="title">', $txt['showPermissions'], '</h4>';
	if ($context['member']['has_all_permissions'])
	{
		echo '
			<div class="row2">
				<p><strong>', $txt['showPermissions_all'], '</strong></p>
				</div>
				<div class="block-foot"><!-- no content--></div>
				';
	}
	else
	{
		if (!empty($context['no_access_boards']))
		{
			echo '
				<h4 class="subtitle">', $txt['showPermissions_restricted_boards'], '</h4>
			<div class="row2">
				<p>', $txt['showPermissions_restricted_boards_desc'], ':<br />';
			foreach ($context['no_access_boards'] as $no_access_board)
				echo '
					<a href="', $scripturl, '?board=', $no_access_board['id'], '">', $no_access_board['name'], '</a>', $no_access_board['is_last'] ? '' : ', ';
			echo '
				</p>
			</div>';
		}
		// General Permissions section.
		echo '<h5 class="subtitle">', $txt['showPermissions_general'], '</h5>';
		if (!empty($context['member']['permissions']['general']))
		{
			echo '
			<div class="row2">
				<p><em>', $txt['showPermissions_permission'], '</em></p>
				</div>
				<div class="row2">
				<dl>';

			foreach ($context['member']['permissions']['general'] as $permission)
			{
				echo '
			<dt>
			<strong>', $permission['is_denied'] ? '<del>' . $permission['id'] . '</del>' : $permission['id'], '</strong> 
			</dt>
				<dd><span class="smalltext">', $permission['name'], ' - ';
				if ($permission['is_denied'])
					echo '
					<span style="color: red; font-weight: bold;">', $txt['showPermissions_denied'], ': </span>', implode(', ', $permission['groups']['denied']);
				else
					echo '
					<span style="font-weight: bold;">', $txt['showPermissions_given'], ': </span>', implode(', ', $permission['groups']['allowed']);
				echo '
				</span></dd>';
			}
		}
		else
			echo '
			<dt>', $txt['showPermissions_none_general'], '</dt>
			<dd> --- </dd>';

		// Board permission section.
		echo '
		</dl>
		</div>
		
		<h5 class="subtitle">
					<a name="board_permissions"></a>', $txt['showPermissions_select'], ':</h5>
					<div class="row2">
					<form action="' . $scripturl . '?action=profile;u=', $context['member']['id'], ';sa=showPermissions#board_permissions" method="post" accept-charset="', $context['character_set'], '">
						
						<select name="board" onchange="if (this.options[this.selectedIndex].value) this.form.submit();">
							<option value="0"', $context['board'] == 0 ? ' selected="selected"' : '', '>', $txt['showPermissions_global'], '</option>';
		if (!empty($context['boards']))
			echo '
							<option value="" disabled="disabled">---------------------------</option>';

		// Fill the box with any local permission boards.
		foreach ($context['boards'] as $board)
			echo '
							<option value="', $board['id'], '"', $board['selected'] ? ' selected="selected"' : '', '>', $board['name'], '</option>';

		echo '
						</select>
					</form>
					</div>
				';
		if (!empty($context['member']['permissions']['board']))
		{
			echo '
			<div class="row2">
				<p>', $txt['showPermissions_permission'], '</p>
			</div>
			<div class="row2">
			<dl>';
			foreach ($context['member']['permissions']['board'] as $permission)
			{
				echo '
			<dt><strong>', $permission['is_denied'] ? '<del>' . $permission['id'] . '</del>' : $permission['id'], '</strong></dt>
				<dd><span class="smalltext">', $permission['name'], ' - ';
				if ($permission['is_denied'])
				{
					echo '
					<span style="color: red; font-weight: bold;">', $txt['showPermissions_denied'], ': </span>', implode(', ', $permission['groups']['denied']), '<br />';
				}
				else
				{
					echo '
					<span style="font-weight: bold;">', $txt['showPermissions_given'], ': </span>', implode(', ', $permission['groups']['allowed']), '<br />';
				}
				echo '
				</span></dd>';
			}
		
		echo '
		</dl></div>';
		}
		else
			echo '
			<div class="row2">
			<p>', $txt['showPermissions_none_board'], '</p>
			</div>';
	}
	echo '
		</div>';
}

// Template for user statistics, showing graphs and the like.
function template_statPanel()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<div class="border">
				<h4 class="title">', $txt['statPanel_generalStats'], ' - ', $context['member']['name'], '</h4>';

	// First, show a few text statistics such as post/topic count.
	echo '
		<div class="row2">
		<ul class="bullit-list">
						<li>', $txt['statPanel_total_time_online'], ': ', $context['time_logged_in'], '</li>
						<li>', $txt['statPanel_total_posts'], ': ', $context['num_posts'], ' ', $txt['statPanel_posts'], '</li>
						<li>', $txt['statPanel_total_topics'], ': ', $context['num_topics'], ' ', $txt['statPanel_topics'], '</li>
						<li>', $txt['statPanel_users_polls'], ': ', $context['num_polls'], ' ', $txt['statPanel_polls'], '</li>
						<li>', $txt['statPanel_users_votes'], ': ', $context['num_votes'], ' ', $txt['statPanel_votes'], '</li>
					</ul>
				</div>';

	// This next section draws a graph showing what times of day they post the most.
	echo '
			<h5 class="subtitle">', $txt['statPanel_activityTime'], '</h5>
			<div class="row2">';

	// If they haven't post at all, don't draw the graph.
	if (empty($context['posts_by_time']))
		echo '
						<p>', $txt['statPanel_noPosts'], '</p>';
	// Otherwise do!
	else
	{
		echo '<ul class="bullit-list" style="text-align:right;">';

		// Loops through each hour drawing the bar to the correct height.
		foreach ($context['posts_by_time'] as $time_of_day)
			echo '
				<li><span class="float-l">', $time_of_day['hour'], '</span> &nbsp; <img src="', $settings['images_url'], '/bar-left.gif" width="2" height="7" alt="" border="0" /><img src="', $settings['images_url'], '/bar.gif" width="', $time_of_day['posts_percent'], '" height="7" alt="" border="0" /><img src="', $settings['images_url'], '/bar-right.gif" width="2" height="7" alt="" border="0" /></li>';
		echo '
							</ul>
						';
	}
	echo '</div>
					';

	// The final section is two columns with the most popular boards by posts and activity (activity = users posts / total posts).
	echo '
			<h5 class="subtitle">', $txt['statPanel_topBoards'], '</h5>
			<div class="row2">
			';
	if (empty($context['popular_boards']))
		echo '
						<p>', $txt['statPanel_noPosts'], '</p>';
	else
	{
	echo'<ul class="bullit-list-stats">';
		// Draw a bar for every board.
		foreach ($context['popular_boards'] as $board)
		{
			echo '
						<li>
						<span class="float-l">', $board['link'], ' ', $board['posts'], '</span>&nbsp;<img src="', $settings['images_url'], '/bar-left.gif" width="2" height="7" alt="" border="0" />', $board['posts'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $board['posts_percent'] . '" height="7" alt="" border="0" />' : '&nbsp;', '<img src="', $settings['images_url'], '/bar-right.gif" width="2" height="7" alt="" border="0" /></li>';
		}
	echo'</ul>';
	}
	echo '
					</div>
			
			<h5 class="subtitle">', $txt['statPanel_topBoardsActivity'], '</h5>
			<div class="row2">';
	if (empty($context['board_activity']))
		echo '
						<p>', $txt['statPanel_noPosts'], '</p>';
	else
	{
	echo'<ul class="bullit-list-stats">';
		// Draw a bar for every board.
		foreach ($context['board_activity'] as $activity)
		{
			echo '
						<li><span class="float-l">', $activity['link'], ' ', $activity['percent'], '%</span>&nbsp;<img src="', $settings['images_url'], '/bar-left.gif" width="2" height="7" alt="" border="0" />', $activity['percent'] > 0 ? '<img src="' . $settings['images_url'] . '/bar.gif" width="' . $activity['percent'] . '" height="7" alt="" border="0" />' : '&nbsp;', '<img src="', $settings['images_url'], '/bar-right.gif" width="2" height="7" alt="" border="0" /></li>';
		}
	echo'</ul>';
	}
	echo '
					</div>
				<div class="block-foot"><!-- no content--></div>
			</div>';
}

// Template for changing user account information.
function template_account()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// Javascript for checking if password has been entered / taking admin powers away from themselves.
	echo '
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
			function checkProfileSubmit()
			{';

	// If this part requires a password, make sure to give a warning.
	if ($context['user']['is_owner'] && $context['require_password'])
		echo '
				// Did you forget to type your password?
				if (document.forms.creator.oldpasswrd.value == "")
				{
					alert("', $txt['smf244'], '");
					return false;
				}';

	// This part checks if they are removing themselves from administrative power on accident.
	if ($context['allow_edit_membergroups'] && $context['user']['is_owner'] && $context['member']['group'] == 1)
		echo '
				if (typeof(document.forms.creator.ID_GROUP) != "undefined" && document.forms.creator.ID_GROUP.value != "1")
					return confirm("', $txt['deadmin_confirm'], '");';

	echo '
				return true;
			}
		// ]]></script>';

	// The main containing header.
	echo '
		<form action="', $scripturl, '?action=profile2" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator" onsubmit="return checkProfileSubmit();">
		<div class="border">
				<h4 class="title">', $txt[79], '</h4>
				';

	// Display Name, language and date user registered.
	echo '
				<div class="row2"><p>', $txt['account_info'], '</p></div>
				<div class="row2">';

	// Only show these settings if you're allowed to edit the account itself (not just the membergroups).
	if ($context['allow_edit_account'])
	{
		if ($context['user']['is_admin'] && !empty($context['allow_edit_username']))
			echo '
					<p style="color: #990000">', $txt['username_warning'], '</p>
						<span class="formleft"><strong>', $txt[35], ': </strong></span>
						<span class="formright"><input type="text" name="memberName" size="30" value="', $context['member']['username'], '" /></span>';
		else
			echo '
					<span class="formleft"><strong>', $txt[35], ': </strong>', $context['user']['is_admin'] ? '
					<p class="smalltext">(<a href="' . $scripturl . '?action=profile;u=' . $context['member']['id'] . ';sa=account;changeusername" style="font-style: italic;">' . $txt['username_change'] . '</a>)</p>' : '', '
					</span>
					<span class="formright"><strong>', $context['member']['username'], '</strong></span>';

		echo '
					<span class="formleft"><strong', (isset($context['modify_error']['no_name']) || isset($context['modify_error']['name_taken']) ? ' style="color: #FF0000;"' : ''), '>', $txt[68], ': </strong>
									<p class="smalltext">', $txt[518], '</p>
								</span>
								<span class="formright">', ($context['allow_edit_name'] ? '<input type="text" name="realName" size="30" value="' . $context['member']['name'] . '" maxlength="60"  />' : $context['member']['name']), '</span>';
								
		if ($context['user']['is_admin'])
			echo '
					<span class="formleft"><strong>', $txt[233], ':</strong></span> 
					<span class="formright"><input type="text" name="dateRegistered" size="30" value="', $context['member']['registered'], '" /></span>
					<span class="formleft"><strong>', $txt[86], ': </strong></span>
					<span class="formright"><input type="text" name="posts" size="4" value="', $context['member']['posts'], '" /></span>';

		// Only display if admin has enabled "user selectable language".
		if (!empty($modSettings['userLanguage']) && count($context['languages']) > 1)
		{
			echo '
							<span class="formleft"><strong>', $txt[349], ':</strong></span> 
							<span class="formright"><select name="lngfile">';

			// Fill a select box with all the languages installed.
			foreach ($context['languages'] as $language)
				echo '
										<option value="', $language['filename'], '"', $language['selected'] ? ' selected="selected"' : '', '>', $language['name'], '</option>';
			echo '
									</select>
								</span>';
		}
	echo'
	</div>';
	}
	
	// Only display member group information/editing with the proper permissions.
	if ($context['allow_edit_membergroups'])
	{
		echo '
	<h5 class="subtitle">', $txt['primary_membergroup'], ': </h5>
	<div class="row2">
	<select name="ID_GROUP">';
		// Fill the select box with all primary member groups that can be assigned to a member.
		foreach ($context['member_groups'] as $member_group)
			echo '
					<option value="', $member_group['id'], '"', $member_group['is_primary'] ? ' selected="selected"' : '', '>
					', $member_group['name'], '
					</option>';
		echo '
			</select>
			<p>(<a href="', $scripturl, '?action=helpadmin;help=moderator_why_missing" onclick="return reqWin(this.href);">', $txt['moderator_why_missing'], '</a>)</p>
			</div>
			<h5 class="subtitle">', $txt['additional_membergroups'], ':</h5>
			<div class="row2">
			<div id="additionalGroupsList">
			<input type="hidden" name="additionalGroups[]" value="0" />';
			
		// For each membergroup show a checkbox so members can be assigned to more than one group.
		foreach ($context['member_groups'] as $member_group)
			if ($member_group['can_be_additional'])
				echo '
										<label for="additionalGroups-', $member_group['id'], '"><input type="checkbox" name="additionalGroups[]" value="', $member_group['id'], '" id="additionalGroups-', $member_group['id'], '"', $member_group['is_additional'] ? ' checked="checked"' : '', ' class="check" /> ', $member_group['name'], '</label><br />';
		echo '
									</div>
										<a href="javascript:void(0);" onclick="document.getElementById(\'additionalGroupsList\').style.display = \'block\'; document.getElementById(\'additionalGroupsLink\').style.display = \'none\'; return false;" id="additionalGroupsLink" style="display: none;">', $txt['additional_membergroups_show'], '</a>
									<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
										document.getElementById("additionalGroupsList").style.display = "none";
										document.getElementById("additionalGroupsLink").style.display = "";
									// ]]></script>
									</div>';
	}

	// Show this part if you're not only here for assigning membergroups.
	if ($context['allow_edit_account'])
	{
		// Show email address box.
		echo '
				<h5', (isset($context['modify_error']['bad_email']) || isset($context['modify_error']['no_email']) || isset($context['modify_error']['email_taken']) ? ' style="color: #FF0000;"' : ''), ' class="subtitle">', $txt[69], ': </h5>
				<div class="row2">
				<p>', $txt[679], '</p><br />
				<input type="text" name="emailAddress" size="30" value="', $context['member']['email'], '" /> ';

		// If the user is allowed to hide their email address from the public give them the option to here.
		if ($context['allow_hide_email'])
		{
			echo '
							<br /><br /><input type="hidden" name="hideEmail" value="0" /><input type="checkbox" name="hideEmail"', $context['member']['hide_email'] ? ' checked="checked"' : '', ' value="1" class="check" /> ', $txt[721], '';
	}

		// Option to show online status - if they are allowed to.
		if ($context['allow_hide_online'])
		{
			echo '
							&nbsp; <input type="hidden" name="showOnline" value="0" /><input type="checkbox" name="showOnline"', $context['member']['show_online'] ? ' checked="checked"' : '', ' value="1" class="check" />  ', $txt['show_online'], '';
		}

		// Show boxes so that the user may change his or her password.
		echo '
							</div>
			<h5', (isset($context['modify_error']['bad_new_password']) ? ' style="color: #990000;"' : ''), ' class="subtitle">', $txt[81], ': </h5>
				<div class="row2">
				<p>', $txt[596], '</p>
				<input type="password" name="passwrd1" size="20" />&nbsp; ', $txt[82], ': &nbsp;<input type="password" name="passwrd2" size="20" />
				</div>';

		// This section allows the user to enter secret question/answer so they can reset a forgotten password.
		echo '
				<h5 class="subtitle">', $txt['pswd1'], ':</h5>
				<div class="row2">
				<p>', $txt['secret_desc'], '</p>
				<input type="text" name="secretQuestion" size="50" value="', $context['member']['secret_question'], '" />
				</div>
				<h5 class="subtitle">', $txt['pswd2'], ':</h5>
				<div class="row2">
				<p>', $txt['secret_desc2'], '</p>
				<input type="text" name="secretAnswer" size="20" />
				<p><a href="', $scripturl, '?action=helpadmin;help=secret_why_blank" onclick="return reqWin(this.href);">', $txt['secret_why_blank'], '</a></p>
				</div>';
	}
	// Show the standard "Save Settings" profile button.
	template_profile_save();

	echo '<div class="block-foot"><!--no content--></div>
			</div>
		</form>';
}

// Template for forum specific options - avatar, signature etc.
function template_forumProfile()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// The main containing header.
	echo '
		<form action="', $scripturl, '?action=profile2" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator" enctype="multipart/form-data">
			<div class="border">
			<h4 class="title">', $txt[79], '</h4>
			<div class="row2"><p>', $txt['forumProfile_info'], '</p></div>';

	// This is the avatar selection table that is only displayed if avatars are enabled!
	if (!empty($context['member']['avatar']['allow_server_stored']) || !empty($context['member']['avatar']['allow_upload']) || !empty($context['member']['avatar']['allow_external']))
	{
		// If users are allowed to choose avatars stored on the server show selection boxes to choice them from.
		if (!empty($context['member']['avatar']['allow_server_stored']))
		{
			echo '
					<div class="subtitle">
					<span class="float-l"><input type="radio" name="avatar_choice" id="avatar_choice_server_stored" value="server_stored"', ($context['member']['avatar']['choice'] == 'server_stored' ? ' checked="checked"' : ''), ' class="check" /></span> <strong', (isset($context['modify_error']['bad_avatar']) ? ' style="color: #990000;"' : ''), '><label for="avatar_choice_server_stored">', $txt[229], ':</label></strong>
					</div>
					<div class="row2">
					<div class="float-r"><img name="avatar" id="avatar" src="', !empty($context['member']['avatar']['allow_external']) && $context['member']['avatar']['choice'] == 'external' ? $context['member']['avatar']['external'] : $modSettings['avatar_url'] . '/blank.gif', '" alt="Do Nothing" />
					</div>
						<select name="cat" id="cat" size="10" onchange="changeSel(\'\');" onfocus="selectRadioByName(document.forms.creator.avatar_choice, \'server_stored\');">';
			// This lists all the file catergories.
			foreach ($context['avatars'] as $avatar)
				echo '
												<option value="', $avatar['filename'] . ($avatar['is_dir'] ? '/' : ''), '"', ($avatar['checked'] ? ' selected="selected"' : ''), '>', $avatar['name'], '</option>';
			echo '
											</select>

											<select name="file" id="file" size="10" style="display: none;" onchange="showAvatar()" onfocus="selectRadioByName(document.forms.creator.avatar_choice, \'server_stored\');" disabled="disabled"><option></option></select>
										</div>';
		}

		// If the user can link to an off server avatar, show them a box to input the address.
		if (!empty($context['member']['avatar']['allow_external']))
		{
			echo '
					<div class="subtitle">
					<span class="float-l"><input type="radio" name="avatar_choice" id="avatar_choice_external" value="external"', ($context['member']['avatar']['choice'] == 'external' ? ' checked="checked"' : ''), ' class="check" />
</span>					
					<strong><label for="avatar_choice_external">', $txt[475], ':</label></strong>
					</div>
					<div class="row2">
					<p>', $txt[474], '</p>
					<input type="text" name="userpicpersonal" size="45" value="', $context['member']['avatar']['external'], '" onfocus="selectRadioByName(document.forms.creator.avatar_choice, \'external\');" onchange="if (typeof(previewExternalAvatar) != \'undefined\') previewExternalAvatar(this.value);" />
					</div>';
		}

		// If the user is able to upload avatars to the server show them an upload box.
		if (!empty($context['member']['avatar']['allow_upload']))
			echo '
					<div class="subtitle">
					<span class="float-l">
					<input type="radio" name="avatar_choice" id="avatar_choice_upload" value="upload"', ($context['member']['avatar']['choice'] == 'upload' ? ' checked="checked"' : ''), ' class="check" /></span> 
					<label for="avatar_choice_upload">', $txt['avatar_will_upload'], ':</label></strong>
					</div>
					<div class="row2">
					', ($context['member']['avatar']['ID_ATTACH'] > 0 ? '<img src="' . $context['member']['avatar']['href'] . '" /><input type="hidden" name="ID_ATTACH" value="' . $context['member']['avatar']['ID_ATTACH'] . '" /><br /><br />' : ''), '
									<input type="file" size="48" name="attachment" value="" onfocus="selectRadioByName(document.forms.creator.avatar_choice, \'upload\');" />
								</div>';
	}

	// Personal text...
	echo '	<div class="subtitle"><strong>', $txt[228], ': </strong></div>
	<div class="row2">
	<input type="text" name="personalText" size="50" maxlength="50" value="', $context['member']['blurb'], '" />
			</div>';

	// Gender, birthdate and location.
	echo '
			<div class="subtitle"><strong>', $txt[563], ':</strong></div>
			<div class="row2">
			<p>', $txt[566], ' - ', $txt[564], ' - ', $txt[565], '</p>
			<input type="text" name="bday3" size="4" maxlength="4" value="', $context['member']['birth_date']['year'], '" /> -
									<input type="text" name="bday1" size="2" maxlength="2" value="', $context['member']['birth_date']['month'], '" /> -
									<input type="text" name="bday2" size="2" maxlength="2" value="', $context['member']['birth_date']['day'], '" />
									</div>
			
			<div class="subtitle"><strong>', $txt[227], ': </strong></div>
			<div class="row2">
			<input type="text" name="location" size="50" value="', $context['member']['location'], '" />
			</div>
			
			<div class="subtitle"><strong>', $txt[231], ': </strong></div>
			<div class="row2">
			<select name="gender" size="1">
			<option value="0"></option>
			<option value="1"', ($context['member']['gender']['name'] == 'm' ? ' selected="selected"' : ''), '>', $txt[238], '</option>
			<option value="2"', ($context['member']['gender']['name'] == 'f' ? ' selected="selected"' : ''), '>', $txt[239], '</option>
			</select>
			</div>';

	// All the messenger type contact info.
	echo '
		<div class="subtitle"><strong>', $txt[513], ': </strong></div>
		<div class="row2">
		<p>', $txt[600], '</p>
		<input type="text" name="ICQ" size="24" value="', $context['member']['icq']['name'], '" />
		</div>
		
		<div class="subtitle"><strong>', $txt[603], ': </strong></div>
		<div class="row2">
		<p>', $txt[601], '</p>
		<input type="text" name="AIM" maxlength="16" size="24" value="', $context['member']['aim']['name'], '" />
		</div>
		
		<div class="subtitle"><strong>', $txt['MSN'], ': </strong></div>
		<div class="row2">
		<p>', $txt['smf237'], '.</p>
		<input type="text" name="MSN" size="24" value="', $context['member']['msn']['name'], '" />
		</div>
		
		<div class="subtitle"><strong>', $txt[604], ': </strong></div>
		<div class="row2">
		<p>', $txt[602], '</p>
		<input type="text" name="YIM" maxlength="32" size="24" value="', $context['member']['yim']['name'], '" />
		</div>';

	// Input box for custom titles, if they can edit it...
	if (!empty($modSettings['titlesEnable']) && $context['allow_edit_title'])
		echo '
		<div class="subtitle"><strong>' . $txt['title1'] . ': </strong></div>
		<div class="row2">
		<input type="text" name="usertitle" size="50" value="' . $context['member']['title'] . '" />
		</div>';

	// Show the signature box.
	echo '
		<div class="subtitle"><strong>', $txt[85], ':</strong></div>
		<div class="row2">
		<p>', $txt[606], '</p>';

	if ($context['show_spellchecking'])
		echo '
			<input type="button" class="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'creator\', \'signature\');" />';

	echo '
		<textarea class="editor" onkeyup="calcCharLeft();" name="signature" rows="5" cols="50">', $context['member']['signature'], '</textarea>';

	// If there is a limit at all!
	if (!empty($context['max_signature_length']))
		echo '
		<p>', $txt[664], ' <span id="signatureLeft">', $context['max_signature_length'], '</span></p>';

	// Load the spell checker?
	if ($context['show_spellchecking'])
		echo '
			<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/spellcheck.js"></script>';

	// Some javascript used to count how many characters have been used so far in the signature.
	echo '
									<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
										function tick()
										{
											if (typeof(document.forms.creator) != "undefined")
											{
												calcCharLeft();
												setTimeout("tick()", 1000);
											}
											else
												setTimeout("tick()", 800);
										}

										function calcCharLeft()
										{
											var maxLength = ', $context['max_signature_length'], ';
											var oldSignature = "", currentSignature = document.forms.creator.signature.value;

											if (!document.getElementById("signatureLeft"))
												return;

											if (oldSignature != currentSignature)
											{
												oldSignature = currentSignature;

												if (currentSignature.replace(/\r/, "").length > maxLength)
													document.forms.creator.signature.value = currentSignature.replace(/\r/, "").substring(0, maxLength);
												currentSignature = document.forms.creator.signature.value.replace(/\r/, "");
											}

											setInnerHTML(document.getElementById("signatureLeft"), maxLength - currentSignature.length);
										}

										setTimeout("tick()", 800);
									// ]]></script>
				
								</div>';

	// Website details.
	echo '
		<div class="subtitle"><strong>', $txt[83], ': </strong></div>
		<div class="row2">
		<p>', $txt[598], '</p>
		<input type="text" name="websiteTitle" size="50" value="', $context['member']['website']['title'], '" />
		</div>
		
		<div class="subtitle"><strong>', $txt[84], ': </strong></div>
		<div class="row2">
		<p>', $txt[599], '</p>
		<input type="text" name="websiteUrl" size="50" value="', $context['member']['website']['url'], '" />
		</div>';

	// If karma is enabled let the admin edit it...
	if ($context['user']['is_admin'] && !empty($modSettings['karmaMode']))
	{
		echo '
		
		<div class="subtitle"><strong>', $modSettings['karmaLabel'], '</strong></div>
		<div class="row2">
		', $modSettings['karmaApplaudLabel'], ' <input type="text" name="karmaGood" size="4" value="', $context['member']['karma']['good'], '" onchange="setInnerHTML(document.getElementById(\'karmaTotal\'), this.value - this.form.karmaBad.value);" style="margin-right: 2ex;" /> ', $modSettings['karmaSmiteLabel'], ' <input type="text" name="karmaBad" size="4" value="', $context['member']['karma']['bad'], '" onchange="this.form.karmaGood.onchange();" /> &nbsp; 
									(', $txt[94], ': <span id="karmaTotal">', ($context['member']['karma']['good'] - $context['member']['karma']['bad']), '</span>)
									</div>';
	}

	// Show the standard "Save Settings" profile button.
	template_profile_save();

	echo '
		<div class="block-foot"><!--no content--></div>
		</div>';

	/* If the user is allowed to choose avatars stored on the server, the below javascript is used to update the
	   file listing of avatars as the user changes catergory. It also updates the preview image as they choose
	   different files on the select box. */
	if (!empty($context['member']['avatar']['allow_server_stored']))
		echo '
			<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
				var files = ["' . implode('", "', $context['avatar_list']) . '"];
				var avatar = document.getElementById("avatar");
				var cat = document.getElementById("cat");
				var selavatar = "' . $context['avatar_selected'] . '";
				var avatardir = "' . $modSettings['avatar_url'] . '/";
				var size = avatar.alt.substr(3, 2) + " " + avatar.alt.substr(0, 2) + String.fromCharCode(117, 98, 116);
				var file = document.getElementById("file");

				if (avatar.src.indexOf("blank.gif") > -1)
					changeSel(selavatar);
				else
					previewExternalAvatar(avatar.src)

				function changeSel(selected)
				{
					if (cat.selectedIndex == -1)
						return;

					if (cat.options[cat.selectedIndex].value.indexOf("/") > 0)
					{
						var i;
						var count = 0;

						file.style.display = "inline";
						file.disabled = false;

						for (i = file.length; i >= 0; i = i - 1)
							file.options[i] = null;

						for (i = 0; i < files.length; i++)
							if (files[i].indexOf(cat.options[cat.selectedIndex].value) == 0)
							{
								var filename = files[i].substr(files[i].indexOf("/") + 1);
								var showFilename = filename.substr(0, filename.lastIndexOf("."));
								showFilename = showFilename.replace(/[_]/g, " ");

								file.options[count] = new Option(showFilename, files[i]);

								if (filename == selected)
								{
									if (file.options.defaultSelected)
										file.options[count].defaultSelected = true;
									else
										file.options[count].selected = true;
								}

								count++;
							}

						if (file.selectedIndex == -1 && file.options[0])
							file.options[0].selected = true;

						showAvatar();
					}
					else
					{
						file.style.display = "none";
						file.disabled = true;
						document.getElementById("avatar").src = avatardir + cat.options[cat.selectedIndex].value;
						document.getElementById("avatar").style.width = "";
						document.getElementById("avatar").style.height = "";
					}
				}

				function showAvatar()
				{
					if (file.selectedIndex == -1)
						return;

					document.getElementById("avatar").src = avatardir + file.options[file.selectedIndex].value;
					document.getElementById("avatar").alt = file.options[file.selectedIndex].text;
					document.getElementById("avatar").alt += file.options[file.selectedIndex].text == size ? "!" : "";
					document.getElementById("avatar").style.width = "";
					document.getElementById("avatar").style.height = "";
				}

				function previewExternalAvatar(src)
				{
					if (!document.getElementById("avatar"))
						return;

					var maxHeight = ', !empty($modSettings['avatar_max_height_external']) ? $modSettings['avatar_max_height_external'] : 0, ';
					var maxWidth = ', !empty($modSettings['avatar_max_width_external']) ? $modSettings['avatar_max_width_external'] : 0, ';
					var tempImage = new Image();

					tempImage.src = src;
					if (maxWidth != 0 && tempImage.width > maxWidth)
					{
						document.getElementById("avatar").style.height = parseInt((maxWidth * tempImage.height) / tempImage.width) + "px";
						document.getElementById("avatar").style.width = maxWidth + "px";
					}
					else if (maxHeight != 0 && tempImage.height > maxHeight)
					{
						document.getElementById("avatar").style.width = parseInt((maxHeight * tempImage.width) / tempImage.height) + "px";
						document.getElementById("avatar").style.height = maxHeight + "px";
					}
					document.getElementById("avatar").src = src;
				}
			// ]]></script>';
	echo '
		</form>';

	if ($context['show_spellchecking'])
		echo '
		<form action="', $scripturl, '?action=spellcheck" method="post" accept-charset="', $context['character_set'], '" name="spell_form" id="spell_form" target="spellWindow"><input type="hidden" name="spellstring" value="" /></form>';
}

// Template for showing theme settings.  Note: template_options() actually adds the theme specific options.
function template_theme()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;
	
	echo '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var localTime = new Date();
		var serverTime = new Date("', $context['current_forum_time'], '");
		
		function autoDetectTimeOffset()
		{
			// Get the difference between the two, set it up so that the sign will tell us who is ahead of who
			var diff = Math.round((localTime.getTime() - serverTime.getTime())/3600000);

			// Make sure we are limiting this to one day\'s difference
			diff %= 24;

			document.forms.creator.timeOffset.value = diff;
		}
	// ]]></script>';

	// The main containing header.
	echo '
		<form action="', $scripturl, '?action=profile2" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator">
			<div class="border">
			<h4 class="title">', $txt[79], '</h4>
			<div class="row2">
			<p>', $txt['theme_info'], '</p>
			</div>';

	// Are they allowed to change their theme?  // !!! Change to permission?
	if ($modSettings['theme_allow'] || $context['user']['is_admin'])
	{
		echo '
			<h5 class="subtitle">', $txt['theme1a'], ':</h5>
			<div class="row2">
			<p>', $context['member']['theme']['name'], ' <a href="', $scripturl, '?action=theme;sa=pick;u=', $context['member']['id'], ';sesc=', $context['session_id'], '">', $txt['theme1b'], '</a></p>
			</div>';
	}

	// Are multiple smiley sets enabled?
	if (!empty($modSettings['smiley_sets_enable']))
	{
		echo '
			<h5 class="subtitle">', $txt['smileys_current'], ':</h5>
			<div class="row2">
			<select name="smileySet" onchange="document.getElementById(\'smileypr\').src = this.selectedIndex == 0 ? \'', $settings['images_url'], '/blank.gif\' : \'', $modSettings['smileys_url'], '/\' + (this.selectedIndex != 1 ? this.options[this.selectedIndex].value : \'', !empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default'], '\') + \'/smiley.gif\';">';
		foreach ($context['smiley_sets'] as $set)
			echo '
				<option value="', $set['id'], '"', $set['selected'] ? ' selected="selected"' : '', '>', $set['name'], '</option>';
		echo '
				</select> <img id="smileypr" src="', $context['member']['smiley_set']['id'] != 'none' ? $modSettings['smileys_url'] . '/' . ($context['member']['smiley_set']['id'] != '' ? $context['member']['smiley_set']['id'] : (!empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default'])) . '/smiley.gif' : $settings['images_url'] . '/blank.gif', '" alt=":)" align="top" style="padding-left: 20px;" />
								</div>';
	}

	if ($modSettings['theme_allow'] || $context['user']['is_admin'] || !empty($modSettings['smiley_sets_enable']))
		echo '';

	// Allow the user to change the way the time is displayed.
	echo '
			<h5 class="subtitle">', $txt[486], ':</h5>
			<div class="row2">
			<p><a href="', $scripturl, '?action=helpadmin;help=time_format" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" border="0" align="', !$context['right_to_left'] ? 'left' : 'right', '" style="', !$context['right_to_left'] ? 'padding-right' : 'padding-left', ': 1ex;" /></a> ', $txt[479], '</p>
			<select name="easyformat" onchange="document.forms.creator.timeFormat.value = this.options[this.selectedIndex].value;" style="margin-bottom: 4px;">';
			
	// Help the user by showing a list of common time formats.
	foreach ($context['easy_timeformats'] as $time_format)
		echo '
										<option value="', $time_format['format'], '"', $time_format['format'] == $context['member']['time_format'] ? ' selected="selected"' : '', '>', $time_format['title'], '</option>';
	echo '
									</select><br />
									<input type="text" name="timeFormat" value="', $context['member']['time_format'], '" size="30" />
							</div>
							<h5', (isset($context['modify_error']['bad_offset']) ? ' style="color: #990000;"' : ''), ' class="subtitle" >', $txt[371], ':</h5>
							<div class="row2">
								<p>', $txt[519], '</p>
								<input type="text" name="timeOffset" size="5" maxlength="5" value="', $context['member']['time_offset'], '" /> <a href="javascript:void(0);" onclick="autoDetectTimeOffset(); return false;">', $txt['timeoffset_autodetect'], '</a>
								<p>', $txt[741], ': <em>', $context['current_forum_time'], '</em></p>
								</div>';

	echo '
			<div class="row2">
			<ul class="row-list">
			<li>
			<input type="hidden" name="default_options[show_board_desc]" value="0" /> 
			<label for="show_board_desc"><input type="checkbox" name="default_options[show_board_desc]" id="show_board_desc" value="1"', !empty($context['member']['options']['show_board_desc']) ? ' checked="checked"' : '', ' class="check" /> ', $txt[732], '</label>
			</li>
			
			<li><input type="hidden" name="default_options[show_children]" value="0" /> 
			<label for="show_children"><input type="checkbox" name="default_options[show_children]" id="show_children" value="1"', !empty($context['member']['options']['show_children']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['show_children'], '</label>
			</li>
			
			<li><input type="hidden" name="default_options[show_no_avatars]" value="0" /> 
			<label for="show_no_avatars"><input type="checkbox" name="default_options[show_no_avatars]" id="show_no_avatars" value="1"', !empty($context['member']['options']['show_no_avatars']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['show_no_avatars'], '</label>
			</li>
			
			<li><input type="hidden" name="default_options[show_no_signatures]" value="0" />
			<label for="show_no_signatures"><input type="checkbox" name="default_options[show_no_signatures]" id="show_no_signatures" value="1"', !empty($context['member']['options']['show_no_signatures']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['show_no_signatures'], '</label>
			</li>';

	if ($settings['allow_no_censored'])
		echo '
			<li><input type="hidden" name="default_options[show_no_censored]" value="0" /> 
			<label for="show_no_censored"><input type="checkbox" name="default_options[show_no_censored]" id="show_no_censored" value="1"' . (!empty($context['member']['options']['show_no_censored']) ? ' checked="checked"' : '') . ' class="check" /> ' . $txt['show_no_censored'] . '</label>
			</li>';

	echo '
			<li>
			<input type="hidden" name="default_options[return_to_post]" value="0" /> 
			<label for="return_to_post"><input type="checkbox" name="default_options[return_to_post]" id="return_to_post" value="1"', !empty($context['member']['options']['return_to_post']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['return_to_post'], '</label>
			</li>
			
			<li>
			<input type="hidden" name="default_options[no_new_reply_warning]" value="0" />
			<label for="no_new_reply_warning"><input type="checkbox" name="default_options[no_new_reply_warning]" id="no_new_reply_warning" value="1"', !empty($context['member']['options']['no_new_reply_warning']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['no_new_reply_warning'], '</label>
			</li>
			
			<li><input type="hidden" name="default_options[view_newest_first]" value="0" /> 
			<label for="view_newest_first"><input type="checkbox" name="default_options[view_newest_first]" id="view_newest_first" value="1"', !empty($context['member']['options']['view_newest_first']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['recent_posts_at_top'], '</label>
			</li>
			
			<li><input type="hidden" name="default_options[view_newest_pm_first]" value="0" /> 
			<label for="view_newest_pm_first"><input type="checkbox" name="default_options[view_newest_pm_first]" id="view_newest_pm_first" value="1"', !empty($context['member']['options']['view_newest_pm_first']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['recent_pms_at_top'], '</label></li>
			</ul>
			</div>
			
			
			<div class="subtitle"><label for="calendar_start_day">', $txt['calendar_start_day'], ':</label></div>
			<div class="row2">
												<select name="default_options[calendar_start_day]" id="calendar_start_day">
													<option value="0"', empty($context['member']['options']['calendar_start_day']) ? ' selected="selected"' : '', '>', $txt['days'][0], '</option>
													<option value="1"', !empty($context['member']['options']['calendar_start_day']) && $context['member']['options']['calendar_start_day'] == 1 ? ' selected="selected"' : '', '>', $txt['days'][1], '</option>
													<option value="6"', !empty($context['member']['options']['calendar_start_day']) && $context['member']['options']['calendar_start_day'] == 6 ? ' selected="selected"' : '', '>', $txt['days'][6], '</option>
												</select>
											</div>
			<div class="subtitle"><label for="display_quick_reply">', $txt['display_quick_reply'], '</label></div>
			<div class="row2">
												<select name="default_options[display_quick_reply]" id="display_quick_reply">
													<option value="0"', empty($context['member']['options']['display_quick_reply']) ? ' selected="selected"' : '', '>', $txt['display_quick_reply1'], '</option>
													<option value="1"', !empty($context['member']['options']['display_quick_reply']) && $context['member']['options']['display_quick_reply'] == 1 ? ' selected="selected"' : '', '>', $txt['display_quick_reply2'], '</option>
													<option value="2"', !empty($context['member']['options']['display_quick_reply']) && $context['member']['options']['display_quick_reply'] == 2 ? ' selected="selected"' : '', '>', $txt['display_quick_reply3'], '</option>
												</select>
											</div>
			
			<div class="subtitle"><label for="display_quick_mod">', $txt['display_quick_mod'], '</label></div>
			<div class="row2">
												<select name="default_options[display_quick_mod]" id="display_quick_mod">
													<option value="0"', empty($context['member']['options']['display_quick_mod']) ? ' selected="selected"' : '', '>', $txt['display_quick_mod_none'], '</option>
													<option value="1"', !empty($context['member']['options']['display_quick_mod']) && $context['member']['options']['display_quick_mod'] == 1 ? ' selected="selected"' : '', '>', $txt['display_quick_mod_check'], '</option>
													<option value="2"', !empty($context['member']['options']['display_quick_mod']) && $context['member']['options']['display_quick_mod'] != 1 ? ' selected="selected"' : '', '>', $txt['display_quick_mod_image'], '</option>
												</select>
											</div>';

	// Show the standard "Save Settings" profile button.
	template_profile_save();

	echo '
		<div class="block-foot"><!--no content--></div>
		</div>
		</form>';
}

function template_notification()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	// The main containing header.
	echo '<form action="', $scripturl, '?action=profile2" method="post" accept-charset="', $context['character_set'], '" style="margin: 0;">
			<div class="border">
			<h4 class="title">', $txt[79], '</h4>
			<div class="row2">
			<p>', $txt['notification_info'], '</p>
			</div>
			<div class="row2">
			<ul class="row-list">';

	// Allow notification on announcements to be disabled?
	if (!empty($modSettings['allow_disableAnnounce']))
		echo '
				<li><input type="hidden" name="notifyAnnouncements" value="0" /> 
				<label for="notifyAnnouncements"><input type="checkbox" id="notifyAnnouncements" name="notifyAnnouncements"', !empty($context['member']['notify_announcements']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['notifyXAnn4'], '</label></li>';

	// More notification options.
	echo '
			<li><input type="hidden" name="notifyOnce" value="0" /> 
			<label for="notifyOnce"><input type="checkbox" id="notifyOnce" name="notifyOnce"', !empty($context['member']['notify_once']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['notifyXOnce1'], '</label></li>

			<li><input type="hidden" name="default_options[auto_notify]" value="0" /> 
			<label for="auto_notify"><input type="checkbox" id="auto_notify" name="default_options[auto_notify]" value="1"', !empty($context['member']['options']['auto_notify']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['auto_notify'], '</label></li>';

	if (empty($modSettings['disallow_sendBody']))
		echo '
			<li><input type="hidden" name="notifySendBody" value="0" /> 
			<label for="notifySendBody"><input type="checkbox" id="notifySendBody" name="notifySendBody"', !empty($context['member']['notify_send_body']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['notify_send_body'], '</label></li>';

	echo '
		</ul>
		</div>
		<div class="row2">
		<label for="notifyTypes">', $txt['notify_send_types'], ':</label>
							<select name="notifyTypes" id="notifyTypes">
								<option value="1"', $context['member']['notify_types'] == 1 ? ' selected="selected"' : '', '>', $txt['notify_send_type_everything'], '</option>
								<option value="2"', $context['member']['notify_types'] == 2 ? ' selected="selected"' : '', '>', $txt['notify_send_type_everything_own'], '</option>
								<option value="3"', $context['member']['notify_types'] == 3 ? ' selected="selected"' : '', '>', $txt['notify_send_type_only_replies'], '</option>
								<option value="4"', $context['member']['notify_types'] == 4 ? ' selected="selected"' : '', '>', $txt['notify_send_type_nothing'], '</option>
							</select>
							</div>
							<div class="subtitle" style="text-align:center;">
								<input type="submit" class="button" value="', $txt['notifyX1'], '" />
								<input type="hidden" name="sc" value="', $context['session_id'], '" />
								<input type="hidden" name="userID" value="', $context['member']['id'], '" />
								<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
							</div>
							</div>
						</form>
				
			<br />
			
				<form action="', $scripturl, '?action=profile2" method="post" accept-charset="', $context['character_set'], '" style="margin: 0;">
					<div class="border">
						<h5 class="title">';
					if (!empty($context['topic_notifications']))
	{
		echo '<span class="float-r"><input type="checkbox" class="check" onclick="invertAll(this, this.form);" /></span>';
	}
				echo '', $txt['notifications_topics'], '
						</h5>
						<div class="row2">
					';
	if (!empty($context['topic_notifications']))
	{
		echo '
				<dl>';
				
		foreach ($context['topic_notifications'] as $topic)
		{
			echo '
						<dt>
						<span class="float-r"><input type="checkbox" name="notify_topics[]" value="', $topic['id'], '" class="check" /> </span>
						<strong>', $topic['link'] ,'</strong>';

			if ($topic['new'])
				echo ' <a href="', $topic['new_href'], '"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new.gif" alt="', $txt[302], '" border="0" /></a>';

			echo '
				</dt>
				<dd>
				<p><em>' . $txt['smf88'] . ' ' . $topic['board']['link'] . '. ' . $txt[109] . ' ' . $topic['poster']['link'] . '.</em></p>
				</dd>';
		}

		echo '
				</dl>
				</div>
				<div class="subtitle" style="text-align:right;">
				<span class="float-l" style="margin-top:4px;"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</span> 
				<input type="submit" class="button" name="edit_notify_topics" value="', $txt['notifications_update'], '" />
				</div>
				</div>';
	}
	else
		echo '
							<p>', $txt['notifications_topics_none'], '</p>
								<br />
								<p>', $txt['notifications_topics_howto'], '</p>
							</div>
						<div class="block-foot"><!--no content--></div>
						</div>';
	echo '
					
					<input type="hidden" name="sc" value="', $context['session_id'], '" />
					<input type="hidden" name="userID" value="', $context['member']['id'], '" />
					<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
				</form>
			<br />
			
				<form action="', $scripturl, '?action=profile2" method="post" accept-charset="', $context['character_set'], '" style="margin: 0;">
					<div class="border">
					<h5 class="title">';
				if (!empty($context['board_notifications']))
	{
		echo '<span class="float-r"><input type="checkbox" class="check" onclick="invertAll(this, this.form);" /></span> ';
	}
				echo'', $txt['notifications_boards'], '
					</h5>
					<div class="row2">';
					
	if (!empty($context['board_notifications']))
	{
		echo '
				<ul class="row-list">';
		foreach ($context['board_notifications'] as $board)
		{
			echo '
						<li><input type="checkbox" name="notify_boards[]" value="', $board['id'], '" /> ', $board['link'];

		if ($board['new'])
			echo ' <a href="', $board['href'], '"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new.gif" alt="', $txt[302], '" border="0" /></a>';

		echo '</li>';
		}

		echo '
		</ul>
		</div>
		<div class="subtitle" style="text-align:right">
		<input type="submit" class="button" name="edit_notify_boards" value="', $txt['notifications_update'], '" />
		</div>
		</div>';
	}
	else
		echo '
				<p>', $txt['notifications_boards_none'], '</p>
				<br />
				</p>', $txt['notifications_boards_howto'], '</p>
				</div>
				<div class="block-foot"><!--no content--></div>
				</div>';
	echo '
					<input type="hidden" name="sc" value="', $context['session_id'], '" />
					<input type="hidden" name="userID" value="', $context['member']['id'], '" />
					<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
				</form>';
}

// Template for options related to personal messages.
function template_pmprefs()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// The main containing header.
	echo '
			<form action="', $scripturl, '?action=profile2" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator">
			<div class="border">
			<h4 class="title">', $txt[79], '</h4>
			<div class="row2">
			<p>', $txt['pmprefs_info'], '</p>
			</div>';

	// A text box for the user to input usernames of everyone they want to ignore personal messages from.
	echo '
			<h5 class="subtitle">', $txt[325], ':</h5>
			<div class="row2">
			<p>', $txt[326], ' <a href="', $scripturl, '?action=findmember;input=pm_ignore_list;delim=\\\\n;sesc=', $context['session_id'], '" onclick="return reqWin(this.href, 350, 400);"><img src="', $settings['images_url'], '/icons/assist.gif" border="0" alt="', $txt['find_members'], '" align="middle" /> ', $txt['find_members'], '</a></p>
			<textarea name="pm_ignore_list" id="pm_ignore_list" rows="10" cols="50">', $context['ignore_list'], '</textarea>
			</div>';

	// Extra options available to the user for personal messages.
	echo '
		<div class="row">
		<input type="hidden" name="default_options[copy_to_outbox]" value="0" />
		<label for="copy_to_outbox"><input type="checkbox" name="default_options[copy_to_outbox]" id="copy_to_outbox" value="1"', !empty($context['member']['options']['copy_to_outbox']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['copy_to_outbox'], '</label>
		<br />
		<input type="hidden" name="default_options[popup_messages]" value="0" />
		<label for="popup_messages"><input type="checkbox" name="default_options[popup_messages]" id="popup_messages" value="1"', !empty($context['member']['options']['popup_messages']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['popup_messages'], '</label>
		</div>
		<div class="row2">
		<label for="pm_email_notify">', $txt[327], '</label>
									<select name="pm_email_notify" id="pm_email_notify">
										<option value="0"', empty($context['send_email']) ? ' selected="selected"' : '', '>', $txt['email_notify_never'], '</option>
										<option value="1"', !empty($context['send_email']) && ($context['send_email'] == 1 || (empty($modSettings['enable_buddylist']) && $context['send_email'] > 1)) ? ' selected="selected"' : '', '>', $txt['email_notify_always'], '</option>';

	if (!empty($modSettings['enable_buddylist']))
		echo '
										<option value="2"', !empty($context['send_email']) && $context['send_email'] > 1 ? ' selected="selected"' : '', '>', $txt['email_notify_buddies'], '</option>';

	echo '
									</select></div>';

	// Show the standard "Save Settings" profile button.
	template_profile_save();

	echo '
						<div class="block-foot"><!--no content--></div>
						</div>
		</form>';
}

// Template to show for deleting a users account - now with added delete post capability!
function template_deleteAccount()
{
	global $context, $settings, $options, $scripturl, $txt, $scripturl;

	// The main containing header.
	echo '
		<form action="', $scripturl, '?action=profile2" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator">
			<div class="border">
			<h4 class="title">', $txt['deleteAccount'], '</h4>';
	// If deleting another account give them a lovely info box.
	if (!$context['user']['is_owner'])
	echo '
			<div class="row2"><p>', $txt['deleteAccount_desc'], '</p></div>';

	// If they are deleting their account AND the admin needs to approve it - give them another piece of info ;)
	if ($context['needs_approval'])
		echo '
			<div class="row2">
			<div style="color: #990000; border: 2px dashed #990000; padding: 4px;">', $txt['deleteAccount_approval'], '</div>
			</div>';

	// If the user is deleting their own account warn them first - and require a password!
	if ($context['user']['is_owner'])
	{
		echo '
			<div class="row2">
			<p style="color: #990000;">', $txt['own_profile_confirm'], '</p>
			<br />
			<strong', (isset($context['modify_error']['bad_password']) || isset($context['modify_error']['no_password']) ? ' style="color: #990000;"' : ''), '>', $txt['smf241'], ': </strong> 
								<input type="password" name="oldpasswrd" size="20" />&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="submit" class="button" value="', $txt[163], '" />
								<input type="hidden" name="sc" value="', $context['session_id'], '" />
								<input type="hidden" name="userID" value="', $context['member']['id'], '" />
								<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
							</div>';
	}
	// Otherwise an admin doesn't need to enter a password - but they still get a warning - plus the option to delete lovely posts!
	else
	{
		echo '						
		
		<div class="row2" style="text-align:center;">
		<p style="color: #990000;">', $txt['deleteAccount_warning'], '</p>
							
								', $txt['deleteAccount_posts'], ': <select name="remove_type">
									<option value="none">', $txt['deleteAccount_none'], '</option>
									<option value="posts">', $txt['deleteAccount_all_posts'], '</option>
									<option value="topics">', $txt['deleteAccount_topics'], '</option>
								</select>
							<br />
								<label for="deleteAccount"><input type="checkbox" name="deleteAccount" id="deleteAccount" value="1" class="check" onclick="if (this.checked) return confirm(\'', $txt['deleteAccount_confirm'], '\');" /> ', $txt['deleteAccount_member'], '.</label>
							</div>
							<div class="subtitle" style="text-align:center;">
								<input type="submit" class="button" value="', $txt['smf138'], '" />
								<input type="hidden" name="sc" value="', $context['session_id'], '" />
								<input type="hidden" name="userID" value="', $context['member']['id'], '" />
								<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
							</div>';
	}
	echo '
						</div>
		</form>';
}

// Template for the password box/save button stuck at the bottom of every profile page.
function template_profile_save()
{
	global $context, $settings, $options, $txt;

	echo '
							';

	// Only show the password box if it's actually needed.
	if ($context['user']['is_owner'] && $context['require_password'])
		echo '
									<h5', isset($context['modify_error']['bad_password']) || isset($context['modify_error']['no_password']) ? ' style="color: #990000;"' : '', ' class="subtitle">', $txt['smf241'], ': </h5>
									<div class="row2">
									<p>', $txt['smf244'], '</p>
									<input type="password" name="oldpasswrd" size="20" style="margin-right: 4ex;" />
									</div>';
	else
		echo '';

	echo '<div class="subtitle" style="text-align:center;">
									<input type="submit" class="button" value="', $txt[88], '" />
									<input type="hidden" name="sc" value="', $context['session_id'], '" />
									<input type="hidden" name="userID" value="', $context['member']['id'], '" />
									<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
								</div>';
}

// Small template for showing an error message upon a save problem in the profile.
function template_error_message()
{
	global $context, $txt;

	echo '
			<h3 class="title">', $txt['profile_errors_occurred'], ' :</h3>
			<div class="row2" style="color:#990000;">
			<ul class="bullit-list">';

		// Cycle through each error and display an error message.
		foreach ($context['post_errors'] as $error)
			//if (isset($txt['profile_error_' . $error]))
				echo '
				<li>', $txt['profile_error_' . $error], '.</li>';

		echo '
			</ul>
		</div>';
}

?>