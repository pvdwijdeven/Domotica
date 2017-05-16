<?php
	unset($_SESSION['logged_in']);
	session_destroy();
	echo "You have succesfully logged out.";
	header ("Refresh: 5; index.php");
?>