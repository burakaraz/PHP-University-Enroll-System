<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">

<style type="text/css">
 .topcorner{
   position:absolute;
   top:5%;
   right:10%;
  }
  .nextbtn{
   position:absolute;
   top:5%;
   right:20%;
  }
  .backbtn{
		position:absolute;
		top:2%;
		right:20%;
	}
  .loginButton{
  	position:absolute;
   	top:20%;
   	margin: 0 auto;
  }
h1 {
	display: block;
	font-size: 2em;
	margin-top: 3cm;
}
h3 {
	display: block;
	margin-top: 4cm;
}
</style>

</head>

<body>

<?php
	$conn = oci_connect("e1818939", "rFEWc", "//144.122.71.31:8085/xe");
	if (!$conn) 
	{
		$m = oci_error();
		echo $m['message'], "\n";
		exit;
	}
	//echo $_POST['userid'];

	

	//var_dump($_POST['logout']);
	?>
	<center><h1>University Data Management System</h1></center>
	<br><br>

	<?php 
		if($_SESSION['false'] == 'false')
		{
	?>
	<center>
		 Incorrect user id or password! Please try again
		
	</center> 
	<?php  
			session_unset();
			session_destroy();
			
		}	
	?>

	<br><br>
	<center>
		<form action="staff_menu.php" method="POST">
		<b> Userid:</b> <input type="text" name="userid" required><br><br>
		<b> Password:</b> <input type="text" name="password" required><br><br>
		<input class="pure-button pure-button-primary" type="submit" value="Login">
		</form>
	</center>
	<?php
		oci_free_statement($stid);
		oci_close($conn);
	
?>

<div class="backbtn">
	<form action="in.php" method="POST">
		<input type="submit" value="Back" />
	</form>
</div>

</body>
</html> 