<?
include "../utility/settings.php";
$error = false;

if(isset($_COOKIE['error'])){
	$error = true;
	setcookie("error", "", time()-3600, $cookiePath, $cookieDomain);
}

echo '
	<!doctype html>
	<html lang="hu">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<title>' . $page_title . '</title>
';
		include "../utility/scripts.php";
echo '
	<script>
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
					$("#dateForm").submit();
				},
				beforeShowDay: color,
				dateFormat:"yy.mm.dd",
				firstDay: 1
			});
		});
		
		function addNumber(e){
				var v = $( "#PINbox" ).val();
				
				if(v.length >= 4)
				{
					showError("A PIN kódnak 4 karakteresnek kell lennie!");
					return;
				}
				
				$( "#PINbox" ).val( v + e.value );
			}
			function clearForm(e){
				$( "#PINbox" ).val( "" );
			}
			function submitForm(e) {
				if (e.value == "") {
					showError("A PIN kódnak 4 karakteresnek kell lennie!");
				}else if(e.value.length < 4){
					showError("A PIN kódnak 4 karakteresnek kell lennie!");
				}else{
					$("#PINbox").prop( "disabled", false );
					$("#submitButton").trigger("click"); 
				};
			};
	</script>
	<style>
		html{
			margin: 16px;
		}
		body {
			background: #3498db;
		}

		#PINform input:focus,
		#PINform select:focus,
		#PINform textarea:focus,
		#PINform button:focus {
			outline: none;
		}
		#PINform {
			background: #ededed;
			position: absolute;
			width: 370px; height: 500px;
			left: 50%;
			margin-left: -180px;
			top: 50%;
			margin-top: -215px;
			padding: 30px;
			  -webkit-box-shadow: 0px 5px 5px -0px rgba(0,0,0,0.3);
				 -moz-box-shadow: 0px 5px 5px -0px rgba(0,0,0,0.3);
					  box-shadow: 0px 5px 5px -0px rgba(0,0,0,0.3);
		}
		#PINbox {
			background: #ededed;
			margin: 3.5%;
			width: 92%;
			font-size: 4em;
			text-align: center;
			border: 1px solid #d5d5d5;
		}
		.PINbutton {
			background: #ededed;
			color: #7e7e7e;
			border: none;
			/*background: linear-gradient(to bottom, #fafafa, #eaeaea);
			  -webkit-box-shadow: 0px 2px 2px -0px rgba(0,0,0,0.3);
				 -moz-box-shadow: 0px 2px 2px -0px rgba(0,0,0,0.3);
					  box-shadow: 0px 2px 2px -0px rgba(0,0,0,0.3);*/
			border-radius: 50%;
			font-size: 1.5em;
			text-align: center;
			width: 60px;
			height: 60px;
			margin: 7px 20px;
			padding: 0;
		}
		.clear, .enter {
			font-size: 1em;
		}
		.PINbutton:hover {
			box-shadow: #506CE8 0 0 1px 1px;
		}
		.PINbutton:active {
			background: #506CE8;
			color: #fff;
		}
		.clear:hover {
			box-shadow: #ff3c41 0 0 1px 1px;
		}
		.clear:active {
			background: #ff3c41;
			color: #fff;
		}
		.enter:hover {
			box-shadow: #47cf73 0 0 1px 1px;
		}
		.enter:active {
			background: #47cf73;
			color: #fff;
		}
		.shadow{
			  -webkit-box-shadow: 0px 5px 5px -0px rgba(0,0,0,0.3);
				 -moz-box-shadow: 0px 5px 5px -0px rgba(0,0,0,0.3);
					  box-shadow: 0px 5px 5px -0px rgba(0,0,0,0.3);
		}
	</style>
</head>
<body>
	<div class="alert alert-danger my-2" id="error" role="alert" style="display: none"></div>
		<div id="PINcode"></div>
		<form action="" method="POST" name="PINform" id="PINform" autocomplete="off">
			<input type="password" id="PINbox" name="pinbox" disabled />
			<br/>
			<input type="button" class="PINbutton" name="1" value="1" id="1" onClick=addNumber(this); />
			<input type="button" class="PINbutton" name="2" value="2" id="2" onClick=addNumber(this); />
			<input type="button" class="PINbutton" name="3" value="3" id="3" onClick=addNumber(this); />
			<br>
			<input type="button" class="PINbutton" name="4" value="4" id="4" onClick=addNumber(this); />
			<input type="button" class="PINbutton" name="5" value="5" id="5" onClick=addNumber(this); />
			<input type="button" class="PINbutton" name="6" value="6" id="6" onClick=addNumber(this); />
			<br>
			<input type="button" class="PINbutton" name="7" value="7" id="7" onClick=addNumber(this); />
			<input type="button" class="PINbutton" name="8" value="8" id="8" onClick=addNumber(this); />					
			<input type="button" class="PINbutton" name="9" value="9" id="9" onClick=addNumber(this); />
			<br>
			<input type="button" class="PINbutton clear" name="-" value="clear" id="-" onClick=clearForm(this); />
			<input type="button" class="PINbutton" name="0" value="0" id="0" onClick=addNumber(this); />
			<input type="button" class="PINbutton enter" name="submit" value="enter" id="+" onClick=submitForm(PINbox); />
			<input type="submit" style="display:none;" id="submitButton" />
		</form>
	</div>';
	
if($error){
	echo'	<script>
			$(document).ready(function(){
				showError("Sikertelen bejelentkezés!");
			});
			</script>';
}

echo '
</body>

</html>';
?>