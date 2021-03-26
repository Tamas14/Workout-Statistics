<?php
include "settings.php";

function connect(){
	global $db_servername, $db_username, $db_password, $db_name;

	$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
	$conn->set_charset("utf8");

	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}

	return $conn;
}

?>