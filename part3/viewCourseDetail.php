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

	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2>View Course Detail</h2></center>
	<br><br><br><br>
	<center>
		<form action="viewCourseDetail.php" method="POST">
		<b> Select Department:</b> 
		<select name="department">
			<?php 
				$curs = oci_new_cursor($conn);
				$stid = oci_parse($conn, "begin Database.getDepartment(:p_profile); end;");
				oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
				oci_execute($stid);
				oci_execute($curs);

				if(isset($_POST['department'])) 
				{
				?>
				<option value="<?php echo urldecode($_POST['department']); ?>"><?php echo urldecode($_POST['department']); ?> </option>
				<?php
				}
				while (($row = oci_fetch_array($curs))) 
				{
			?>
			<option value="<?php echo $row[2]; ?>"><?php echo $row[2]; ?> </option>
			<?php } 
			?>
			
		</select><br>
			<input type="submit" value="search">
		</form>
	</center><br><br>
	
	<?php
		if(isset($_POST['department'])) 
		{
			$department = urldecode($_POST['department']);
			$curs = oci_new_cursor($conn);
			$stid = oci_parse($conn, "begin Database.getCourse(:p_deptID,:p_profile); end;");
			oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
			oci_bind_by_name($stid, ":p_deptID", $department);
			oci_execute($stid);
			oci_execute($curs);
			

			?>
				<div style="float: left; position:fixed; left:35%">
					<form action="viewCourseDetail.php" method="POST">
					<input type="hidden" name="department" value=<?php echo urlencode($department); ?> />
					<b> Select Course:</b> 
					<select name="course">
						<?php 
						if(isset($_POST['course'])) 
						{
						?>
						<option value="<?php echo urldecode($_POST['course']); ?>"><?php echo urldecode($_POST['course']); ?> </option>
						<?php
						}


						while (($row = oci_fetch_array($curs))) 
								{
						?>
						<option value="<?php echo $row[2]; ?>"><?php echo $row[2]; ?></option>
						<?php } ?>
					</select><br>
					<input type="submit" value="search">
					</form>
				</div><br><br><br><br><br><br>
				<?php if(isset($_POST['course'])){?>
				

				<center><h3>Course Details</h3></center>
					<table style="width:100%">
						<tr>
							<th>Section</th>
							<th>Instructor</th>
							<th>Capacity</th>
							<th>CapacityUsed</th>
							<th></th>
						</tr>

						<?php 
							$curs2 = oci_new_cursor($conn);
							$stid2 = oci_parse($conn, "begin Database.getSection(:p_courseName,:p_profile); end;");
							oci_bind_by_name($stid2, ":p_profile", $curs2, -1, OCI_B_CURSOR);
							oci_bind_by_name($stid2, ":p_courseName", $_POST['course']);
							oci_execute($stid2);
							oci_execute($curs2);

							while (($row2 = oci_fetch_array($curs2))) 
							{

						?>

						<tr>
							<td><?php echo $row2[0]; ?></td>
							<td><?php echo $row2[6] . " " . $row2[7]; ?></td>
							<td><?php echo $row2[2]; ?></td>
							<td><?php echo $row2[3]; ?></td>
							<td>
								<form action="section.php" method="POST">
									<input type="hidden" name="courseName" value=<?php echo urlencode($_POST['course']); ?> />
								   	<input type="hidden" name="instructor" value=<?php echo urlencode($row2[6] . " " . $row2[7]); ?> />
									<input type="hidden" name="courseCode" value=<?php echo $row2[1]; ?> />
								   	<input type="hidden" name="section" value=<?php echo $row2[0]; ?> />
									<input type="submit" value="View Section">
								</form>
							</td>	
						</tr>
						<?php } ?>
					</table>

				<?php }?>
			<?php
		}
	?>

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