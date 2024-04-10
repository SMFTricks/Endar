<?php
// Version: 1.1; Search

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		function selectBoards(ids)
		{
			var toggle = true;

			for (i = 0; i < ids.length; i++)
				toggle = toggle & document.searchform["brd" + ids[i]].checked;

			for (i = 0; i < ids.length; i++)
				document.searchform["brd" + ids[i]].checked = !toggle;
		}

		function expandCollapseBoards()
		{
			var current = document.getElementById("searchBoardsExpand").style.display != "none";

			document.getElementById("searchBoardsExpand").style.display = current ? "none" : "";
			document.getElementById("exandBoardsIcon").src = smf_images_url + (current ? "/expand.gif" : "/collapse.gif");
		}
	// ]]></script>
	<form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '" name="searchform" id="searchform">
		', theme_linktree(), '

		<div class="border">
	  <h3 class="title">', $txt[183], '</h3>
			';

	if (!empty($context['search_errors']))
	{
		echo '
			<div class="row1-center" style="color: #990000 ;">
						', implode('<br />', $context['search_errors']['messages']), '
					</div>';
	}


	if ($context['simple_search'])
	{
		echo '<div class="row1-center">
					<strong>', $txt[582], ':</strong><br />
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" /> 
					<input type="submit" class="button" name="submit" value="', $txt[182], '" />';
		if (empty($modSettings['search_simple_fulltext']))
			echo '
					<p>', $txt['search_example'], '</p>';
			echo '<a href="', $scripturl, '?action=search;advanced" onclick="this.href += \';search=\' + escape(document.searchform.search.value);">', $txt['smf298'], '</a>
					<input type="hidden" name="advanced" value="0" />';
	}
	else
	{
		echo '
					<input type="hidden" name="advanced" value="1" />
					<div class="subtitle"><strong>', $txt[582], ':</strong></div>
					<div class="row1">';
					if (empty($modSettings['search_simple_fulltext']))
			echo '
					<p>', $txt['search_example'], '</p>';
			echo '
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" />
					
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
										if (document.forms.searchform.search.value.indexOf("%u") != -1)
											document.forms.searchform.search.value = unescape(document.forms.searchform.search.value);
									}
									window.addEventListener("load", initSearch, false);
								// ]]></script>
					
					<select name="searchtype">
					<option value="1"', empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt[343], '</option>
					<option value="2"', !empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt[344], '</option>
					</select>
					</div>
					<div class="row1">
					<div class="left">
					<strong>', $txt[583], ':</strong><br />
					<input type="text" name="userspec" value="', empty($context['search_params']['userspec']) ? '*' : $context['search_params']['userspec'], '" size="40" />
					</div>
					<div class="right">
					<strong>', $txt['search_order'], ':</strong><br />
								<select name="sort">
									<option value="relevance|desc">', $txt['search_orderby_relevant_first'], '</option>
									<option value="numReplies|desc">', $txt['search_orderby_large_first'], '</option>
									<option value="numReplies|asc">', $txt['search_orderby_small_first'], '</option>
									<option value="ID_MSG|desc">', $txt['search_orderby_recent_first'], '</option>
									<option value="ID_MSG|asc">', $txt['search_orderby_old_first'], '</option>
								</select>
					</div>
					</div>
					
					<div class="row1">
					 <div class="left">
					 <strong>', $txt['search_post_age'], ': </strong><br />
					 ', $txt['search_between'], ' <input type="text" name="minage" value="', empty($context['search_params']['minage']) ? '0' : $context['search_params']['minage'], '" size="5" maxlength="5" /> ', $txt['search_and'], ' <input type="text" name="maxage" value="', empty($context['search_params']['maxage']) ? '9999' : $context['search_params']['maxage'], '" size="5" maxlength="5" /> ', $txt[579], '.
					 </div>
							<div class="right">
					 <strong>', $txt['search_options'], ':</strong><br />
					 <label for="show_complete"><input type="checkbox" name="show_complete" id="show_complete" value="1"', !empty($context['search_params']['show_complete']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['search_show_complete_messages'], '</label><br />
								<label for="subject_only"><input type="checkbox" name="subject_only" id="subject_only" value="1"', !empty($context['search_params']['subject_only']) ? ' checked="checked"' : '', ' class="check" /> ', $txt['search_subject_only'], '</label>
					 </div>';

		// If $context['search_params']['topic'] is set, that means we're searching just one topic.
		if (!empty($context['search_params']['topic']))
			echo '
					<div class="row1"><p>', $txt['search_specific_topic'], ' "', $context['search_topic']['link'], '".<p>
					<input type="hidden" name="topic" value="', $context['search_topic']['id'], '" />
				</div>';
		else
		{
			echo '</div>
					<h4 class="subtitle">
					<a href="javascript:void(0);" onclick="expandCollapseBoards(); return false;"><img src="', $settings['images_url'], '/expand.gif" id="exandBoardsIcon" class="subtitle-collapse-icon" alt="" border="0" /></a>
					<a href="javascript:void(0);" onclick="expandCollapseBoards(); return false;">', $txt[189], '</a>
					</h4>
				<div class="row1">
					<div id="searchBoardsExpand" style="display: none;">';

			$alternate = true;
			foreach ($context['board_columns'] as $board)
			{
				if ($alternate)
					echo '';
				echo '
							';

				if (!empty($board) && empty($board['child_ids']))
					echo '
								<div class="float-l" style="width:40%;"> <label for="brd', $board['id'], '" style="margin-left: ', $board['child_level'], 'ex;"><input type="checkbox" id="brd', $board['id'], '" name="brd[', $board['id'], ']" value="', $board['id'], '"', $board['selected'] ? ' checked="checked"' : '', ' class="check" />
								', $board['name'], '</label></div>';
				elseif (!empty($board))
					echo '<div class="float-l" style="width:40%;"><a href="javascript:void(0);" onclick="selectBoards([', implode(', ', $board['child_ids']), ']); return false;" style="text-decoration: underline;">', $board['name'], '</a></div>';

				echo '';
				if (!$alternate)
					echo '';

				$alternate = !$alternate;
			}

			echo '
					</div>
					<div class="row" style="clear:both;">
					<input type="checkbox" name="all" id="check_all" value="" checked="checked" onclick="invertAll(this, this.form, \'brd\');" class="check" /><em> <label for="check_all">', $txt[737], '</label></em></div>';
		}

		echo '
					</div>

					<div class="subtitle" style="text-align:center;"><input type="submit" class="button" name="submit" value="', $txt[182], '" /></div>';
	}
