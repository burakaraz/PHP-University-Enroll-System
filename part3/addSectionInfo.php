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
	
	$cid=(int)$_POST["cid"];
	$sid=(int)$_POST["sid"];
	$date=$_POST["date"];
	$loc=$_POST["loc"];
	$shour=$_POST["start"];
	$ehour=$_POST["end"];

	if($_POST["addflag"] == "add"){

			$stid2 = oci_parse($conn, "begin Database.addSectionInfo(:p_cid,:p_sid,:p_dateSec,:p_loc,:p_start,:p_end,:p_RESULT); end;");
			oci_bind_by_name($stid2, ":p_cid", $cid);
			oci_bind_by_name($stid2, ":p_sid", $sid);
			oci_bind_by_name($stid2, ":p_dateSec", $date);
			oci_bind_by_name($stid2, ":p_loc", $loc);
			oci_bind_by_name($stid2, ":p_start", $shour);
			oci_bind_by_name($stid2, ":p_end", $ehour);
			oci_bind_by_name($stid2, ":p_RESULT", $result);
			oci_execute($stid2);

			?>

	<center> <h3>
	<?php

		if(trim($result) == "F" ){
					$_SESSION['message'] = "Invalid Course ID or Section No!";
				}

	?>

	 </h3> </center>

	<?php
	}

	$curs = oci_new_cursor($conn);
	$stid = oci_parse($conn, "begin Database.showSectionInfo(:p_profile); end;");
	oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
	oci_execute($stid);
	oci_execute($curs);

	//var_dump($_POST['logout']);
	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2> Section Info </h2></center>

	<table style="width:100%">
		<tr>
			<th>Course No</th>
			<th>Section No</th>
			<th>Date</th>
			<th>Location</th>
			<th>Start Hour</th>
			<th>End Hour</th>
		</tr>

		<?php

			while (($row = oci_fetch_array($curs))) 
				{

		?>
		<tr>
			<td><?php echo $row[0]; ?></td>
			<td><?php echo $row[1]; ?></td>
			<td><?php echo $row[2]; ?></td>
			<td><?php echo $row[3]; ?></td>	
			<td><?php echo $row[4]; ?></td>	
			<td><?php echo $row[5]; ?></td>	
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

		<h4> Add Section Information </h4>
		<form action="addSectionInfo.php" method="POST">
			<p><label for="cid">Course No:</label> <input type="text" name="cid" value="" required></p>
			<p><label for="sid">Section No:</label><input type="text" name="sid" value="" required></p>
			<p><label for="date">Date:</label> <input type="text" name="date" value="" required></p>
			<p><label for="loc">Location:</label> <input type="text" name="loc" value="" required></p>
			<p><label for="start">Start Hour:</label><input type="text" name="start" value="" required></p>
			<p><label for="end">End Hour:</label><input type="text" name="end" value="" required></p>
			<input type="hidden" name="addflag" value="add" />
			<input class="pure-button pure-button-primary" type="submit" value="Add Section Info">
		</form>
</center>

<div class="backbtn">
	<form action="admin_menu.php" method="POST">
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