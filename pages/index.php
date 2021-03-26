<?
include "../utility/settings.php";
include "../utility/connection.php";
header('Content-Type: text/html; charset=UTF-8');
	if(!isset($_COOKIE['auth_ID']))
	{
		if(!('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['pinbox']))){
			include "login.php";
			return;
		}else{
			$password = $_POST['pinbox'];
			$found = false;

			$conn = connect();

			$query = "SELECT * FROM users WHERE password = '" . md5($password . "WORKOUT") . "'";
			$result = $conn->query($query);

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();

				setcookie("auth_ID", $row['id'], time()+3600);
				setcookie("auth_NAME", $row['name'], time()+3600);
				$found = true;
			}
			$conn->close();

			/*$lines = file($userFile, FILE_IGNORE_NEW_LINES);
			
			for($i = 0; $i < count($lines); $i++){
				$inner_data = explode($separator, $lines[$i]);

				if($inner_data[2] == md5($password . 'WORKOUT')){
					setcookie("auth_ID", $inner_data[0], time()+6000, $cookiePath, $cookieDomain);
					setcookie("auth_NAME", $inner_data[1], time()+6000, $cookiePath, $cookieDomain);
					$found = true;
				}
*/
			if($found)
			{
				header("Refresh:0");
				return;
			}else{
				setcookie("error", "1", time()+60, $cookiePath, $cookieDomain);
				header("Refresh:0");
				return;
			}
		}
	}else{
		header ("Location: home.php");
		return;
	}
?>