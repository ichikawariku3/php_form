<?php
// XSS対策
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// dbへの接続
function dbconnect() {
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];//us-cdbr-east-05.cleardb.net
$username = $url["user"];//b3be3df7a84238
$password = $url["pass"];//8a987262
$db = substr($url["path"], 1);//heroku_c4974c1348a806b

	$conn = new mysqli($server, $username, $password, $db);
	if (!$db) {
		die($db->error);
	}

	return $db;
}
?>
