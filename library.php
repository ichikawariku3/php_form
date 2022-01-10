<?php
// XSS対策
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// dbへの接続
function dbconnect() {
	$db = new mysqli('localhost', 'root', 'root', 'form');
	if (!$db) {
		die($db->error);
	}

	return $db;
}
?>
