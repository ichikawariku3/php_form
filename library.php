<?php
// XSS対策
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// dbへの接続
function dbconnect() {
	$db = new mysqli('us-cdbr-east-05.cleardb.net', 'b3be3df7a84238', '8a987262', 'heroku_c4974c1348a806b');
	if (!$db) {
		die($db->error);
	}

	return $db;
}
?>