if ($context['simple_search'])
	{
		echo '
					</div>';
	}
	echo '
				
			</div>
	</form>';








}
function template_results()
{
	global $context, $settings, $options, $txt, $scripturl;

	if (isset($context['did_you_mean']) || empty($context['topics']))
	{
		echo '
<div class="border">
	<h2 class="title">', $txt['search_adjust_query'], '</h2>
	<div class="row1">';

		// Did they make any typos or mistakes, perhaps?
		if (isset($context['did_you_mean']))
			echo '
			<p>', $txt['search_did_you_mean'], ' <a href="', $scripturl, '?action=search2;params=', $context['did_you_mean_params'], '">', $context['did_you_mean'], '</a>.<br /></p>';

		echo '
			<form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '" style="margin: 0;">
				<strong>', $txt[582], ':</strong><br />
				<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" />
						<input type="submit" name="submit" value="', $txt['search_adjust_submit'], '" />

						<input type="hidden" name="searchtype" value="', !empty($context['search_params']['searchtype']) ? $context['search_params']['searchtype'] : 0, '" />
						<input type="hidden" name="userspec" value="', !empty($context['search_params']['userspec']) ? $context['search_params']['userspec'] : '', '" />
						<input type="hidden" name="show_complete" value="', !empty($context['search_params']['show_complete']) ? 1 : 0, '" />
						<input type="hidden" name="subject_only" value="', !empty($context['search_params']['subject_only']) ? 1 : 0, '" />
						<input type="hidden" name="minage" value="', !empty($context['search_params']['minage']) ? $context['search_params']['minage'] : '0', '" />
						<input type="hidden" name="maxage" value="', !empty($context['search_params']['maxage']) ? $context['search_params']['maxage'] : '9999', '" />
						<input type="hidden" name="sort" value="', !empty($context['search_params']['sort']) ? $context['search_params']['sort'] : 'relevance', '" />';

		if (!empty($context['search_params']['brd']))
			foreach ($context['search_params']['brd'] as $board_id)
				echo '
				<input type="hidden" name="brd[', $board_id, ']" value="', $board_id, '" />';

		echo '
			</form>
		</div>
	<div class="block-foot"><!--no content--></div>
</div>';
	}

	if ($context['compact'])
	{
		echo '
', theme_linktree(), '';

// Quick moderation set to checkboxes?  Oh, how fun :/.
		if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1)
			echo '
<form action="', $scripturl, '?action=quickmod" method="post" accept-charset="', $context['character_set'], '" name="topicForm" style="margin: 0;">';
echo '
<div class="border">
		<h3 class="title">';
	  if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1)
				echo '<span class="float-r"><input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');" class="check" /></span>';
			echo '<strong>', $txt[139], ':</strong> ', $context['page_index'], '
		</h3>';

		if (!empty($context['topics']))
		{
			echo '
		<dl>';
		}
		else
			echo '
		<div class="row1"><strong><p>', $txt['search_no_results'], '</p></strong></div>';
	  

		while ($topic = $context['get_topics']())
		{
			echo '
	<dt>';
	
	 if (!empty($options['display_quick_mod']))
			{
				echo '';
				if ($options['display_quick_mod'] == 1)
					echo '
			<span class="float-r"><input type="checkbox" name="topics[]" value="', $topic['id'], '" class="check" />';
				else
				{
					if ($topic['quick_mod']['remove'])
						echo '<span class="float-r-quickmod"><a href="', $scripturl, '?action=quickmod;actions[', $topic['id'], ']=remove;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_remove.gif" width="16" alt="', $txt[63], '" title="', $txt[63], '" border="0" /></a>';
					if ($topic['quick_mod']['lock'])
						echo '<a href="', $scripturl, '?action=quickmod;actions[', $topic['id'], ']=lock;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_lock.gif" width="16" alt="', $txt['smf279'], '" title="', $txt['smf279'], '" border="0" /></a>';
					if ($topic['quick_mod']['lock'] || $topic['quick_mod']['remove'])
						echo '';
					if ($topic['quick_mod']['sticky'])
						echo '<a href="', $scripturl, '?action=quickmod;actions[', $topic['id'], ']=sticky;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_sticky.gif" width="16" alt="', $txt['smf277'], '" title="', $txt['smf277'], '" border="0" /></a>';
					if ($topic['quick_mod']['move'])
						echo '<a href="', $scripturl, '?action=movetopic;topic=', $topic['id'], '.0"><img src="', $settings['images_url'], '/icons/quick_move.gif" width="16" alt="', $txt[132], '" title="', $txt[132], '" border="0" /></a>';
				}
				echo '</span>';
			}
	
	echo '<strong>', $topic['first_post']['link'], '</strong></dt>
	
  <dd>
  <img src="', $settings['images_url'], '/topic/', $topic['class'], '.gif" class="topic-icon" alt="" />
  ', $txt[109], ' ', $topic['first_post']['member']['link'], ' ', $txt['smf88'], ' ', $topic['board']['link'], '. ', $txt['search_relevance'], '  ', $topic['relevance'], '. ', $txt['search_date_posted'], ' ', $topic['first_post']['time'], '.';
  
  foreach ($topic['matches'] as $message)
			{
				echo '
			<br />
			<div class="quoteheader" style="clear:both;margin-top:10px;"><a href="', $scripturl, '?topic=', $topic['id'], '.msg', $message['id'], '#msg', $message['id'], '">', $message['subject_highlighted'], '</a> ', $txt[525], ' ', $message['member']['link'], '</div>';

				if ($message['body_highlighted'] != '')
					echo '
			<div class="quote">', $message['body_highlighted'], '</div>';
			}
		 
		 
  echo '</dd>';
		 } 
