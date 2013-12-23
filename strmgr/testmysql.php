<?php
	$conn = mysql_connect("localhost", "root", "intel@123");
	if (!$conn || $conn == null){
		echo "failed to connect to mysql.";
	}
	mysql_close($conn);
	echo "success to connect to mysql.";
?>