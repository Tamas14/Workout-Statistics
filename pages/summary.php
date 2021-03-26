<?
ini_set('display_errors',1);
error_reporting(-1);
include "../utility/settings.php";
include_once "../utility/firewall.php";
include_once "../utility/queries.php";

$userdata = getUsers();
$id = "";
$name = "";

$authID = $_COOKIE['auth_ID'];

if(isset($_POST['date'])){
	$date = $_POST['date'];
}else if(isset($_GET['date'])){
	$date = $_GET['date'];
}else{
	$date = date("Y.m.d");
}

if(isset($_POST['hidden']) && $_POST['hidden'] != "asd")
{
	$id = $_POST['hidden'];
	$name = getUserById($_POST['hidden']);
	$dates = getUserExercisedDates($name);
}else{
	$id = $userdata[0][0];
	$name = $userdata[0][1];
	$dates = getUserExercisedDates($name);
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
	let err = false;
	function color(_date){
		let date = _date.getFullYear() + "." + ("0" + (_date.getMonth() + 1)).slice(-2) + "." + ("0" + _date.getDate()).slice(-2);

		if(dates.includes(date))
			return [true, "colored", ""];
		else
			return [true, "", ""];
	}
	$(document).ready(()=>{
		$("#datepicker").datepicker({
			onClose: function(){
				if(!err)
					$("#nameForm").submit();
			},
			onSelect: function(dateText) {
				if(!(dates.includes(dateText) || dateText == \'' . date("Y.m.d") . '\')){
					$("#dateModal").modal("show");
					err = true;
				}
			},
			beforeShowDay: color,
			dateFormat:"yy.mm.dd",
			firstDay: 1
		});
	});

	function resetDate(){
		$("#datepicker").val("' . date("Y.m.d") . '");
		err = false;
		$("#nameForm").submit();
	}

	function setNameData(){
		$(".selectpicker").selectpicker("val", "' . $id . '");
		$("#hidden").val($("#namePicker").val());
		err = false;
	}

	function submitName(){
		$("#hidden").val($("#namePicker").val());
		$("#datepicker").val("' . date("Y.m.d") . '");
		$("#nameForm").submit();
	}
</script>';

include "../utility/style.php";

echo'
	<style>
	</style>
';

echo '<script>let dates = [';

if(!empty($dates)){
	foreach($dates as $value)
		echo '"' . $value . '",';
}
echo ']</script>';

include "../utility/navbar.php";

echo '
	<div class="display-4" id="welcome" style="display: none;"> Szia ' . $name . '!</div>
		<form action="" method="POST" id="nameForm" autocomplete="off" class="mt-4">
			<input type="hidden" name="hidden" id="hidden" value="asd" />
			<select onchange="submitName()" class="selectpicker col-md-4 px-0" data-live-search="true" id="namePicker">';
				$array = $userdata;

				foreach($array as $user){
					if($authID == 3)
						echo '<option value="' . $user[0] . '">' . $user[1] . '</option>';
					else if($user[2] == 0)
						echo '<option value="' . $user[0] . '">' . $user[1] . '</option>';
				}

echo '		</select>
			<br>
			<input type="text" placeholder="Dátum" id="datepicker" name="date">
		</form>';

if(isset($_POST['date'])){
	echo '<script>$("#datepicker").val("' . $_POST['date'] . '")</script>';
}else if(isset($_GET['date'])){
      	echo '<script>$("#datepicker").val("' . $_GET['date'] . '")</script>';
}else{
	echo '<script>$("#datepicker").val("' . date("Y.m.d") . '")</script>';
}

echo'		<div class="table-responsive-sm">
			<table class="table">
				<thead>
					<tr>';

for($i = 0; $i < count($datatypes_HUN); $i++)
	echo '<th scope="col">' . $datatypes_HUN[$i] . '</th>';

echo ' </tr></thead><tbody>';

if(empty($dates) || !in_array($date, $dates)){
	echo '<tr><td style="border: 0px" colspan = "5">Nem találtam adatot!</td></tr>';
}else{
	$data = getUserExercisesByDate($id, $date);

	$table_output = '';

	$sets = array();
	$exercises = array();
	$exercises2 = array();

	foreach($data as $exercise){
		if(empty($sets[$exercise[1]]))
			$sets[$exercise[1]] = 0;

		$sets[$exercise[1]]++;

		$exercises2[$exercise[1]][$sets[$exercise[1]]] = $exercise;
	}

	$first = true;
	foreach($exercises2 as $repdata){
		foreach($repdata as $exercise){
			if(!in_array($exercise[1], $exercises)){
				if($first)
					$table_output .= '<tr><td style="vertical-align: middle;" rowspan="' . $sets[$exercise[1]] . '">'.getExerciseById($exercise[1]).'</td>';
				else
					$table_output .= '<tr style="border-top: 2px solid"><td style="vertical-align: middle;" rowspan="' . $sets[$exercise[1]] . '">'.getExerciseById($exercise[1]).'</td>';
				array_push($exercises, $exercise[1]);
			}

			$first = false;

			$table_output .= '<td>' . $exercise[2] . '</td>';
			$table_output .= '<td>' . $exercise[3] . '</td>';
			//$table_output .= '<td style=""><div style="" onclick="document.location = \' editWorkout.php?id=' . $exercise[0] . '\'"><i class="fas fa-pencil-alt" style="font-size: 16pt;"></i></div></td>';
			//$table_output .= '<td style="display: inline-flex; margin-top: -1px;"><div style="" onclick="document.location = \' editWorkout.php?id=' . $exercise[0] . '\'"><i class="fas fa-pencil-alt" style="font-size: 16pt;"></i></div><div style="padding-left: 20px" onclick=openModal("' . $exercise[0] . '")><i class="fas fa-trash-alt" style="font-size: 16pt;"></i></div></td>';
			$table_output .= '</tr>';
		}
	}

	echo $table_output;
}


echo'	</tbody></table></div>';

if(isset($_POST['hidden'])){
	echo '<script>setNameData();</script>';
}

$modals = array(array('dateModal', 'Dátum választás', 'Nem választhatsz csak zöld/mai dátumot!', '<button type="button" class="btn btn-primary" onclick=\'resetDate()\' data-dismiss="modal">Bezárás</button>'));

foreach($modals as $modal){
echo '<div class="modal fade" id="' . $modal[0] . '">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">'. $modal[1] .'</h4></div>

      <!-- Modal body -->
      <div class="modal-body">
        '. $modal[2] .'
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        '. $modal[3] .'
      </div>
    </div>
  </div>
</div>';
}