<?php
// Version: 1.1; Login

// This is just the basic "login" form.
function template_login()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/sha1.js"></script>
		
		<form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" name="frmLogin" id="frmLogin" style="margin-top: 4ex;"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
			<div class="border">
		 <h2 class="title"><img src="', $settings['images_url'], '/icons/login_sm.gif" alt="" align="top" /> ', $txt[34], '</h2>
		 <div class="row1">';

	// Did they make a mistake last time?
	if (isset($context['login_error']))
		echo '
						<p style="color: #990000;">', $context['login_error'], '</p>
						</div>
						<div class="row1">';

	// Or perhaps there's some special description for this time?
	if (isset($context['description']))
		echo '
						<p>', $context['description'], '</p><br />
						<br />';

	// Now just get the basic information - username, password, etc.
	echo '
				<span class="formleft"><strong>', $txt[35], ':</strong></span>
					<span class="formright"><input type="text" name="user" size="20" value="', $context['default_username'], '" /></span>
				<span class="formleft"><strong>', $txt[36], ':</strong></span>
					<span class="formright"><input type="password" name="passwrd" value="', $context['default_password'], '" size="20" /></span>
				<span class="formleft"><strong>', $txt[497], ':</strong></span>
					<span class="formright"><input type="text" name="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '"', $context['never_expire'] ? ' disabled="disabled"' : '', ' /></span>
				<span class="formleft"><strong>', $txt[508], ':</strong></span>
					<span class="formright"><input type="checkbox" name="cookieneverexp"', $context['never_expire'] ? ' checked="checked"' : '', ' class="check" onclick="this.form.cookielength.disabled = this.checked;" /></span>';
	// If they have deleted their account, give them a chance to change their mind.
	if (isset($context['login_show_undelete']))
		echo '
					<div class="row">
				<span class="formleft" style="color: #990000;">', $txt['undelete_account'], ':</span>
					<span class="formright"><input type="checkbox" name="undelete" class="check" /></span>
				</div>';
	echo '
	</div>
	<div class="row1-center">
	<a href="', $scripturl, '?action=reminder">', $txt[315], '</a>
	</div>
					<div class="subtitle" style="text-align:center;">
<input class="button" type="submit" value="', $txt[34], '" />
				</div>
			</div>

			<input type="hidden" name="hash_passwrd" value="" />
		</form>';

	// Focus on the correct input - username or password.
	echo '
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
			document.forms.frmLogin.', isset($context['default_username']) && $context['default_username'] != '' ? 'passwrd' : 'user', '.focus();
		// ]]></script>';
}

// Tell a guest to get lost or login!
function template_kick_guest()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// This isn't that much... just like normal login but with a message at the top.
	echo '
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/sha1.js"></script>
		
		<form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" name="frmLogin" id="frmLogin"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
			<div class="border">
				<h2 class="title">', $txt[633], '</h2>';

	// Show the message or default message.
	echo '
					<div class="row1">
						<p>', empty($context['kick_message']) ? $txt[634] : $context['kick_message'], '<br />
						', $txt[635], ' <a href="', $scripturl, '?action=register">', $txt[636], '</a> ', $txt[637], '</p>
					</div>';

	// And now the login information.
	echo '
				<h3 class="subtitle" style="text-align:right;">
			<span class="float-l"><img src="', $settings['images_url'], '/icons/login_sm.gif" alt="" align="top" /> ', $txt[34], '</span> <a href="', $scripturl, '?action=reminder">', $txt[315], '</a>
			</h3>
				<div class="row1">
				<span class="formleft"><strong>', $txt[35], ':</strong></span>
				<span class="formright"><input type="text" name="user" size="20" /></span>
				<span class="formleft"><strong>', $txt[36], ':</strong></span>
				<span class="formright"><input type="password" name="passwrd" size="20" /></span>
				<span class="formleft"><strong>', $txt[497], ':</strong></span>
				<span class="formright"><input type="text" name="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '" /></span>
			<span class="formleft"><strong>', $txt[508], ':</strong></span>
				<span class="formright"><input type="checkbox" name="cookieneverexp" class="check" onclick="this.form.cookielength.disabled = this.checked;" /></span>
			</div>
			<div class="subtitle" style="text-align:center;"><input type="submit" class="button" value="', $txt[34], '"  /></div>
			</div>

			<input type="hidden" name="hash_passwrd" value="" />
		</form>';

	// Do the focus thing...
	echo '
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
			document.forms.frmLogin.user.focus();
		// ]]></script>';
}

// This is for maintenance mode.
function template_maintenance()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Display the administrator's message at the top.
	echo '
