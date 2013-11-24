<?php

	require 'dbconn.php';                    
	$conn = DBConnect();
	$result = "";
	if (!$conn) $hostresult = '<tr><td colspan=8>Could not connect to mysql server:' . mysql_error() .'</td></tr>';
	else {
		// get host list
		$allhostsql = "select c.name as Product, r.Name as Region, h.Name as ServerName, h.IP, h.User, h.Password, h.CreateDate, h.UPdatedate from Host h join Component c on h.componentid = c.id join Region r on h.regionid = r.id";
		$rs = mysql_query($allhostsql, $conn);
		$hosts = array();
		$hostsresult = "";
		if (!$rs) $hostsresult = '<tr><td colspan=8>Could not execute query:' . mysql_error() .'</td></tr>';
		else {
			if (mysql_num_rows($rs) > 0){
				while($row = mysql_fetch_row($rs)) {
					$hosts[] = $row;
				}
			}
			else {
				$hostsresult = '<tr><td colspan=8>Nothing Matches</td></tr>';
			}
		}
		// get region list
		$allregionsql = "select distinct Name from Region order by Name asc";
		$rs = mysql_query($allregionsql, $conn);
		$regions = array();
		$regionsresult = "";
		if (!$rs) $regionsresult = 'Failed to get region list:' . mysql_error();
		else {
			if (mysql_num_rows($rs) > 0){
				while($row = mysql_fetch_row($rs)) {
					$regions[] = $row;
				}
			}
		}
		// get product list
		$allproductsql = "select distinct Name from Component order by Name asc";
		$rs = mysql_query($allproductsql, $conn);
		$products = array();
		$productresult = "";
		if (!$rs) $productresult = 'Failed to get product list:' . mysql_error();
		else {
			if (mysql_num_rows($rs) > 0){
				while($row = mysql_fetch_row($rs)) {
					$products[] = $row;
				}
			}
		}
		
		$result = json_encode((object)Array(
			'host'=>((isset($hostresult) && empty($hostresult)) ? $hostresult : json_encode($hosts)), 
			'region'=>json_encode($regions),
			'product'=>json_encode($products)
			));
	}
	mysql_close($conn);
	echo $result;
?>