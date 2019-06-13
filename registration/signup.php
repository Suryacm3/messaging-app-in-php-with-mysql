<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
	<link rel="stylesheet" href="stylesignup.css">
	<!--profile-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#form").validate();
		});
	</script>
	<script type="text/javascript">
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$('#blah')
					.attr('src', e.target.result)
					.width(150)
					.height(150);
				};

				reader.readAsDataURL(input.files[0]);
			}
		}

	</script>

</head>
<body>
	<?php
	error_reporting(0);
	if(isset($_POST["submit"])){
		$firstname=$_POST["firstname"];
		$lastname=$_POST["lastname"];
		$username=$_POST["username"];
		$password=$_POST["password"];
		$confirmpassword=$_POST["confirmpassword"];
		$email=$_POST["email"];
		$phonenumber=$_POST["phonenumber"];	
		$msg="";
				//img upload
		$profileimage="";
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				        //$msg= "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				$msg= "File is not an image.";
				$uploadOk = 0;
			}
		}
				//if (file_exists($target_file)) {
				   // $msg= "Sorry, file already exists.";
				  //  $uploadOk = 0;
				//}
		if ($_FILES["fileToUpload"]["size"] > 500000) {
			$msg="Sorry, your file is too large.";
			$uploadOk = 0;
		}
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			$msg="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		if ($uploadOk == 0) {
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				      // $msg= "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				$profileimage="uploads/".basename( $_FILES["fileToUpload"]["name"]);
				$image="<img src='\" $profileimage \" ' height='50' width='50'>";
			} else {
				$msg= "Sorry, there was an error uploading your file.";
			}
		}

		//db connection
		$conn = new mysqli("localhost","root","","surya");
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "INSERT INTO login (firstname, lastname,username,password,confirmpassword,email,phonenumber,profileimage)
		values ('".$firstname."','".$lastname."','".$username."','".$password."','".$confirmpassword."','".$email."','".$phonenumber."','".$profileimage."')";
		if($password == $confirmpassword){
			if ($conn->query($sql) === TRUE) {
				echo '<div id="example" style="display: hidden;width:100%,height:100%"></div>';
				echo "<script type='text/javascript'>alert('signup successful');
				window.location.href='registration/signin.php';</script>";
			} 
		}else{
			$msg="NOTE: password and confirmpassword must be match.";
		}
		$conn->close();
	}
	?>

	<form method="post" action="signup.php" enctype="multipart/form-data" id="form"><br>
		<div id="formid">
			<div id="profilebutton">
				<img id="blah" /><br>
				choose profile:<input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);" required="true" >
			</div><br>
			<input type="text" name="firstname" placeholder="First name" id="firstname" value="<?php echo $_POST['firstname']; ?>" maxlength="20" pattern="[A-Za-z]{1,32}"  required>
			<br>
			<input type="text" name="lastname" placeholder="last name" id="lastname" value="<?php echo $_POST['lastname']; ?>" 
			maxlength="20" pattern="[A-Za-z]{1,32}" title="name should have letters only" required>
			<br>
			<input type="text" name="username" placeholder="username" id="username" value="<?php echo $_POST['username']; ?>" 
			maxlength="20" pattern="[A-Za-z]{1,32}" title="name should have letters only" required>
			<br>
			<input type="password" name="password" placeholder="password" id="password" value="<?php echo $_POST['password']; ?>" 
			minlength="6" required>
			<br>
			<input type="password" name="confirmpassword" placeholder="confirm password" id="confirmpassword" value="<?php echo $_POST['confirmpassword']; ?>" minlength="6"  required>
			<br>
			<input type="email" name="email" placeholder="email" id="email" value="<?php echo $_POST['email']; ?>" maxlength="30" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
			<br>	
			<input type="text" name="phonenumber" placeholder="phone number" id="phonenumber" pattern="^\d{10}$" maxlength="10" title="phoneumber should have numbers only" required="true" value="<?php echo $_POST['phonenumber']; ?>" >
			<br><br>
			<div id="buttons"> 
				<input type="submit" name="submit" value="signup">
				<input type="reset" name="reset" value="reset" ><br><br>
			</div>
			<footer>
				<a href="signin.php">Already having an account!</a>
			</footer>
			<br><br>
			<div id="spann">
				<span  class="message">
					<?php echo  $msg; ?>
				</span>
			</div>
		</div>
	</form>
</body>
</html>