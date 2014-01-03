<?php
	require_once("../config/dbconn.php");
	//echo var_dump($argv);
	if (count($argv) >= 3){
		$region = trim($argv[1]);
		$logtype = trim($argv[2]);
	}
	if (isset($_GET['region']) && !empty($_GET['region'])){
		$region = $_GET['region'];
	}
	if (isset($_GET['logtype']) && !empty($_GET['logtype'])){
		$logtype = $_GET['logtype'];
	}
	$conn = DBConnect();
	if ($conn){
		$sql = "select h.IP, h.User, h.Name, h.Password, c.Name, r.Name from Host h inner join Component c on h.ComponentId = c.Id inner join Region r on r.Id = h.RegionId where r.Name ='".$region."'";
		$rs = mysql_query($sql, $conn);
		if ($rs && mysql_num_rows($rs) > 0){
			while ($row = mysql_fetch_row($rs)){
				// TODO
				// IP: $row[0]
				// User: $row[1]
				// HostName: $row[2]
				// Password: $row[3]
				// CompoentName: $row[4]
				// RegionName: $row[5]
				// 
			}
		}else{
			echo "non match";
		}
	}else{
		echo "failed to connect to database.";
	}
?>
