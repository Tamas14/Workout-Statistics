<?
ini_set('display_errors',1);
error_reporting(-1);
include "connection.php";

function getExerciseByName($name){
	$conn = connect();

	$query = "SELECT id FROM exercises WHERE name = '" . $name . "'";
	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$conn->close();
		return $row['id'];
	}else{
		$conn->close();
		return "";
	}
}

function getExerciseById($id){
	$conn = connect();

	$query = "SELECT name FROM exercises WHERE id = " . $id;
	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$conn->close();
		return $row['name'];
	}else{
		$conn->close();
		return "";
	}
}

function getExerciseIds(){
	$conn = connect();

	$query = "SELECT id FROM exercises";
	$result = $conn->query($query);
	$output = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
            array_push($output, $row['id']);
        }
		$conn->close();
        return $output;
	}else{
		$conn->close();
		return "";
	}
}

function getExerciseNames(){
	$conn = connect();

	$query = "SELECT name FROM exercises";
	$result = $conn->query($query);
	$output = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
            array_push($output, $row['name']);
        }
		$conn->close();
        return $output;
	}else{
		$conn->close();
		return "";
	}
}

function getUserById($id){
	$conn = connect();

	$query = "SELECT name FROM users WHERE id = " . $id;
	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$conn->close();
        return $row['name'];
	}else{
		$conn->close();
		return "";
	}
}

function getUsers(){
	$conn = connect();

	$query = "SELECT id, name, hide FROM users ORDER BY name";
	$result = $conn->query($query);
	$output = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
            array_push($output, array($row['id'], $row['name'], $row['hide']));
        }
		$conn->close();
        return $output;
	}else{
		$conn->close();
		return "";
	}
}

function getUserHideDataById($id){
	$conn = connect();

	$query = "SELECT hide FROM users WHERE id = " . $id;
	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$conn->close();
        return $row['hide'];
	}else{
		$conn->close();
		return "";
	}
}

function getUserExercisedDates($name){
	$conn = connect();

	$query = 'SELECT date FROM workout where userid = (SELECT id FROM users WHERE name = "'. $name . '")';
	$result = $conn->query($query);
	$output = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
            array_push($output, $row['date']);
        }
		$conn->close();
        return $output;
	}else{
		$conn->close();
		return "";
	}
}

function getExerciseDateById($id){
	$conn = connect();

	$query = 'SELECT date FROM workout where id = ' . $id;
	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$conn->close();
        return $row['date'];
	}else{
		$conn->close();
		return "";
	}
}

function getUserExercisesByDate($id, $date){
	$conn = connect();

	$query = 'SELECT id, exerciseid, repetition, weight FROM workout WHERE `userid` = ' . $id . ' AND `date` = "' . $date . '" ORDER BY id ASC';
	$result = $conn->query($query);
	$output = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
            array_push($output, array($row['id'], $row['exerciseid'], $row['repetition'], $row['weight']));
        }
		$conn->close();
        return $output;
	}else{
		$conn->close();
		return "";
	}
}

function getExerciseIdByWorkoutId($id){
	$conn = connect();

	$query = 'SELECT exerciseid FROM workout WHERE id = ' . $id;
	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$conn->close();
        return $row['exerciseid'];
	}else{
		$conn->close();
		return "";
	}
}

function getExerciseStatsByWorkoutId($id){
	$conn = connect();

	$query = 'SELECT weight, repetition FROM workout WHERE id = ' . $id;
	$result = $conn->query($query);
	$output = array();

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$conn->close();

		array_push($output, $row['weight'], $row['repetition']);

        return $output;
	}else{
		$conn->close();
		return "";
	}
}

function getLastDateWorkouts($userid, $exerciseid){
	$conn = connect();
	$query = 'SELECT * FROM `workout` WHERE exerciseid = ' . $exerciseid . ' AND date = (SELECT date FROM `workout` WHERE exerciseid = ' . $exerciseid . ' AND userid = ' . $userid . ' ORDER BY date DESC LIMIT 1) AND userid = ' . $userid . ' ORDER BY date DESC';

	$result = $conn->query($query);
	$output = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			array_push($output, array($row['id'], $row['exerciseid'], $row['repetition'], $row['weight']));
        }
		$conn->close();

        return $output;
	}else{
		$conn->close();
		return "";
	}
}

