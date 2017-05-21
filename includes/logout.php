<?php
	if (isset($_COOKIE['login'])) {
		setcookie('login','',1,'/','',1,1);
		unset($_COOKIE['login']);
	}
		setcookie('sec_session_id','',1);
		unset($_COOKIE['sec_session_id']);
	echo "You have succesfully logged out.";
	header ("Refresh: 5; ../index.php");
?>