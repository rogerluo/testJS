<?php
	$cmd="pwd";
	$cmd = EscapeShellCmd($cmd);
	$outs = array();
	$ret = 0;
	exec($cmd, $outs, $ret);
	var_dump($outs);
?>
