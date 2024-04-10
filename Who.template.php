<?php
// Version: 1.1; Who

// The only template in the file.
function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Display the table header and linktree.
	echo '
', theme_linktree(), '
	<div class="border">
	<h3 class="title">', $txt[158], '</h3>
	<div class="row1">
	<dl>';

	// This is used to alternate the color of the background.
	$alternate = true;

	// For every member display their name, time and action (and more for admin).
	foreach ($context['members'] as $member)
	{

		echo '<dt>';

		// Guests don't have information like icq, msn, y!, and aim... and they can't be messaged.
		if (!$member['is_guest'])
		{
			echo '
				<span class="float-r">
					', $member['icq']['link'], ' ', $member['msn']['link'], ' ', $member['yim']['link'], ' ', $member['aim']['link'], '
				</span>';
				echo '', $context['can_send_pm'] ? '<a href="' . $member['online']['href'] . '" title="' . $member['online']['label'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $member['online']['image_href'] . '" alt="' . $member['online']['text'] . '" align="middle" />' : $member['online']['text'], $context['can_send_pm'] ? '</a>' : '', '';
		}


		echo'<span', $member['is_hidden'] ? ' style="font-style: italic;"' : '', '>', $member['is_guest'] ? $member['name'] : '<a href="' . $member['href'] . '" title="' . $txt[92] . ' ' . $member['name'] . '"' . (empty($member['color']) ? '' : ' style="color: ' . $member['color'] . '"') . '>' . $member['name'] . '</a>', '</span>';

		if (!empty($member['ip']))
			echo '
				(<a href="' . $scripturl . '?action=trackip;searchip=' . $member['ip'] . '" target="_blank">' . $member['ip'] . '</a>)';

		echo '
			</dt>
			<dd>', $member['action'], ' ', $member['time'], '</dd>';

		// Switch alternate to whatever it wasn't this time. (true -> false -> true -> false, etc.)
		$alternate = !$alternate;
	}

	echo '
		</dl>
		</div>
			<div class="subtitle">
			<span class="float-r"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</span>
			Sort by - <a href="' . $scripturl . '?action=who;start=', $context['start'], ';sort=user', $context['sort_direction'] == 'down' && $context['sort_by'] == 'user' ? ';asc' : '', '">', $txt['who_user'], ' ', $context['sort_by'] == 'user' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a> &middot; <a href="' . $scripturl . '?action=who;start=', $context['start'], ';sort=time', $context['sort_direction'] == 'down' && $context['sort_by'] == 'time' ? ';asc' : '', '">', $txt['who_time'], ' ', $context['sort_by'] == 'time' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" />' : '', '</a>
			</div>
		<div class="block-foot"><!--no content--></div>
	</div>';
}

?>