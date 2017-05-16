<?php

$myfile = fopen("DB/total.csv","w") or die("Unable to open file!!");

$db = new SQLite3('DB/measureDB.sqlite3') or die('Unable to open database');
$total = $db->query('SELECT count(*) as total from [valtable]');
$total = $total->fetchArray(SQLITE3_ASSOC);
$result = $db->query('SELECT * FROM valtable order by [date] ASC');
$x=0;  
fwrite($myfile,"datetime,TemperatureA,Inside Temperature,Inside Pressure,Inside Humidity,Temperature difference,date,time,timemsec,Outside Temperature,Outside Pressure,Outside Humidity\n");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
  $x=$x+1;
  if ($x>($total['total']/50)) {
  $datetimefield = $row['date'];
  $datefield = substr($datetimefield,1,strlen($datetimefield)-6);
  $timefield = substr($datetimefield,strlen($datetimefield)-8,5);
  $tempAfield = $row['temp1'];
  $tempBfield = $row['temp2'];
  $pressfield = $row['press']*1000;
  $humfield = $row['hum'];
  $tdifffield = $tempAfield-$tempBfield;
  $timemsecfield = $row['timemsec'];
  $outtempfield = ($row['outtemp'] == '') ? '' : round($row['outtemp']-273.15,1);
  $outpressfield = $row['outpress'];
  $outhumfield = $row['outhum'];
  fwrite($myfile,$datetimefield.",".$tempAfield.",".$tempBfield.",".$pressfield.",".$humfield.",".$tdifffield.",".$datefield.",".$timefield.",".$timemsecfield.",".$outtempfield.",".$outpressfield.",".$outhumfield."\n");
  $x=0;
}
}
fclose($myfile);
?>
