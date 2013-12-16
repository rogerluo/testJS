<?php
	$mcudump = "./data/adh_ric.dump";
	$cmd = "python convert.py ".$mcudump;
	$ret = 0;
	$fids = array();
	unset($fids);
	echo $cmd.'<br />';
	$cmd = EscapeShellCmd($cmd);
	exec($cmd, $fids, $ret);
	echo $ret."<br />";
	$len = count($fids);
	$kv = array();
	$fidlist = array();
	foreach ($fids as $fid)
	{
		$kv = (explode(",", $fid));
		$fidlist[intval($kv[0])] = $kv[1];
	}
	var_dump($fidlist);
?>