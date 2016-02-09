<?php

	// Copyright (C) 2014-2015 Jacob Barkdull
	//
	//	This program is free software: you can redistribute it and/or modify
	//	it under the terms of the GNU Affero General Public License as
	//	published by the Free Software Foundation, either version 3 of the
	//	License, or (at your option) any later version.
	//
	//	This program is distributed in the hope that it will be useful,
	//	but WITHOUT ANY WARRANTY; without even the implied warranty of
	//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	//	GNU Affero General Public License for more details.
	//
	//	You should have received a copy of the GNU Affero General Public License
	//	along with this program.  If not, see <http://www.gnu.org/licenses/>.


	// Display source code
	if (isset($_GET['source']) and basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
		header('Content-type: text/plain; charset=UTF-8');
		exit(file_get_contents(basename(__FILE__)));
	}

	// Tell browser output is JavaScript
	header ('Content-Type: application/javascript');

	// Disable browser cache
	header ('Expires: Wed, 08 May 1991 12:00:00 GMT');
	header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . ' GMT');
	header ('Cache-Control: no-store, no-cache, must-revalidate');
	header ('Cache-Control: post-check=0, pre-check=0', false);
	header ('Pragma: no-cache');

?>
// Copyright (C) 2014-2015 Jacob Barkdull
//
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU Affero General Public License as
//	published by the Free Software Foundation, either version 3 of the
//	License, or (at your option) any later version.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU Affero General Public License for more details.
//
//	You should have received a copy of the GNU Affero General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
//--------------------
//
// Source Code and Installation Instructions:
//	http://<?php echo $domain . $_SERVER['PHP_SELF'] . "?source\n"; ?>


// Default form settings
if (rows	== undefined) { var rows	=  '<?php echo $rows; ?>'; }
if (name_on	== undefined) { var name_on	=  'yes'; }
if (email_on	== undefined) { var email_on	=  'yes'; }
if (sites_on	== undefined) { var sites_on	=  'yes'; }
if (passwd_on	== undefined) { var passwd_on	=  'yes'; }

// Add comment stylesheet to page header
var head = document.getElementsByTagName('head')[0];
var links = document.getElementsByTagName('link');

if (document.querySelector('link[href="/hashover/comments.css"]') == null) {
	link = document.createElement('link');
	link.rel = 'stylesheet';
	link.href = '<?php echo $root_dir; ?>comments.css';
	link.type = 'text/css';
	head.appendChild(link);
}

