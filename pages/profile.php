<?
ini_set('display_errors',1);
error_reporting(-1);
include "../utility/settings.php";
include_once "../utility/firewall.php";
include_once "../utility/queries.php";

$id = $_COOKIE["auth_ID"];
$name = $_COOKIE["auth_NAME"];
$settingsStyle = "display: none";

if(isset($_POST['hidden'])){
	$settingsStyle = "";

	if( $_POST['hidden'] == "true"){
		updateUserHideData($id, 1);
	}else{
		updateUserHideData($id, 0);
	}
}

if(isset($_POST['submit']))
	$settingsStyle = "";

echo '
	<!doctype html>
	<html lang="hu">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<title>' . $page_title . '</title>';

include "../utility/scripts.php";

echo'<script>
	$(document).ready(()=>{
		$("#statbutton").click(function() {
			$("#stats").slideToggle("slow");
		});
		$("#settingsButton").click(function() {
			$("#settings").slideToggle("slow");
		});
		$("#customSwitches").click(function() {
			$("#hidden").val($("#customSwitches").prop("checked"))
			$("#customSwitches").prop("disabled", "disabled");
			setTimeout(function() {
			   $("#dataHideForm").submit();
			  }, 100);
		});
	});
</script>';

function getColoredTd($percent){
	if($percent > 0)
		return "table-success";
	else if ($percent < 0)
		return "table-danger";
	else
		return "";
}

include "../utility/style.php";

echo'
	<style>
		#profile-box{

		}
		#profile-box i{
			font-size: 72pt;
			color: #ff000091;
		}
		.checkbox-1x {
			transform: scale(1.5);
			-webkit-transform: scale(1.5);
		}
	</style>
';

include "../utility/navbar.php";

if(getUserHideDataById($id)){
	echo '<script>$(document).ready(()=>{
		$("#customSwitches").prop("checked", true);
		$("#hidden").val($("#customSwitches").prop("checked"));
	})</script>';
}else{
	echo '<script>$(document).ready(()=>{
		$("#customSwitches").prop("checked", false);
		$("#hidden").val($("#customSwitches").prop("checked"));
	})</script>';
}

echo'	<div class="alert alert-danger my-2" id="error" role="alert" style="display: none"></div>
     	<div class="alert alert-success my-2" id="success" role="alert" style="display: none"></div>';

echo'<div id="profile-box" class="col-xl-4 mx-auto my-3 shadow p-3 mb-5 rounded">
		<i class="far fa-user-circle wrapper"></i>
		<div class="my-2 h3">' . $name . ' profilja</div>
		<hr>

		<button id="statbutton" class="btn btn-secondary btn-lg btn-block" style="min-width: 100px;">Statisztika <i class="fas fa-chevron-down" style="float: right; font-size: 16pt; padding-top: 6px; color: white;"></i></button>

		<div id="stats" style="display: none">

			<table class="table">
				<thead><th>Gyakorlat<th>Utolsó kettő<th>Első óta</thead>
				<tbody>';

				foreach(getExerciseIds() as $exerciseID){
					$firstArray = getFirstDateWorkouts($id, $exerciseID);
					$lastArray = getLastDateWorkouts($id, $exerciseID);
					$lastlastArray = getLastLastDateWorkouts($id, $exerciseID);

					if(empty($firstArray) || empty($lastArray))
						continue;

					$load = 0;
					$load2 = 0;
					$load3 = 0;

					foreach ($firstArray as $val){
						$load += $val[2] * $val[3];
					}

					foreach ($lastArray as $val){
						$load2 += $val[2] * $val[3];
					}

					if(!empty($lastlastArray))
					{
						foreach ($lastlastArray as $val){
							$load3 += $val[2] * $val[3];
						}
					}else{
						$load3 = $load2;
					}

					$percent = round(($load2/$load)*100 - 100);
					$percent2 = round(($load2/$load3)*100 - 100);

					echo '<tr><td>' . getExerciseById($exerciseID) . '<td class="' . getColoredTd($percent2) . '">' . $percent2 . ' %<td class="' . getColoredTd($percent) . '">' . $percent . ' %';

				}

echo '			</tbody>
			</table>
		</div>

		<button id="settingsButton" class="btn btn-secondary btn-lg btn-block my-2 mb-3" style="min-width: 100px;">Beállítások <i class="fas fa-chevron-down" style="float: right; font-size: 16pt; padding-top: 6px; color: white;"></i></button>
		<div id="settings" style="' . $settingsStyle .  '">
			<form action="" method="POST" id="dataHideForm">
				<div class="form-group">
					<div class="custom-control custom-switch checkbox-1x">
						<input type="checkbox" class="custom-control-input" name="customSwitches" id="customSwitches">
						<input type="hidden" name="hidden" id="hidden" value="asd" />
						<label class="custom-control-label" for="customSwitches">Adatok elrejtése</label>
					</div>
				</div>
			</form>

			<form action="" method="POST">
				<div class="form-group">
					<input type="number" name="pin" class="form-control my-2" min="1000" max="9999" placeholder="PIN-kód módosítás"/>
					<input type="submit" name="submit" class="form-control btn-primary" value="Submit" />
				</div>
			</form>
		</div>
		</div>';

if(isset($_POST["submit"]) || isset($_POST["hidden"]))
	echo'<script>$(document).ready(function(){showSuccess("Sikeres módosítás!");});</script>';

?>