function getLastLastDateWorkouts($userid, $exerciseid){
	$conn = connect();
	$query = 'SELECT * FROM `workout` WHERE exerciseid = ' . $exerciseid . ' AND date = (SELECT date FROM `workout` WHERE exerciseid = ' . $exerciseid . ' AND userid = ' . $userid . ' AND date <> (SELECT date FROM `workout` WHERE exerciseid = ' . $exerciseid . ' AND userid = ' . $userid . ' ORDER BY date DESC LIMIT 1) ORDER BY date DESC LIMIT 1) AND userid = ' . $userid . ' ORDER BY date DESC';

	$result = $conn->query($query);
	$output = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			array_push($output, array($row['id'], $row['exerciseid'], $row['repetition'], $row['weight']));
        }
		$conn->close();

        return $output;
	}else{
		$conn->close();
		return "";
	}
}

function getFirstDateWorkouts($userid, $exerciseid){
	$conn = connect();
	$query = 'SELECT * FROM `workout` WHERE exerciseid = ' . $exerciseid . ' AND date = (SELECT date FROM `workout` WHERE exerciseid = ' . $exerciseid . ' AND userid = ' . $userid . ' ORDER BY date ASC LIMIT 1) AND userid = ' . $userid . ' ORDER BY date DESC';

	$result = $conn->query($query);
	$output = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			array_push($output, array($row['id'], $row['exerciseid'], $row['repetition'], $row['weight']));
        }
		$conn->close();
        return $output;
	}else{
		$conn->close();
		return "";
	}
}

function getWorkoutsDistinctDate($id, $userid){
	$query = 'SELECT * FROM workout WHERE date <> (SELECT date FROM workout WHERE id = ' . $id . ') AND exerciseid = (SELECT exerciseid FROM workout WHERE id = ' . $id . ') AND userid = ' . $userid;
}

function insertWorkout($userid, $exerciseid, $date, $weight, $repetition){
	$conn = connect();

	$sql = "INSERT INTO workout (userid, exerciseid, date, weight, repetition) VALUES ('" . $userid . "', '" . $exerciseid . "', '" . $date . "', '" . $weight . "', '" . $repetition . "')";

	if ($conn->query($sql) === TRUE) {
		$conn->close();
		return true;
	} else {
		$conn->close();
		return false;
	}
}

function insertExercise($exercisename){
 	$conn = connect();

 	$sql = "INSERT INTO exercises (name) VALUES ('" . $exercisename . "')";

 	if ($conn->query($sql) === TRUE) {
 		$conn->close();
 		return true;
 	} else {
 		$conn->close();
 		return false;
 	}
 }

function deleteWorkout($workoutid){
	$conn = connect();

	$sql = "DELETE FROM workout WHERE id=" . $workoutid;

	if ($conn->query($sql) === TRUE) {
		$conn->close();
		return true;
	} else {
		$conn->close();
		return false;
	}
}

function updateWorkout($id, $weight, $repetition){
	$conn = connect();

	$sql = "UPDATE workout SET weight = " . $weight . ", repetition = " . $repetition . " WHERE id = " . $id .  ";";

	if ($conn->query($sql) === TRUE) {
		$conn->close();
		return true;
	} else {
		$conn->close();
		return false;
	}
}

function updateUserHideData($id, $value){
	$conn = connect();

	$sql = "UPDATE users SET hide = " . $value . " WHERE id = " . $id .  ";";

	if ($conn->query($sql) === TRUE) {
		$conn->close();
		return true;
	} else {
		$conn->close();
		return false;
	}
}

function copyWorkout($id){
	$conn = connect();

	$sql = "INSERT INTO workout (userid, exerciseid, date, weight, repetition) SELECT userid, exerciseid, date, weight, repetition FROM workout WHERE id=" . $id;

	if ($conn->query($sql) === TRUE) {
		$conn->close();
		return true;
	} else {
		$conn->close();
		return false;
	}
}



?>