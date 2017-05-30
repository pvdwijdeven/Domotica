		<div id="mySidenav" class="sidenav">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			<table class="menutable">
				<tr><td><button class="menubutton" id="menuButton_1" onmouseup="window.location.href='domo_main.php'">domo main</button></td></tr>
				<tr><td><button class="menubutton" id="menuButton_2" onmouseup="window.location.href='whathappened.php'">What happened?</button></td></tr>
				<?php if ($_SESSION['username'] == $adminName){ ?>
				<tr><td><button class="menubutton" id="menuButton_3" onmouseup="window.location.href='view_tables.php'">View/modify tables</button></td></tr>
				<?php } ?>
				<tr><td><button class="menubutton" id="menuButton_4" onmouseup="window.location.href='includes/logout.php'">Log out</button></td></tr>
			</table>
		</div>
	
		<header>
			<div class="header_left"><button class="tablebutton" onclick="openNav()">Menu</button></div>
			
			<div class="header_right" id="loginfo"><?php if ($_SESSION['username'] == $adminName): ?>
			<a href="domo_admin.php">admin page</a>
			<?php endif; ?>
			<br>logged in as <b><?php echo htmlentities($_SESSION['username']);?>
			</b><br> (<a href="includes/logout.php">Log out</a>)</div>
			<div class="header_center"><?php echo $title; ?></div>
			<hr>
		</header>