<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Domo logout page</title>
		<meta name="description" content="Domo logout page">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="../css/style.css?v=1.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script type="text/JavaScript" src="../js/sha512.js"></script> 
        <script type="text/JavaScript" src="../js/forms.js"></script> 
		<script type="text/javascript">
			<!--
		function pressTButton(elem) {
				$(elem).attr('class', 'tablebuttonpressed');
			}
			
			function clickTButton(elem) {
				releaseTButton(elem);
				document.getElementById('id01').style.display='block'
			}

			function releaseTButton(elem) {
				$(elem).attr('class', 'tablebutton');
			}
			//-->
		</script>
		
    </head>
    <body>
		<header>
			<div id=headercontainer>
			<div id="domoheader">Homey Domotica</div>
			</div>
		</header>
		<nav class=menuHidden>
			<p>menu stuff here</p>
		</nav>
		<hr>
		<div class="buttonarea">
<?php
	if (isset($_COOKIE['login'])) {
		setcookie('login','',1,'/','',1,1);
		unset($_COOKIE['login']);
	}
		setcookie('sec_session_id','',1);
		unset($_COOKIE['sec_session_id']);
	echo "You have succesfully logged out - you will be redirected to <a href=../index.php>login</a> page in 5 seconds.";
	header ("Refresh: 5; ../index.php");
?>
</div>

		<footer>
			<hr>
			<p>
			<?php include 'footer.php' ?>
			</p>
		</footer>
    </body>
</html>





