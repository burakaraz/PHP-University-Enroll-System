
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

	$user_id=(int)$_SESSION["id"];
	

	?>

	<center><h1>University Data Management System</h1></center>
	<center><h2>Menu</h2></center>
	<br><br><br><br>
	<center>
		<form action="addUnderReg.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Register Under Graduate Student">
		</form>
		<br>
		<form action="addGradReg.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Register Graduate Student">
		</form>
		<br>
		<form action="addSpecReg.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Register Special Student">
		</form>
		<br>
	</center>
	<?php

	oci_free_statement($stid);
	oci_close($conn);
	
?>

<div class="topcorner">
    <form action="in.php" method="POST">
    	<input type="hidden" name="logout" value="1" />
		<input type="submit" value="Logout" />
	</form>
</div>

<div class="backbtn">
	<form action="admin_menu.php" method="POST">
		<input type="hidden" name="back" value=<?php echo 2; ?> />
		<input type="submit" value="Back" />
	</form>
</div>

</body>
</html> 