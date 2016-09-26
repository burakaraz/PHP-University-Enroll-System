<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">

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

<?php

	$conn = oci_connect("e1818939", "rFEWc", "//144.122.71.31:8085/xe");

	$user_id=(int)$_SESSION["id"];
	
	$pid=(int)$_POST["pid"];
	$sid=(int)$_POST["sid"];
	$did=(int)$_POST["did"];
	$flag = 0;
	

	if($_POST["addflag"] == "add"){

			$stid2 = oci_parse($conn, "begin Database.addReg(:p_pid,:p_sid,:p_did,:p_flag,:p_RESULT); end;");
			oci_bind_by_name($stid2, ":p_pid", $pid);
			oci_bind_by_name($stid2, ":p_sid", $sid);
			oci_bind_by_name($stid2, ":p_did", $did);
			oci_bind_by_name($stid2, ":p_flag", $flag);
			oci_bind_by_name($stid2, ":p_RESULT", $result);
			oci_execute($stid2);


			?>

			<center> <h3>
			<?php

				if(trim($result) == "F" ){
					$_SESSION['message'] = "Invalid departmentID, programID or studentID!";
				}

			?>

			 </h3> </center>

			 <?php
			}

	$curs = oci_new_cursor($conn);
	$stid = oci_parse($conn, "begin Database.showProg(:p_profile); end;");
	oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
	oci_execute($stid);
	oci_execute($curs);

	//var_dump($_POST['logout']);
	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2> Programs </h2></center>

	<table style="width:100%">
		<tr>
			<th>Program ID</th>
			<th>Dapartment ID</th>
			<th>Level</th>
		</tr>

		<?php

			while (($row = oci_fetch_array($curs))) 
				{

		?>
		<tr>
			<td><?php echo $row[0]; ?></td>
			<td><?php echo $row[1]; ?></td>
			<td><?php echo $row[2]; ?></td>
		<?php
			}
		?>
	</table>

	<?php

		$curs2 = oci_new_cursor($conn);
		$stid2 = oci_parse($conn, "begin Database.showUnderReg(:p_profile); end;");
		oci_bind_by_name($stid2, ":p_profile", $curs2, -1, OCI_B_CURSOR);
		oci_execute($stid2);
		oci_execute($curs2);

	?>

	<center><h2> Unregistered Students </h2></center>

	<table style="width:100%">
		<tr>
			<th>Student ID</th>
			<th>Dapartment ID</th>
			<th>Name</th>
			<th>Surname</th>
		</tr>

		<?php

			while (($row2 = oci_fetch_array($curs2))) 
				{

		?>
		<tr>
			<td><?php echo $row2[0]; ?></td>
			<td><?php echo $row2[1]; ?></td>
			<td><?php echo $row2[2]; ?></td>
			<td><?php echo $row2[3]; ?></td>	
		<?php
			}
		?>
	</table>

	<center> <h3>
	<?php
		echo $_SESSION['message'];
		$_SESSION['message'] = '';
	?>

	 </h3> </center>

<center>	

		<h4> Register Student </h4>
		<form action="addUnderReg.php" method="POST">
			<p><label for="pid">Program ID:</label> <input type="text" name="pid" value="" required></p>
			<p><label for="sid">Student ID:</label><input type="text" name="sid" value="" required></p>
			<p><label for="sid">Department ID:</label><input type="text" name="did" value="" required></p>
			<input type="hidden" name="addflag" value="add" />
			<input class="pure-button pure-button-primary" type="submit" value="Register Student">
		</form>
</center>

<div class="backbtn">
	<form action="regStudent.php" method="POST">
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