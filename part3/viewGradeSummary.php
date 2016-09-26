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

	$condition = $_SESSION["condition"];
	$user_id=(int)$_SESSION["id"];

	$curs = oci_new_cursor($conn);
	$stid = oci_parse($conn, "begin Database.gradeSummary(:p_userID,:p_condition,:p_profile); end;");
	oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
	oci_bind_by_name($stid, ":p_userID", $user_id);
	oci_bind_by_name($stid, ":p_condition", $condition);
	oci_execute($stid);
	oci_execute($curs);

	$curs2 = oci_new_cursor($conn);
	$stid2 = oci_parse($conn, "begin Database.getGradeUpInfo(:p_userID,:p_condition,:p_profile); end;");
	oci_bind_by_name($stid2, ":p_profile", $curs2, -1, OCI_B_CURSOR);
	oci_bind_by_name($stid2, ":p_userID", $user_id);
	oci_bind_by_name($stid2, ":p_condition", $condition);
	oci_execute($stid2);
	oci_execute($curs2);

	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2>Grade Summary</h2></center>

	<?php
		while (($row2 = oci_fetch_array($curs2))) 
			{
	?>
	<p><label for="Name">Name: <?php echo $row2[2]; ?></label></p>
	<p><label for="Surname">Surname: <?php echo $row2[3]; ?></label> </p>
	<p><label for="Faculty">Faculty: <?php echo $row2[14]; ?></label> </p>
	<p><label for="Department">Department: <?php echo $row2[12]; ?></label> </p>
	<?php
			}
	?>


	<table style="width:100%">
		<tr>
			<th>Semester</th>
			<th>CourseCode</th>
			<th>CourseName</th>
			<th>Credit</th>
			<th>Grade</th>
		</tr>
		<?php
			while (($row = oci_fetch_array($curs))) 
			{
		?>
		<tr>
			<td><?php echo $row[3]; ?></td>
			<td><?php echo $row[2]; ?></td>
			<td><?php echo $row[8]; ?></td>
			<td><?php echo $row[9]; ?></td>
			<td><?php echo $row[4]; ?></td>
		</tr>
		<?php
			}
		?>
	</table>
	<?php

	
?>

<div class="backbtn">
	<form action="menu.php" method="POST">
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