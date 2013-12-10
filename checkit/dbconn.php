<?php
	function DBConnect() {
		$dbhost = "localhost";
		$dbuser = "root";
		$dbpwd 	= "";
		$dbinst = "checkit";
		$conn = mysql_connect($dbhost, $dbuser, $dbpwd);
		$result = "";
		if ($conn) mysql_select_db($dbinst, $conn);
		return $conn;
	}
	 
?>
