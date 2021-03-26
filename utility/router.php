<?

if(isset($_GET["to"])){
	header ("Location: ../pages/" . $_GET["to"] . ".php");
}else{
	header ("Location: ../");
}

?>