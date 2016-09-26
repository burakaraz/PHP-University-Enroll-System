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
	<center><h2>Section Detail</h2></center>

	<?php

		$courseCode = $_POST['courseCode'];
		$section = $_POST['section'];
		
		$curs = oci_new_cursor($conn);
		$stid = oci_parse($conn, "begin Database.getSectionInfo(:p_courseCode,:p_section,:p_profile); end;");
		oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
		oci_bind_by_name($stid, ":p_courseCode", $courseCode);
		oci_bind_by_name($stid, ":p_section", $section);
		oci_execute($stid);
		oci_execute($curs);

	?>
		<br><br>
		<p><label for="courseName">Course Name:<?php echo urldecode($_POST['courseName']); ?></label></p>
		<p><label for="Instructor">Instructor: <?php echo urldecode($_POST['instructor']); ?></label> </p>
		<p><label for="section">Section No: <?php echo $section; ?></label></p>

		<br><br><br><br>
		<table style="width:100%">
			<tr>
				<th>StartHour</th>
				<th>EndHour</th>
				<th>Date</th>
				<th>Location</th>
			</tr>

			<?php

			while (($row = oci_fetch_array($curs))) 
			{

			?>

			<tr>
				<td><?php echo $row[4]; ?></td>
				<td><?php echo $row[5]; ?></td>
				<td><?php echo $row[2]; ?></td>
				<td><?php echo $row[3]; ?></td>
			</tr>

			<?php } ?>
		</table>
	

<div class="backbtn">
	<form action="viewCourseDetailP.php" method="POST">
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