<?php
// XSS対策
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// dbへの接続
function dbconnect() {
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

	$conn = new mysqli($server, $username, $password, $db);
	$conn->set_charset('utf8');

	if (!$conn) {
		die($conn->error);
	}

	return $conn;
}
?>
