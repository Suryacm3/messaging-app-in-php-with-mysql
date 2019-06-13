<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="stylehome.css">
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
</script>

</head>
<body>
	<form method="post" name="form" action="homepage.php">
		<?php error_reporting(0); ?>
		<div id="header">
			<div id="username" >
				<?php
				$sid=$_COOKIE["senderid"];
				$sname=$_COOKIE["sendername"];
				$image=$_COOKIE["senderprofileimage"];
				echo "<img id='image' src='". $image ."' height='50' width='50' >";
				echo "<label style='color:#33adff;font-size:30px;padding:10px; float:left;'>".$sname."</label>";
				?>
			</div>
			<div id="messagebox" >
				<TEXTAREA autofocus rows="1" cols="80" placeholder="Type a message here.." name="message" reqiured="TRUE"></TEXTAREA>
				<div id="send"><input type="submit" name="send" value="send">
					<!--input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);" required="true" -->
				</div>
			</div>
			<?php
			
		//echo "<label style='margin-left:500px;font-size:15px;font-style:italic;color:black;'>"."chatting with ".$_COOKIE['rname']."</label>";
		//?>
		<div id="aside" style='background-color:#33adff;'>
			<?php
			include "dbconnection.php";
			$sql = "SELECT id,firstname, lastname,profileimage FROM login";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$i=1;
				while($row = $result->fetch_assoc()) {
					$id[$i]=$row["id"];
					$name[$i]=$row["firstname"]." ".$row["lastname"];
					$profileimage=$row["profileimage"];
					$image="'". $row['profileimage']."'";
					if($id!=$sid){
						echo "<div id='names'>
						<img id='image' src=". $image ." height=50 width=50 padding-right:20px;>
						<div id='name'>
						<input type='submit' style='background-color:#33adff;border:none;font-size:15px;' 
						name='rname[".$i."]' value='".$name[$i]."' autofocus='false' onclick='result=re($i);alert('result');'></div></div>";
					}
					$i++;
				}/*
				if($id != $sid){
					if($_POST["rname_".$id]==$name."_".$id){
						$rid=$id;
						$rname=$name;
						setcookie("rid",$rid,time()+3600);
						setcookie("rname",$rname,time()+3600);
					}
				}*/
			} else {
				echo "0 results";
			}
			$conn->close();
			?>
		</div>
	</form>
	<?php
	if(isset($_POST["send"])){
/*
		if(isset($_POST["file"])){
			//img upload
			$filetobeupload="";
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
					$image="<img src='\" $filetobeupload \" ' height='50' width='50'>";
				} else {
					$msg= "Sorry, there was an error uploading your file.";
				}
			}
		}


*/


		$smessage=$_POST["message"];
		include "dbconnection.php";
		$rid=$_COOKIE["rid"];
		$rname=$_COOKIE["rname"];
		if($smessage !=""){
			$sendsql = "INSERT INTO chatroom (sender_id, receiver_id, sender, receiver, sender_meassage, date_created,rdate_created)
			values ('".$sid."','".$rid."','".$sname."','".$rname."','".$smessage."','".date("F j, Y, g:i a")."','".date("F j, Y, g:i a")."')";

			if ($conn->query($sendsql) === TRUE) {
				$errmsg ="message sent!";
			}else{
				$errmsg= "message not sent!";
			}
		}
		else{
			echo '<script>alert("Message cannot be empty!")</script>';
		}
		?>
		<div id='content'>
			<?php
			$retrivesql= "SELECT sender_meassage,reciever_message,date_created,rdate_created FROM chatroom WHERE sender_id='".$sid."' and receiver_id='".$rid."' ";
			$messageresult = $conn->query($retrivesql);
			if ($messageresult->num_rows > 0) {
				while($row = $messageresult->fetch_assoc()) {
					$stime=$row["date_created"];
					$rtime=$row["rdate_created"];
					$smsg=$row["sender_meassage"];
					$rmsg=$row["reciever_message"];
					?>
					<br>
					<?php if($smsg != ""){?>
						<div id="senderside">
							<div id='stime'>
								<?php 
								echo $stime;
								?>
							</div>
							<br>
							<div id='sendermessages'>
								<?php
								echo "<label>". $smsg."</label>";
								?>
							</div>
						</div>
					<?php }else{} ?>
					<br>
					<?php if($rmsg != ""){?>
						<div id="receiverside">
							<div id='rtime'>
								<?php 
								echo $rtime;
								?>
							</div>
							<br>
							<div id='receivermessages'>
								<?php
								echo "<label>". $rmsg."</label>";
								?>
							</div>
						</div>
					<?php }else{} ?>
					<br>
					<br>
					<br>
					<?php
				}
			}
			else{
				$errmsg ="could not send!";
			}
			?>
		</div>
		<?
		echo "</div>";
	}

	?>
		<!--div id="errors">
			<?php //echo $errmsg; ?>
		</div-->
	</body>
	</html>