<?php
// Version: 1.1; PersonalMessage


// This is the main sidebar for the personal messages section.
function template_pm_above()
{
	global $context, $settings, $options, $txt;

	echo '', theme_linktree(), '';
	echo '
	<div id="messages">
		<div id="user-cp-left">
		<div class="border">
		<div class="title">&nbsp;</div>';
	// Loop through every main area - giving a nice section heading.
	foreach ($context['pm_areas'] as $section)
	{
		echo '
			<h3 class="subtitle" style="clear:none;">', $section['title'], '</h3>
			<ul>';
		// Each sub area.
		foreach ($section['areas'] as $i => $area)
		{
			if (empty($area))
				echo '';
			else
			{
				if ($i == $context['pm_area'])
					echo '<li id="selected">', $area['link'], (empty($area['unread_messages']) ? '' : ' (' . $area['unread_messages'] . ')'), '</li>';
				else
					echo '<li>', $area['link'], (empty($area['unread_messages']) ? '' : ' (' . $area['unread_messages'] . ')'), '</li>';
			}
		}
		echo '
							</ul>';
		// Special case for the capacity bar.
			if (!empty($area['limit_bar']))
			{
				// !!! Hardcoded colors = bad.
				echo '
					<h3 class="subtitle" style="clear:none;">', $txt['pm_capacity'], '</h3>
					<div class="row2">
					<div class="border" style="height: 7px;width: 100px;">
					<div style="border: 0; background-color: ', $context['limit_bar']['percent'] > 85 ? '#A53D05' : ($context['limit_bar']['percent'] > 40 ? '#EEA800' : '#468008'), '; height: 7px; width: ', $context['limit_bar']['bar'], 'px;"></div>
					</div>
					<p', ($context['limit_bar']['percent'] > 90 ? ' style="color: #990000;"' : ''), '>', $context['limit_bar']['text'], '</p>
					</div>';
			}
	}
	echo '
					</div>
				</div>
				<div id="user-cp-right">';
}


// Just the end of the index bar, nothing special.
function template_pm_below()
{
	global $context, $settings, $options;

	echo '
				</div>
				<div class="clear"><!--no content--></div>
				</div>';
}

function template_folder()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
<form action="', $scripturl, '?action=pm;sa=pmactions;f=', $context['folder'], ';start=', $context['start'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '" method="post" accept-charset="', $context['character_set'], '" name="pmFolder">
	<div class="border">
	<div class="title" style="text-align:right;">
	<span class="float-l"><input type="checkbox" onclick="invertAll(this, this.form);" class="check" /></span>
	<strong>', $txt[139], ':</strong> ', $context['page_index'], '
	</div>
	<div class="subtitle">
