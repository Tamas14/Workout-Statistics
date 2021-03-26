<?
ini_set('display_errors',1);
error_reporting(-1);
include "../utility/settings.php";
include_once "../utility/firewall.php";
include_once "../utility/queries.php";

if(isset($_POST['date'])){
	$date = $_POST['date'];
}else if(isset($_GET['date'])){
	$date = $_GET['date'];
}else{
	$date = date("Y.m.d");
}

echo '
	<!doctype html>
	<html lang="hu">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<title>' . $page_title . '</title>';

include "../utility/scripts.php";

echo'		
	<script>
		let err = false;
		function color(_date){
			let date = _date.getFullYear() + "." + ("0" + (_date.getMonth() + 1)).slice(-2) + "." + ("0" + _date.getDate()).slice(-2);
			
			if(dates.includes(date))
				return [true, "colored", ""];
			else
				return [true, "", ""];
		}
	
		$(document).ready(function(){
			$("#datepicker").datepicker({
				onClose: function(){
					if(!err)
						$("#dateForm").submit();
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
			});';
			
			if(!isset($_COOKIE['welcome'])){
				echo '
				$("#welcome").show("slide", {direction: "up" }, "slow");
				setTimeout(function(){ $("#welcome").hide("slide", {direction: "up" }, "slow"); }, 3000);
				document.cookie = "welcome=1;max-age=3600;"';
			}

echo'
		});
		
		let id;
		
		function openModal(_id, _modal){
			id = _id;
			$(_modal).modal("show");
		}
		
		function deleteWorkout(){
			document.location = "deleteWorkout.php?id="+id;
		}

		function copyWorkout(){
        		document.location = "copyWorkout.php?id="+id;
        }

        function resetDate(){
			$("#datepicker").val("' . date("Y.m.d") . '");
			err = false;
			$("#dateForm").submit();
		}
	</script>';
		
include "../utility/style.php";
		
$id = $_COOKIE["auth_ID"];
$name = $_COOKIE["auth_NAME"];
$dates = getUserExercisedDates($name);

echo '<script>let dates = [';

if(!empty($dates)){
	foreach($dates as $value)
		echo '"' . $value . '",';
}
echo ']</script>';


include "../utility/navbar.php";

echo '
	<div class="display-4" id="welcome" style="display: none;"> Szia ' . $name . '!</div>
		<form action="" method="POST" id="dateForm" autocomplete="off">
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
echo '<th scope="col" colspan = "2" style="width: 10%"><i class="fas fa-toolbox" style="font-size: 16pt"></i></th>';

echo ' </tr></thead><tbody>';

if(empty($dates) || !in_array($date, $dates)){
	echo '<tr style="background-color: rgba(0, 255, 0, 0.3); border-top: 2px solid; cursor: pointer;" onclick="document.location = \'addWorkout.php\'"><td style="border: 0px" colspan = "5"><div ><i class="fas fa-plus" style="font-size: 16pt;"></i></div></td></tr>';
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
			$table_output .= '<td style=""><table onclick="" class="col-md-1" style="float: right">
			<td style="border: 0px;"><div style="cursor: pointer;" onclick="openModal(\'' . $exercise[0] . '\', $(\'#copyModal\'))"><i class="far fa-copy" style="font-size: 16pt;"></i></div></td>
			<td style="border: 0px;"><div style="cursor: pointer;" onclick="document.location = \' editWorkout.php?id='. $exercise[0] . '\'"><i class="fas fa-pencil-alt" style="font-size: 16pt;"></i></div></td>
			<td style="border: 0px;"><div style="cursor: pointer;" onclick="openModal(\'' . $exercise[0] . '\', $(\'#myModal\'))"><i class="fas fa-trash-alt" style="font-size: 16pt;"></i></div></td>
			</table></td>';
			//$table_output .= '<td style="display: inline-flex; margin-top: -1px;"><div style="" onclick="document.location = \' editWorkout.php?id=' . $exercise[0] . '\'"><i class="fas fa-pencil-alt" style="font-size: 16pt;"></i></div><div style="padding-left: 20px" onclick=openModal("' . $exercise[0] . '")><i class="fas fa-trash-alt" style="font-size: 16pt;"></i></div></td>';
			$table_output .= '</tr>';
		}
	}

			$table_output .= '<tr style="background-color: rgba(0, 255, 0, 0.3); border-top: 2px solid; cursor: pointer;" onclick="document.location = \'addWorkout.php\'"><td style="border: 0px" colspan = "5"><div ><i class="fas fa-plus" style="font-size: 16pt;"></i></div></td></tr>';


	echo $table_output;
}

	echo '</tbody></table></div>';

$modals = array(array('myModal', 'Törlés', 'Biztosan törölni szeretnéd?', '<button type="button" class="btn btn-danger" onclick=\'deleteWorkout()\'>Igen</button><button type="button" class="btn btn-primary" data-dismiss="modal">Nem</button>'),
				array('copyModal', 'Másolás', 'Biztosan le szeretnéd másolni?', '<button type="button" class="btn btn-success" onclick=\'copyWorkout()\'>Igen</button><button type="button" class="btn btn-primary" data-dismiss="modal">Nem</button>'),
				array('dateModal', 'Dátum választás', 'Nem választhatsz csak zöld/mai dátumot!', '<button type="button" class="btn btn-primary" onclick=\'resetDate()\' data-dismiss="modal">Bezárás</button>')
				);

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

?>