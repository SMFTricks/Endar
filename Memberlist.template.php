<?php
// Version: 1.1; Memberlist


// Displays a sortable listing of all members registered on the forum.
function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Show the link tree.
	echo '
		', theme_linktree(), '';

	// Display links to view all/search.
	echo '
	<div class="border">
	<h3 class="title">';

		$links = array();
				foreach ($context['sort_links'] as $link)
					$links[] = ($link['selected'] ? '<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" /> ' : '') . '<a href="' . $scripturl . '?action=mlist' . (!empty($link['action']) ? ';sa=' . $link['action'] : '') . '">' . $link['label'] . '</a>';

				echo '
					', implode(' | ', $links), '';
		
	echo '
	</h3>
	<div class="subtitle">';

	// Display page numbers and the a-z links for sorting by name if not a result of a search.
		if (!isset($context['old_search']))
			echo '
			<span class="float-r">', $txt[139], ': ', $context['page_index'], '</span>
	  ', $context['letter_links'] . '';
	// If this is a result of a search then just show the page numbers.
	else
		echo '
					', $txt[139], ': ', $context['page_index'];

	echo '
				</div>';

	echo '
			<div class="row1">
		 <dl>';

	// Assuming there are members loop through each one displaying their data.
	if (!empty($context['members']))
	{
		foreach ($context['members'] as $member)
			echo '
			<dt style="text-align:right;"', empty($member['sort_letter']) ? '' : ' id="letter' . $member['sort_letter'] . '"', '>
					<span class="float-l">', $context['can_send_pm'] ? '<a href="' . $member['online']['href'] . '" title="' . $member['online']['text'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $member['online']['image_href'] . '" alt="' . $member['online']['text'] . '" border="0" align="middle" />' : $member['online']['label'], $context['can_send_pm'] ? '</a>' : '', '
				', $member['link'], '</span>&nbsp; ', $member['hide_email'] ? '' : '<a href="mailto:' . $member['email'] . '"><img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt[69] . '" title="' . $txt[69] . ' ' . $member['name'] . '" border="0" /></a>', '', $member['website']['url'] != '' ? '<a href="' . $member['website']['url'] . '" target="_blank"><img src="' . $settings['images_url'] . '/www.gif" alt="' . $member['website']['title'] . '" title="' . $member['website']['title'] . '" border="0" /></a>' : '', '', $member['icq']['link'], '', $member['aim']['link'], '', $member['yim']['link'], '', $member['msn']['link'], '
			</dt>
				<dd>
				<p>', empty($member['group']) ? $member['post_group'] : $member['group'], '. ', $txt[233], ' ', $member['registered_date'], '. ', $txt[26], ': ', $member['posts'], ' ', $member['posts'] > 0 ? '<img src="' . $settings['images_url'] . '/bar-left.gif" width="2" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar.gif" width="' . $member['post_percent'] . '" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar-right.gif" width="2" height="7" alt="" border="0" />' : '', '
				</p></dd>';
	}
	// No members?
	else
		echo '
			<dt>', $txt[170], '</dt>
			<dd> ---- </dd>';

	// Show the page numbers again. (makes 'em easier to find!)
	echo '</dl>
	</div>
			<div class="subtitle" style="text-align:right;">';
		 
		 // If it is displaying the result of a search show a "search again" link to edit their criteria.
	if (isset($context['old_search']))
		echo '
		<span class="float-l"><strong><a href="', $scripturl, '?action=mlist;sa=search;search=', $context['old_search_value'], '">', $txt['mlist_search2'], '</a></strong></span>
	  ';
			echo '', $txt[139], ': ', $context['page_index'], '
			</div>
		 <div class="block-foot"><!-- no content --></div>
		 </div>
		 
		 <br />
		 <div class="border">
	  <h4 class="title">Sort list by :</h4>
	  <div class="row1-center">
		 <div class="buttons" style="padding:5px;">';
		 
		 // Display each of the column headers of the table.
	foreach ($context['columns'] as $column)
	{
		// We're not able (through the template) to sort the search results right now...
		if (isset($context['old_search']))
			echo '';
		// This is a selected solumn, so underline it or some such.
		elseif ($column['selected'])
			echo '
				<a href="' . $column['href'] . '">' . $column['label'] . ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" border="0" /></a>';
		// This is just some column... show the link and be done with it.
		else
			echo '
				', $column['link'], '';
	}

	echo'</div>
	</div>
	  <div class="block-foot"><!--no content--></div>
	 </div>';
	 
}





// A page allowing people to search the member list.
function template_search()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Start the submission form for the search!
	echo '
	<form action="', $scripturl, '?action=mlist;sa=search" method="post" accept-charset="', $context['character_set'], '">';

	// Display that link tree...
	echo '', theme_linktree(), '';

	// Display links to view all/search.
	echo '
		<div class="border">
	  <h3 class="title">';
		$links = array();
				foreach ($context['sort_links'] as $link)
					$links[] = ($link['selected'] ? '<img src="' . $settings['images_url'] . '/selected.gif" alt="&gt;" /> ' : '') . '<a href="' . $scripturl . '?action=mlist' . (!empty($link['action']) ? ';sa=' . $link['action'] : '') . '">' . $link['label'] . '</a>';

				echo '
					', implode(' | ', $links), '
				</h3>';

	// Display the input boxes for the form.
	echo '
			<div class="row1">
			<strong>', $txt[582], ':</strong> <input type="text" name="search" value="', $context['old_search'], '" size="35" /> <input type="submit" class="button" name="submit" value="' . $txt[182] . '" style="margin-left: 20px;" />
			</div>
			<div class="row1">
							<div class="left">
								<label for="fields-email"><input type="checkbox" name="fields[]" id="fields-email" value="email" checked="checked" class="check" /> ', $txt['mlist_search_email'], '</label><br />
								<label for="fields-messenger"><input type="checkbox" name="fields[]" id="fields-messenger" value="messenger" class="check" /> ', $txt['mlist_search_messenger'], '</label><br />
								<label for="fields-group"><input type="checkbox" name="fields[]" id="fields-group" value="group" class="check" /> ', $txt['mlist_search_group'], '</label>
							</div>
							<div class="right">
								<label for="fields-name"><input type="checkbox" name="fields[]" id="fields-name" value="name" checked="checked" class="check" /> ', $txt['mlist_search_name'], '</label><br />
								<label for="fields-website"><input type="checkbox" name="fields[]" id="fields-website" value="website" class="check" /> ', $txt['mlist_search_website'], '</label>
							</div>
					 <div class="clear"><!--no content--></div>
						</div>
				  <div class="block-foot"><!--no content--></div>
				  </div>
					
	</form>';
}

?>