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
	$condition = $_SESSION['condition'];

	//var_dump($_POST['logout']);
	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2>Semester Detail</h2></center>

	<center>
		<form action="viewSemesterDetail.php" method="POST">
		<input type="hidden" name="userid" value=<?php echo $user_id; ?> />
		<b> Select Program Type:</b> 
		<select name="program">
			<option value=1>Major</option>
			<option value=2>Minor</option>
		</select><br>
		<input type="submit" value="submit">
		</form>
	</center>
	<?php
		if(isset($_POST['program'])) 
		{
			$program = (int)$_POST['program'];

			
			$curs = oci_new_cursor($conn);
			$stid = oci_parse($conn, "begin Database.getSemester(:p_userID,:p_condition,:p_program,:p_profile); end;");
			oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
			oci_bind_by_name($stid, ":p_userID", $user_id);
			oci_bind_by_name($stid, ":p_condition", $condition);
			oci_bind_by_name($stid, ":p_program", $program);
			oci_execute($stid);
			oci_execute($curs);

			$curs2 = oci_new_cursor($conn);
			$stid2 = oci_parse($conn, "begin Database.getProgram(:p_userID,:p_condition,:p_program,:p_profile); end;");
			oci_bind_by_name($stid2, ":p_profile", $curs2, -1, OCI_B_CURSOR);
			oci_bind_by_name($stid2, ":p_userID", $user_id);
			oci_bind_by_name($stid2, ":p_condition", $condition);
			oci_bind_by_name($stid2, ":p_program", $program);
			oci_execute($stid2);
			oci_execute($curs2);

			while (($row2 = oci_fetch_array($curs2))) 
				{

			?>
				<p><label for="Semester">Semester: 20151</label></p>
				<p><label for="Program">Program: <?php echo $row2[0]; ?> / <?php echo $row2[2]; ?></label> </p>
				<p><label for="Level">Level: <?php echo $row2[1]; ?></label> </p>

				<center><h3>Enrolled Courses in Current Semester</h3></center>
			<?php
				}
			?>
			

				<table style="width:100%">
				<tr>
					<th>CourseCode</th>
					<th>CourseName</th>
					<th>Credit</th>
					<th>Section</th>
				</tr>

				<?php

					//echo 'asak';	
				while (($row = oci_fetch_array($curs))) 
				{
					//echo 'asa';	
				?>

				<tr>
					<td><?php echo $row[0]; ?></td>
					<td><?php echo $row[1]; ?></td>
					<td><?php echo $row[2]; ?></td>
					<td><?php echo $row[3]; ?></td>
				</tr>
			
			<?php
				}
			?>	
				</table>
	<?php
		}
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