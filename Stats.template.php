<?php
// Version: 1.1; Stats

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
		', theme_linktree(), '

<div class="border">
<h3 class="title">', $context['page_title'], '</h3>
<h4 class="subtitle">', $txt['smf_stats_2'], '</h4>
<div class="row1">
<div class="left">
<ul class="bullit-list">
<li>', $txt[488], ': ', $context['show_member_list'] ? '<a href="' . $scripturl . '?action=mlist">' . $context['num_members'] . '</a>' : $context['num_members'], '</li>
<li>', $txt[489], ': ', $context['num_posts'], '</li>
<li>', $txt[490], ': ', $context['num_topics'], '</li>
<li>', $txt[658], ': ', $context['num_categories'], '</li>
<li>', $txt['users_online'], ': ', $context['users_online'], '</li>
<li>', $txt[888], ': ', $context['most_members_online']['number'], ' - ', $context['most_members_online']['date'], '</li>
<li>', $txt['users_online_today'], ': ', $context['online_today'], '</li>';

if (!empty($modSettings['hitStats']))
		echo '
<li>', $txt['num_hits'], ': ', $context['num_hits'], '</li>';
	
echo '
</ul>
</div>
<div class="right">
<ul class="bullit-list">
<li>', $txt['average_members'], ': ', $context['average_members'], '</li>
<li>', $txt['average_posts'], ': ', $context['average_posts'], '</li>
<li>', $txt['average_topics'], ': ', $context['average_topics'], '</li>
<li>', $txt[665], ': ', $context['num_boards'], '</li>
<li>', $txt[656], ': ', $context['common_stats']['latest_member']['link'], '</li>
<li>', $txt['average_online'], ': ', $context['average_online'], '</li>
<li>', $txt['gender_ratio'], ': ', $context['gender']['ratio'], '</li>';

if (!empty($modSettings['hitStats']))
		echo '
<li>', $txt['average_hits'], ': ', $context['average_hits'], '</li>';
	
echo '
</ul>
</div>
</div>

<div class="stats-left">
<h4 class="subtitle">', $txt['smf_stats_3'], '</h4>
<div class="row2">
<ul class="bullit-list-stats">';
	foreach ($context['top_posters'] as $poster)
		echo '<li><span class="float-r"> ', $poster['num_posts'] > 0 ? '<img src="' . $settings['images_url'] . '/bar-left.gif" width="2" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar.gif" width="' . $poster['post_percent'] . '" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar-right.gif" width="2" height="7" alt="" border="0" />' : '&nbsp;', '</span>', $poster['link'], ' &nbsp;', $poster['num_posts'], '</li>';
echo '</ul>
</div>
</div>

<div class="stats-right">	
<h4 class="subtitle">', $txt['smf_stats_4'], '</h4>
<div class="row2">
<ul class="bullit-list-stats">';
foreach ($context['top_boards'] as $board)
echo '<li><span class="float-r">', $board['num_posts'] > 0 ? '<img src="' . $settings['images_url'] . '/bar-left.gif" width="2" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar.gif" width="' . $board['post_percent'] . '" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar-right.gif" width="2" height="7" alt="" border="0" />' : '&nbsp;', '</span>', $board['link'], ' &nbsp;', $board['num_posts'], '</li>';
echo '
</ul>
</div>
</div>

<div class="clear"><!--no content--></div>
				
<div class="stats-left">
<h4 class="subtitle">', $txt['smf_stats_11'], '</h4>
<div class="row2">
<ul class="bullit-list-stats">';
foreach ($context['top_topics_replies'] as $topic)
echo '
<li>
<span class="float-r">
', $topic['num_replies'] > 0 ? '<img src="' . $settings['images_url'] . '/bar-left.gif" width="2" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar.gif" width="' . $topic['post_percent'] . '" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar-right.gif" width="2" height="7" alt="" border="0" />' : '&nbsp;', '
</span>
', $topic['link'], ' &nbsp;', $topic['num_replies'], '
</li>';
echo '</ul>
</div>
</div>
				
