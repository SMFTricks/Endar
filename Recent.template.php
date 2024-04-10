<?php
// Version: 1.1.9; Recent

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
			<div class="border">
			<div class="title"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</div>
			', theme_linktree(), '
			</div>
			<br />';

	foreach ($context['posts'] as $post)
	{
		echo '
			<div class="border">
			<h3 class="title">
			<span class="float-l">&nbsp;', $post['counter'], '&nbsp;&nbsp;', $post['category']['link'], ' / ', $post['board']['link'], ' / ', $post['link'], '</span>&nbsp;', $txt[30], ': ', $post['time'], '
			</h3>
			<div class="subtitle">
			', $txt[109], ' ' . $post['first_poster']['link'] . ' - ' . $txt[22] . ' ' . $txt[525] . ' ' . $post['poster']['link'] . '
			</div>
			<div class="row1">
			<div class="post">' . $post['message'] . '</div>
			</div>
			<div class="post-foot"';

		if ($post['can_delete'])
			echo '
								<a href="', $scripturl, '?action=deletemsg;msg=', $post['id'], ';topic=', $post['topic'], ';recent;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt[154], '?\');">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/delete.gif" alt="' . $txt[121] . '" border="0" />' : $txt[31]), '</a>';
		if ($post['can_delete'] && ($post['can_mark_notify'] || $post['can_reply']))
			echo '
								', $context['menu_separator'];
		if ($post['can_reply'])
			echo '
						<a href="', $scripturl, '?action=post;topic=', $post['topic'], '.', $post['start'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/reply_sm.gif" alt="' . $txt[146] . '" border="0" />' : $txt[146]), '</a>', $context['menu_separator'], '
						<a href="', $scripturl, '?action=post;topic=', $post['topic'], '.', $post['start'], ';quote=', $post['id'], ';sesc=', $context['session_id'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/quote.gif" alt="' . $txt[145] . '" border="0" />' : $txt[145]), '</a>';
		if ($post['can_reply'] && $post['can_mark_notify'])
			echo '
						', $context['menu_separator'];
		if ($post['can_mark_notify'])
			echo '
						<a href="' . $scripturl . '?action=notify;topic=' . $post['topic'] . '.' . $post['start'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/notify_sm.gif" alt="' . $txt[131] . '" border="0" />' : $txt[131]) . '</a>';

		echo '</div>
		<div class="block-foot"><!--no content--></div>
			</div>
			<br />';
	}
echo '
		<div class="border">
		<div class="title"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</div>';
	if ($settings['linktree_inline'])
	echo '', theme_linktree(), '';
	echo '</div>
	<br />';
	
}

function template_unread()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '', theme_linktree(), '

	<div class="clearfix" id="topic-buttons-top">
	', $settings['show_mark_read'] ? '
	<div class="float-r">
	<a href="' . $scripturl . '?action=markasread;sa=' . (empty($context['current_board']) ? 'all' : 'board;board=' . $context['current_board'] . '.0') . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[452] . '" border="0" />' : $txt[452]) . '</a></div>' : '', '
	<strong>' . $txt[139] . ':</strong> ' . $context['page_index'] . '</div>
	<br />

<div class="border">
<div class="title"><p>';

if (!empty($context['topics']))
		echo '
				Sort by: &nbsp; <a href="', $scripturl, '?action=unread', $context['showing_all_topics'] ? ';all' : '', $context['querystring_board_limits'], ';sort=subject', $context['sort_by'] == 'subject' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[70], $context['sort_by'] == 'subject' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a> &middot; <a href="', $scripturl, '?action=unread', $context['showing_all_topics'] ? ';all' : '', $context['querystring_board_limits'], ';sort=starter', $context['sort_by'] == 'starter' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[109], $context['sort_by'] == 'starter' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a> &middot; <a href="', $scripturl, '?action=unread', $context['showing_all_topics'] ? ';all' : '', $context['querystring_board_limits'], ';sort=replies', $context['sort_by'] == 'replies' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[110], $context['sort_by'] == 'replies' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a> &middot; <a href="', $scripturl, '?action=unread', $context['showing_all_topics'] ? ';all' : '', $context['querystring_board_limits'], ';sort=views', $context['sort_by'] == 'views' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[301], $context['sort_by'] == 'views' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a> &middot; <a href="', $scripturl, '?action=unread', $context['showing_all_topics'] ? ';all' : '', $context['querystring_board_limits'], ';sort=last_post', $context['sort_by'] == 'last_post' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[111], $context['sort_by'] == 'last_post' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a>';
	else
		echo '
			', $context['showing_all_topics'] ? $txt[151] : $txt['unread_topics_visit_none'], '';
	

