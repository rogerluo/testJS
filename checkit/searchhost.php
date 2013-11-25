<?php
	require 'dbconn.php';     
	$result = "";
	
	if (isset($_POST['region']) && !empty($_POST['region']))	{
		$regionfilter = preg_split("/[,\s\|]+/", $_POST['region'], -1, PREG_SPLIT_NO_EMPTY);
	}
	if (isset($_POST['product']) && !empty($_POST['product'])){
		$productfilter = preg_split("/[,\s\|]+/", $_POST['product'], -1, PREG_SPLIT_NO_EMPTY);
	}
	
	$conn = DBConnect();
	if (!$conn) $hostresult = '<tr><td colspan=8>Could not connect to mysql server:' . mysql_error() .'</td></tr>';
	else {
		// spell the filter sql for region
		$regionsql = " JOIN Region r on h.regionid = r.id";
		if (isset($regionfilter) && count($regionfilter) > 0){
			$regionsql .= " and r.Name in(";
			for ($i = 0; $i < count($regionfilter) - 1; $i++){
				$regionsql .= "'".$regionfilter[$i]."', ";
			}
			$regionsql .= "'".$regionfilter[count($regionfilter) - 1]."')";
		}
		
		$productsql = " JOIN Component c on h.componentid = c.id";
		if (isset($productfilter) && count($productfilter) > 0){
			$productsql .= " and c.Name in(";
			for ($i = 0; $i < count($productfilter) - 1; $i++){
				$productsql .= "'".$productfilter[$i]."', ";
			}
			$productsql .= "'".$productfilter[count($productfilter) - 1]."')";
		}
		
		$searchsql = "select c.name as Product, r.Name as Region, h.Name as ServerName, h.IP, h.User, h.Password, h.CreateDate, h.UPdatedate from Host h ".$productsql.$regionsql;
		$rs = mysql_query($searchsql, $conn);
		$hosts = array();
		if (!$rs) $hostresult = '<tr><td colspan=8>Could not execute query:' . mysql_error() .'</td></tr>';
		else {
			if (mysql_num_rows($rs) > 0){
				while($row = mysql_fetch_row($rs)) {
					$hosts[] = $row;
				}
			}
			else {
				//$hostresult = '<tr><td colspan=8>Nothing Matches</td></tr>';
				$hosts[] = Array(0=>"", 1=>"", 2=>"", 3=>"", 4=>"", 5=>"", 6=>"", 7=>"", 8=>"");
			}
		}
		$result = json_encode((isset($hostresult) && !empty($hostresult)) ? $hostresult : $hosts);
		//$result = '<tr><td colspan=8>'.$searchsql.'</td></tr>';
	}
	mysql_close($conn);
	echo $result;
?>