// Add comment RSS feed to page header
link = document.createElement('link');
link.rel = 'alternate';
link.href = '/hashover.php?rss=' + location.href.replace(/#.*$/g, '') + "&title=<?php echo (isset($_GET['pagetitle'])) ? $_GET['pagetitle'] . '"' : '" + document.title'; ?>;
link.type = 'application/rss+xml';
link.title = 'Comments';
head.appendChild(link);

// Put number of comments into "cmtcount" identified HTML element
if (document.getElementById('cmtcount') != null) {
	if (<?php echo $total_count - 1; ?> != 0) {
		document.getElementById('cmtcount').innerHTML = '<?php echo $total_count - 1; ?>';
	}
}

// Displays reply form
function reply(r, f) {
	var reply_form = '\n<b class="cmtfont"><?php echo $text['reply_to_cmt']; ?></b>\n\
	<span<?php echo (isset($_COOKIE['name']) and !empty($_COOKIE['name'])) ? ' style="max-height: 0px;"' : ''; ?> class="options" id="options-'+r+'"><hr style="clear: both;">\n\
	<table width="100%" cellpadding="0" cellspacing="0" align="center">\n\
	<tbody>\n<tr>\n';

	if (name_on == 'yes') {
		reply_form += '<td width="1%" rowspan="2">\n<?php
		if (isset($_COOKIE['name']) and preg_match('/^@([a-zA-Z0-9_@]{1,29}$)/', $_COOKIE['name'])) {
			echo '<img align="left" width="34" height="34" src="' . $root_dir . 'scripts/avatars.php?username=' . $_COOKIE['name'] . '&email=' . md5(strtolower(trim($_COOKIE['email']))) . '">';
		} else {
			echo '<img align="left" width="34" height="34" src="';
			echo (isset($_COOKIE['email'])) ? 'http://gravatar.com/avatar/' . md5(strtolower(trim($_COOKIE['email']))) . '?d=http://' . $domain . $root_dir . 'images/avatar.png&s=34&r=pg">' : $root_dir . 'images/avatar.png">';
		}
		?>\n</td>\n';
	}

	if (name_on == 'yes') {
		reply_form += '<td align="right">\n<input type="text" name="name" title="<?php echo $text['nickname_tip']; ?>" value="<?php echo (isset($_COOKIE['name'])) ? $_COOKIE['name'] : $text['nickname']; ?>" maxlength="30" class="opt-name" onFocus="this.value=(this.value == \'<?php echo $text['nickname']; ?>\') ? \'\' : this.value;" onBlur="this.value=(this.value == \'\') ? \'<?php echo $text['nickname']; ?>\' : this.value;">\n</td>\n';
	}

	reply_form += '</tr>\n\
	</tbody>\n</table>\n</span>\n\
	<textarea rows="6" cols="62" name="comment" onFocus="this.value=(this.value==\'<?php echo $text['reply_form']; ?>\') ? \'\' : this.value;" onBlur="this.value=(this.value==\'\') ? \'<?php echo $text['reply_form']; ?>\' : this.value;" style="width: 100%;" title="<?php echo $text['cmt_tip']; ?>"><?php echo $text['reply_form']; ?></textarea><br>\n\
	<input class="post_cmt" type="submit" value="<?php echo $text['post_reply']; ?>">\n\
  <input type="button" value="<?php echo $text['cancel']; ?>" onClick="cancelform(\''+r+'\'); return false;">\n\<?php
	echo (isset($_GET['canon_url']) or isset($canon_url)) ? "\n\t" . '<input type="hidden" name="canon_url" value="' . $page_url . '">\n\\' . PHP_EOL : PHP_EOL; ?>
	<input type="hidden" name="cmtfile" value="' + f + '">\n\
	<input type="hidden" name="reply_to" value="'+f+'">\n';

	document.getElementById('cmtopts-' + r).style.display = 'none';
	document.getElementById('cmtforms-' + r).innerHTML = reply_form;
	return false;
}

// Function to cancel reply and edit forms
function cancelform(f) {
	document.getElementById('cmtopts-' + f).style.display = '';
	document.getElementById('cmtforms-' + f).innerHTML = '';
	return false;
}

// Get page title
if (document.title != '') {
	var pagetitle = ' on "'+document.title+'"';
} else {
	var pagetitle = '';
}

var show_cmt = '';

function parse_template(object, sort, method) {
	var indent = (sort == false || method == 'ascending') ? object['indent'] : '16px 0px 12px 0px';

	if (!object['deletion_notice']) {
		var 
			permalink = object['permalink'],
			cmtclass = (sort == false || method == 'ascending') ? object['cmtclass'] : 'cmtdiv',
			avatar = object['avatar'],
			name = object['name'],
			thread = (object['thread']) ? object['thread'] : '',
			date = object['date'],
			likes = (object['likes']) ? object['likes'] : '',
			like_link = (object['like_link']) ? object['like_link'] : '',
			edit_link = (object['edit_link']) ? object['edit_link'] : '',
			reply_link = object['reply_link'],
			comment = object['comment'],
			form = '',
			cmtopts_style = ''
		;

<?php
		// Load HTML template
		$html_template = explode("\n", file_get_contents('html-templates/' . $template . '.html'));

		for ($line = 0; $line != count($html_template) - 1; $line++) {
			echo "\t\t" . 'show_cmt += \'' . trim($html_template[$line]) . '\n\';' . PHP_EOL;
		}
?>
	} else {
		show_cmt += '<a name="' + object['permalink'] + '"></a>\n';
		show_cmt += '<div style="margin: ' + indent + '; clear: both;" class="' + object['cmtclass'] + '">\n';
		show_cmt += object['deletion_notice'] + '\n';
		show_cmt += '</div>\n';
	}
}

function sort_comments(method) {
	var methods = {
		ascending: function() {
			for (var comment in comments) {
				parse_template(comments[comment], true, method);
			}
		},

		descending: function() {
			for (var comment = (comments.length - 1); comment >= 0; comment--) {
				parse_template(comments[comment], true, method);
			}
		},

		byname: function() {
			var tmpSortArray = comments.slice(0).sort(function(a, b) {
				if(a.sort_name < b.sort_name) return -1;
				if(a.sort_name > b.sort_name) return 1;
			})

			for (var comment in tmpSortArray) {
				parse_template(tmpSortArray[comment], true, method);
			}
		},

		bydate: function() {
			var tmpSortArray = comments.slice(0).sort(function(a, b) {
				return b.sort_date - a.sort_date;
			})

			for (var comment in tmpSortArray) {
				parse_template(tmpSortArray[comment], true, method);
			}
		},

		bylikes: function() {
			var tmpSortArray = comments.slice(0).sort(function(a, b) {
				return b.sort_likes - a.sort_likes;
			})

			for (var comment in tmpSortArray) {
				parse_template(tmpSortArray[comment], true, method);
			}
		}
	}

	show_cmt = '';
	document.getElementById('sort_div').innerHTML = 'Loading...' + '\n';
	methods[method]();
	document.getElementById('sort_div').innerHTML = show_cmt + '\n';
}
<?php

	if ($page_title == 'yes') {
		$js_title = "'+pagetitle+'";
		$js_title = (isset($_GET['pagetitle'])) ? ' on "' . $_GET['pagetitle'] . '"' : $js_title;
	}

	echo '// Place "hashover" DIV' . PHP_EOL;
	echo 'if (document.getElementById("hashover") == null) {' . PHP_EOL;
	echo "\t" . 'document.write("<div id=\"hashover\"></div>\n");' . PHP_EOL;
	echo '}' . PHP_EOL . PHP_EOL;

//	echo jsAddSlashes('<a name="comments"></a><br><b class="cmtfont">' . $text['post_cmt'] . ':</b>');

	if (isset($_COOKIE['message']) and !empty($_COOKIE['message'])) {
		echo jsAddSlashes('<b id="message" class="cmtfont">' . $_COOKIE['message'] . '</b><br><br>\n');
	} else {
		echo jsAddSlashes('<br><br>\n');
	}

	echo jsAddSlashes('<form id="comment_form" name="comment_form" action="/hashover.php" method="post">\n');
/*	echo jsAddSlashes('<span class="cmtnumber">');

	if (isset($_COOKIE['name']) and preg_match('/^@([a-zA-Z0-9_@]{1,29}$)/', $_COOKIE['name'])) {
		echo "\t" . jsAddSlashes('<img align="left" width="' . $icon_size . '" height="' . $icon_size . '" src="' . $script = $root_dir . 'scripts/avatars.php?username=' . $_COOKIE['name'] . '&email=' . md5(strtolower(trim($_COOKIE['email']))) . '">');
	} else {
		echo "\t" . jsAddSlashes('<img align="left" width="' . $icon_size . '" height="' . $icon_size . '" src="' . $script = (isset($_COOKIE['email'])) ? 'http://gravatar.com/avatar/' . md5(strtolower(trim($_COOKIE['email']))) . '?d=http://' . $domain . $root_dir . 'images/avatar.png&s=' . $icon_size . '&r=pg">\n' : $root_dir . 'images/avatar.png">');
	}

	echo jsAddSlashes('</span>\n');*/
	echo jsAddSlashes('<div class="cmtbox" align="center">\n');
	echo jsAddSlashes('<table width="100%" cellpadding="0" cellspacing="0">\n<tbody>\n<tr>\n');

	// Display name input tag if told to
	echo "if (name_on == 'yes') {\n";
	echo "\t" . jsAddSlashes('<td align="right">\n');
	echo "\t" . jsAddSlashes('<input type="text" name="name" title="' . $text['nickname_tip'] . '" maxlength="30" class="opt-name" onFocus="this.value=(this.value == \'' . $text['nickname'] . '\') ? \'\' : this.value;" onBlur="this.value=(this.value == \'\') ? \'' . $text['nickname'] . '\' : this.value;" value="' . $script = (isset($_COOKIE['name'])) ? $_COOKIE['name'] . '">\n' : $text['nickname'] . '">\n');
	echo "\t" . jsAddSlashes('</td>\n');
	echo "}\n\n";

	echo jsAddSlashes('</tr>\n</tbody>\n</table>\n') . PHP_EOL;

	echo jsAddSlashes('<div id="requiredFields" style="display: none;">\n');
	echo jsAddSlashes('<input type="text" name="summary" value="" placeholder="Summary">\n');
	echo jsAddSlashes('<input type="hidden" name="middlename" value="" placeholder="Middle Name">\n');
	echo jsAddSlashes('<input type="text" name="lastname" value="" placeholder="Last Name">\n');
	echo jsAddSlashes('<input type="text" name="address" value="" placeholder="Address">\n');
	echo jsAddSlashes('<input type="hidden" name="zip" value="" placeholder="Last Name">\n');
	echo jsAddSlashes('</div>\n') . PHP_EOL;

	$rows = "'+rows+'";
	$replyborder = (isset($_COOKIE['success']) and $_COOKIE['success'] == "no") ? ' border: 2px solid #FF0000 !important; -moz-border-radius: 5px 5px 0px 0px; border-radius: 5px 5px 0px 0px;' : '';

	echo jsAddSlashes('<textarea rows="' . $rows . '" cols="63" name="comment" onFocus="this.value=(this.value==\'' . $text['comment_form'] . '\') ? \'\' : this.value;" onBlur="this.value=(this.value==\'\') ? \'' . $text['comment_form'] . '\' : this.value;" style="width: 100%;' . $replyborder . '" title="' . $text['cmt_tip'] . '">' . $text['comment_form'] . '</textarea><br>\n');
	echo jsAddSlashes('<input type="submit" value="' . $text['post_button'] . '"><br>\n');
	echo (isset($_GET['canon_url']) or isset($canon_url)) ? jsAddSlashes('<input type="hidden" name="canon_url" value="' . $page_url . '">\n') : '';
	echo (isset($_COOKIE['replied'])) ? jsAddSlashes('<input type="hidden" name="reply_to" value="' . $_COOKIE['replied'] . '">\n') : '';
	echo jsAddSlashes('</div>\n</form><br>\n'). PHP_EOL;

	// Display three most popular comments
	if (!empty($top_likes)) {
		echo jsAddSlashes('<br><b class="cmtfont">' . $text['popular_cmts'] . ' Comment' . ((count($top_likes) != '1') ? 's' : '') . ':</b>\n') . PHP_EOL;
		echo 'var popComments = [' . PHP_EOL;

		for ($p = 1; $p <= count($top_likes) and $p <= $top_cmts; $p++) {
			if (!empty($top_likes)) {
				echo parse_comments(array_shift($top_likes), '', 'no');
			}
		}

		echo '];' . PHP_EOL . PHP_EOL;
		echo 'for (var comment in popComments) {' . PHP_EOL;
		echo "\t" . 'parse_template(popComments[comment], false);' . PHP_EOL;
		echo '}' . PHP_EOL . PHP_EOL;
	}

	if (!empty($show_cmt)) {
		echo 'var comments = [' . PHP_EOL;
		echo $show_cmt;
		echo '];' . PHP_EOL . PHP_EOL;
	}

	// Display comment count
	echo jsAddSlashes('<br><b class="cmtfont">' . $text['showing_cmts'] . ' ' . $script = ($cmt_count == "1") ? '0 Comments:</b>\n' : display_count() . ':</b>\n') . PHP_EOL;

	// Display comments, if there are no comments display a note
	if (!empty($show_cmt)) {

		echo jsAddSlashes('<div id="sort_div">\n'). PHP_EOL;
		echo 'for (var comment in comments) {' . PHP_EOL;
		echo "\t" . 'parse_template(comments[comment], false);' . PHP_EOL;
		echo '}' . PHP_EOL . PHP_EOL;
		echo jsAddSlashes('</div>\n') . PHP_EOL;
	} else {
		//echo jsAddSlashes('<div style="margin: 16px 0px 12px 0px;" class="cmtdiv">\n');
		//echo jsAddSlashes('<span class="cmtnumber"><img width="' . $icon_size . '" height="' . $icon_size . '" src="/hashover/images/first-comment.png"></span>\n');
		//echo jsAddSlashes('<div style="height: ' . $icon_size . 'px;" class="cmtbubble">\n');
		//echo jsAddSlashes('<b class="cmtnote cmtfont" style="color: #000000;">Be the first to comment!</b>\n</div>');
	}

	echo jsAddSlashes('</div><br>\n') . PHP_EOL;
	echo jsAddSlashes('<center>\n');
	echo jsAddSlashes('<a href="http://tildehash.com" target="_blank">HashOver Comments</a>\n');
	//if (!empty($show_cmt)) echo jsAddSlashes('<a href="http://' . $domain . '/hashover.php?rss=' . $page_url . '" target="_blank">RSS Feed</a> &middot;\n');
	//echo jsAddSlashes('<a href="http://' . $domain . '/hashover.zip" rel="hashover-source" target="_blank">Source Code</a> &middot;\n');
	//echo jsAddSlashes('<a href="http://' . $domain . '/hashover.php" rel="hashover-javascript" target="_blank">JavaScript</a> &middot;\n');
	//echo jsAddSlashes('<a href="http://tildehash.com/hashover/changelog.txt" target="_blank">ChangeLog</a> &middot;\n');
	//echo jsAddSlashes('<a href="http://tildehash.com/hashover/archives/" target="_blank">Archives</a><br>\n');
	echo jsAddSlashes('</center>\n');

	// Script execution ending time
	$exec_time = explode(' ', microtime());
	$exec_end = $exec_time[1] + $exec_time[0];
	$exec_time = ($exec_end - $exec_start);

	echo PHP_EOL . '// Place all content on page' . PHP_EOL;
	echo 'document.getElementById("hashover").innerHTML = show_cmt;' . PHP_EOL . PHP_EOL;
	echo '// Script Execution Time: ' . round($exec_time, 5) . ' Seconds';

?>
