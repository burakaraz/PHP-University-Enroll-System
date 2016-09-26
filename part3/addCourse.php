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
	$did=(int)$_POST["did"];
	$name=$_POST["name"];
	$credit=(int)$_POST["credit"];


	if($_POST["addflag"] == "add"){

			$stid2 = oci_parse($conn, "begin Database.addCourse(:p_cid,:p_did,:p_name,:p_credit,:p_RESULT); end;");
			oci_bind_by_name($stid2, ":p_cid", $cid);
			oci_bind_by_name($stid2, ":p_did", $did);
			oci_bind_by_name($stid2, ":p_name", $name);
			oci_bind_by_name($stid2, ":p_credit", $credit);
			oci_bind_by_name($stid2, ":p_RESULT", $result);
			oci_execute($stid2);

			?>

	<center> <h3>
	<?php

		if(trim($result) == "F" ){
					$_SESSION['message'] = "Invalid Department ID!";
				}

	?>

	 </h3> </center>

	<?php
	}

	$curs = oci_new_cursor($conn);
	$stid = oci_parse($conn, "begin Database.showCourse(:p_profile); end;");
	oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
	oci_execute($stid);
	oci_execute($curs);

	//var_dump($_POST['logout']);
	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2> Courses </h2></center>

	<table style="width:100%">
		<tr>
			<th>Course Id</th>
			<th>Department Id</th>
			<th>Name</th>
			<th>Credit</th>
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

		<h4> Add Course </h4>
		<form action="addCourse.php" method="POST">
			<p><label for="cid">CourseId:</label> <input type="text" name="cid" value="" required></p>
			<p><label for="did">DepartmentId:</label><input type="text" name="did" value="" required></p>
			<p><label for="name">Name:</label> <input type="text" name="name" value="" required></p>
			<p><label for="credit">Credit:</label><input type="text" name="credit" value="" required></p>
			<input type="hidden" name="addflag" value="add" />
			<input class="pure-button pure-button-primary" type="submit" value="Add Course">
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