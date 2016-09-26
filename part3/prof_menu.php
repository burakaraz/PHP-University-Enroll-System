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

		$stid = oci_parse($conn, "begin Database.loginProf(:p_user_id,:p_password,:p_RESULT); end;");
		oci_bind_by_name($stid, ":p_user_id", $user_id);
		oci_bind_by_name($stid, ":p_password", $password);
		oci_bind_by_name($stid, ":p_RESULT", $result);
		oci_execute($stid);

		if(trim($result) == "F" ){
			$_SESSION["false"] = 'false';
			header('Location: prof_login.php');
		}

		$_SESSION["id"] = $user_id;
		$_SESSION["condition"] = $condition;
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
	
		<form action="viewProfInfo.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="View Personal Information">
		</form>
		<br>
		<form action="viewMySections.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="View My Sections">
		</form>
		<br>
		<form action="viewMyAdvisedStudents.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="View My Advised Students">
		</form>
		<br>
		<form action="viewCourseDetailP.php" method="POST">
			<input class="pure-button pure-button-primary" type="submit" value="View Course Details">
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