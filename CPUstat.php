<?php
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';

	$df = disk_free_space("/");
	$dt = disk_total_space("/");
	$diskfree = intval(sprintf('%.2f',($df / $dt) * 100));
	$CPUtemp=floatval(shell_exec("cat /sys/class/thermal/thermal_zone0/temp")/1000);
	$CPUmax=floatval(sys_getloadavg()[2]*100);

	$sqlstring = "select * from currentstats where status='NOW'";
	$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
	$row = mysql_fetch_row($result, MYSQL_ASSOC);
	$diskfree=min(intval($diskfree), intval($row['diskfree']));
	$CPUtemp=max(floatval($CPUtemp), floatval($row['CPUtemp']));
	$CPUmax=max(floatval($CPUmax), floatval($row['CPUmax']));
	
	$sqlstring = "update currentstats set CPUtemp = '" . $CPUtemp . "', CPUmax = '" . $CPUmax . "',diskfree = '" . $diskfree . "' where status='NOW'";
	$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
 ?>