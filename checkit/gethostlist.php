<?php

	require 'dbconn.php';                    
	$conn = DBConnect();
	$result = "";
	$hostresult = "";
	if (!conn)
		$result = '<tr><td colspan=8>Could not connect to mysql server:' . mysql_error() .'</td></tr>';
	else {
		// get host list
		$allhostsql = "select c.name as Product, r.Name as Region, h.Name as ServerName, h.IP, h.User, h.Password, h.CreateDate, h.UPdatedate from Host h join Component c on h.componentid = c.id join Region r on h.regionid = r.id";
		$rs = mysql_query($allhostsql, $conn);
		$hosts = array();
		if (!rs) $hostresult = '<tr><td colspan=8>Could not execute query:' . mysql_error() .'</td></tr>';
		else {
			if (mysql_num_rows($rs) > 0){
				while($row = mysql_fetch_row($rs)) {
					// $result .= "<tr><td>".$row['Product']."</td>";
					// $result .= "<td>".$row['Region']."</td>";   
					// $result .= "<td>".$row['ServerName']."</td>";   
					// $result .= "<td>".$row['IP']."</td>";   
					// $result .= "<td>".$row['User']."</td>";   
					// $result .= "<td>".$row['Password']."</td>";   
					// $result .= "<td>".$row['CreateDate']."</td>";   
					// $result .= "<td>".$row['CreateDate']."</td></tr>"; 
					// $result .= "<tr><td>".$row[0]."</td>";
					// $result .= "<td>".$row[1]."</td>";   
					// $result .= "<td>".$row[2]."</td>";   
					// $result .= "<td>".$row[3]."</td>";   
					// $result .= "<td>".$row[4]."</td>";   
					// $result .= "<td>".$row[5]."</td>";   
					// $result .= "<td>".$row[6]."</td>";   
					// $result .= "<td>".$row[7]."</td></tr>"; 
					$hosts[] = $row;
					// $hosts[] = array(
						// $row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7]
					// );
				}
			}
			else {
				$hostresult = '<tr><td colspan=8>Could not execute query:' . mysql_error() .'</td></tr>';
			}
			//$result = json_encode($hosts);
		}
		// get region list
		$allregionsql = "select distinct Name from Region";
		$rs = mysql_query($allregionsql, $conn);
		$regions = array();
		$regionsresult = "";
		if (!rs) $regionsresult = '<tr><td colspan=8>Could not execute query:' . mysql_error() .'</td></tr>';
		else {
			if (mysql_num_rows($rs) > 0){
				while($row = mysql_fetch_row($rs)) {
					// $result .= "<tr><td>".$row['Product']."</td>";
					// $result .= "<td>".$row['Region']."</td>";   
					// $result .= "<td>".$row['ServerName']."</td>";   
					// $result .= "<td>".$row['IP']."</td>";   
					// $result .= "<td>".$row['User']."</td>";   
					// $result .= "<td>".$row['Password']."</td>";   
					// $result .= "<td>".$row['CreateDate']."</td>";   
					// $result .= "<td>".$row['CreateDate']."</td></tr>"; 
					$regions[] = $row;
				}

			}
		}
		$result = json_encode((object)Array('host'=>json_encode($hosts), 'region'=>json_encode($regions)));
		// $result = "{
			// 'host':".json_encode($hosts).",
			// 'region':".json_encode($regions)."
		// }";
	}
	mysql_close($conn);
	echo $result;
?>