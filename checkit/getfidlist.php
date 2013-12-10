<?php
	require_once("getrecordtemplate.php");
	global $RTs;
	$mcudump = "./data/mcu_ric.dump";
	$adhdump = "./data/adh_ric.dump";
	$eeddump = "./data/eed_ric.dump";
	// if (isset($_POST['mcu']) && !empty($_POST['mcu'])){
		// $mcudump=$_POST['mcu'];
	// }
	// if (isset($_POST['adh']) && !empty($_POST['adh'])){
		// $adhdump=$_POST['adh'];
	// }
	// if (isset($_POST['eed']) && !empty($_POST['eed'])){
		// $eeddump=$_POST['eed'];
	// }
	$mcuout = array();
	$adhout = array();
	$eedout = array();
	$mcufids = array();
	$adhfids = array();
	$eedfids = array();
	$mcuret = 0;
	$aduret = 0;
	$eedret = 0;
	// retrieve mcu fids
	$cmd = "python convert.py ".$mcudump;
	$cmd = EscapeShellCmd($cmd);
	exec($cmd, $mcuout, $mcuret);
	// retrieve adh fids
	$cmd = "python convert.py ".$adhdump;
	$cmd = EscapeShellCmd($cmd);
	exec($cmd, $adhout, $aduret);
	// retrieve eed fids
	$cmd = "python convert.py ".$eeddump;
	$cmd = EscapeShellCmd($cmd);
	exec($cmd, $eedout, $eedret);
	if ($mcuret == 0 && $aduret == 0 && $eedret == 0){
		$kv = array();
		foreach ($mcuout as $line){
			$kv = (explode(",", $line));
			$mcufids[intval($kv[0])] = $kv[1];
		}
		unset($kv);
		foreach ($adhout as $line){
			$kv = (explode(",", $line));
			$adhfids[intval($kv[0])] = $kv[1];
		}
		unset($kv);
		foreach ($eedout as $line){
			$kv = (explode(",", $line));
			$eedfids[intval($kv[0])] = $kv[1];
		}
		$alls = array();
		foreach (array_keys($mcufids) as $mcukey){
			$isdiff = false;
			$tmp = "<td>".$mcukey."</td>";
			if (array_key_exists($mcukey, $RTs)){
				$tmp.="<td>".$RTs[$mcukey]."</td>";
			}
			else{
				$tmp.="<td>UnKnown</td>";
			}
			// show mcu value
			$tmp .= "<td>".$mcufids[$mcukey]."</td>";			
			// show adh value
			if (array_key_exists($mcukey, $adhfids)) {
				if (strcasecmp($mcufids[$mcukey], $adhfids[$mcukey]) != 0){
					$isdiff = true;
				}
				$tmp.="<td>".$adhfids[$mcukey]."</td>";				
			}
			else {
				$isdiff = true;
				$tmp.="<td>Miss</td>";	
			}
			// show eed value
			if (array_key_exists($mcukey, $eedfids)) {
				if (strcasecmp($mcufids[$mcukey], $eedfids[$mcukey]) != 0){
					$isdiff = true;
				}
				$tmp.="<td>".$eedfids[$mcukey]."</td>";				
			}
			else {
				$isdiff = true;
				$tmp.="<td>Miss</td>";	
			}
			
			if ($isdiff) {
				$alls[$mcukey] = "<tr class='danger'>".$tmp."</tr>";
			}
			else {
				$alls[$mcukey] = "<tr>".$tmp."</tr>";
			}
		}
		
		foreach (array_keys($adhfids) as $adhkey){
			if (array_key_exists($adhkey, $alls)){
				continue;
			}
			$tmp = "<td>".$adhkey."</td>";
			if (array_key_exists($adhkey, $rts)){
				$tmp.="<td>".$rts[$adhkey]."</td>";
			}
			else{
				$tmp.="<td>unknown</td>";
			}
			// show mcu value
			$tmp.="<td>miss</td>";
			// show adh value
			$tmp.="<td>".$adhfids[$adhkey]."</td>";
			// show eed value
			if (array_key_exists($adhkey, $eedfids)) {
				if (strcasecmp($adhfids[$adhkey], $eedfids[$adhkey]) != 0){
					$isdiff = true;
				}
				$tmp.="<td>".$eedfids[$adhkey]."</td>";				
			}
			else {
				$isdiff = true;
				$tmp.="<td>miss</td>";	
			}
			$alls[$adhkey]="<tr class='danger'>".$tmp."</tr>";
		}
		
		foreach (array_keys($eedfids) as $eedkey){
			if (array_key_exists($eedkey, $alls)){
				continue;
			}
			$tmp = "<td>".$eedkey."</td>";
			if (array_key_exists($eedkey, $rts)){
				$tmp.="<td>".$rts[$eedkey]."</td>";
			}
			else{
				$tmp.="<td>unknown</td>";
			}
			// show mcu value
			$tmp.="<td>miss</td>";
			// show adh value
			$tmp.="<td>miss</td>";
			// show eed value
			$tmp.="<td>".$eedfids[$eedkey]."</td>";
			$alls[$eedkey]="<tr class='danger'>".$tmp."</tr>";
		}
		// render the body for html
		$tmp = "";
		foreach ($alls as $fid)
			$tmp .= $fid;
		$result = $tmp;
		//$result = "<tr><td colspan='5'>".count($mcufids).", ".count($adhfids).", ".count($eedfids)."</td></tr>";
	}
	else {
		$result = "<tr><td colspan='5'>Failed to parse ";
		if ($mcuret != 0)
			$result += "muc,";
		if ($adhret != 0)
			$result += "adh,";
		if ($eedret != 0)
			$result += "eeh,";
		$result += " file</td></tr>";
	}
	echo $result;
?>