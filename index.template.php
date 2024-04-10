<?php
// Version: 1.1; index

/*	This template is, perhaps, the most important template in the theme.  It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below.  It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '1.1';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = false;

	/* Use plain buttons - as oppossed to text buttons? */
	$settings['use_buttons'] = false;

	/* Show sticky and lock status seperate from topic icons? */
	$settings['seperate_sticky_lock'] = false;
}

// The main sub template above the content.
function template_main_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '','','','','><head>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title'], '" />', empty($context['robot_no_index']) ? '' : '
	<meta name="robots" content="noindex" />', '
	<meta name="keywords" content="PHP, MySQL, bulletin, board, free, open, source, smf, simple, machines, forum" />
	<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/script.js?fin11"></script>
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";
	// ]]></script>
	<title>', $context['page_title'], '</title>';

	// The ?fin11 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/', $context['right_to_left'] ? 'style-rtl-text.css?fin11' : 'style.css?fin11', '" />
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/print.css?fin11" media="print" />';


	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="' . $scripturl . '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name'], ' - RSS" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="' . $scripturl . '?board=' . $context['current_board'] . '.0" />';
	
		// We'll have to use the cookie to remember the header...
	if ($context['user']['is_guest'])
		$options['collapse_header'] = !empty($_COOKIE['upshrink']);

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'], '

	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var current_header = ', empty($options['collapse_header']) ? 'false' : 'true', ';

		function shrinkHeader(mode)
		{';

	// Guests don't have theme options!!
	if ($context['user']['is_guest'])
		echo '
			document.cookie = "upshrink=" + (mode ? 1 : 0);';
	else
		echo '
			smf_setThemeOption("collapse_header", mode ? 1 : 0, null, "', $context['session_id'], '");';

	echo '
			document.getElementById("upshrink").src = smf_images_url + (mode ? "/expand.gif" : "/collapse.gif");

			document.getElementById("upshrinkHeader").style.display = mode ? "none" : "";

			current_header = mode;
		}
	// ]]></script>
</head>
<body>';

// wrapper div defines style width. (in the css)

	echo '
	<div id="wrapper">
<div id="header">
<div id="header-right">';

	// This part is the logo and forum name.  You should be able to change this to whatever you want...

	if (empty($settings['header_logo_url']))
		echo '<h1 id="logo"><a href="', $scripturl, '">', $context['forum_name'], '</a></h1>';
	else
		echo '
					<h1 id="logo"><a href="', $scripturl, '">', $context['forum_name'], '</a></h1>';
				
				 echo ' </div></div>
<div id="nav">
<div id="nav-left">
<div id="nav-right">';
// Show the menu here, according to the menu sub template.
		template_menu();
	  echo '
<div id="nav-bot"><!--no content--></div>
</div>
</div>
</div>
<div id="content-container">
<div id="content">
';

// If the user is logged in, display stuff like their name, new messages, etc.
if ($context['user']['is_logged'])
{

echo '<div class="border-mod">
<h2 class="subtitle">
<a href="javascript:void(0);" onclick="shrinkHeader(!current_header); return false;"><img id="upshrink" class="subtitle-collapse-icon" src="', $settings['images_url'], '/', empty($options['collapse_header']) ? 'collapse.gif' : 'expand.gif', '" alt="*" title="', $txt['upshrink_description'], '" border="0" /></a>
', $txt['hello_member_ndt'], ' <strong>', $context['user']['name'], '</strong>';

// Only tell them about their messages if they can read their messages!

if ($context['allow_pm'])
echo ', ', $txt[152], ' <a href="', $scripturl, '?action=pm">', $context['user']['messages'], ' ', $context['user']['messages'] != 1 ? $txt[153] : $txt[471], '</a>', $txt['newmessages4'], ' ', $context['user']['unread_messages'], ' ', $context['user']['unread_messages'] == 1 ? $txt['newmessages0'] : $txt['newmessages1'];		  
			  
// Is the forum in maintenance mode?
if ($context['in_maintenance'] && $context['user']['is_admin'])
echo '. <strong>', $txt[616], '</strong>';
echo '.</h2>
<div class="row1-right" id="upshrinkHeader"', empty($options['collapse_header']) ? '' : ' style="display: none;"', '>
<div class="float-l" style="width:50%;text-align:left;">';

// Are there any members waiting for approval?
if (!empty($context['unapproved_members']))
echo '
<p class="smalltext">', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=regcenter;sa=browse">', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'], '</p>';

// Show a random news item? (or you could pick one from news_lines...)
if (!empty($settings['enable_news']))
echo '
<div id="news" class="smalltext"><p><strong>News</strong>: ', $context['random_news_line'], '</p></div>';

echo '</div>';
echo '
<form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '" style="margin: 0;">
<input type="text" name="search" value="" style="width: 130px;" /> 
<input type="submit" class="button" style="margin-right:0;" name="submit" value="', $txt[182], '" />
<input type="hidden" name="advanced" value="0" />
<p class="smalltext" style="margin: 4px 0 0 0;"><a href="', $scripturl, '?action=unread">', $txt['unread_since_visit'], '</a><br />
<a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a><br /><a href="', $scripturl, '?action=search;advanced">', $txt['smf298'], '</a></p>';

// Search within current topic?
	if (!empty($context['current_topic']))
		echo '
							<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
				  

// If we're on a certain board, limit it to this board ;).
	elseif (!empty($context['current_board']))
		echo '
							<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

echo '</form>';
echo '
<div class="clear"><!--no content--></div>
</div>
<div class="block-foot" style="clear:both;"><!--no content--></div>
</div><br />';
}
  
