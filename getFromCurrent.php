<?php
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	if (array_key_exists('request',$_GET)){
		$request=$_GET['request'];
	}

	$sqlstring = "select * from currentstats where status='NOW'";
	$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
	$row = mysql_fetch_row($result, MYSQL_ASSOC);
	echo $row[$request];
?>