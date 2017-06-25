<?php 
$df = disk_free_space("/");
$dt = disk_total_space("/");
$du = $dt - $df;
$dp = sprintf('%.2f',($du / $dt) * 100);
$temp=floatval(shell_exec("cat /sys/class/thermal/thermal_zone0/temp")/1000);

echo "Overview raspberry PI:<BR><BR>";
echo "Uptime: ".substr(shell_exec('uptime -p'),3);
echo "<BR>";
echo "CPU temperature: ".$temp."&degC";
echo "<BR>";
echo "Free disk space: ".$dp."%";
echo "<BR>";
echo "Average CPU load: ". floatval(sys_getloadavg()[2]*100)."%";

 ?>