Sort by : <a href="', $scripturl, '?action=pm;f=', $context['folder'], ';start=', $context['start'], ';sort=date', $context['sort_by'] == 'date' && $context['sort_direction'] == 'up' ? ';desc' : '', ';', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '">', $txt[317], $context['sort_by'] == 'date' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
	
	&middot;
	
	<a href="', $scripturl, '?action=pm;f=', $context['folder'], ';start=', $context['start'], ';sort=subject', $context['sort_by'] == 'subject' && $context['sort_direction'] == 'up' ? ';desc' : '', ';', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '">', $txt[319], $context['sort_by'] == 'subject' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
	&middot;
	<a href="', $scripturl, '?action=pm;f=', $context['folder'], ';start=', $context['start'], ';sort=name', $context['sort_by'] == 'name' && $context['sort_direction'] == 'up' ? ';desc' : '', ';', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '">', ($context['from_or_to'] == 'from' ? $txt[318] : $txt[324]), $context['sort_by'] == 'name' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
	</div>
	';
	if (!$context['show_delete'])
		echo '
		<div class="row2">
		<p>', $txt[151], '></p>
		</div>';
	echo'<div class="row2">
	<dl>';
	$next_alternate = false;
	while ($message = $context['get_pmessage']())
	{
		echo '
		<dt>
		<span class="float-r">', $message['is_replied_to'] ? '<img src="' . $settings['images_url'] . '/icons/pm_replied.gif" alt="' . $txt['pm_replied'] . '" border="0" />' : '<img src="' . $settings['images_url'] . '/icons/pm_read.gif" alt="' . $txt['pm_read'] . '" border="0" />', '</span>
		<input type="checkbox" name="pms[]" id="deletelisting', $message['id'], '" value="', $message['id'], '"', $message['is_selected'] ? ' checked="checked"' : '', ' onclick="document.getElementById(\'deletedisplay', $message['id'], '\').checked = this.checked;" class="check" /> 
		<a href="#msg', $message['id'], '">', $message['subject'], '</a>
		</dt>
		
		<dd>
		<p><em>', ($context['from_or_to'] == 'from' ? $txt[318] : $txt[324]),': ', ($context['from_or_to'] == 'from' ? $message['member']['link'] : (empty($message['recipients']['to']) ? '' : implode(', ', $message['recipients']['to']))), '. ', $message['time'], '</em></p>
		</dd>';
		$next_alternate = $message['alternate'];
	}

	echo '
		</dl>
		</div>
		<div class="subtitle">
			';

	if ($context['show_delete'])
	{
		if (!empty($context['currently_using_labels']) && $context['folder'] != 'outbox')
		{
			echo '
								<select name="pm_action" onchange="if (this.options[this.selectedIndex].value) this.form.submit();" onfocus="loadLabelChoices();">
					<option value="">', $txt['pm_sel_label_title'], ':</option>
					<option value="" disabled="disabled">---------------</option>';

			echo '
									<option value="" disabled="disabled">', $txt['pm_msg_label_apply'], ':</option>';
			foreach ($context['labels'] as $label)
				if ($label['id'] != $context['current_label_id'])
					echo '
					<option value="add_', $label['id'], '">&nbsp;', $label['name'], '</option>';
			echo '
					<option value="" disabled="disabled">', $txt['pm_msg_label_remove'], ':</option>';
			foreach ($context['labels'] as $label)
				echo '
					<option value="rem_', $label['id'], '">&nbsp;', $label['name'], '</option>';
			echo '
				</select>
				<noscript>
					<input type="submit" value="', $txt['pm_apply'], '" />
				</noscript>';
		}

		echo '
				<input type="submit" class="button" name="del_selected" value="', $txt['quickmod_delete_selected'], '" style="font-weight: normal;" onclick="if (!confirm(\'', $txt['smf249'], '\')) return false;" />';
	}

	echo '
				<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
					var allLabels = {};
					function loadLabelChoices()
					{
						var listing = document.forms.pmFolder.elements;
						var theSelect = document.forms.pmFolder.pm_action;
						var add, remove, toAdd = {length: 0}, toRemove = {length: 0};

						if (theSelect.childNodes.length == 0)
							return;';

	// This is done this way for internationalization reasons.
	echo '
						if (typeof(allLabels[-1]) == "undefined")
						{
							for (var o = 0; o < theSelect.options.length; o++)
								if (theSelect.options[o].value.substr(0, 4) == "rem_")
									allLabels[theSelect.options[o].value.substr(4)] = theSelect.options[o].text;
						}

						for (var i = 0; i < listing.length; i++)
						{
							if (listing[i].name != "pms[]" || !listing[i].checked)
								continue;

							var alreadyThere = [], x;
							for (x in currentLabels[listing[i].value])
							{
								if (typeof(toRemove[x]) == "undefined")
								{
									toRemove[x] = allLabels[x];
									toRemove.length++;
								}
								alreadyThere[x] = allLabels[x];
							}

							for (x in allLabels)
							{
								if (typeof(alreadyThere[x]) == "undefined")
								{
									toAdd[x] = allLabels[x];
									toAdd.length++;
								}
							}
						}

						while (theSelect.options.length > 2)
							theSelect.options[2] = null;

						if (toAdd.length != 0)
						{
							theSelect.options[theSelect.options.length] = new Option("', $txt['pm_msg_label_apply'], '", "");
							setInnerHTML(theSelect.options[theSelect.options.length - 1], "', $txt['pm_msg_label_apply'], '");
							theSelect.options[theSelect.options.length - 1].disabled = true;

							for (i in toAdd)
							{
								if (i != "length")
									theSelect.options[theSelect.options.length] = new Option(toAdd[i], "add_" + i);
							}
						}

						if (toRemove.length != 0)
						{
							theSelect.options[theSelect.options.length] = new Option("', $txt['pm_msg_label_remove'], '", "");
							setInnerHTML(theSelect.options[theSelect.options.length - 1], "', $txt['pm_msg_label_remove'], '");
							theSelect.options[theSelect.options.length - 1].disabled = true;

							for (i in toRemove)
							{
								if (i != "length")
									theSelect.options[theSelect.options.length] = new Option(toRemove[i], "rem_" + i);
							}
						}
					}
				// ]]></script>';

	echo '
				</div>
				<div class="block-foot"><!--no content--></div>
				</div>
	<br />

	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var currentLabels = {};
	// ]]></script>';

	if ($context['get_pmessage'](true))
	{
		

		// Cache some handy buttons.
		$quote_button = create_button('quote.gif', 145, 'smf240', '');
		$reply_button = create_button('im_reply.gif', 146, 146, '');
		$reply_all_button = create_button('im_reply_all.gif', 'reply_to_all', 'reply_to_all', '');
		$forward_button = create_button('quote.gif', 145, 145, '');
		$delete_button = create_button('delete.gif', 154, 31, '');
		
		while ($message = $context['get_pmessage']())
		{
			$windowcss = $message['alternate'] == 0 ? 'windowbg' : 'windowbg2';

			echo '
		<div class="border-post">
			<a name="msg', $message['id'], '"></a>
			<h4 class="title">';
		
		if (!$message['member']['is_guest'])
			{
			echo'
			<span class="float-r">', $message['member']['group_stars'], '</span>';
			}
			echo'', $message['member']['link'], '';
			if (isset($message['member']['group']) && $message['member']['group'] != '')
				echo ' [', $message['member']['group'], ']';
			
			
			echo'</h4>
			<h5 class="subtitle" style="text-align:right;">
			<span class="float-l">', $message['subject'], '</span>';

			// Show who the message was sent to.
			echo '
			', $txt['sent_to'], ': ';

			// People it was sent directly to....
			if (!empty($message['recipients']['to']))
				echo implode(', ', $message['recipients']['to']);
			// Otherwise, we're just going to say "some people"...
			elseif ($context['folder'] != 'outbox')
				echo '(', $txt['pm_undisclosed_recipients'], ')';

			echo ' ', $txt[30], ': ', $message['time'], '';

			// If we're in the outbox, show who it was sent to besides the "To:" people.
			if (!empty($message['recipients']['bcc']))
				echo '
						', $txt[1502], ': ', implode(', ', $message['recipients']['bcc']), '';
				
			echo'
			</h5>
			<div class="post-top"><div class="post-top-left"><!-- no content--> </div></div>
			<div class="post-bg">
			<div class="post-left">';
			
			if (!$message['member']['is_guest'])
			{
			
			// Show avatars, images, etc.?
				if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']))
					echo '
									', $message['member']['avatar']['image'], '<br />';
			
			// Is karma display enabled?  Total or +/-?
				if ($modSettings['karmaMode'] == '1')
					echo '
									<br />
									', $modSettings['karmaLabel'], ' ', $message['member']['karma']['good'] - $message['member']['karma']['bad'], '<br />';
				elseif ($modSettings['karmaMode'] == '2')
					echo '
									<br />
									', $modSettings['karmaLabel'], ' +', $message['member']['karma']['good'], '/-', $message['member']['karma']['bad'], '<br />';

				// Is this user allowed to modify this member's karma?
				if ($message['member']['karma']['allow'])
					echo '
									<a href="', $scripturl, '?action=modifykarma;sa=applaud;uid=', $message['member']['id'], ';f=', $context['folder'], ';start=', $context['start'], ';', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pm=', $message['id'], ';sesc=', $context['session_id'], '">', $modSettings['karmaApplaudLabel'], '</a> <a href="', $scripturl, '?action=modifykarma;sa=smite;uid=', $message['member']['id'], ';f=', $context['folder'], ';start=', $context['start'], ';', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pm=', $message['id'], ';sesc=', $context['session_id'], '">', $modSettings['karmaSmiteLabel'], '</a><br />';
			}						
			if (!empty($message['is_replied_to']))
				echo '<img src="' . $settings['images_url'] . '/icons/pm_replied.gif" alt="' . $txt['pm_is_replied_to'] . '" border="0" title="' . $txt['pm_is_replied_to'] . '" />
				';
			
			echo '
			<input type="checkbox" name="pms[]" id="deletedisplay', $message['id'], '" value="', $message['id'], '" class="check" onclick="document.getElementById(\'deletelisting', $message['id'], '\').checked = this.checked;" />
			</div>
			<div class="post-right">';
			
			echo'
			<div class="post">', $message['body'], '</div></div>';
	
// Show the member's signature?
if (!empty($message['member']['signature']) && empty($options['show_no_signatures']))
			echo'<div class="signature"><div class="sig-text">', $message['member']['signature'], '</div></div>';
			
			echo'
			<div class="clear"><!--no content--></div>
			</div>
			<div class="post-foot">';
			
// Show the profile, website, email address, and personal message buttons.
	
		if ($settings['show_profile_buttons'])
		{
		
			if (!$message['member']['is_guest'])
			{
		
			echo '
			<a href="', $message['member']['href'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/profile.gif" alt="' . $txt[27] . '" title="' . $txt[27] . '" />' : $txt[27]), '</a>';
			
			if ($message['member']['website']['url'] != '')
				echo '
				<a href="', $message['member']['website']['url'], '" target="_blank">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/website.gif" alt="' . $txt[515] . '" title="' . $message['member']['website']['title'] . '" />' : $txt[515]), '</a>';
			
			if (empty($message['member']['hide_email']))
				echo '<a href="mailto:', $message['member']['email'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/email.gif" alt="' . $txt[69] . '" title="' . $txt[69] . '" />' : $txt[69]), '</a>';
			}
		}
			
		elseif (empty($message['member']['hide_email']))
			echo '
			<a href="mailto:', $message['member']['email'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/email.gif" alt="' . $txt[69] . '" title="' . $txt[69] . '" />' : $txt[69]), '</a>';
				
// Show reply buttons if you have the permission to send PMs.
			if ($context['can_send_pm'])
			{
				// You can't really reply if the member is gone.
				if (!$message['member']['is_guest'])
				{
					// Were than more than one recipient you can reply to? (Only in the "button style", or text)
					if ($message['number_recipients'] > 1)
						echo '<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote;u=all">', $reply_all_button, '</a>', $context['menu_separator'];
										
					echo '
										<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote;u=', $context['folder'] == 'outbox' ? '' : $message['member']['id'], '">', $quote_button, '</a>', $context['menu_separator'], '
										<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';u=', $message['member']['id'], '">', $reply_button, '</a> ', $context['menu_separator'];
				}
				
				// This is for "forwarding" - even if the member is gone.
				else
					echo '
										<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote">', $forward_button, '</a>', $context['menu_separator'];
			}
			echo '
										<a href="', $scripturl, '?action=pm;sa=pmactions;pm_actions[', $message['id'], ']=delete;f=', $context['folder'], ';start=', $context['start'], ';', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';sesc=', $context['session_id'], '" onclick="return confirm(\'', addslashes($txt[154]), '?\');">', $delete_button, '</a>
			';
			
			
			echo'', (!empty($modSettings['enableReportPM']) && $context['folder'] != 'outbox' ? '<a href="' . $scripturl . '?action=pm;sa=report;l=' . $context['current_label_id'] . ';pmsg=' . $message['id'] . '" title="' . $txt['pm_report_to_admin'] . '"><img src="' . $settings['images_url'] . '/' . $context['user']['language'] . '/report.gif" alt="' . $txt[154] . '" border="0" /></a>' : '');
			
			echo'</div>';
			
						// Add an extra line at the bottom if we have labels enabled.
		if ($context['folder'] != 'outbox' && !empty($context['currently_using_labels']))
		{
			echo '';
			// Add the label drop down box.
			if (!empty($context['currently_using_labels']))
			{
				echo '<div class="subtitle" style="text-align:right;">
								<select name="pm_actions[', $message['id'], ']" onchange="if (this.options[this.selectedIndex].value) form.submit();">
									<option value="">', $txt['pm_msg_label_title'], ':</option>
									<option value="" disabled="disabled">---------------</option>';
				// Are there any labels which can be added to this?
				if (!$message['fully_labeled'])
				{
					echo '
									<option value="" disabled="disabled">', $txt['pm_msg_label_apply'], ':</option>';
					foreach ($context['labels'] as $label)
					{
						if (!isset($message['labels'][$label['id']]))
							echo '
										<option value="', $label['id'], '">&nbsp;', $label['name'], '</option>';
					}
				}
				// ... and are there any that can be removed?
				if (!empty($message['labels']) && (count($message['labels']) > 1 || !isset($message['labels'][-1])))
				{
					echo '
									<option value="" disabled="disabled">', $txt['pm_msg_label_remove'], ':</option>';
					foreach ($message['labels'] as $label)
						echo '
									<option value="', $label['id'], '">&nbsp;', $label['name'], '</option>';
				}
				echo '
								</select>
								<noscript>
								<input type="submit" class="button" value="', $txt['pm_apply'], '" />
								</noscript>
								</div>';
			}
			
		}	
			echo'<div class="block-foot"><!--no content--></div>
			</div><br />
			';
		


		echo '

					<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
						currentLabels[', $message['id'], '] = {';

		if (!empty($message['labels']))
		{
			$first = true;
			foreach ($message['labels'] as $label)
			{
				echo $first ? '' : ',', '
								"', $label['id'], '": "', $label['name'], '"';
				$first = false;
			}
		}

		echo '
						};
					// ]]></script>';
		}

		echo '

	<div class="float-r"><input type="submit" class="button" name="del_selected" value="', $txt['quickmod_delete_selected'], '" style="font-weight: normal;" onclick="if (!confirm(\'', $txt['smf249'], '\')) return false;" /></div>
				<p>', $txt[139], ': ', $context['page_index'], '</p>
	';
	}

	echo '
	<input type="hidden" name="sc" value="', $context['session_id'], '" />
</form>';
}