echo '</p></div>';
	if (!empty($context['topics']))
		echo '<dl>';
	else
		echo '
				<div class="row1"><p>', $context['showing_all_topics'] ? $txt[151] : $txt['unread_topics_visit_none'], '</p></div>';


	foreach ($context['topics'] as $topic)
	{
		echo '
			<dt>' . $topic['first_post']['link'] . ' </dt>
			<dd>
			<div class="left">
			<img src="' . $settings['images_url'] . '/topic/' . $topic['class'] . '.gif" class="topic-icon" alt="" />
			', $txt[109],': ', $topic['first_post']['member']['link'], ' ' . $txt['smf88'] . ' ' . $topic['board']['link'] . '<br />
			<a href="' . $topic['new_href'] . '"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/new.gif" alt="' . $txt[302] . '" border="0" /></a> ', $txt[139], ': ', $topic['pages'], '<br />
			', $txt[110],': ', $topic['replies'], ' ', $txt[301],': ', $topic['views'], '
			</div>
			
			<div class="right">
			', $txt[111],': <a href="', $topic['last_post']['href'], '"><img src="', $settings['images_url'], '/icons/last_post.gif" alt="', $txt[111], '" title="', $txt[111], '" border="0" /></a>
			<br />', $topic['last_post']['time'], '
			<br />', $txt[525], ' ', $topic['last_post']['member']['link'], '
			</div>
			</dd>';
	}
	if (!empty($context['topics']))
		echo '</dl>';
	if (!empty($context['topics']) && !$context['showing_all_topics'])
		echo '
			<div class="subtitle">
			<a href="', $scripturl, '?action=unread;all', $context['querystring_board_limits'], '">', $txt['unread_topics_all'], '</a>
			</div>';

	echo '
		<div class="block-foot"><!--no content--></div>
		</div>
		<br />

<div class="clearfix" id="topic-buttons-bot">
	', $settings['show_mark_read'] ? '
	<div class="float-r"><a href="' . $scripturl . '?action=markasread;sa=' . (empty($context['current_board']) ? 'all' : 'board;board=' . $context['current_board'] . '.0') . ';sesc=' . $context['session_id'] . '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[452] . '" border="0" />' : $txt[452]) . '</a></div>' : '', '
	<strong>' . $txt[139] . ':</strong> ' . $context['page_index'] . '</div>
	<br />
	
<div class="border">
<div class="title">&nbsp;</div>
<div class="row1">
	<ul id="topiclegend" class="smalltext">', !empty($modSettings['enableParticipation']) ? '
	<li><img src="' . $settings['images_url'] . '/topic/my_normal_post.gif" alt="" align="middle" /> ' . $txt['participation_caption'] . '</li>' : '', '
	<li><img src="' . $settings['images_url'] . '/topic/normal_post.gif" alt="" align="middle" /> ' . $txt[457] . '</li>
	<li><img src="' . $settings['images_url'] . '/topic/hot_post.gif" alt="" align="middle" /> ' . $txt[454] . '</li>
	<li><img src="' . $settings['images_url'] . '/topic/veryhot_post.gif" alt="" align="middle" /> ' . $txt[455] . '</li>
	<li><img src="' . $settings['images_url'] . '/topic/normal_post_locked.gif" alt="" align="middle" /> ' . $txt[456] . '</li>' . ($modSettings['enableStickyTopics'] == '1' ? '
	<li><img src="' . $settings['images_url'] . '/topic/normal_post_sticky.gif" alt="" align="middle" /> ' . $txt['smf96'] . '</li>' : '') . ($modSettings['pollMode'] == '1' ? '
	<li><img src="' . $settings['images_url'] . '/topic/normal_poll.gif" alt="" align="middle" /> ' . $txt['smf43'] : '') . '</li>
	</ul>
	<div class="clear"><!--no content--></div>
	</div>
	<div class="block-foot"><!--no content--></div>
	</div>
	';
}

function template_replies()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '', theme_linktree(), '

	<div class="clearfix" id="topic-buttons-top">';
	if (isset($context['topics_to_mark']) && !empty($settings['show_mark_read']))
		echo '
			<div class="float-r"><a href="', $scripturl, '?action=markasread;sa=unreadreplies;topics=', $context['topics_to_mark'], ';sesc=', $context['session_id'], '">', $settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[452] . '" border="0" />' : $txt[452], '</a></div>';
	
	echo'<strong>' . $txt[139] . ':</strong> ' . $context['page_index'] . '</div>
	<br />

<div class="border">
<div class="title"><p>';

