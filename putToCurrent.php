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
	if (array_key_exists('diskfree',$_GET)){
		$value=intval($_GET['diskfree']);
		$field='diskfree';
	}
	
	if (array_key_exists('CPUtemp',$_GET)){
		$value=floatval($_GET['CPUtemp']);
		$field='CPUtemp';
	}
	if (array_key_exists('CPUmax',$_GET)){
		$value=floatval($_GET['CPUmax']);
		$field='CPUmax';
	}

	//trigger_error(implode(",",$_GET).' - '.implode(",",array_keys($_GET)));
	$sqlstring = "update currentstats set " . $field . " = " . $value . " where status='NOW'";
	$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
	echo $sqlstring;
	//trigger_error(implode(",",$_GET));
?>