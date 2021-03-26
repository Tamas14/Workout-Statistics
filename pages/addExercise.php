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
	<div class="alert alert-success my-2" id="success" role="alert" style="display: none"></div>

<div class="my-3">
    <form action="" method="POST" autocomplete="off">
    <div class="h3 my-4">Gyakorlat hozzáadás</div>
        <div class="form-group">
            <div class="col-md-4 mx-auto my-3">
				<select class="selectpicker col-md-12 px-0" data-live-search="true">';
					$array = getExerciseNames();

					sort($array);

					for($i = 0; $i < count($array); $i++){
						echo '<option value="' . $array[$i] . '">' . $array[$i] . '</option>';
					}

echo '		</select>
            </div>

            <div class="col-md-4 mx-auto">
				<div class="input-group">
					<input id="exercise" name="exercise" placeholder="Gyakorlat neve" type="text" required="required" class="form-control vw-50">
					<div class="input-group-append">
						<div class="input-group-text">
							<i class="fa fa-heartbeat"></i>
						</div>
					</div>
				</div>
			</div>
        </div>
        <div class="form-group">
            <div class="col-md-4 mx-auto">
                <button name="submit" type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </div>
    </form>
</div>';

if(isset($_POST['exercise'])){
    $exercise = $_POST['exercise'];

	if(!empty(getExerciseByName($exercise))){
		echo'<script>$(document).ready(function(){showError("Már létező gyakorlat!");});</script>';
	}else{
		if(insertExercise($exercise))
			echo'<script>$(document).ready(function(){showSuccess("Sikeres hozzáadás");});</script>';
		else
			echo'<script>$(document).ready(function(){showError("Sikertelen hozzáadás!");});</script>';
	}
}

?>