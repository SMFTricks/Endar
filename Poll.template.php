<?php
// Version: 1.1.2; Poll

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Some javascript for adding more options.
	echo '
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
			var pollOptionNum = 0;

			function addPollOption()
			{
				if (pollOptionNum == 0)
				{
					for (var i = 0; i < document.forms.postmodify.elements.length; i++)
						if (document.forms.postmodify.elements[i].id.substr(0, 8) == "options-")
							pollOptionNum++;
				}
				pollOptionNum++

				setOuterHTML(document.getElementById("pollMoreOptions"), \'<li><label for="options-\' + pollOptionNum + \'" ', (isset($context['poll_error']['no_question']) ? ' style="color: red;"' : ''), '>', $txt['smf22'], ' \' + pollOptionNum + \'</label>: <input type="text" name="options[\' + (pollOptionNum - 1) + \']" id="options-\' + (pollOptionNum - 1) + \'" value="" size="25" /></li><span id="pollMoreOptions"></span>\');
			}

			function saveEntities()
			{
				document.forms.postmodify.question.value = document.forms.postmodify.question.value.replace(/&#/g, "&#38;#");
				for (i in document.forms.postmodify)
					if (document.forms.postmodify[i].name.indexOf("options") == 0)
						document.forms.postmodify[i].value = document.forms.postmodify[i].value.replace(/&#/g, "&#38;#");
			}
		// ]]></script>';

	// Start the main poll form.
	echo '
		<form action="' . $scripturl . '?action=editpoll2', $context['is_edit'] ? '' : ';add', ';topic=' . $context['current_topic'] . '.' . $context['start'] . '" method="post" accept-charset="', $context['character_set'], '" onsubmit="submitonce(this);saveEntities();" name="postmodify" id="postmodify">
			
			', theme_linktree(), '
			
			<div class="border">
			<h3 class="title">' . $context['page_title'] . '</h3>
			<input type="hidden" name="poll" value="' . $context['poll']['id'] . '" />';

	if (!empty($context['poll_error']['messages']))
		echo '
							<div id="errors" class="row1">
							<div style="padding: 0px; font-weight: bold;" id="error_serious">
										', $context['is_edit'] ? $txt['error_while_editing_poll'] : $txt['error_while_adding_poll'], ':</div>
									<div style="color: #990000; margin: 1ex 0 2ex 3ex;" id="error_list">
										', empty($context['poll_error']['messages']) ? '' : implode('<br />', $context['poll_error']['messages']), '
									</div>
								</div>';

	echo '
			<div class="row1">
			<span
			', (isset($context['poll_error']['no_question']) ? ' style="color: #990000;"' : ''), '><strong>' . $txt['smf21'] . ':</strong>
			</span> <input type="text" name="question" size="40" value="' . $context['poll']['question'] . '" />
			</div>
			<div class="row1">
			<ul class="row-list">';

	foreach ($context['choices'] as $choice)
	{
		echo '
				<li><label for="options-', $choice['id'], '" ', (isset($context['poll_error']['poll_few']) ? ' style="color: #990000;"' : ''), '>', $txt['smf22'], ' ', $choice['number'], '</label>: <input type="text" name="options[', $choice['id'], ']" id="options-', $choice['id'], '" size="25" value="', $choice['label'], '" />';

		// Does this option have a vote count yet, or is it new?
		if ($choice['votes'] != -1)
			echo ' (', $choice['votes'], ' ', $txt['smf42'], ')';

		if (!$choice['is_last'])
			echo '';
		echo'</li>';
	}

	echo '<span id="pollMoreOptions"></span>
			</ul>
			</div>
			 <div class="row1-center" style="padding:15px 4px 8px 4px;">
			 <p class="buttons"><strong><a href="javascript:addPollOption(); void(0);">(', $txt['poll_add_option'], ')</a></strong></p>
			 </div>
									';

	if ($context['can_moderate_poll'])
		echo '
				<h4 class="subtitle">', $txt['poll_options'], ':</h4>
				<div class="row1-center">
				<ul class="row-list">
				<li><input type="text" name="poll_max_votes" size="2" value="', $context['poll']['max_votes'], '" /> ', $txt['poll_options5'], '</li>
				<li>', $txt['poll_options1a'], ' <input type="text" name="poll_expire" size="2" value="', $context['poll']['expiration'], '" onchange="this.form.poll_hide[2].disabled = isEmptyText(this) || this.value == 0; if (this.form.poll_hide[2].checked) this.form.poll_hide[1].checked = true;" /> ', $txt['poll_options1b'], '</li>
				<li><label for="poll_change_vote"><input type="checkbox" id="poll_change_vote" name="poll_change_vote"', !empty($context['poll']['change_vote']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['poll_options7'], '</label></li>';
	else
		echo '
				<h4 class="subtitle">', $txt['poll_options'], ':</h4>
				<div class="row1-center">
				<ul class="row-list">
				';

	echo '
			<li><input type="radio" name="poll_hide" value="0"', $context['poll']['hide_results'] == 0 ? ' checked="checked"' : '', ' class="check" /> ' . $txt['poll_options2'] . '
			</li>
			<li><input type="radio" name="poll_hide" value="1"', $context['poll']['hide_results'] == 1 ? ' checked="checked"' : '', ' class="check" /> ' . $txt['poll_options3'] . '
			</li>
			<li><input type="radio" name="poll_hide" value="2"', $context['poll']['hide_results'] == 2 ? ' checked="checked"' : '', empty($context['poll']['expiration']) ? 'disabled="disabled"' : '', ' class="check" /> ' . $txt['poll_options4'] . '
			</li>';
	// If this is an edit, we can allow them to reset the vote counts.
	if ($context['is_edit'])
		echo '
				<li><strong>' . $txt['smf40'] . ':</strong> <input type="checkbox" name="resetVoteCount" value="on" class="check" /> ' . $txt['smf41'] . '</li>';
	echo '<li><span class="smalltext">' . $txt['smf16'] . '</span></li>
			</ul>
			</div>
			<div class="subtitle" style="text-align:center;">
			<input type="submit" class="button" name="post" value="' . $txt[10] . '" onclick="return submitThisOnce(this);" accesskey="s" /> 
			<input type="submit" class="button" name="preview" value="' . $txt[507] . '" onclick="return submitThisOnce(this);" accesskey="p" />
			</div>
			</div>
			<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
		</form>';
}

?>