<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
</head>

<style type="text/css">
	.topcorner{
		position:absolute;
		top:2%;
		right:10%;
	}
	.backbtn{
		position:absolute;
		top:2%;
		right:20%;
	}
	.nextbtn{
		position:absolute;
		top:2%;
		right:30%;
	}
	table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
	}
	th, td {
		padding: 5px;
	}

	h1 {
		display: block;
		font-size: 2em;
		margin-top: 2cm;
	}


</style>

<body>

<?php
	$conn = oci_connect("e1818939", "rFEWc", "//144.122.71.31:8085/xe");
	if (!$conn) 
	{
		$m = oci_error();
		echo $m['message'], "\n";
		exit;
	}

	if($_POST['logout'] == "1")
	{
		session_unset();
		session_destroy(); 
	} 

	?>

	<center><h1>University Data Management System</h1></center>
	<center><h2>Menu</h2></center>
	<br><br><br><br>
	<center>
		<form action="admin_login.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Admin">
		</form>
		<br>
		<form action="indexProject.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Student">
		</form>
		<br>
		<form action="prof_login.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Professor">
		</form>
		<br>
		<form action="staff_login.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Staff">
		</form>
		
	
	</center>
	<?php

	oci_free_statement($stid);
	oci_close($conn);
	
?>




</body>
</html> 