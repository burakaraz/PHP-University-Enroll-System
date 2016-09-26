<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">

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
</head>


<body>

	<center><h1>University Data Management System</h1></center>
	<center><h2>Personal Information</h2></center>
	<br><br><br><br>

<?php

	$conn = oci_connect("e1818939", "rFEWc", "//144.122.71.31:8085/xe");

	$user_id=(int)$_SESSION["id"];


	if(isset($_POST['address']) || isset($_POST['email']) || isset($_POST['phoneNo'])) {

		$stid = oci_parse($conn, "begin Database.updateStaffProfile(:p_userID,:p_address,:p_phone,:p_email,:p_RESULT); end;");
		oci_bind_by_name($stid, ":p_userID", $user_id);
		oci_bind_by_name($stid, ":p_address", $_POST['address']);
		oci_bind_by_name($stid, ":p_phone", $_POST['phoneNo']);
		oci_bind_by_name($stid, ":p_email", $_POST['email']);
		oci_bind_by_name($stid, ":p_RESULT", $result);
		oci_execute($stid);

		?>

	<center> <h3>
	<?php

		if(trim($result) == "F" ){
			echo "Please write your email in the form of myemail@something.com";
		}

	?>

	 </h3> </center>

	<?php
	}
	

	$curs = oci_new_cursor($conn);
	$stid = oci_parse($conn, "begin Database.getStaffProfile(:p_userID,:p_profile); end;");
	oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
	oci_bind_by_name($stid, ":p_userID", $user_id);
	oci_execute($stid);
	oci_execute($curs);
	
	while (($row = oci_fetch_array($curs))) 
	{
		
	?>
	
	<center>
		<p><label for="studID">Employee ID: <?php echo $row[0]; ?></label></p>
		<p><label for="name">Name: <?php echo $row[2]; ?></label> </p>
		<p><label for="surname">Surname: <?php echo $row[3]; ?></label></p>
		<br><br><br><br>		

		<form action="viewStaffInfo.php" method="POST">
			<input type="hidden" name="userid" value=<?php echo $userid; ?> />
			<p><label for="address">Address:</label> <input type="text" name="address" value="<?php echo $row[8]; ?>"></p>
			<p><label for="phoneNo">Phone Number:</label> <input type="text" name="phoneNo" value="<?php echo $row[7]; ?>"></p>
			<p><label for="email">Email:</label> <input type="text" name="email" value="<?php echo $row[6]; ?>"></p>
			<input class="pure-button pure-button-primary" type="submit" value="Apply Changes">
		</form>
	</center>
	<?php
	}
	
?>

<div class="backbtn">
	<form action="staff_menu.php" method="POST">
		<input type="hidden" name="back" value=<?php echo 2; ?> />
		<input type="submit" value="Back" />
	</form>
</div>

<div class="topcorner">
    <form action="in.php" method="POST">
    	<input type="hidden" name="logout" value="1" />
		<input type="submit" value="Logout" />
	</form>
</div>

</body>
</html> 