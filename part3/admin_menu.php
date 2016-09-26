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

	
	if (isset($_POST['userid']) && isset($_POST['password'])) 
	{
		$user_id = $_POST['userid'];
		$password = $_POST['password'];			

		$stid = oci_parse($conn, "begin Database.loginAdmin(:p_user_id,:p_password,:p_RESULT); end;");
		oci_bind_by_name($stid, ":p_user_id", $user_id);
		oci_bind_by_name($stid, ":p_password", $password);
		oci_bind_by_name($stid, ":p_RESULT", $result);
		oci_execute($stid);

		if(trim($result) == "F" ){
			$_SESSION["false"] = 'false';
			header('Location: admin_login.php');
		}

		$_SESSION["id"] = $user_id;
	}
	if (isset($_POST['back'])) 
	{
		$back = $_POST['back'];
		if($back==2)
		{
			$user_id = (int)$_SESSION["id"];
		}
	}

	?>

	<center><h1>University Data Management System</h1></center>
	<center><h2>Menu</h2></center>
	<br><br><br><br>
	<center>
		<form action="addStudent.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Add Student">
		</form>
		<br>
		<form action="addProf.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Add Professor">
		</form>
		<br>
		<form action="addStaff.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Add Staff">
		</form>
		<br>
		<form action="addCourse.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Add Course">
		</form>
		<br>
		<form action="addSection.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Add Section">
		</form>
		<br>
		<form action="addSectionInfo.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Add Section Info">
		</form>
		<br>
		<form action="addAdvise.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Assign Professors to Students">
		</form>
		<br>
		<form action="regStudent.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Register Students">
		</form>
		<br>
		<form action="profSection.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="Add Proffesor Section">
		</form>
		
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

</body>
</html> 