<div class="stats-right">
<h4 class="subtitle">', $txt['smf_stats_12'], '</h4>
<div class="row2">
<ul class="bullit-list-stats">';
foreach ($context['top_topics_views'] as $topic)
echo '
<li>
<span class="float-r">
', $topic['num_views'] > 0 ? '<img src="' . $settings['images_url'] . '/bar-left.gif" width="2" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar.gif" width="' . $topic['post_percent'] . '" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar-right.gif" width="2" height="7" alt="" border="0" />' : '&nbsp;', '
</span>
', $topic['link'], ' &nbsp;', $topic['num_views'], '
</li>';
echo '
</ul>
</div>
</div>

<div class="clear"><!--no content--></div>
				
<div class="stats-left">
<h4 class="subtitle">', $txt['smf_stats_15'], '</h4>
<div class="row2">
<ul class="bullit-list-stats">';
foreach ($context['top_starters'] as $poster)
echo '
<li>
<span class="float-r">
', $poster['num_topics'] > 0 ? '<img src="' . $settings['images_url'] . '/bar-left.gif" width="2" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar.gif" width="' . $poster['post_percent'] . '" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar-right.gif" width="2" height="7" alt="" border="0" />' : '&nbsp;', '
</span>
', $poster['link'], ' &nbsp;', $poster['num_topics'], '
</li>';
echo '
</ul>
</div>
</div>
				
<div class="stats-right">
<h4 class="subtitle">', $txt['smf_stats_16'], '</h4>
<div class="row2">
<ul class="bullit-list-stats">';
foreach ($context['top_time_online'] as $poster)
echo '
<li><span class="float-r">
', $poster['time_online'] > 0 ? '<img src="' . $settings['images_url'] . '/bar-left.gif" width="2" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar.gif" width="' . $poster['time_percent'] . '" height="7" alt="" border="0" /><img src="' . $settings['images_url'] . '/bar-right.gif" width="2" height="7" alt="" border="0" />' : '&nbsp;', '
</span>
', $poster['link'], ' &nbsp;', $poster['time_online'], '
</li>';
echo '
</ul>
</div>
</div>