function template_search()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		function expandCollapseLabels()
		{
			var current = document.getElementById("searchLabelsExpand").style.display != "none";

			document.getElementById("searchLabelsExpand").style.display = current ? "none" : "";
			document.getElementById("expandLabelsIcon").src = smf_images_url + (current ? "/expand.gif" : "/collapse.gif");
		}
	// ]]></script>

<form action="', $scripturl, '?action=pm;sa=search2" method="post" accept-charset="', $context['character_set'], '" name="pmSearchForm">
<div class="border">
<h4 class="title">', $txt['pm_search_title'], '</h4>';

	if (!empty($context['search_errors']))
	{
		echo '
			<div class="row2">
					<div style="color: #990000; margin: 1ex 0 2ex 3ex;">
						', implode('<br />', $context['search_errors']['messages']), '
					</div>
			</div>';
	}


	if ($context['simple_search'])
	{
		echo '<div class="row2">
					<strong>', $txt['pm_search_text'], ':</trong><br />
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" />&nbsp;
					<input type="submit" name="submit" value="', $txt['pm_search_go'], '" /><br />
					<a href="', $scripturl, '?action=pm;sa=search;advanced" onclick="this.href += \';search=\' + escape(document.forms.pmSearchForm.search.value);">', $txt['pm_search_advanced'], '</a>
					<input type="hidden" name="advanced" value="0" />
				</div>';
	}
	else
	{
		echo '
					<input type="hidden" name="advanced" value="1" />
					<div class="subtitle"><strong>', $txt['pm_search_text'], ':</strong></div>
					<div class="row2">
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" /> &nbsp; 
					<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
									if (typeof(window.addEventListener) == "undefined")
									{
										if (window.attachEvent)
										{
											window.addEventListener = function (sEvent, funcHandler, bCapture)
											{
												window.attachEvent("on" + sEvent, funcHandler);
											}
										}
										else
										{
											window.addEventListener = function (sEvent, funcHandler, bCapture) 
											{
												window["on" + sEvent] = funcHandler;
											}
										}
									}
									function initSearch()
									{
										if (document.forms.pmSearchForm.search.value.indexOf("%u") != -1)
											document.forms.pmSearchForm.search.value = unescape(document.forms.pmSearchForm.search.value);
									}
									window.addEventListener("load", initSearch, false);
								// ]]></script>
					<select name="searchtype">
									<option value="1"', empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['pm_search_match_all'], '</option>
									<option value="2"', !empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['pm_search_match_any'], '</option>
								</select>
					</div>
					<div class="subtitle"><strong>', $txt['pm_search_user'], ':</strong></div>
					<div class="row2">
					<input type="text" name="userspec" value="', empty($context['search_params']['userspec']) ? '*' : $context['search_params']['userspec'], '" size="40" />
					</div>

								
					<div class="subtitle"><strong>', $txt['pm_search_options'], ':</strong></div>
					<div class="row2">
					<label for="show_complete"><input type="checkbox" name="show_complete" id="show_complete" value="1"', !empty($context['search_params']['show_complete']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['pm_search_show_complete'], '</label><br />
					<label for="subject_only"><input type="checkbox" name="subject_only" id="subject_only" value="1"', !empty($context['search_params']['subject_only']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['pm_search_subject_only'], '</label>
					</div>
					<div class="subtitle"><strong>', $txt['pm_search_post_age'], ':</strong></div>
					<div class="row2">
					', $txt['pm_search_between'], ' <input type="text" name="minage" value="', empty($context['search_params']['minage']) ? '0' : $context['search_params']['minage'], '" size="5" maxlength="5" />&nbsp;', $txt['pm_search_between_and'], '&nbsp;<input type="text" name="maxage" value="', empty($context['search_params']['maxage']) ? '9999' : $context['search_params']['maxage'], '" size="5" maxlength="5" /> ', $txt['pm_search_between_days'], '.
					</div>
					<div class="subtitle"><strong>', $txt['pm_search_order'], ':</strong></div>
					<div class="row2">
								<select name="sort">
									<!--- <option value="relevance|desc">', $txt['pm_search_orderby_relevant_first'], '</option> --->
									<option value="ID_PM|desc">', $txt['pm_search_orderby_recent_first'], '</option>
									<option value="ID_PM|asc">', $txt['pm_search_orderby_old_first'], '</option>
								</select>
					</div>';
					
					// Do we have some labels setup? If so offer to search by them!
		if ($context['currently_using_labels'])
		{
			echo '
					<div class="subtitle">
					<a href="javascript:void(0);" onclick="expandCollapseLabels(); return false;"><img src="', $settings['images_url'], '/expand.gif" class="subtitle-collapse-icon" id="expandLabelsIcon" alt="" border="0" /></a> <a href="javascript:void(0);" onclick="expandCollapseLabels(); return false;"><strong>', $txt['pm_search_choose_label'], '</strong></a>
					</div>

					<div class="row2" id="searchLabelsExpand" ', $context['check_all'] ? 'style="display: none;"' : '', '>
					<ul class="row-list"';

			$alternate = true;
			foreach ($context['search_labels'] as $label)
			{
				if ($alternate)
					echo '';
				echo '
							<li>
								<label for="searchlabel_', $label['id'], '"><input type="checkbox" id="searchlabel_', $label['id'], '" name="searchlabel[', $label['id'], ']" value="', $label['id'], '" ', $label['checked'] ? 'checked="checked"' : '', ' class="check" />
								', $label['name'], '</label>
							</li>';
				if (!$alternate)
					echo '';

				$alternate = !$alternate;
			}

			echo '
					</ul>

					</div>
					<div class="row2">
					<input type="checkbox" name="all" id="check_all" value="" ', $context['check_all'] ? 'checked="checked"' : '', ' onclick="invertAll(this, this.form, \'searchlabel\');" class="check" /><em> <label for="check_all">', $txt[737], '</label></em>
					</div>';
		}
					
					echo'<div class="subtitle" style="text-align:center;"><input type="submit" class="button" name="submit" value="', $txt['pm_search_go'], '" /></div>';
	}

	echo '</div>
	</form>';
}