// Otherwise they're a guest - so politely ask them to register or login.
else
{
	echo '
	  <div class="border-mod">
	  <h2 class="subtitle">
	  <a href="javascript:void(0);" onclick="shrinkHeader(!current_header); return false;"><img id="upshrink" class="subtitle-collapse-icon" src="', $settings['images_url'], '/', empty($options['collapse_header']) ? 'collapse.gif' : 'expand.gif', '" alt="*" title="', $txt['upshrink_description'], '" border="0" /></a>
	  ', $txt['welcome_guest'], '
	  </h2>
	  <div class="row1-right" id="upshrinkHeader"', empty($options['collapse_header']) ? '' : ' style="display: none;"', '>
	  <div class="float-l" style="width:50%;text-align:left;">
			 ';
		  
		  // Show a random news item? (or you could pick one from news_lines...)
	if (!empty($settings['enable_news']))
		echo '
	  <div id="news" class="smalltext"><p><strong>News</strong>: ', $context['random_news_line'], '</p></div>
			  ';
		echo '</div>

							<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/sha1.js"></script>
							<form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" style="margin: 3px 1ex 1px 0;"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
									<input type="text" name="user" size="10" /> <input type="password" name="passwrd" size="10" />
									<select name="cookielength">
										<option value="60">', $txt['smf53'], '</option>
										<option value="1440">', $txt['smf47'], '</option>
										<option value="10080">', $txt['smf48'], '</option>
										<option value="43200">', $txt['smf49'], '</option>
										<option value="-1" selected="selected">', $txt['smf50'], '</option>
									</select>
									<input type="submit" class="button" value="', $txt[34], '" style="margin-right:0;" /><br />
									', $txt['smf52'], '
									<input type="hidden" name="hash_passwrd" value="" />
							</form>
							<div class="clear"><!--no content--></div>
					 </div>
					<div class="block-foot"><!--no content--></div>
					</div><br />
					 ';
	}

	
}
  















	// The main content should go here.  A table is used because IE 6 just can't handle a div [Bollocks ;)].
