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

	// Read comment files, and wrap them in HTML divs
	$array_count = 0;

	function parse_comments($file, $variable, $check) {
		global $mode, $root_dir, $ref_path, $text, $html_template, $icons, $icon_size, $short_dates, $top_likes, $popular, $domain, $indention, $admin_nickname, $admin_password, $script_query;

		// Generate permalink
		$permalink = 'c' . str_replace('-', 'r', basename($file, '.xml'));
		$file_parts = explode('-', basename($file, '.xml'));
		$permatext = end($file_parts);

		// Calculate CSS padding for reply indention
		if (($dashes = substr_count(basename($file), '-')) != '0' and $check == 'yes') {
			$indent = ($dashes >= 1) ? (($icon_size + 4) * $dashes) + 16 : ($icon_size + 20) * $dashes;
		} else {
			$indent = '0';
		}

		if (!isset($_GET['count_link']) or !isset($script_query)) {
			if (($read_cmt = @simplexml_load_file($file)) !== false) {
				$permalink .= ($check == 'yes') ? '' : '_pop';
				if ($read_cmt['likes'] >= $popular) $top_likes["{$read_cmt['likes']}"] = $file;

				$name_at = (preg_match('/^@.*?$/', $read_cmt->name)) ? '@' : '';
				$name_class = (preg_match('/^@.*?$/', $read_cmt->name)) ? ' at' : '';
				$user_login = false;
				$admin_login = false;

				// "Like" cookie
				//$like_cookie = md5($_SERVER['SERVER_NAME'] . $ref_path . '/' . basename($file, '.xml'));

				if (!empty ($_COOKIE['name'])) {
					$admin_cookie = 'hashover-' . strtolower(str_replace(' ', '-', $_COOKIE['name']));

					if (!empty($_COOKIE[$admin_cookie])) {
						if ($_COOKIE[$admin_cookie] == hash('ripemd160', $admin_nickname . md5(encrypt($admin_password)))) {
							$admin_login = true;
						}
					}
				}

				if (!empty($read_cmt->name) and !empty($read_cmt->passwd)) {
					$edit_cookie = 'hashover-' . strtolower(str_replace(' ', '-', $read_cmt->name));

					if (!empty($_COOKIE[$edit_cookie])) {
						if ($_COOKIE[$edit_cookie] == hash('ripemd160', $read_cmt->name . $read_cmt->passwd)) {
							$user_login = true;
						}
					}
				}

				if (empty($read_cmt->website)) {
					if (preg_match('/^@([a-zA-Z0-9_@]{1,29}$)/', $read_cmt->name)) {
						$variable_name = $name_at . '<a id="opt-website-' . $permalink . '" href="http://' . ((!preg_match('/@identica/i', $read_cmt->name)) ? 'twitter.com/' : 'identi.ca/') . str_replace(array('@identica', '@'), '', $read_cmt->name) . '" target="_blank">' . preg_replace('/^@(.*?)$/', '\\1', str_replace('@identica', '<span style="display: none;">@identica</span>', $read_cmt->name)) . '</a>';
					} else {
						$variable_name = preg_replace('/^@(.*?)$/', '\\1', str_replace('@identica', '<span style="display: none;">@identica</span>', $read_cmt->name));
					}
				} else {
					$variable_name = $name_at . '<a id="opt-website-' . $permalink . '" href="' . $read_cmt->website . '" target="_blank">' . preg_replace('/^@(.*?)$/', '\\1', str_replace('@identica', '<span style="display: none;">@identica</span>', $read_cmt->name)) . '</a>';
				}

				// Format date and time
        $str_cmtdate = new DateTime($read_cmt->date);
        $nicestr_cmtdate = 'Le ' . $str_cmtdate->format('d/m/Y') . ' à ' . $str_cmtdate->format('H:i');
        $str_cmtdate = $str_cmtdate->format('c');
        //$str_cmtdate = '';
				if ($short_dates == 'yes') {
					//$get_cmtdate = explode(' - ', $read_cmt->date);
					$make_cmtdate = new DateTime($read_cmt->date);
					$cur_date = new DateTime();
					$interval = $make_cmtdate->diff($cur_date);

					if ($interval->y != '') {
						$cmt_date = 'Il y a ' . $interval->y . ' année';
						$cmt_date .= ($interval->y != '1') ? 's' : '';
					} else if ($interval->m != '') {
						$cmt_date = 'Il y a ' . $interval->m . ' mois';
					} else if ($interval->d != '') {
						$cmt_date = 'Il y a ' . $interval->d . ' jour';
            $cmt_date .= ($interval->d != '1') ? 's' : '';
					} else {
						$cmt_date = $make_cmtdate->format('H:i') . ' aujourd\'hui';
					}
				} else {
					$cmt_date = $read_cmt->date;
				}

				$avatar_icon = '<a href="#' . $permalink . '" title="Permalink">#' . $permatext . '</a>';

        $email_indicator = $read_cmt->name . ' ' . $text['unsubbed_note'] . '" class="no-email"';

				// Add HTML anchor tag to URLs
				$clean_code = preg_replace('/(((ftp|http|https){1}:\/\/)[a-zA-Z0-9-@:%_\+.~#?&\/=]+)([\s]{0,})/i', '<a href="\\1" target="_blank">\\1</a>', $read_cmt->body);

				// Remove repetitive and trailing HTML <br> tags
				$clean_code = preg_replace('/^(<br><br>)/', '', preg_replace('/(<br><br>)$/', '', preg_replace('/(<br>){2,}/i', '<br><br>', $clean_code)));

					// Add keys to comments object
					$variable .= "\t" . '{' . PHP_EOL;
					$variable .= "\t\t" . 'permalink: \'' . $permalink . '\',' . PHP_EOL;
					$variable .= "\t\t" . 'cmtclass: \'' . ((preg_match('/r/', $permalink)) ? 'cmtdiv reply' : 'cmtdiv') . '\',' . PHP_EOL;
					$variable .= "\t\t" . 'avatar: \'' . addcslashes($avatar_icon, "'") . '\',' . PHP_EOL;
					$variable .= "\t\t" . 'indent: \'' . (($indention == 'right') ? '16px ' . $indent . 'px 12px 0px' : '16px 0px 12px ' . $indent . 'px') . '\',' . PHP_EOL;
					$variable .= "\t\t" . 'name: \'' . addcslashes($variable_name, "'") . '\',' . PHP_EOL;
					$variable .= (preg_match("/r/", $permalink)) ? "\t\t" . 'thread: \'' . addcslashes('<a href="#' . preg_replace('/^(.*)r.*$/', '\\1', $permalink) . '" title="' . $text['thread_tip'] . '" style="float: right;">' . $text['thread'] . '</a>', "'") . '\',' . PHP_EOL : '';
					$variable .= "\t\t" . 'date: \'' . addcslashes('<a class="cmtdate" href="#' . str_replace('_pop', '', $permalink) . '"><time datetime="' . $str_cmtdate . '" title="' . $nicestr_cmtdate . '">' . $cmt_date . '</time></a>', "'") . '\',' . PHP_EOL;
					$variable .= ($read_cmt['likes'] > '0') ? "\t\t" . 'likes: \'' . $read_cmt['likes'] . ' Like' . (($read_cmt['likes'] != '1') ? 's' : '') . '\',' . PHP_EOL : '';
					$variable .= "\t\t" . 'sort_name: \'' . addcslashes($read_cmt->name, "'") . '\',' . PHP_EOL;
					$variable .= "\t\t" . 'sort_date: ' . '\'' . strtotime(str_replace('- ', '', $read_cmt->date)) . '\',' . PHP_EOL;
					$variable .= "\t\t" . 'sort_likes: \'' . $read_cmt['likes'] . '\',' . PHP_EOL;

					$variable .= "\t\t" . 'reply_link: \'' . addcslashes('<a href="#" id="cmtreply-' . $permalink . '" class="cmtreply" onClick="reply(\'' . $permalink . '\', \'' . basename($file, '.xml') . '\'); return false;" title="' . $text['reply_to_cmt'] . ' - ' . $email_indicator . '>Répondre</a>', "'") . '\',' . PHP_EOL;
					$variable .= "\t\t" . 'comment: \'' . addcslashes($clean_code, "'") . '\'' . PHP_EOL;
					$variable .= "\t" . '},' . PHP_EOL . PHP_EOL;
			}
		}

		return $variable;
	}

?>
