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

	//var_dump($_POST['logout']);
	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2>My Section Details</h2></center>

	<?php

		
		$curs = oci_new_cursor($conn);
		$stid = oci_parse($conn, "begin Database.getMySections(:p_empID,:p_profile); end;");
		oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
		oci_bind_by_name($stid, ":p_empID", $user_id);
		oci_execute($stid);
		oci_execute($curs);

		$curs2 = oci_new_cursor($conn);
		$stid2 = oci_parse($conn, "begin Database.getMySectionsUp(:p_empID,:p_profile); end;");
		oci_bind_by_name($stid2, ":p_profile", $curs2, -1, OCI_B_CURSOR);
		oci_bind_by_name($stid2, ":p_empID", $user_id);
		oci_execute($stid2);
		oci_execute($curs2);

		$row2 = oci_fetch_array($curs2);


	?>
		<br><br>
		<p><label for="profName">Professor Name: <?php echo $row2[2]. " " . $row2[3] ?></label></p>
		<p><label for="department">Department: <?php echo $row2[16] ?> </label> </p>

		<br><br><br><br>
		<table style="width:100%">
			<tr>
				<th>CourseID</th>
				<th>CourseName</th>
				<th>SectionNo</th>
				<th>NumberOfStudents</th>
				<th></th>
			</tr>

			<?php

			while (($row = oci_fetch_array($curs))) 
			{

			?>

			<tr>
				<td><?php echo $row[0]; ?></td>
				<td><?php echo $row[5]; ?></td>
				<td><?php echo $row[1]; ?></td>
				<td><?php echo $row[11]; ?></td>
				<td>
					<form action="viewStudentList.php" method="POST">
						<input type="hidden" name="courseCode" value=<?php echo $row[0]; ?> />
					   	<input type="hidden" name="section" value=<?php echo $row[1]; ?> />
						<input type="submit" value="View Student List">
					</form>
				</td>
			</tr>

			<?php } ?>
		</table>
	

<div class="backbtn">
	<form action="prof_menu.php" method="POST">
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