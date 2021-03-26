<?
include "../utility/settings.php";
include_once "../utility/firewall.php";
include_once "../utility/queries.php";

echo '
	<!doctype html>
	<html lang="hu">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<title>' . $page_title . '</title>';

include "../utility/scripts.php";
include "../utility/style.php";
include "../utility/navbar.php";

echo '
<div class="alert alert-danger my-2" id="error" role="alert" style="display: none"></div>

<div class="my-3">
<form action="" method="POST" autocomplete="off">
	<div class="h3 my-4">Elvégzett gyakorlat hozzáadása</div>

	<div class="form-group">
		<div class="input-group col-lg-4 mx-auto">
			<select class="custom-select" disabled>
				<option value="">' . date("Y.m.d") . '</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-4 mx-auto">
			<select id="exercise" name="exercise" class="selectpicker col-md-12 px-0" data-live-search="true">';
                $array = getExerciseNames();

				sort($array);

				for($i = 0; $i < count($array); $i++){
					echo '<option value="' . $array[$i] . '">' . $array[$i] . '</option>';
				}

echo '		</select>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group col-lg-4 mx-auto">
			<select id="repetition" name="repetition" class="custom-select">';
				for($i = 1; $i <= 20; $i++)
					echo '<option value="' . $i . '">' . $i . '</option>';
echo'		</select>
			<div class="input-group-append">
				<div class="input-group-text">
					<i class="fa fa-repeat"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="input-group col-lg-4 mx-auto">
			<select id="weight" name="weight" class="custom-select">';
				for($i = 0; $i <= 100; $i++)
					echo '<option value="' . $i . '">' . $i . '</option>';
echo'			</select>
			<div class="input-group-append">
				<div class="input-group-text">
					<i class="fas fa-weight-hanging"></i>
				</div>
			</div>

			<div class="input-group-prepend" style="margin-left: 10px">
				<div class="input-group-text">
					<i class="fas fa-plus" style="font-size: 8pt"></i>
				</div>
			</div>
			<select id="weight_plus" name="weight_plus" class="custom-select col-md-2">';
				echo '<option value="0">0</option>';
				for($i = 0.1; $i < 0.9; $i += 0.1)
					echo '<option value="' . substr(strval($i), 1, 2) . '">' . substr(strval($i), 1, 2) . '</option>';
echo'		</select>

		</div>
	</div>

	<div class="form-group col-lg-4 mx-auto">
		<button name="submit"id="submitButton" type="submit" class="btn btn-primary btn-block">Submit</button>
	</div>
</form>
</div>';

if(isset($_POST['exercise'])){
	$userid = $_COOKIE["auth_ID"];

	$exercise = $_POST['exercise'];
	$weight = $_POST['weight'];
	$weight_plus = $_POST['weight_plus'];
	$repetition = $_POST['repetition'];

	$date = strval(date("Y.m.d"));
	$exerciseid = getExerciseByName($exercise);

	if(insertWorkout($userid, $exerciseid, $date, ($weight + $weight_plus), $repetition)){
		echo '<script>location.href = "home.php"</script>';
	}else{
		echo'<script>$(document).ready(function(){showError("Sikertelen hozzáadás!");});</script>';
	}
}
?>