function template_main_below()
{
	global $context, $settings, $options, $scripturl, $txt;


	// Show the "Powered by" and "Valid" logos, as well as the copyright.  Remember, the copyright must be somewhere!
	echo '
	</div>
	</div>
	<div id="footer">
	<div id="foot-left">
	<div id="foot-right">
	<!--no content-->
	</div></div></div>
<p class="copy-r">Endar template 1.0.8 &copy; <a href="http://www.endar.org" title="Endar SMF theme copyright of Endar.org">www.Endar.org</a>.</p>
<p class="copy-r">', theme_copyright(), '';
// Show the load time?
if ($context['show_load_time'])
	echo '
	', $txt['smf301'], $context['load_time'], $txt['smf302'], $context['load_queries'], $txt['smf302b'], '';
	echo '</p></div>';


	// This is an interesting bug in Internet Explorer AND Safari. Rather annoying, it makes overflows just not tall enough.
	if (($context['browser']['is_ie'] && !$context['browser']['is_ie4']) || $context['browser']['is_mac_ie'] || $context['browser']['is_safari'] || $context['browser']['is_firefox'])
	{
		// The purpose of this code is to fix the height of overflow: auto div blocks, because IE can't figure it out for itself.
		echo '
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[';

		// Unfortunately, Safari does not have a "getComputedStyle" implementation yet, so we have to just do it to code...
		if ($context['browser']['is_safari'])
			echo '
			window.addEventListener("load", smf_codeFix, false);

			function smf_codeFix()
			{
				var codeFix = document.getElementsByTagName ? document.getElementsByTagName("div") : document.all.tags("div");

				for (var i = 0; i < codeFix.length; i++)
				{
					if ((codeFix[i].className == "code" || codeFix[i].className == "post" || codeFix[i].className == "signature") && codeFix[i].offsetHeight < 20)
						codeFix[i].style.height = (codeFix[i].offsetHeight + 20) + "px";
				}
			}';
		elseif ($context['browser']['is_firefox'])
			echo '
			window.addEventListener("load", smf_codeFix, false);
			function smf_codeFix()
			{
				var codeFix = document.getElementsByTagName ? document.getElementsByTagName("div") : document.all.tags("div");

				for (var i = 0; i < codeFix.length; i++)
				{
					if (codeFix[i].className == "code" && (codeFix[i].scrollWidth > codeFix[i].clientWidth || codeFix[i].clientWidth == 0))
						codeFix[i].style.overflow = "scroll";
				}
			}';			
		else
			echo '
			var window_oldOnload = window.onload;
			window.onload = smf_codeFix;

			function smf_codeFix()
			{
				var codeFix = document.getElementsByTagName ? document.getElementsByTagName("div") : document.all.tags("div");

				for (var i = codeFix.length - 1; i > 0; i--)
				{
					if (codeFix[i].currentStyle.overflow == "auto" && (codeFix[i].currentStyle.height == "" || codeFix[i].currentStyle.height == "auto") && (codeFix[i].scrollWidth > codeFix[i].clientWidth || codeFix[i].clientWidth == 0) && (codeFix[i].offsetHeight != 0 || codeFix[i].className == "code"))
						codeFix[i].style.height = (codeFix[i].offsetHeight + 36) + "px";
				}

				if (window_oldOnload)
				{
					window_oldOnload();
					window_oldOnload = null;
				}
			}';

		echo '
		// ]]></script>';
	}

	// The following will be used to let the user know that some AJAX process is running
	echo '
        <div id="ajax_in_progress" style="display: none;', $context['browser']['is_ie'] && !$context['browser']['is_ie7'] ? 'position: absolute;' : '', '">
	<div class="border">
	<div class="title">&nbsp;</div>
	<div class="row1-center"><p><strong>', $txt['ajax_in_progress'], '</strong></p></div>
	</div>
	</div>
		</body>
</html>';
}



// Show a linktree.  This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree()
{
	global $context, $settings, $options;

	// Folder style or inline?  Inline has a smaller font.
	echo '<div class="bread-nav"><p class="subtitle"', $settings['linktree_inline'] ? ' ' : '', '>';

	// Each tree item has a URL and name.  Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		// Show the | | |-[] Folders.
		if (!$settings['linktree_inline'])
		{
			if ($link_num > 0)
				echo str_repeat('<img src="' . $settings['images_url'] . '/icons/linktree_main.gif" alt="| " border="0" />', $link_num - 1), '<img src="' . $settings['images_url'] . '/icons/linktree_side.gif" alt="|-" border="0" />';
			echo '<img src="' . $settings['images_url'] . '/icons/folder_open.gif" alt="+" border="0" />  ';
		}

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo '<strong>', $settings['linktree_link'] && isset($tree['url']) ? '<a href="' . $tree['url'] . '" class="nav">' . $tree['name'] . '</a>' : $tree['name'], '</strong>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo $settings['linktree_inline'] ? '  »  ' : '<br />';
	}

	echo '</p></div>';
}





