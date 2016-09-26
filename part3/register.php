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
	$condition = $_SESSION["condition"];
	$program = $_POST["program"];

	if($_POST["dropflag"] == "drop"){
			$stid3 = oci_parse($conn, "begin Database.enrollDropCourse(:p_userID,:p_condition,:p_courseID,:p_sectionNo); end;");
			oci_bind_by_name($stid3, ":p_userID", $user_id);
			oci_bind_by_name($stid3, ":p_condition", $condition);
			oci_bind_by_name($stid3, ":p_sectionNo", $_POST["section"]);
			oci_bind_by_name($stid3, ":p_courseID", $_POST["courseCode"]);
			oci_execute($stid3);

	}

	if($_POST["addflag"] == "add"){
			$stid2 = oci_parse($conn, "begin Database.enrollAddCourse(:p_userID,:p_condition,:p_courseID,:p_program,:p_sectionNo,:p_RESULT); end;");
			oci_bind_by_name($stid2, ":p_userID", $user_id);
			oci_bind_by_name($stid2, ":p_condition", $condition);
			oci_bind_by_name($stid2, ":p_program", $program);
			oci_bind_by_name($stid2, ":p_sectionNo", $_POST["section"]);
			oci_bind_by_name($stid2, ":p_courseID", $_POST["courseID"]);
			oci_bind_by_name($stid2, ":p_RESULT", $result);
			oci_execute($stid2);

			?>

	<center> 
	<?php

		if(trim($result) == "P" ){
			$_SESSION['message'] = "You already added this course.";
		}
		if(trim($result) == "F" ){
			$_SESSION['message'] = "Add Operation Failed: Invalid courseID or section";
		}

	?>

	  </center>

	<?php

	}

	if($_POST["updateflag"] == "update"){
			$stid4 = oci_parse($conn, "begin Database.enrollUpdateSection(:p_userID,:p_condition,:p_courseID,:p_sectionNo,:p_RESULT); end;");
			oci_bind_by_name($stid4, ":p_userID", $user_id);
			oci_bind_by_name($stid4, ":p_condition", $condition);
			//oci_bind_by_name($stid2, ":p_program", $program);
			oci_bind_by_name($stid4, ":p_sectionNo", $_POST["section"]);
			oci_bind_by_name($stid4, ":p_courseID", $_POST["courseID"]);
			oci_bind_by_name($stid4, ":p_RESULT", $result);
			oci_execute($stid4);

			?>
	
	<?php

		if(trim($result) == "F" ){
			$_SESSION['message'] = "Update Operation Failed: Invalid courseID or section";
		}

	?>

	<?php

	}

	$curs = oci_new_cursor($conn);
			$stid = oci_parse($conn, "begin Database.registration(:p_userID,:p_condition,:p_profile); end;");
			oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
			oci_bind_by_name($stid, ":p_userID", $user_id);
			oci_bind_by_name($stid, ":p_condition", $condition);
			oci_execute($stid);
			oci_execute($curs);

	//var_dump($_POST['logout']);
	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2>Course Registration</h2></center>

	<table style="width:100%">
		<tr>
			<th>CourseCode</th>
			<th>CourseName</th>
			<th>Credit</th>
			<th>Section</th>
			<th></th>
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

			<td>
				<form action="register.php" method="POST">
					<input type="hidden" name="courseCode" value=<?php echo $row[0]; ?> />
				   	<input type="hidden" name="section" value=<?php echo $row[3]; ?> />
				   	<input type="hidden" name="dropflag" value="drop" />
					<input type="submit" value="Drop Course">
				</form>
			</td>

		<?php
			}
		?>
	</table>
	<br><br>
	<center> <h3>
	<?php
		echo $_SESSION['message'];
		$_SESSION['message'] = '';
	?>

	</h3> </center>

<center>	

		<h4> Add Course </h4>
		<form action="register.php" method="POST">
			<input type="hidden" name="userid" value=<?php echo $userid; ?> />
			<input type="hidden" name="condition" value=<?php echo $condition; ?> />
			<input type="hidden" name="addflag" value="add" />
			<p><label for="courseID">CourseID:</label> <input type="text" name="courseID" value=""></p>
			<p><label for="section">Section:</label><input type="text" name="section" value=""></p>
			<p><label for="program">Program:</label><select name="program">
				<option value="1">Major</option>
				<option value="2">Minor</option>
			</select></p>
			<input class="pure-button pure-button-primary" type="submit" value="Add Course">
		</form>

		<h4> Update Course </h4>
		<form action="register.php" method="POST">
			<input type="hidden" name="userid" value=<?php echo $userid; ?> />
			<input type="hidden" name="condition" value=<?php echo $condition; ?> />
			<input type="hidden" name="updateflag" value="update" />
			<p><label for="courseID">CourseID:</label> <input type="text" name="courseID" value=""></p>
			<p><label for="section">Section:</label><input type="text" name="section" value=""></p>
			<input class="pure-button pure-button-primary" type="submit" value="Update Course">
		</form>
	</center>

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