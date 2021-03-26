<?
include "../utility/settings.php";
include_once "../utility/firewall.php";
include_once "../utility/queries.php";

if(isset($_GET['id']))
	deleteWorkout($_GET['id']);

header("Location: home.php");

?>