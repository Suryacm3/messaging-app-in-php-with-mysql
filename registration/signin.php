<!DOCTYPE html>
<html>
<head>
	<title>Signin</title>
	<link rel="stylesheet" type="text/css" href="stylesignin.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#form").validate();
		});
	</script>
</head>
<body>
	<?php
	error_reporting(0);
	if(isset($_POST["signin"])){
		$msg="";
		$username=$_POST["username"];
		$password=$_POST["password"];
		$conn = new mysqli("localhost","root","","surya");
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT * FROM login WHERE username = '".$username."' and password = '".$password."' ";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$senderid=$row["id"];
				$sendername=$row["username"];
				$senderprofileimage=$row["profileimage"];
			}
			setcookie("sendername", $username, time()+12800);
			setcookie("senderid",$senderid,time()+12800);
			setcookie("senderprofileimage",$senderprofileimage,time()+12800);
			header("location:home.php");
		}else{
			$msg= "NOTE: please check your inputs";
		}
		$conn->close();
	}
	?>
	<form id="form" name="form" method="post" action="signin.php">
		<div id="formid">
			<input type="text" name="username" placeholder="username" required><br>
			<input type="password" name="password" placeholder="password"  minlength="6" title="invalid password" required="true"><br>
			<div>
				<input type="submit" name="signin" value="signin">
				<input type="reset" name="reset" value="reset">
				<a href="signup.php">create account</a>
			</div>
			<br>
			<br>
			<div id="spann">
				<span  class="message">
					<?php echo ($msg != "") ? $msg : "" ?>
				</span>
			</div>
		</div>
	</form>
</body>
</html>