<form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '">
	<div class="border">
	<h2 class="title">', $context['title'], '</h2>
	<div class="row1-center">
	<img src="', $settings['images_url'], '/construction.gif" width="40" height="40" alt="', $txt['maintenance3'], '" /><br />
	', $context['description'], '</div>
	<h3 class="subtitle">', $txt[114], '</h3>';

	// And now all the same basic login stuff from before.
	echo '
			<div class="row1">
						<div class="left">
				  <strong>', $txt[35], ':</strong> <input type="text" name="user" size="15" />
				  </div>
						<div class="right">
				  <strong>', $txt[36], ':</strong> <input type="password" name="passwrd" size="10" />
				  </div>
				  <div class="left">
				  <strong>', $txt[497], ':</strong> <input type="text" name="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '" />
				  </div>
				  <div class="right">
						<strong>', $txt[508], ':</strong> <input type="checkbox" name="cookieneverexp" class="check" />
				  </div>
				  </div>
				  <div class="subtitle" style="text-align:center;"><input type="submit" class="button" value="', $txt[34], '" /></div>
						<div class="block-foot"><!--no content--></div>
				  </div>
</form>';
}

// This is for the security stuff - makes administrators login every so often.
function template_admin_login()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Since this should redirect to whatever they were doing, send all the get data.
	echo '
<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/sha1.js"></script>

<form action="', $scripturl, $context['get_data'], '" method="post" accept-charset="', $context['character_set'], '" name="frmLogin" id="frmLogin" onsubmit="hashAdminPassword(this, \'', $context['user']['username'], '\', \'', $context['session_id'], '\');">
<div class="border">
	  <h2 class="title"><img src="', $settings['images_url'], '/icons/login_sm.gif" alt="" align="top" /> ', $txt[34], '</h2>';

	// We just need the password.
	echo '
		<div class="row1-center">
		<strong>', $txt[36], ':</strong> <input type="password" name="admin_pass" size="24" /> <a href="', $scripturl, '?action=helpadmin;help=securityDisable_why" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt[119], '" align="middle" /></a>
</div>
				<div class="subtitle" style="text-align:center;"><input class="button" type="submit" value="', $txt[34], '" />
			</div>
		 </div>';

	// Make sure to output all the old post data.
	echo $context['post_data'], '

	<input type="hidden" name="admin_hash_pass" value="" />
</form>';

	// Focus on the password box.
	echo '
<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
	document.forms.frmLogin.admin_pass.focus();
// ]]></script>';
}

// Activate your account manually?
function template_retry_activate()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Just ask them for their code so they can try it again...
	echo '
		<br />
		<form action="', $scripturl, '?action=activate;u=', $context['member_id'], '" method="post" accept-charset="', $context['character_set'], '">
			<div class="border">
				<h2 class="title">', $context['page_title'], '</h2>
			<div class="row1">';

	// You didn't even have an ID?
	if (empty($context['member_id']))
		echo '
				<p><strong>', $txt['invalid_activation_username'], ':</strong></p>
				<input type="text" name="user" size="30" />';

	echo '
				<p><strong>', $txt['invalid_activation_retry'], ':</strong></p>
				<input type="text" name="code" size="30" />
				</div>
			<div class="subtitle" style="text-align:center;">
			<input type="submit" class="button" value="', $txt['invalid_activation_submit'], '" />
			</div>
				<div class="block-foot"><!-no content--></div>
			</div>
		</form>';
}

// Activate your account manually?
function template_resend()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Just ask them for their code so they can try it again...
	echo '
		<br />
		<form action="', $scripturl, '?action=activate;sa=resend" method="post" accept-charset="', $context['character_set'], '">
			<div class="border">
				<h2 class="title">', $context['page_title'], '</h2>
				<div class="row1">
					<p><strong>', $txt['invalid_activation_username'], ':</strong></p>
					<input type="text" name="user" size="40" value="', $context['default_username'], '" />
				</div>
				<h3 class="subtitle">', $txt['invalid_activation_new'], '</h3>
				<div class="row1">
					<p><strong>', $txt['invalid_activation_new_email'], ':</strong></p>
					<input type="text" name="new_email" size="40" />
				<p><strong>', $txt['invalid_activation_password'], ':</strong></p>
					<input type="password" name="passwd" size="30" />
				</div>';

	if ($context['can_activate'])
		echo '
					<h4 class="subtitle">', $txt['invalid_activation_known'], '</h4>
				<div class="row1">
					<p><strong>', $txt['invalid_activation_retry'], ':</strong></p>
					<input type="text" name="code" size="30" />
				</div>';

	echo '
					<div class="subtitle" style="text-align:center;"><input type="submit" class="button" value="', $txt['invalid_activation_resend'], '" /></div>
				<div class="block-foot"><!-- no content--></div>
				</div>
		</form>';
}

?>