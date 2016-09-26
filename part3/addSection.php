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
	
	$sec=(int)$_POST["sec"];
	$cid=(int)$_POST["cid"];
	$capacity=$_POST["cap"];

	if($_POST["addflag"] == "add"){

			$stid2 = oci_parse($conn, "begin Database.addSection(:p_sec,:p_cid,:p_cap,:p_RESULT); end;");
			oci_bind_by_name($stid2, ":p_sec", $sec);
			oci_bind_by_name($stid2, ":p_cid", $cid);
			oci_bind_by_name($stid2, ":p_cap", $capacity);
			oci_bind_by_name($stid2, ":p_RESULT", $result);
			oci_execute($stid2);

			?>

	<center> <h3>
	<?php

		if(trim($result) == "F" ){
					$_SESSION['message'] = "Invalid Course ID!";
				}

	?>

	 </h3> </center>

	<?php
	}

	$curs = oci_new_cursor($conn);
	$stid = oci_parse($conn, "begin Database.showSection(:p_profile); end;");
	oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
	oci_execute($stid);
	oci_execute($curs);

	//var_dump($_POST['logout']);
	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2> Sections of All Courses </h2></center>

	<table style="width:100%">
		<tr>
			<th>Section No</th>
			<th>Course No</th>
			<th>Capacity</th>
			<th>Capacity Used</th>
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

		<h4> Add Section </h4>
		<form action="addSection.php" method="POST">
			<p><label for="sec">Section No:</label> <input type="text" name="sec" value="" required></p>
			<p><label for="cid">Course No:</label><input type="text" name="cid" value="" required></p>
			<p><label for="cap">Capacity:</label> <input type="text" name="cap" value="" required></p>
			<input type="hidden" name="addflag" value="add" />
			<input class="pure-button pure-button-primary" type="submit" value="Add Section">
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