<?
include "../utility/settings.php";
include_once "../utility/firewall.php";
include_once "../utility/queries.php";

if(!(isset($_GET['id']))){
	header("Location: home.php");
}

$id = $_GET['id'];

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

<div id="success" class="alert alert-success my-4" role="alert" style="display: none">Sikeres hozzáadás!</div>
<div id="err" class="alert alert-danger my-4" role="alert" style="display: none">Már létező gyakorlat!</div>

<div class="my-3">
<form action="" method="POST" autocomplete="off">
	<input type="hidden" id="id" name="id" value="' . $id . '">
	<div class="h3 my-4">Elvégzett gyakorlat szerkesztése</div>
	<div class="form-group">
		<div class="col-lg-4 mx-auto">
			<select id="exercise" name="exercise" class="selectpicker col-md-12 px-0" data-live-search="true" disabled>';
				$name = getExerciseById(getExerciseIdByWorkoutId($id));
				echo '<option value="' . $name . '">' . $name . '</option>';

echo '		</select>
		</div>
	</div>
	<div class="form-group">
			<div class="input-group col-lg-4 mx-auto">
			<select id="repetition" name="repetition" class="custom-select">';
				for($i = 1; $i <= 20; $i++)
					echo '<option value="' . $i . '">' . $i . '</option>';
echo'		</select>
				<div class="input-group-prepend">
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
		<button name="submit" type="submit" class="btn btn-primary btn-block">Submit</button>
	</div>
</form>
</div>';

if(isset($_POST['submit'])){
	$reps = $_POST['repetition'];
	$weight = $_POST['weight'];
	$weight_plus = $_POST['weight_plus'];

	if(updateWorkout($id, ($weight + $weight_plus), $reps))
		echo '<script>location.href = "home.php?date=' . getExerciseDateById($id) . '"</script>';
	else
		echo'<script>$(document).ready(function(){showError("Sikertelen hozzáadás!");});</script>';
}else{
	$data = getExerciseStatsByWorkoutId($id);
	$weight = floor($data[0]);
	$weight_plus = $data[0] - $weight;
	$weight_plus_print = ($weight_plus == 0)?"0":substr(strval($weight_plus), 1, 2);
	echo'<script>$(document).ready(function(){
		$("#repetition").val(' . $data[1] . ');
		$("#weight").val(' . $weight . ');
		$("#weight_plus").val("' . $weight_plus_print . '");
	});</script>';
}
?>