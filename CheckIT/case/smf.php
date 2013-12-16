<?php
//Copy single machine's SMF log to local machine.
//Usage: php smf.php ip password user hostname component region
chdir( dirname ( __FILE__ ) );
include_once('../config/config.php');
include_once('../bin/function.php');
$Type=$LogType['smf'];

$today = @date("m.d.Y", mktime(0, 0, 0, date("m") , date("d"),date("Y")));
$day = @date("Ymd", mktime(0, 0, 0, date("m") , date("d"),date("Y")));

$Dir = "../log/$Type"; if(!is_dir($Dir)) { mkdir($Dir);}
$Dir = "../log/$Type/$today"; if(!is_dir($Dir)) { mkdir($Dir);}
$TmpDir = "../tmp/$Type"; if(!is_dir($TmpDir)) { mkdir($TmpDir);}

//Get ip/address/user information
foreach($argv as $value) { $var[] = trim($value);}
$ip = $var[1];
$password = $var[2];
$user = $var[3];
$hostname = $var[4];
$component = $var[5];
$region = $var[6];

//Public file name
$SMFLog = $region.'_'.$Type.'_'.$hostname.'_'.$day;
$ResultLog = $Dir.'/'.$region.'_'.$Type.'_'.$hostname.'_'.$day.'_'.'status.txt';
//Clear public of target machine saved in local server
$cmd = "../bin/ClearKey $ip";
passthru($cmd);

//Will get the log of yesterday!!!
$cmd = "rm -rf $TmpDir/*";
exec($cmd);

//Get SMF log saving directory based on different component
switch ($component)
{
	case 'EDGE': { $dir = "D:/ThomsonReuters/SMF/log/log*$day*"; break; }
        case 'ADS':  { $dir = "/ThomsonReuters/smf/log/log_$day*";   break; }
        default:     { $dir = "D:/ThomsonReuters/SMF/log/smf*log*files.$day*";}
}

//Gopy log file to local machine
$cmd = "../bin/copy_file $ip $dir $password $user $TmpDir";
ob_start();
passthru($cmd);
$status = ob_get_contents();
ob_end_clean();
write_file($status,$ResultLog);

unset($out);
$cmd = "ls -1 $TmpDir/*$day*";
exec($cmd,$out);
$i=1;
foreach($out as $var) {
	$cmd = "mv '$var' '$Dir/$SMFLog.$i.txt'";
        exec($cmd);
        $i++;
}
                        //$cmd = "grep -E -h 'Critical|Warning' ../log/$today/$smf_name*txt > ../log/$today/$smf_name.Critical";
                        //passthru($cmd);
                        //$cmd = "grep -w -h Gap ../log/$today/$smf_name*txt > ../log/$today/$smf_name.Gap";
                        //exec($cmd);
                        //$cmd = "grep -E -h '$KeyWord' ../log/$today/$smf_name*txt > ../log/$today/$smf_name-KeyWord.txt";
                        //exec($cmd);

$cmd = "chmod -R 777 ../log/*";
exec($cmd);

$Result = ResultCheck($ResultLog);
echo "$Result\n\r";
?>
