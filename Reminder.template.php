<?php
// Version: 1.1; Reminder

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<br />
	<form action="', $scripturl, '?action=reminder;sa=mail" method="post" accept-charset="', $context['character_set'], '">
		<div class="border">
		<h2 class="title">', $txt[194], '</h2>
		<div class="row1-center">
		<p>', $txt['pswd4'], '</p>
		</div>
		<div class="row1-center">
		', $txt['smf100'], ': <input type="text" name="user" size="30" />
		<br /><br />
		<label for="secret"><input type="checkbox" name="sa" value="secret" id="secret" class="check" /> ', $txt['pswd3'], '.</label>
		</div>
		<div class="subtitle" style="text-align:center;">
		<input type="submit" class="button" value="', $txt['sendtopic_send'], '" />
		</div>
		</div>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

function template_sent()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<br />
		<div class="border">
		<h2 class="title">' . $context['page_title'] . '</h2>
		<div class="row1-center">
		<p>' . $context['description'] . '</p>
		</div>
		<div class="block-foot"><!--no content--></div>
		</dib>';
}

function template_set_password()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<br />
	<form action="', $scripturl, '?action=reminder;sa=setpassword2" method="post" accept-charset="', $context['character_set'], '">
	<div class="border">
	<h2 class="title">', $context['page_title'], '</h2>
	<div class="subtitle"><strong>', $txt[81], ': </strong></div>
	<div class="row1">
	<p>', $txt[596], '</p>
	<input type="password" name="passwrd1" size="22" />
	</div>
	<div class="subtitle"><strong>', $txt[82], ': </strong></div>
	<div class="row1">
	<input type="password" name="passwrd2" size="22" />
	</div>
	<div class="subtitle" style="text-align:center;">
	<input type="submit" class="button" value="', $txt[10], '" />
	</div>
	</div>
		<input type="hidden" name="code" value="', $context['code'], '" />
		<input type="hidden" name="u" value="', $context['memID'], '" />
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

function template_ask()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<br />
	<form action="', $scripturl, '?action=reminder;sa=secret2" method="post" accept-charset="', $context['character_set'], '">
		<div class="border">
		<h2 class="title">', $txt[194], '</h2>
		<div class="row1"><p>', $txt['pswd6'], '</p></div>
		<div class="subtitle"><strong>', $txt['pswd1'], ':</strong></div>
		<div class="row1">', $context['secret_question'], '</div>
		<div class="subtitle"><strong>', $txt['pswd2'], ':</strong></div>
		<div class="row1"><input type="text" name="secretAnswer" size="22"/></div>
		<div class="subtitle"><strong>', $txt[81], ':</strong></div>
		<div class="row1"><p>', $txt[596], '</p>
		<input type="password" name="passwrd1" size="22" />
		</div>
		<div class="subtitle"><strong>', $txt[82], ':</strong></div>
		<div class="row1"><input type="password" name="passwrd2" size="22" /></div>
		<div class="subtitle" style="text-align:center;">
		<input type="submit" class="button" value="', $txt[10], '" />
		</div>
		</div>

		<input type="hidden" name="user" value="', $context['remind_user'], '" />
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}

?>