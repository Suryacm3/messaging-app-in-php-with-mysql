<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="stylehome.css">
	<script type="text/javascript">
		function re(i){
			return 	document.cookie = "rid = " + i;
		}
	</script>
</head>
<body>

</body>
</html>

<?php
include "dbconnection.php";
$sid=$_COOKIE["senderid"];
$sql = "SELECT id,firstname, lastname,profileimage FROM login";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$i=1;
	while($row = $result->fetch_assoc()) {
		$rid="";
		$id[$i]=$row["id"];
		$name[$i]=$row["firstname"]." ".$row["lastname"];
		$profileimage=$row["profileimage"];
		$image[$i]="'". $row['profileimage']."'";
		if($i!=$sid){
			echo "<div id='names'>
			<img id='image' src=". $image[$i] ." height=70 width=70 '>
			<div id='name'>
			<input type='submit' style='outline:none;background-color:#33adff;border:none;font-size:15px;'
			name='rname[".$i."]' value='".$name[$i]."' autofocus='false' onclick='re($i);'></div></div>";
		}
		$i++;
	}
	$rid= $_COOKIE['rid'];
	if($rid != $sid){
		$rid=$id;
		setcookie("rid",$rid,time()+3600);
	}
} else {
	echo "0 results";
}
$conn->close();
?>