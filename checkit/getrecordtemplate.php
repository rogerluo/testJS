<?php
	require_once("dbconn.php");
	$RTs = array();
	function GetRecordTemplate(){
		$allhostsql = "select fidname, fidid from RecordTemplate";
		$conn = DBConnect();
		$sql = "select fidname, fidid from RecordTemplate";
		$rs = mysql_query($sql, $conn);
		global $RTs;
		if (!empty($rs) && mysql_num_rows($rs) > 0){
			while($row = mysql_fetch_row($rs)) {
				$RTs[intval($row[1])] = $row[0];
			}
		}
		mysql_close($conn);
	}
	GetRecordTemplate();
?>