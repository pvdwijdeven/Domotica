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

	echo "Overview raspberry PI:<BR><BR>";
	echo "Up since: ". shell_exec('uptime -s');
	echo "<BR>";
	echo "CPU temperature: ".$temp."&degC";
	echo "<BR>";
	echo "Free disk space: ".$dp."%";
	echo "<BR>";
	echo "Average CPU load: ". floatval(sys_getloadavg()[2]*100)."%";
	echo "<BR>";
	echo "Last software update: " . date("Y-m-d H:i:s", substr($epoch, 0, 10));
	}
 ?>