// Show the menu up top.  Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;
	
	// Work out where we currently are.
	$current_action = 'home';
	if (in_array($context['current_action'], array('admin', 'ban', 'boardrecount', 'cleanperms', 'detailedversion', 'dumpdb', 'featuresettings', 'featuresettings2', 'findmember', 'maintain', 'manageattachments', 'manageboards', 'managecalendar', 'managesearch', 'membergroups', 'modlog', 'news', 'optimizetables', 'packageget', 'packages', 'permissions', 'pgdownload', 'postsettings', 'regcenter', 'repairboards', 'reports', 'serversettings', 'serversettings2', 'smileys', 'viewErrorLog', 'viewmembers')))
		$current_action = 'admin';
	if (in_array($context['current_action'], array('search', 'admin', 'calendar', 'profile', 'mlist', 'register', 'login', 'help', 'pm')))
		$current_action = $context['current_action'];
	if ($context['current_action'] == 'search2')
		$current_action = 'search';
	if ($context['current_action'] == 'theme')
		$current_action = isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'pick' ? 'profile' : 'admin';
		
	// Are we using right-to-left orientation?
	if ($context['right_to_left'])
	{
		$first = 'last';
		$last = 'first';
	}
	else
	{
		$first = 'first';
		$last = 'last';
	}
	
	echo '<ul id="usernav">';
	// Show the [home] and [help] buttons.
	echo '
				<li><a href="', $scripturl, '">' . $txt[103] . '</a></li>
				<li><a href="', $scripturl, '?action=help">' . $txt[119] . '</a></li>';

	// How about the [search] button?
	if ($context['allow_search'])
		echo '
				<li><a href="', $scripturl, '?action=search">' . $txt[182] . '</a></li>';

	// Is the user allowed to administrate at all? ([admin])
	if ($context['allow_admin'])
		echo '
				<li><a href="', $scripturl, '?action=admin">' . $txt[2] . '</a></li>';

	// Edit Profile... [profile]
	if ($context['allow_edit_profile'])
		echo '
				<li><a href="', $scripturl, '?action=profile">' . $txt[79] . '</a></li>';
	
	// Go to PM center... [pm]
	if ($context['user']['is_logged'] && $context['allow_pm'])
		echo '<li><a href="', $scripturl, '?action=pm">' , $txt['pm_short'] , ' ', $context['user']['unread_messages'] > 0 ? '[<strong>'. $context['user']['unread_messages'] . '</strong>]' : '' , '</a></li>';
	
	if ($settings['show_member_bar'])
	{
		echo '<li><a href="' . $scripturl . '?action=mlist">', $txt[331], '</a></li>';
	}

	// The [calendar]!
	if ($context['allow_calendar'])
		echo '
				<li><a href="', $scripturl, '?action=calendar">' . $txt['calendar24'] . '</a></li>';

	// If the user is a guest, show [login] and [register] buttons.
	if ($context['user']['is_guest'])
	{
		echo '
				<li><a href="', $scripturl, '?action=login">' . $txt[34] . '</a></li>
				<li><a href="', $scripturl, '?action=register">' . $txt[97] . '</a></li>';
	}
	// Otherwise, they might want to [logout]...
	else
		echo '
				<li><a href="', $scripturl, '?action=logout;sesc=', $context['session_id'], '">' . $txt[108] . '</a></li>';
echo '</ul>';
}

// Generate a strip of buttons, out of buttons.
function template_button_strip($button_strip, $direction = 'top', $force_reset = false, $custom_td = '')
{
	global $settings, $buttons, $context, $txt, $scripturl;

	if (empty($button_strip))
		return '';

	// Create the buttons...
	foreach ($button_strip as $key => $value)
	{
		if (isset($value['test']) && empty($context[$value['test']]))
		{
			unset($button_strip[$key]);
			continue;
		}
		elseif (!isset($buttons[$key]) || $force_reset)
			$buttons[$key] = '<a href="' . $value['url'] . '" ' .( isset($value['custom']) ? $value['custom'] : '') . '>' . ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . ($value['lang'] ? $context['user']['language'] . '/' : '') . $value['image'] . '" alt="' . $txt[$value['text']] . '" border="0" />' : $txt[$value['text']]) . '</a>';

		$button_strip[$key] = $buttons[$key];
	}

	echo '
		<td ', $custom_td, '>', implode($context['menu_separator'], $button_strip) , '</td>';
}

?>