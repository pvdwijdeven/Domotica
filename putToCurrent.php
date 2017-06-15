<?php
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	if (array_key_exists('wasmachine',$_GET)){
		$value=intval($_GET['wasmachine']);
		$field='wasmachine';
	}
	
	if (array_key_exists('sabotagehal',$_GET)){
		$value=intval($_GET['sabotagehal']);
		$field='sabotagehal';
	}
	
	$sqlstring = "update currentstats set " . $field . " = " . $value . " where status='NOW'";
	$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
	echo $sqlstring;
?>