echo '
	</dl>';

		echo '
<div class="subtitle">';


if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
		{
			echo '
			<div class="float-r">
			<select name="qaction"', $context['can_move'] ? ' onchange="this.form.moveItTo.disabled = (this.options[this.selectedIndex].value != \'move\');"' : '', '>
						<option value="">--------</option>', $context['can_remove'] ? '
						<option value="remove">' . $txt['quick_mod_remove'] . '</option>' : '', $context['can_lock'] ? '
						<option value="lock">' . $txt['quick_mod_lock'] . '</option>' : '', $context['can_sticky'] ? '
						<option value="sticky">' . $txt['quick_mod_sticky'] . '</option>' : '',	$context['can_move'] ? '
						<option value="move">' . $txt['quick_mod_move'] . ': </option>' : '', $context['can_merge'] ? '
						<option value="merge">' . $txt['quick_mod_merge'] . '</option>' : '', '
						<option value="markread">', $txt['quick_mod_markread'], '</option>
					</select>';

			if ($context['can_move'])
			{
				echo '
							<select id="moveItTo" name="move_to" disabled="disabled">';
				foreach ($context['jump_to'] as $category)
					foreach ($category['boards'] as $board)
					{
						if (!$board['is_current'])
							echo '
								<option value="', $board['id'], '"', !empty($board['selected']) ? ' selected="selected"' : '', '>', str_repeat('-', $board['child_level'] + 1), ' ', $board['name'], '</option>';
					}
				echo '
							</select>';
			}

			echo '
							<input type="hidden" name="redirect_url" value="', $scripturl . '?action=search2;params=' . $context['params'], '" />
							<input type="submit" value="', $txt['quick_mod_go'], '" onclick="return document.topicForm.qaction.value != \'\' && confirm(\'', $txt['quickmod_confirm'], '\');" />
						</div>';
		}