function template_search_results()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	// This splits broadly into two types of template... complete results first.
	if (!empty($context['search_params']['show_complete']))
	{
		echo '
		<div class="border">
			<h3 class="title"><span class="float-r"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</span>', $txt['pm_search_results'], '</h3>
			<div class="block-foot"><!--no content--></div>
		</div><br />';
	}
	else
	{
		echo '
		<div class="border">
		<h3 class="title">', $txt['pm_search_results'], '</h3>
		<div class="subtitle"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</div>
		<div class="row2">
		<dl>';
	}

	$alternate = true;
	// Print each message out...
	foreach ($context['personal_messages'] as $message)
	{
		// We showing it all?
		if (!empty($context['search_params']['show_complete']))
		{
			// !!! This still needs to be made pretty.
			echo '
		<div class="border">
		<h3 class="title" style="text-align:right">
		<span class="float-l">', $message['counter'], '&nbsp;&nbsp;<a href="', $message['href'], '">', $message['subject'], '</a></span>
		', $txt[176], ': ', $message['time'], '
		</h3>
		<p class="subtitle" style="margin:0;">', $txt[318], ': ', $message['member']['link'], ', ', $txt[324], ': ';

			// Show the recipients.
			// !!! This doesn't deal with the outbox searching quite right for bcc.
			if (!empty($message['recipients']['to']))
				echo implode(', ', $message['recipients']['to']);
			// Otherwise, we're just going to say "some people"...
			elseif ($context['folder'] != 'outbox')
				echo '(', $txt['pm_undisclosed_recipients'], ')';

			echo '
				</p>
			<div class="row2" style="padding-left:10px;padding-right:10px;">', $message['body'], '</div>
			<div class="post-foot">';

			if ($context['can_send_pm'])
			{
				$quote_button = create_button('quote.gif', 145, 145, '');
				$reply_button = create_button('im_reply.gif', 146, 146, '');
				
				// You can only reply if they are not a guest...
				if (!$message['member']['is_guest'])
					echo '
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote;u=', $context['folder'] == 'outbox' ? '' : $message['member']['id'], '">', $quote_button , '</a>', $context['menu_separator'], '
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';u=', $message['member']['id'], '">', $reply_button , '</a> ', $context['menu_separator'];
							
				// This is for "forwarding" - even if the member is gone.
				else
					echo '
							<a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote">', $quote_button , '</a>', $context['menu_separator'];
			}

			echo '
				</div>
			<div class="block-foot"><!--no content--></div>
			</div><br />';
		}
		// Otherwise just a simple list!
		else
		{
			// !!! No context at all of the search?
			echo '
			<dt class="', $alternate ? '' : '', '" ><strong>', $message['link'], '</strong></dt>
			<dd class="', $alternate ? '' : '', '">', $txt[318], ' ', $message['member']['link'], '. ', $txt[317], ' ', $message['time'], '</dd>';
		}

		$alternate = !$alternate;
	}

	// Finish off the page...
	if (!empty($context['search_params']['show_complete']))
	{
		// No results?
		if (empty($context['personal_messages']))
			echo '
		<div class="border">
		<div class="title"><!--no content--></div>
		<div class="row1-center">
		<p><strong>', $txt['pm_search_none_found'], '</strong></p>
		</div>
		<div class="block-foot"><!--no content--></div>
		</div><br />';

		echo '
		<div class="border">
		<div class="title"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</div>
		<div class="block-foot"><!--no content--></div>
		</div>';
	}
	else
	{
	echo'</dl>
			</div>
			</div><br />';
		if (empty($context['personal_messages']))
			echo '
			<div class="border">
		<div class="title"><!--no content--></div>
		<div class="row1-center">
		<p><strong>', $txt['pm_search_none_found'], '</strong></p>
		</div>
		<div class="block-foot"><!--no content--></div>
		</div><br />';

		echo '
		<div class="border">
		<div class="title"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</div>
		<div class="block-foot"><!--no content--></div>
		</div>';
	}
}


