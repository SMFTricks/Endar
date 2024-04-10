<?php
// Version: 1.1; SendTopic

//------------------------------------------------------------------------------
/*	This template contains two humble sub templates - main.  Its job is pretty
	simple: it collects the information we need to actually send the topic.

	The main sub template gets shown from:
		'?action=sendtopic;topic=##.##'
	And should submit to:
		'?action=sendtopic;topic=' . $context['current_topic'] . '.' . $context['start']
	It should send the following fields:
		y_name: sender's name.
		y_email: sender's email.
		comment: any additional comment.
		r_name: receiver's name.
		r_email: receiver's email address.
		send: this just needs to be set, as by the submit button.
		sc: the session id, or $context['session_id'].

	The report sub template gets shown from:
		'?action=reporttm;topic=##.##;msg=##'
	It should submit to:
		'?action=reporttm;topic=' . $context['current_topic'] . '.' . $context['start']
	It only needs to send the following fields:
		comment: an additional comment to give the moderator.
		sc: the session id, or $context['session_id'].
*/

// This is where we get information about who they want to send the topic to, etc.
function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<form action="', $scripturl, '?action=sendtopic;topic=', $context['current_topic'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '">
			<div class="border">
			<h3 class="title">', $context['page_title'], '</h3>
			<div class="row1">';

	// Just show all the input boxes, in a line...
	echo '
				<span class="formleft"><strong>', $txt['sendtopic_sender_name'], ':</strong></span>
				<span class="formright"><input type="text" name="y_name" size="24" maxlength="40" value="', $context['user']['name'], '" /></span>
				<span class="formleft"><strong>', $txt['sendtopic_sender_email'], ':</strong></span>
				<span class="formright"><input type="text" name="y_email" size="24" maxlength="50" value="', $context['user']['email'], '" /></span>
				<span class="formleft"><strong>', $txt['sendtopic_comment'], ':</strong></span>
				<span class="formright"><input type="text" name="comment" size="24" maxlength="100" /></span>
				</div>
				<div class="row1">
				<span class="formleft"><strong>', $txt['sendtopic_receiver_name'], ':</strong></span>
				<span class="formright"><input type="text" name="r_name" size="24" maxlength="40" /></span>
				<span class="formleft"><strong>', $txt['sendtopic_receiver_email'], ':</strong></span>
				<span class="formright"><input type="text" name="r_email" size="24" maxlength="50" /></span>
				</div>
				<div class="subtitle" style="text-align:center;">
				<input type="submit" class="button" name="send" value="', $txt['sendtopic_send'], '" />
				</div>
			</div>
			<input type="hidden" name="sc" value="', $context['session_id'], '" />
		</form>';
}

function template_report()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<form action="', $scripturl, '?action=reporttm;topic=', $context['current_topic'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '">
		<input type="hidden" name="msg" value="' . $context['message_id'] . '" />
		<div class="border">
		<h3 class="title">', $txt['rtm1'], '</h3>
		<div class="row1-center">
		<p>', $txt['smf315'], '</p>
		<br />
		', $txt['rtm2'], ': <input type="text" name="comment" size="50" /> 
		<input type="submit" class="button" name="submit" value="', $txt['rtm10'], '" style="margin-left: 1ex;" />
		</div>
		<div class="block-foot"><!--no content--></div>
		</div>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

?>