echo'<a name="bot"></a><strong>', $txt[139], ':</strong> ', $context['page_index'], '';

if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
			echo '<div class="clear"><!--no content--></div>';

echo'</div>
				<div class="block-foot"></div>
				</div>';
		
	  if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
			echo '
			<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
		</form>';
	  
	  if ($settings['linktree_inline'])
			echo '
<br />', theme_linktree(), '';
		echo '
			<form action="', $scripturl, '" method="get" name="jumptoForm" accept-charset="', $context['character_set'], '">
				<label for="jumpto">', $txt[160], ':
				<select name="jumpto" id="jumpto" onchange="if (this.selectedIndex > 0 && this.options[this.selectedIndex].value) window.location.href = smf_scripturl + this.options[this.selectedIndex].value.substr(smf_scripturl.indexOf(\'?\') == -1 ? 0 : 1);">
					<option value="">', $txt[251], ':</option>';
		foreach ($context['jump_to'] as $category)
		{
			echo '
					<option value="" disabled="disabled">-----------------------------</option>
					<option value="#', $category['id'], '">', $category['name'], '</option>
					<option value="" disabled="disabled">-----------------------------</option>';
			foreach ($category['boards'] as $board)
				echo '
					<option value="?board=', $board['id'], '.0"> ', str_repeat('==', $board['child_level']), '=> ', $board['name'], '</option>';
		}
		echo '
				</select></label> 
				<input type="button" value="', $txt[161], '" onclick="if (document.jumptoForm.jumpto.options[document.jumptoForm.jumpto.selectedIndex].value) window.location.href = \'', $scripturl, '\' + document.jumptoForm.jumpto.options[document.jumptoForm.jumpto.selectedIndex].value;" />
			</form>
		';
	}
	else
	{
		echo '
			<div class="border">
		 <h2 class="title"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</h2>
		 ', theme_linktree(), '';
		 if (empty($context['topics']))
			echo '
				<div class="row1">
					<p><br /><strong>(', $txt['search_no_results'], ')</strong><br /><br /></p>
				</div>';
		 echo'
		 <div class="block-foot"></div>
		 </div>
			<br />';

		while ($topic = $context['get_topics']())
		{
			foreach ($topic['matches'] as $message)
			{
				// Create buttons row.
				$quote_button = create_button('quote.gif', 145, 145, 'align="middle"');
				$reply_button = create_button('reply_sm.gif', 146, 146, 'align="middle"');
				$notify_button = create_button('notify_sm.gif', 131, 131, 'align="middle"');

				$buttonArray = array();
				if ($topic['can_reply'])
				{
					$buttonArray[] = '<a href="' . $scripturl . '?action=post;topic=' . $topic['id'] . '.' . $message['start'] . '">' . $reply_button . '</a>';
					$buttonArray[] = '<a href="' . $scripturl . '?action=post;topic=' . $topic['id'] . '.0;quote=' . $message['id'] . '/' . $message['start'] . ';sesc=' . $context['session_id'] . '">' . $quote_button . '</a>';
				}
				if ($topic['can_mark_notify'])
					$buttonArray[] = '<a href="' . $scripturl . '?action=notify;topic=' . $topic['id'] . '.' . $message['start'] . '">' . $notify_button . '</a>';
				
			 echo'<div class="border">
			<h4 class="title" style="text-align:right;"><span class="float-l">
		 ', $message['counter'], '&nbsp;', $topic['category']['link'], ' / ', $topic['board']['link'], ' / <a href="', $scripturl, '?topic=', $topic['id'], '.', $message['start'], ';topicseen#msg', $message['id'], '">', $message['subject_highlighted'], '</a></span>', $txt['search_relevance'], ': ', $topic['relevance'], '</h4>
		 <h5 class="subtitle">', $txt[109], ' ', $topic['first_post']['member']['link'], ', ', $txt[72], ' ', $txt[525], ' ', $message['member']['link'], ' ', $txt[30], ': ', $message['time'], '</h5>
		 <div class="row1">
									<div class="post">', $message['body_highlighted'], '</div>
							</div>
							<div class="post-foot">', implode($context['menu_separator'], $buttonArray), '</div>
							<div class="block-foot"><!--no content--></div>
							</div>
							<br />
		 ';  
			
			}
		}

		echo '
			<div class="border">
		 <h2 class="title"><strong>', $txt[139], ':</strong> ', $context['page_index'], '</h2>
		 ', theme_linktree(), '';
		 
		 echo'
		 <div class="block-foot"></div>
		 </div>';
	}
}

?>