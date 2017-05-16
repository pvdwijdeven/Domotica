<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
if (login_check($mysqli) == true) :
$sql = "SELECT * FROM config where row= 'config'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();


$adminName = $row['admin'];
?>


<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Test</title>
		<meta name="description" content="Test page">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/teststyle.css?v=1.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript">
			<!--

			var generalInfo = {};
			
			function pressButton(elem) {
				$(elem).attr('class', 'buttonpressed');
			}
			
			function clickButton(elem) {
				releaseButton(elem);
			}

			function releaseButton(elem) {
				$(elem).attr('class', 'button');
			}
			
			function addButton(ID){
				var tempEl = $( "#0" ).clone();
				$(tempEl).attr('id',ID);
				tempEl.appendTo( ".buttonarea" );
				return tempEl;
			}
			
			function showHideMenu(){
				if ($("nav").attr('class')=='menuShown'){
					$("nav").attr('class','menuHidden');
				} else {
					$("nav").attr('class','menuShown');
				}
			}

			function pressTButton(elem) {
				$(elem).attr('class', 'tablebuttonpressed');
			}
			
			function clickTButton(elem) {
				releaseTButton(elem);
			}

			function releaseTButton(elem) {
				$(elem).attr('class', 'tablebutton');
			}

			function getGeneralInfo(){
				generalInfo['ID'] = [1,2,3,4,5,6];
				generalInfo['Floor'] = ["Begane grond","Begane grond","Begane grond","1e verdieping","1e verdieping","Zolder", "Zolder"];
				generalInfo['Room'] = ["Woonkamer","Woonkamer","Hal","Slaapkamer","Wasruimte", "Wasruimte"];
				generalInfo['Type'] = ["Temperatuur","Luchtvochtigheid","Lamp","Kodi","Energie", "Energie totaal"];
				generalInfo['Description'] = ["Temperatuur","Luchtvochtigheid","Lamp","Kodi","Energie", "Energie totaal"];
				generalInfo['Graph'] = ["yes","yes","no","no","yes", "yes"];
				generalInfo['Interaction'] = ["none","none","toggle","kodi","none", "none"];
				generalInfo['UoM'] = ["&deg;C","%","","","Watt", "kWhr"];
			}
			
			function disableButton(temp){
				temp.attr('class', 'tablebuttondisabled');
				temp.attr('onmousedown', '');
				temp.attr('onmouseleave', '');
				temp.attr('onmouseup', '');
				temp.attr('ontouchstart', '');
				temp.attr('ontouchend', '');
			}
			
			
			function setButtons(){
				for (i=0;i<=generalInfo['ID'].length-1;i++){
					thisButton = addButton(generalInfo['ID'][i]);
					var temp = $(thisButton.find('.location'));
					$(temp).html(generalInfo['Floor'][i] + ' - ' + generalInfo['Room'][i]);
					var temp = $(thisButton.find('.measurement'));
					$(temp).html(generalInfo['Description'][i]);
					var temp = $(thisButton.find('.value'));
					$(temp).html('20' + ' ' + generalInfo['UoM'][i]);
					var temp = $(thisButton.find('#graphButton'));
					if (generalInfo['Graph'][i]=="no") {
						disableButton(temp);
					}
					var temp = $(thisButton.find('#interactButton'));
					if (generalInfo['Interaction'][i]=="none") {
						disableButton(temp);
						temp.html(' - ');
					} else {
						temp.html(generalInfo['Interaction'][i]);
					}
				}

				$("#0").remove();
			}
			
			$(function() {
				getGeneralInfo();
				setButtons();
				
			});
			
			//-->
		</script>
	</head>
	<body>
		<header>
			<table class="headertable">
			<tr class="headertr">
			<td class="headercenter"><div class="header">Homey Domotica</div>
			<td class="headerleft"><div class="loginfo"> 
			<?php if ($_SESSION['username'] == $adminName): ?>
			<a href="domo_admin.php">admin page</a>
			<?php endif; ?>
			logged in as <b><?php echo htmlentities($_SESSION['username']);?>
			</b> (<a href="includes/logout.php">Log out</a>)</div></td>
			</tr>
			</table>
			<hr>
		</header>
		<nav class=menuHidden>
			<p>menu stuff here</p>
		</nav>
		<p>actual body here</p>
		<div class="buttonarea">
			<div class="button" id="0">
				<table>
					<tr>
						<td colspan=3>
							<div class="location">
								Woonkamer
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<div class="measurement">
								Temperatuur
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<div class="value">
								20 &deg;C
							</div>
						</td>
					</tr>
					<tr>
						<td> <div class="tablebutton" id="interactButton" ontouchstart="pressTButton(this)" onmousedown="pressTButton(this)" ontouchend="clickTButton(this)" onmouseup="clickTButton(this)" onmouseleave="releaseTButton(this)">Interact</div></td>
						<td class="splitter"></td>
						<td> <div class="tablebutton" id="graphButton" ontouchstart="pressTButton(this)" onmousedown="pressTButton(this)" ontouchend="clickTButton(this)" onmouseup="clickTButton(this)" onmouseleave="releaseTButton(this)">Graph</div></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="content">
		</div>
		<footer>
			<hr>
			<p>Domo footer stuff here</p>
		</footer>
		
	
	</body>
</html>

<?php else : ?>
            <p>
                <span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.
            </p>
        <?php endif; ?>
