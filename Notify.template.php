<?php
// Version: 1.1; Notify

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
			<div class="border">
			<h2 class="title">', $txt[125], '</h2>
			<div class="row1-center">
						<p>', $context['notification_set'] ? $txt[212] : $txt[126], '</p>
						<br />
						<br />
						<p><strong><a href="', $scripturl, '?action=notify;sa=', $context['notification_set'] ? 'off' : 'on', ';topic=', $context['current_topic'], '.', $context['start'], ';sesc=', $context['session_id'], '">', $txt[163], '</a> - <a href="', $context['topic_href'], '">', $txt[164], '</a></strong></p>
					</div>
					<div class="block-foot"><!--no content--></div>
					</div>';
}

function template_notify_board()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
			<div class="border">
			<h2 class="title">', $txt[125], '</h2>
			<divclass="row1">
			<p>', $context['notification_set'] ? $txt['notifyboard_turnoff'] : $txt['notifyboard_turnon'], '</p>
			<br /><br />
			<p><strong><a href="', $scripturl, '?action=notifyboard;sa=', $context['notification_set'] ? 'off' : 'on', ';board=', $context['current_board'], '.', $context['start'], ';sesc=', $context['session_id'], '">', $txt[163], '</a> - <a href="', $context['board_href'], '">', $txt[164], '</a></strong></p>
			</div>
			<div class="block-foot"><!--no content--></div>
			</div>';
}

?>