function template_send()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	if ($context['show_spellchecking'])
		echo '
		<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/spellcheck.js"></script>';

	// Show which messages were sent successfully and which failed.
	if (!empty($context['send_log']))
	{
		echo '
		<br />
		<div class="border">
		<h4 class="title">', $txt['pm_send_report'], '</h4>
		<div class="row2"';
		foreach ($context['send_log']['sent'] as $log_entry)
			echo '<p style="color: green">', $log_entry, '</p><br />';
		foreach ($context['send_log']['failed'] as $log_entry)
			echo '<p style="color: #990000">', $log_entry, '</p>';
		echo '
			</div>
			</div><br />';
	}

	// Show the preview of the personal message.
	if (isset($context['preview_message']))
	echo '
		<div class="border">
		<div class="title"><strong>', $context['preview_subject'], '</strong></div>
		<div class="row2">
		', $context['preview_message'], '
		</div>
		<div class="block-foot"><!--no content--></div>
		</div>
		<br />';

	// Main message editing box.
	echo '<form action="', $scripturl, '?action=pm;sa=send2" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" onsubmit="submitonce(this);saveEntities();">
		<div class="border">
		<h4 class="title">', $txt[321], '</h4>
		
		';

	// If there were errors for sending the PM, show them.
	if (!empty($context['post_error']['messages']))
	{
		echo '<div class="row2">
									<p><strong>', $txt['error_while_submitting'], '</strong></p>
									<div style="color: #990000; margin: 1ex 0 2ex 3ex;">
										', implode('<br />', $context['post_error']['messages']), '
									</div>
									</div>';
	}

	// To and bcc. Include a button to search for members.
	echo '<div class="row2">
			<div class="row">
			<span class="formleft"><strong', (isset($context['post_error']['no_to']) || isset($context['post_error']['bad_to']) ? ' style="color: #FF0000;"' : ''), '>', $txt[150], ':</strong></span> 
			
			<input type="text" name="to" id="to" value="', $context['to'], '" tabindex="', $context['tabindex']++, '" size="40" />&nbsp;
			<a href="', $scripturl, '?action=findmember;input=to;quote=1;sesc=', $context['session_id'], '" onclick="return reqWin(this.href, 350, 400);"><img src="', $settings['images_url'], '/icons/assist.gif" border="0" alt="', $txt['find_members'], '" /></a> <a href="', $scripturl, '?action=findmember;input=to;quote=1;sesc=', $context['session_id'], '" onclick="return reqWin(this.href, 350, 400);">', $txt['find_members'], '</a>
			</div>
			
			<div class="row">
			<span class="formleft"><strong', (isset($context['post_error']['bad_bcc']) ? ' style="color: #FF0000;"' : ''), '>', $txt[1502], ':</strong></span> 
			<input type="text" name="bcc" id="bcc" value="', $context['bcc'], '" tabindex="', $context['tabindex']++, '" size="40" />&nbsp;
			<a href="', $scripturl, '?action=findmember;input=bcc;quote=1;sesc=', $context['session_id'], '" onclick="return reqWin(this.href, 350, 400);"><img src="', $settings['images_url'], '/icons/assist.gif" border="0" alt="', $txt['find_members'], '" /></a> ', $txt[748], '
			</div>';
	// Subject of personal message.
	echo '
			<div class="row">
			<span class="formleft"><strong', (isset($context['post_error']['no_subject']) ? ' style="color: #FF0000;"' : ''), '>', $txt[70], ':</strong></span> 
			<input type="text" name="subject" value="', $context['subject'], '" tabindex="', $context['tabindex']++, '" size="40" maxlength="50" />
			</div>
			</div>';
			
		if ($context['visual_verification'])
	{
		echo '
							<h4 class="subtitle">', $txt['pm_visual_verification_label'], ':</h4>
							<div class="row1">';
		if ($context['use_graphic_library'])
			echo '
									<img src="', $context['verificiation_image_href'], '" alt="', $txt['pm_visual_verification_desc'], '" /><br />';
		else
			echo '
									<img src="', $context['verificiation_image_href'], ';letter=1" alt="', $txt['pm_visual_verification_desc'], '" />
									<img src="', $context['verificiation_image_href'], ';letter=2" alt="', $txt['pm_visual_verification_desc'], '" />
									<img src="', $context['verificiation_image_href'], ';letter=3" alt="', $txt['pm_visual_verification_desc'], '" />
									<img src="', $context['verificiation_image_href'], ';letter=4" alt="', $txt['pm_visual_verification_desc'], '" />
									<img src="', $context['verificiation_image_href'], ';letter=5" alt="', $txt['pm_visual_verification_desc'], '" /><br />';
		echo '
									<a href="', $context['verificiation_image_href'], ';sound" onclick="return reqWin(this.href, 400, 120);">', $txt['pm_visual_verification_listen'], '</a><br /><br />
									<input type="text" name="visual_verification_code" size="30" tabindex="', $context['tabindex']++, '" />
									<p style="margin-top: 4px;"><em>', $txt['pm_visual_verification_desc'], '</em></p>
								</div>';
	}

	// Show BBC buttons, smileys and textbox.
	theme_postbox($context['message']);
	
	echo'<p class="row1-center">
			<label for="outbox"><input type="checkbox" name="outbox" id="outbox" value="1" tabindex="', $context['tabindex']++, '"', $context['copy_to_outbox'] ? ' checked="checked"' : '', ' class="check" /> ', $txt['pm_save_outbox'], '</label>
			</p>';

	// Send, Preview, spellcheck buttons.
	echo '
			<div class="subtitle" style="text-align:center;">
			<input type="submit" class="button" value="', $txt[148], '" tabindex="', $context['tabindex']++, '" onclick="return submitThisOnce(this);" accesskey="s" /> 
			<input type="submit" class="button" name="preview" value="', $txt[507], '" tabindex="', $context['tabindex']++, '" onclick="return submitThisOnce(this);" accesskey="p" />';
	if ($context['show_spellchecking'])
		echo '
		<input type="button" class="button" value="', $txt['spell_check'], '" tabindex="', $context['tabindex']++, '" onclick="spellCheck(\'postmodify\', \'message\');" />';
	echo '
								</div>
	</div>
						<input type="hidden" name="sc" value="', $context['session_id'], '" />
						<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
						<input type="hidden" name="replied_to" value="', !empty($context['quoted_message']['id']) ? $context['quoted_message']['id'] : 0, '" />
						<input type="hidden" name="f" value="', isset($context['folder']) ? $context['folder'] : '', '" />
						<input type="hidden" name="l" value="', isset($context['current_label_id']) ? $context['current_label_id'] : -1, '" />
					</form>';

	// Some hidden information is needed in order to make the spell checking work.
	if ($context['show_spellchecking'])
		echo '
		<form name="spell_form" id="spell_form" method="post" accept-charset="', $context['character_set'], '" target="spellWindow" action="', $scripturl, '?action=spellcheck"><input type="hidden" name="spellstring" value="" /></form>';

	// Show the message you're replying to.
	if ($context['reply'])
		echo '
		<br />
		<br />
		<div class="border">
		<h5 class="title">', $txt[319], ': ', $context['quoted_message']['subject'], '</h5>
		<div class="subtitle"><strong>
		', $txt[318], ': ', $context['quoted_message']['member']['name'], ' ', $txt[30], ': ', $context['quoted_message']['time'], '
		</strong></div>
		<div class="row2">
		<p>', $context['quoted_message']['body'], '</p>
		</div>
		</div>';

	echo '
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
			function autocompleter(element)
			{
				if (typeof(element) != "object")
					element = document.getElementById(element);

				this.element = element;
				this.key = null;
				this.request = null;
				this.source = null;
				this.lastSearch = "";
				this.oldValue = "";
				this.cache = [];

				this.change = function (ev, force)
				{
					if (window.event)
						this.key = window.event.keyCode + 0;
					else
						this.key = ev.keyCode + 0;
					if (this.key == 27)
						return true;
					if (this.key == 34 || this.key == 8 || this.key == 13 || (this.key >= 37 && this.key <= 40))
						force = false;

					if (isEmptyText(this.element))
						return true;

					if (this.request != null && typeof(this.request) == "object")
						this.request.abort();

					var element = this.element, search = this.element.value.replace(/^("[^"]+",[ ]*)+/, "").replace(/^([^,]+,[ ]*)+/, "");
					this.oldValue = this.element.value.substr(0, this.element.value.length - search.length);
					if (search.substr(0, 1) == \'"\')
						search = search.substr(1);

					if (search == "" || search.substr(search.length - 1) == \'"\')
						return true;

					if (this.lastSearch == search)
					{
						if (force)
							this.select(this.cache[0]);

						return true;
					}
					else if (search.substr(0, this.lastSearch.length) == this.lastSearch && this.cache.length != 100)
					{
						// Instead of hitting the server again, just narrow down the results...
						var newcache = [], j = 0;
						for (var k = 0; k < this.cache.length; k++)
						{
							if (this.cache[k].substr(0, search.length) == search)
								newcache[j++] = this.cache[k];
						}

						if (newcache.length != 0)
						{
							this.lastSearch = search;
							this.cache = newcache;

							if (force)
								this.select(newcache[0]);

							return true;
						}
					}

					this.request = new XMLHttpRequest();
					this.request.onreadystatechange = function ()
					{
						element.autocompleter.handler(force);
					}

					this.request.open("GET", this.source + escape(textToEntities(search).replace(/&#(\d+);/g, "%#$1%")).replace(/%26/g, "%25%23038%25") + ";" + (new Date().getTime()), true);
					this.request.send(null);

					return true;
				}
				this.keyup = function (ev)
				{
					this.change(ev, true);

					return true;
				}
				this.keydown = function ()
				{
					if (this.request != null && typeof(this.request) == "object")
						this.request.abort();
				}
				this.handler = function (force)
				{
					if (this.request.readyState != 4)
						return true;

					var response = this.request.responseText.split("\n");
					this.lastSearch = this.element.value;
					this.cache = response;

					if (response.length < 2)
						return true;

					if (force)
						this.select(response[0]);

					return true;
				}
				this.select = function (value)
				{
					if (value == "")
						return;

					var i = this.element.value.length + (this.element.value.substr(this.oldValue.length, 1) == \'"\' ? 0 : 1);
					this.element.value = this.oldValue + \'"\' + value + \'"\';

					if (typeof(this.element.createTextRange) != "undefined")
					{
						var d = this.element.createTextRange();
						d.moveStart("character", i);
						d.select();
					}
					else if (this.element.setSelectionRange)
					{
						this.element.focus();
						this.element.setSelectionRange(i, this.element.value.length);
					}
				}

				this.element.autocompleter = this;
				this.element.setAttribute("autocomplete", "off");

				this.element.onchange = function (ev)
				{
					this.autocompleter.change(ev);
				}
				this.element.onkeyup = function (ev)
				{
					this.autocompleter.keyup(ev);
				}
				this.element.onkeydown = function (ev)
				{
					this.autocompleter.keydown(ev);
				}
			}

			if (window.XMLHttpRequest)
			{
				var toComplete = new autocompleter("to"), bccComplete = new autocompleter("bcc");
				toComplete.source = "', $scripturl, '?action=requestmembers;sesc=', $context['session_id'], ';search=";
				bccComplete.source = "', $scripturl, '?action=requestmembers;sesc=', $context['session_id'], ';search=";
			}
			
			function saveEntities()
			{
				var textFields = ["subject", "message"];
				for (i in textFields)
					if (document.forms.postmodify.elements[textFields[i]])
						document.forms.postmodify[textFields[i]].value = document.forms.postmodify[textFields[i]].value.replace(/&#/g, "&#38;#");
			}
		// ]]></script>';
}


// This template asks the user whether they wish to empty out their folder/messages.
function template_ask_delete()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;

	echo '
		<div class="border">
		<h4 class="title">', ($context['delete_all'] ? $txt[411] : $txt[412]), '</h4>
		<div class="row2">
					', $txt[413], '<br />
					<br />
					<strong><a href="', $scripturl, '?action=pm;sa=removeall2;f=', $context['folder'], ';', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';sesc=', $context['session_id'], '">', $txt[163], '</a> - <a href="javascript:history.go(-1);">', $txt[164], '</a></strong>
				</div>
			</div>';
}


// This template asks the user what messages they want to prune.
function template_prune()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
	<form action="', $scripturl, '?action=pm;sa=prune" method="post" accept-charset="', $context['character_set'], '" onsubmit="return confirm(\'', $txt['pm_prune_warning'], '\');">
		<div class="border">
		<h4 class="title">', $txt['pm_prune'], '</h4>
		<div class="row2">', $txt['pm_prune_desc1'], ' <input type="text" name="age" size="3" value="14" /> ', $txt['pm_prune_desc2'], '</div>
		<div class="subtitle" style="text-align:center;">
		<input type="submit" class="button" value="', $txt['smf138'], '" />
		</div>
		</div>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}


// Here we allow the user to setup labels, remove labels and change rules for labels (i.e, do quite a bit)
function template_labels()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
	<form action="', $scripturl, '?action=pm;sa=manlabels;sesc=', $context['session_id'], '" method="post" accept-charset="', $context['character_set'], '">
		<div class="border">
		<h4 class="title">', $txt['pm_manage_labels'], '</h4>
		<div class="row2">
		<p>', $txt['pm_labels_desc'], '</p>
		</div>
		<h5 class="subtitle">
			<span class="float-r"><input type="checkbox" class="check" onclick="invertAll(this, this.form);" /> </span>
			', $txt['pm_label_name'], '
		</h5>';
	if (empty($context['labels']))
		echo '
			<div class="row2">
			<p>', $txt['pm_labels_no_exist'], '</p>
			</div>';
	else
	{
	echo'<ul class="row-list">';
		$alternate = true;
		foreach ($context['labels'] as $label)
		{
			if ($label['id'] != -1)
			{
				echo '
				<li class="', $alternate ? 'placeholderclass' : 'placeholderclass', '">
				<span class="float-r"><input type="checkbox" class="check" name="delete_label[', $label['id'], ']" /></span>
				<input type="text" name="label_name[', $label['id'], ']" value="', $label['name'], '" size="30" maxlength="30" />
				</li>';
				$alternate = !$alternate;
			}
		}

		echo '
		</ul>
			<div class="subtitle" style="text-align:center;">
			<input type="submit" class="button" name="save" value="', $txt[10], '" style="font-weight: normal;" /> 
			<input type="submit" class="button" name="delete" value="', $txt['quickmod_delete_selected'], '" style="font-weight: normal;" onclick="return confirm(\'', $txt['pm_labels_delete'], '\');" />
			</div>';
	}
	echo '
	<div class="block-foot"><!--no content--></div>
		</div>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>
	<form action="', $scripturl, '?action=pm;sa=manlabels;sesc=', $context['session_id'], '" method="post" accept-charset="', $context['character_set'], '">
		<div class="border-mod">
		<h5 class="subtitle">', $txt['pm_label_add_new'], '</h5>
		<div class="row2">
		<strong>', $txt['pm_label_name'], ':</strong> <input type="text" name="label" value="" size="30" maxlength="20"/>
		</div>
		<div class="subtitle" style="text-align:center;">
		<input type="submit" class="button" name="add" value="', $txt['pm_label_add_new'], '" style="font-weight: normal;" />
		</div>
		<div class="block-foot"><!--no content--></div>
		</div>
	</form>';
}


// Template for reporting a personal message.
function template_report_message()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<form action="', $scripturl, '?action=pm;sa=report;l=', $context['current_label_id'], '" method="post" accept-charset="', $context['character_set'], '">
		<input type="hidden" name="pmsg" value="', $context['pm_id'], '" />
		<div class="border">
		<h4 class="title">', $txt['pm_report_title'], '</h4>
		<div class="row2">
		<p>', $txt['pm_report_desc'], '</p>
		</div>';

	// If there is more than one admin on the forum, allow the user to choose the one they want to direct to.
	// !!! Why?
	if ($context['admin_count'] > 1)
	{
		echo '
			<div class="row2">
					<strong>', $txt['pm_report_admins'], ':</strong>
					<select name="ID_ADMIN">
						<option value="0">', $txt['pm_report_all_admins'], '</option>';
		foreach ($context['admins'] as $id => $name)
			echo '
						<option value="', $id, '">', $name, '</option>';
		echo '
					</select>
				</div>';
	}

	echo '
			<div class="row1-center">
			<p><strong>', $txt['pm_report_reason'], ':</strong></p>
			
					<textarea name="reason" rows="4" cols="70" style="width: 80%;"></textarea>
			</div>
			<div class="subtitle" style="text-align:center;">
			<input type="submit" class="button" name="report" value="', $txt['pm_report_message'], '" />
			</div>
			</div>
		<input type="hidden" name="sc" value="', $context['session_id'], '" />
	</form>';
}


// Little template just to say "Yep, it's been submitted"
function template_report_message_complete()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
		<div class="border">
		<h4 class="title"', $txt['pm_report_title'], '</h4>
		<div class="row2">
		<p>', $txt['pm_report_done'], '</p>
		<br />
		<p><a href="', $scripturl, '?action=pm;l=', $context['current_label_id'], '">', $txt['pm_report_return'], '</a></p>
		</div>
		<div class="block-foot"><!--no content--></div>
		</div>';
}

?>