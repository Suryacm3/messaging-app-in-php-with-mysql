<!DOCTYPE html>
<html>
<head>
	<title>home</title>
	<meta http-equiv="refresh" content="10">
	<link rel="stylesheet" type="text/css" href="homestyle.css">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>

</head>
<body onload=”javascript:setTimeout(“location.reload(true);”,10000);”>
	<form id="form" name="form" method="post" action="home.php" enctype="multipart/form-data">
		<?php
		$url=$_SERVER['REQUEST_URI'];
		header("Refresh: 10; URL=$url");
		?>
		<div id="sidechat">
			<?php

			error_reporting(0);
			include "getuserlist.php";
			$reid=$_COOKIE["rid"];
			$rimage=$_COOKIE["rimage"];
			include 'dbconnection.php';
			$sql = "SELECT id,firstname,lastname,profileimage FROM login where id='".$reid."' ";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()){
					$did=$row["id"];
					$rimage=$row["profileimage"];
					$rname=$row["firstname"]." ".$row["lastname"];
				}
			}
			?>
		</div>
		<div id="messagebox">
			<?php
			$sid=$_COOKIE["senderid"];
			$uname=$_COOKIE["sendername"];
			include "dbconnection.php";	
			$sql = "SELECT id,firstname,lastname FROM login where username='".$uname."' ";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()){
					$sname=$row["firstname"]." ".$row["lastname"];
				}
			}
			$image=$_COOKIE["senderprofileimage"];
			?>
			<?php
			echo "<div id='username'><img id='image' src='". $image ."' height='60' width='60' >";
			echo "<label style='font-style:italic;color:#006080;font-size:25px; '>".$sname."</label></div>";
			echo "<div id='receivername'><img id='image' src='". $rimage ."' height='60' width='60' >";
			echo "<label style='font-style:italic;color:#006080;font-size:25px; '>".$rname."</label></div>";
			?>
			<TEXTAREA autofocus rows="1" cols="80" align="center" placeholder="Type a message here.." name="message" reqiured="TRUE"></TEXTAREA><input type="submit" name="send" value="send" >
			<input type="file" name="f" id="fileToUpload">
		</div>
		<hr>
		<div id="chat">
			<?php
			include "dbconnection.php";
			$smessage=$_POST["message"];
			if(isset($_POST["send"])){
				//message send
				if($smessage !="" ){
					$sendsql = "INSERT INTO chatroom (sender_id, receiver_id, sender, receiver, sender_meassage, date_created,rdate_created)
					values('".$sid."','".$did."','".$sname."','".$rname."','".$smessage."','".date("F j, Y, g:i a")."','".date("F j, Y, g:i a")."')";
					if ($conn->query($sendsql) === TRUE) {
						$errmsg ="message sent!";
					}else{
						$errmsg= "message not sent!";
					}
				}
				else{
				//uploads
					$fnm=$_FILES["f"]["name"];
					$dst="uploads/".$fnm;
					move_uploaded_file($_FILES["f"]["tmp_name"], $dst);
					$sendsql="INSERT INTO chatroom (sender_id, receiver_id, sender, receiver, sender_meassage, date_created,rdate_created)
					values ('".$sid."','".$did."','".$sname."','".$rname."','".$dst."','".date("F j, Y, g:i a")."','".date("F j, Y, g:i a")."')";
					if ($conn->query($sendsql)== TRUE){
						//echo "<script>alert('inserted')</script>";
					}
				}			
			}
			?>
			<div id='content'>
				<div id="senderside">
					<?php
					$retrivesql= "SELECT sender_id,sender_meassage,date_created FROM chatroom WHERE sender_id='".$sid."' and receiver_id='".$did."' ";
					$messageresult = $conn->query($retrivesql);
					if ($messageresult->num_rows > 0) {
						while($row = $messageresult->fetch_assoc()) {
							$senderid=$row["sender_id"];
							$stime=$row["date_created"];
							$smsg=$row["sender_meassage"];
							?>
							<br>
							<?php if($smsg != ""){?>
								<div id='stime'>
									<?php 
									echo $stime;
									?>
								</div>
								<br>
								<div id='sendermessages'>
									<?php
									$supported_image_formats = array('gif','jpg','jpeg','png');
									$supported_video_formats = array('mp4','mov','avi','mp3');
									$supported_file_formats = array('doc','txt','zip','pdf');
									$src_file_name = $smsg;
									$src_link='"'.$src_file_name.'"';
									$ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); 
									if (in_array($ext, $supported_image_formats)) {
										echo "<img src=$src_link height='70' width='70' align='left'>";
										//echo "<img src=$src_link height='70' width='70' align='left'><a href='uploads/' download=image.jpg align=left>download</a>";
									} 
									elseif(in_array($ext, $supported_video_formats)) {
										echo "<video width='320' height='240' controls style='outline:none;' align='left'><source src=$src_link type='video/mp4' ></video>";
									}
									elseif(in_array($ext, $supported_file_formats)) {
										echo "<label>".$smsg."</label>"." --- file sent";
										//echo '<a href="uploads\" download="downloadfile.txt">Download</a><br>';
										
									}
									else {
										//echo "<script>alert('invalid file format');</script>";
										echo "<label>". $smsg."</label>";
									}
									?>
								</div>

							<?php }else{} ?>
							<br><br>
							<?php
						}
					}
					?>
				</div><br><br>
				<div id="receiverside">
					<?php
					$retrivesql= "SELECT sender_meassage,date_created FROM chatroom WHERE sender_id='".$did."' and receiver_id='".$sid."' ";
					$messageresult = $conn->query($retrivesql);
					if ($messageresult->num_rows > 0) {
						while($row = $messageresult->fetch_assoc()) {
							$rtime=$row["date_created"];
							$rmsg=$row["sender_meassage"];
							?>
							<br>
							<?php if($rmsg != ""){?>

								<div id='rtime'>
									<?php 
									echo $rtime;
									?>
								</div>
								<br>
								<div id='receivermessages'>
									<?php
									$supported_image_formats = array('gif','jpg','jpeg','png');
									$supported_video_formats = array('mp4','mov','avi','mp3');
									$supported_file_formats = array('doc','txt','zip','pdf');
									$src_file_name = $rmsg;
									$src_link='"'.$src_file_name.'"';
									$ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); 
									if (in_array($ext, $supported_image_formats)) {
										echo "<img src=$src_link height='70' width='70' align='right'><a href='uploads/' download=image.jpg align=right>download</a>";
									} 
									elseif(in_array($ext, $supported_video_formats)) {
										echo "<video width='320' height='240' controls style='outline:none;' align='right'><source src=$src_link type='video/mp4'></video>";
									}
									elseif(in_array($ext, $supported_file_formats)) {
										echo "<label>". $rmsg."</label>";
										//echo "It's file";
										echo '<a href="download.php?id=<? echo $senderid ?>">download</a>';
									}
									else {
										echo "<label>". $rmsg."</label>";
									//	echo "<script>alert('invalid file format');</script>";
									}
									?>
								</div>

							<?php }else{} ?>
							<br><br>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
	</form>
	<script>
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
		}
	</script>
</body>
</html>