<div class="clear"><!--no content--></div>
<div class="block-foot"><!--no content--></div>
</div>
<br />
				
				
				
			<div class="border">
				<h5 class="title">', $txt['smf_stats_5'], '</h5>
				<div class="row1">
				';

		if (!empty($context['monthly']))
	{
		echo '
					<table border="0" width="100%" cellspacing="1" cellpadding="4" class="tborder" style="margin-bottom: 1ex;" id="stats">
						<tr class="titlebg" valign="middle" align="center">
							<td width="25%">', $txt['smf_stats_13'], '</td>
							<td width="15%">', $txt['smf_stats_7'], '</td>
							<td width="15%">', $txt['smf_stats_8'], '</td>
							<td width="15%">', $txt['smf_stats_9'], '</td>
							<td width="15%">', $txt['smf_stats_14'], '</td>';
		if (!empty($modSettings['hitStats']))
			echo '
							<td>', $txt['smf_stats_10'], '</td>';
		echo '
						</tr>';

		foreach ($context['monthly'] as $month)
		{
			echo '
						<tr class="windowbg2" valign="middle" id="tr_', $month['id'], '">
							<th align="left" width="25%">
								<a name="', $month['id'], '" id="link_', $month['id'], '" href="', $month['href'], '" onclick="return doingExpandCollapse || expand_collapse(\'', $month['id'], '\', ', $month['num_days'], ');"><img src="', $settings['images_url'], '/', $month['expanded'] ? 'collapse.gif' : 'expand.gif', '" alt="" border="0" id="img_', $month['id'], '" /> ', $month['month'], ' ', $month['year'], '</a>
							</th>
							<th align="center" width="15%">', $month['new_topics'], '</th>
							<th align="center" width="15%">', $month['new_posts'], '</th>
							<th align="center" width="15%">', $month['new_members'], '</th>
							<th align="center" width="15%">', $month['most_members_online'], '</th>';
			if (!empty($modSettings['hitStats']))
				echo '
							<th align="center">', $month['hits'], '</th>';
			echo '
						</tr>';

			if ($month['expanded'])
			{
				foreach ($month['days'] as $day)
				{
					echo '
						<tr class="windowbg2" valign="middle" align="left">
							<td align="left" style="padding-left: 3ex;">', $day['year'], '-', $day['month'], '-', $day['day'], '</td>
							<td align="center">', $day['new_topics'], '</td>
							<td align="center">', $day['new_posts'], '</td>
							<td align="center">', $day['new_members'], '</td>
							<td align="center">', $day['most_members_online'], '</td>';
					if (!empty($modSettings['hitStats']))
						echo '
							<td align="center">', $day['hits'], '</td>';
					echo '
						</tr>';
				}
			}
		}
		echo '
					</table>';
	}

	echo '
				</div>
			<div class="block-foot"><!---no content--></div>
		</div>
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
			var doingExpandCollapse = false;

			function expand_collapse(curId, numDays)
			{
				if (window.XMLHttpRequest)
				{
					if (document.getElementById("img_" + curId).src.indexOf("expand") > 0)
					{
						if (typeof window.ajax_indicator == "function")
							ajax_indicator(true);
						getXMLDocument(smf_scripturl + "?action=stats;expand=" + curId + ";xml", onDocReceived);
						doingExpandCollapse = true;
					}
					else
					{
						var myTable = document.getElementById("stats"), i;
						var start = document.getElementById("tr_" + curId).rowIndex + 1;
						for (i = 0; i < numDays; i++)
							myTable.deleteRow(start);
						// Adjust the image and link.
						document.getElementById("img_" + curId).src = smf_images_url + "/expand.gif";
						document.getElementById("link_" + curId).href = smf_scripturl + "?action=stats;expand=" + curId + "#" + curId;
						// Modify the session variables.
						getXMLDocument(smf_scripturl + "?action=stats;collapse=" + curId + ";xml");
					}
					return false;
				}
				else
					return true;
			}
			function onDocReceived(XMLDoc)
			{
				var numMonths = XMLDoc.getElementsByTagName("month").length, i, j, k, numDays, curDay, start;
				var myTable = document.getElementById("stats"), curId, myRow, myCell, myData;
				var dataCells = [
					"date",
					"new_topics",
					"new_posts",
					"new_members",
					"most_members_online"
				];

				if (numMonths > 0 && XMLDoc.getElementsByTagName("month")[0].getElementsByTagName("day").length > 0 && XMLDoc.getElementsByTagName("month")[0].getElementsByTagName("day")[0].getAttribute("hits") != null)
					dataCells[5] = "hits";

				for (i = 0; i < numMonths; i++)
				{
					numDays = XMLDoc.getElementsByTagName("month")[i].getElementsByTagName("day").length;
					curId = XMLDoc.getElementsByTagName("month")[i].getAttribute("id");
					start = document.getElementById("tr_" + curId).rowIndex + 1;
					for (j = 0; j < numDays; j++)
					{
						curDay = XMLDoc.getElementsByTagName("month")[i].getElementsByTagName("day")[j];
						myRow = myTable.insertRow(start + j);
						myRow.className = "windowbg2";

						for (k in dataCells)
						{
							myCell = myRow.insertCell(-1);
							if (dataCells[k] == "date")
								myCell.style.paddingLeft = "3ex";
							else
								myCell.style.textAlign = "center";
							myData = document.createTextNode(curDay.getAttribute(dataCells[k]));
							myCell.appendChild(myData);
						}
					}
					// Adjust the arrow to point downwards.
					document.getElementById("img_" + curId).src = smf_images_url + "/collapse.gif";
					// Adjust the link to collapse instead of expand
					document.getElementById("link_" + curId).href = smf_scripturl + "?action=stats;collapse=" + curId + "#" + curId;
				}

				doingExpandCollapse = false;
				if (typeof window.ajax_indicator == "function")
					ajax_indicator(false);
			}
		// ]]></script>';
}

?>