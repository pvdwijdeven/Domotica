<?php
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	$loginchecked=login_check($mysqli);
	
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$adminName = $row['admin'];
	if (($adminpage AND $adminName==$_SESSION['username'] AND $loginchecked) OR (!$adminpage AND $loginchecked)){


	$df = disk_free_space("/");
	$dt = disk_total_space("/");
	$dp = sprintf('%.2f',($df / $dt) * 100);
	$temp=floatval(shell_exec("cat /sys/class/thermal/thermal_zone0/temp")/1000);
	$epoch=filemtime('/var/log/apt/history.log');

	$sqlstring = "select * from currentstats where status='NOW'";
	$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
	$row = mysql_fetch_row($result, MYSQL_ASSOC);
	$diskfree=intval($row['diskfree']);
	$CPUtemp=floatval($row['CPUtemp']);
	$CPUmax=floatval($row['CPUmax']);

	
	echo "Up since: ". shell_exec('uptime -s');
	echo "<BR>";
	echo "CPU temperature: ".$temp."&degC (max: ".$CPUtemp."&degC)";
	echo "<BR>";
	echo "Free disk space: ".$dp."% (min: ".$diskfree."%)";
	echo "<BR>";
	echo "Average CPU load: ". floatval(sys_getloadavg()[2]*100)."% (max: ".$CPUmax."%)";
	echo "<BR>";
	echo "Last software update: " . date("Y-m-d H:i:s", substr($epoch, 0, 10));
	}
 ?>