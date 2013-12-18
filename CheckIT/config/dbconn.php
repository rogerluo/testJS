<?php
	require_once("config.php");
	function DBConnect() {
		$dbhost = DBHST;
		$dbuser = DBUSR;
		$dbpwd 	= DBPWD;
		$dbinst = DBIST;
		$conn = mysql_connect($dbhost, $dbuser, $dbpwd);
		$result = "";
		if ($conn) mysql_select_db($dbinst, $conn);
		return $conn;
	}
?>