if (!empty($context['topics']))
		echo 'Sort by: &nbsp; <a href="', $scripturl, '?action=unreadreplies', $context['querystring_board_limits'], ';sort=subject', $context['sort_by'] == 'subject' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[70], $context['sort_by'] == 'subject' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a> &middot; <a href="', $scripturl, '?action=unreadreplies', $context['querystring_board_limits'], ';sort=starter', $context['sort_by'] == 'starter' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[109], $context['sort_by'] == 'starter' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a> &middot; <a href="', $scripturl, '?action=unreadreplies', $context['querystring_board_limits'], ';sort=replies', $context['sort_by'] == 'replies' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[110], $context['sort_by'] == 'replies' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a> &middot; <a href="', $scripturl, '?action=unreadreplies', $context['querystring_board_limits'], ';sort=views', $context['sort_by'] == 'views' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[301], $context['sort_by'] == 'views' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a> &middot; <a href="', $scripturl, '?action=unreadreplies', $context['querystring_board_limits'], ';sort=last_post', $context['sort_by'] == 'last_post' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt[111], $context['sort_by'] == 'last_post' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a>';
	else
		echo '
				' . $txt[151] . '';

echo'</p></div>';
	if (!empty($context['topics']))
		echo '<dl>';
	else
		echo '
				<div class="row1"><p>', $context['showing_all_topics'] ? $txt[151] : $txt['unread_topics_visit_none'], '</p></div>';


	foreach ($context['topics'] as $topic)
	{
		echo '
			<dt>' . $topic['first_post']['link'] . ' </dt>
			<dd>
			<div class="left">
			<img src="' . $settings['images_url'] . '/topic/' . $topic['class'] . '.gif" class="topic-icon" alt="" />
			', $txt[109],': ', $topic['first_post']['member']['link'], ' ' . $txt['smf88'] . ' ' . $topic['board']['link'] . '<br />
			<a href="', $topic['new_href'], '"><img src="', $settings['images_url'], '/', $context['user']['language'], '/new.gif" alt="', $txt[302], '" /></a> ', $txt[139], ': ', $topic['pages'], '<br />
			', $txt[110],': ', $topic['replies'], ' ', $txt[301],': ', $topic['views'], '
			</div>
			
			<div class="right">
			', $txt[111],': <a href="', $topic['last_post']['href'], '"><img src="', $settings['images_url'], '/icons/last_post.gif" alt="', $txt[111], '" title="', $txt[111], '" border="0" /></a>
			<br />', $topic['last_post']['time'], '
			<br />', $txt[525], ' ', $topic['last_post']['member']['link'], '
			</div>
			</dd>';
	}
	if (!empty($context['topics']))
		echo '</dl>';
	if (!empty($context['topics']) && !$context['showing_all_topics'])
		echo '
			<div class="subtitle">
			<a href="', $scripturl, '?action=unread;all', !empty($context['current_board']) ? ';board=' . $context['current_board'] . '.0' : '', '">', $txt['unread_topics_all'], '</a>
			</div>';

	echo '
		<div class="block-foot"><!--no content--></div>
		</div>
		<br />

<div class="clearfix" id="topic-buttons-bot"> ';
	if (isset($context['topics_to_mark']) && !empty($settings['show_mark_read']))
		echo '
			<div class="float-r"><a href="' . $scripturl . '?action=markasread;sa=unreadreplies;topics=' . $context['topics_to_mark'] . ';sesc=', $context['session_id'], '">' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/markread.gif" alt="' . $txt[452] . '" border="0" />' : $txt[452]) . '</a></div>';
	
	echo'<strong>' . $txt[139] . ':</strong> ' . $context['page_index'] . '</div>
	<br />
	
<div class="border">
<div class="title">&nbsp;</div>
<div class="row1">
	<ul id="topiclegend" class="smalltext">', !empty($modSettings['enableParticipation']) ? '
	<li><img src="' . $settings['images_url'] . '/topic/my_normal_post.gif" alt="" align="middle" /> ' . $txt['participation_caption'] . '</li>' : '', '
	<li><img src="' . $settings['images_url'] . '/topic/normal_post.gif" alt="" align="middle" /> ' . $txt[457] . '</li>
	<li><img src="' . $settings['images_url'] . '/topic/hot_post.gif" alt="" align="middle" /> ' . $txt[454] . '</li>
	<li><img src="' . $settings['images_url'] . '/topic/veryhot_post.gif" alt="" align="middle" /> ' . $txt[455] . '</li>
	<li><img src="' . $settings['images_url'] . '/topic/normal_post_locked.gif" alt="" align="middle" /> ' . $txt[456] . '</li>' . ($modSettings['enableStickyTopics'] == '1' ? '
	<li><img src="' . $settings['images_url'] . '/topic/normal_post_sticky.gif" alt="" align="middle" /> ' . $txt['smf96'] . '</li>' : '') . ($modSettings['pollMode'] == '1' ? '
	<li><img src="' . $settings['images_url'] . '/topic/normal_poll.gif" alt="" align="middle" /> ' . $txt['smf43'] : '') . '</li>
	</ul>
	<div class="clear"><!--no content--></div>
	</div>
	<div class="block-foot"><!--no content--></div>
	</div>';
}

?>