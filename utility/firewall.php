<?
ini_set('display_errors',1);
error_reporting(-1);
include "settings.php";
include_once "queries.php";


if(!isset($_COOKIE['auth_ID']) || !isset($_COOKIE['auth_NAME']))
{
	header ("Location: ../");
	return;
}else{
	$id = $_COOKIE['auth_ID'];

	$data = getUserById($id);

	if(empty($data))
	{
		setcookie('auth_ID', "", time()-3600, $cookiePath, $cookieDomain);
		setcookie('auth_NAME', "", time()-3600, $cookiePath, $cookieDomain);
		header ("Location: ../");
		return;
	}
	
	if(!($_COOKIE['auth_ID'] == $id && $_COOKIE['auth_NAME'] == $data))
	{
		setcookie('auth_ID', "", time()-3600, $cookiePath, $cookieDomain);
		setcookie('auth_NAME', "", time()-3600, $cookiePath, $cookieDomain);
		header ("Location: ../");
		return;
	}
}
?>