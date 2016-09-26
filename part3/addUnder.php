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
	
	$stuid=(int)$_POST["StudentId"];
	$depid=(int)$_POST["DeptID"];
	$name=$_POST["Name"];
	$surname=$_POST["Surname"];
	$nat=(int)$_POST["NatID"];
	$email=$_POST["email"];
	$phone=(int)$_POST["phone"];
	$address=$_POST["adress"];
	$password=(int)$_POST["password"];
	$cgpa=(float)$_POST["cGPA"];

	

	if($_POST["addflag"] == "add"){

			$stid2 = oci_parse($conn, "begin Database.addUnder(:p_stuId,:p_deptId,:p_name,:p_surname,:p_natId,:p_email,:p_phone,:p_address,:p_password,:p_cgpa,:p_RESULT); end;");
			oci_bind_by_name($stid2, ":p_stuID", $stuid);
			oci_bind_by_name($stid2, ":p_deptID", $depid);
			oci_bind_by_name($stid2, ":p_name", $name);
			oci_bind_by_name($stid2, ":p_surname", $surname);
			oci_bind_by_name($stid2, ":p_natId", $nat);
			oci_bind_by_name($stid2, ":p_email", $email);
			oci_bind_by_name($stid2, ":p_phone", $phone);
			oci_bind_by_name($stid2, ":p_address", $address);
			oci_bind_by_name($stid2, ":p_password", $password);
			oci_bind_by_name($stid2, ":p_cgpa", $cgpa);
			oci_bind_by_name($stid2, ":p_RESULT", $result);
			oci_execute($stid2);
			?>

			<center> <h3>
			<?php

				if(trim($result) == "F" ){
					$_SESSION['message'] = "Add operation failed!";
				}

			?>

	 </h3> </center>

	 <?php
	}



	$curs = oci_new_cursor($conn);
	$stid = oci_parse($conn, "begin Database.showUnder(:p_profile); end;");
	oci_bind_by_name($stid, ":p_profile", $curs, -1, OCI_B_CURSOR);
	oci_execute($stid);
	oci_execute($curs);

	//var_dump($_POST['logout']);
	?>
	<center><h1>University Data Management System</h1></center>
	<center><h2>Under Graduate Student </h2></center>

	<table style="width:100%">
		<tr>
			<th>StudentId</th>
			<th>DeptId</th>
			<th>Name</th>
			<th>Surname</th>
			<th>National Id</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Address</th>
			<th>Password</th>
			<th>cGPA</th>
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
			<td><?php echo $row[6]; ?></td>
			<td><?php echo $row[7]; ?></td>	
			<td><?php echo $row[8]; ?></td>
			<td><?php echo $row[9]; ?></td>	

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

		<h4> Add Under Graduate Student </h4>
		<form action="addUnder.php" method="POST">
			<p><label for="StudentId">StudentId:</label> <input type="text" name="StudentId" value="" required></p>
			<p><label for="DeptID">DeptID:</label><input type="text" name="DeptID" value="" required></p>
			<p><label for="Name">Name:</label> <input type="text" name="Name" value="" required></p>
			<p><label for="Surname">Surname:</label><input type="text" name="Surname" value="" required></p>
			<p><label for="NatID">NatID:</label> <input type="text" name="NatID" value="" required></p>
			<p><label for="email">E-mail:</label><input type="text" name="email" value=""></p>
			<p><label for="phone">Phone:</label> <input type="text" name="phone" value=""></p>
			<p><label for="adress">Adress:</label><input type="text" name="adress" value=""></p>
			<p><label for="password">Password:</label> <input type="text" name="password" value="" required></p>
			<p><label for="cGPA">cGPA:</label><input type="text" name="cGPA" value="" required></p>
			<input type="hidden" name="addflag" value="add" />
			<input class="pure-button pure-button-primary" type="submit" value="Add Student">
		</form>
</center>

<div class="backbtn">
	<form action="addStudent.php" method="POST">
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