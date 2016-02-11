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

	// URL back to comment
	$kickback = $parse_url['path'] . ((!empty($parse_url['query'])) ? '?' . $parse_url['query'] : '');

	// Clean up name, set name cookie
	if (isset($_POST['name']) and trim($_POST['name'], ' ') != '' and $_POST['name'] != $text['nickname']) {
		$name = substr(str_replace($search, $replace, $_POST['name']), 0, 30);
	}

	// Default email headers
	$header = "From: $noreply_email\r\nReply-To: $noreply_email";

	function sanitize($query) {
		$value = str_replace('../', '', $query);
		return $value;
	}

	function xml_sanitize($string) {
		$string = mb_convert_encoding(mb_convert_encoding($string, 'UTF-16', 'UTF-8'), 'UTF-8', 'UTF-16');
		$string = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '?', $string);
		return $string;
	}

	// Check trap fields
	if (!empty($_POST['summary'])) $is_spam = true;
	if (!empty($_POST['middlename'])) $is_spam = true;
	if (!empty($_POST['lastname'])) $is_spam = true;
	if (!empty($_POST['address'])) $is_spam = true;
	if (!empty($_POST['zip'])) $is_spam = true;

	// Check if a comment has been entered, clean comment, replace HTML, create hyperlinks
	if (!isset($is_spam) and isset($_POST['comment']) and !isset($_POST['delete'])) {

		if (!empty($_POST['comment']) and trim($_POST['comment'], " \r\n") != '' and (!preg_match('/' . str_replace(array('(', ')'), array('\(', '\)'), $text['comment_form']) . '/i', $_POST['comment']) and !preg_match('/' . str_replace(array('(', ')'), array('\(', '\)'), $text['reply_form']) . '/i', $_POST['comment']))) {
			// Characters to search for and replace with in comments
			$data_search = array('\\', '"', '<', '>', "\n\r", "\n", "\r", '  ');
			$data_replace = array('&#92;', '&quot;', '&lt;', '&gt;', '<br>', '', '<br>', ' &nbsp;');

			$clean_code = preg_replace('/(((ftp|http|https){1}:\/\/)[a-zA-Z0-9-@:%_\+.~#?&\/=]+)/i', '\\1 ', $_POST['comment']); // Add space to end of URLs to separate '&' characters from escaped HTML tags
			$clean_code = str_ireplace($data_search, $data_replace, preg_replace('/\n{2,}/', "\n\r\n", preg_replace('/^\s+$/m', '', rtrim($clean_code, " \r\n")))); // Escape HTML tags; remove trailing new lines
			$clean_code = preg_replace('/^(<br><br>)/', '', preg_replace('/(<br><br>)$/', '', preg_replace('/(<br>){2,}/i', '<br><br>', $clean_code))); // Remove repetitive and trailing HTML <br> tags

			// Open comment template; prepare data
			$write_cmt = simplexml_load_file('template.xml');
			$write_cmt->name = xml_sanitize(trim($name, ' '));
			$write_cmt->passwd = (!empty($_POST['password']) and !empty($_POST['password']) and $_POST['password'] != $text['password']) ? md5(encrypt(stripslashes($_POST['password']))) : '';
			$write_cmt->email = (!empty($email) and $email != $text['email']) ? str_replace('"', '&quot;', encrypt(stripslashes(xml_sanitize($email)))) : '';
			$write_cmt->website = (!empty($website)) ? xml_sanitize(trim($website, ' ')) : '';
			$write_cmt->date = date('c');
			$write_cmt['likes'] = '0';
			$write_cmt['notifications'] = 'yes';
			$write_cmt['ipaddr'] = ($ip_addrs == 'yes') ? $_SERVER['REMOTE_ADDR'] : '';
			$write_cmt->body = xml_sanitize($clean_code); // Final comment body

			// Read comments without output
			read_comments($dir, 'no');

			// Rename file for reply
			if (isset($_POST['reply_to']) and !empty($_POST['reply_to'])) {
				if (!preg_match('/[a-zA-Z]/i', $_POST['reply_to']) and file_exists($dir . '/' . $_POST['reply_to'] . ".xml")) {
					// Set reply directory information & "cookie" for successful reply
					$reply_dir = $dir . '/' . $_POST['reply_to'] . '.xml';
					$cmt_file = $dir . '/' . $_POST['reply_to'] . '-' . $subfile_count["$reply_dir"] . '.xml';
					//setcookie('replied', $_POST['reply_to'], $expire, '/', str_replace('www.', '', $domain));
				}
			} else {
				$cmt_file = $dir . '/' . $cmt_count . '.xml';
			}

			// Write comment to file
			if ($write_cmt->asXML(sanitize($cmt_file))) {
				chmod($cmt_file, 0600);
        $permalink = 'c' . str_replace('-', 'r', basename($cmt_file, '.xml'));
				exit(header('Location: ' . $kickback . '#' . $permalink));
			} else {
				exit(header('Location: ' . $kickback . '#comments'));
			}
		} else {

			// Kick visitor back to comment form
			exit(header('Location: ' . $kickback . '#comments'));
		}
	}

?>
