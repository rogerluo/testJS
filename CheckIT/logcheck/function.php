<?php

function write_file($content, $file_name)
{
	$fp = fopen($file_name, 'w');
	fwrite($fp, $content);
	fclose($fp);
}

function ResultCheck($file)
{
        $content = @file_get_contents($file);
	if(preg_match("/No such file or directory/",$content)=='1') {return "File Not Exist";}
        else if(preg_match("/exit/",$content)=='1') {return "Dump Status";}
        else if(preg_match("/Connection closed by remote host/",$content)=='1') {return "Machine Error";}
        else if(preg_match("/Connection timed out/",$content)=='1') {return "Login Timeout";}
        else if(preg_match("/REMOTE HOST IDENTIFICATION HAS CHANGED/",$content)=='1') {return "Host Identification changed";}
        else if(preg_match("/Permission denied, please try again/",$content)=='1') {return "Password Error";}
	else if(preg_match("/100%/",$content)=='1') {return "Copy Successed";}
        else if(preg_match("/0.0KB/",$content)=='1') {return "File Too Large";}
	else if(preg_match("/timeout/",$content)=='1') {return "Connection Timeout";}
	else if(preg_match("/port 22: Connection refused/",$content)=='1') {return "Connection refused";}
        else return "Unknow Error";
}
?>
