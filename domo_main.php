<?php 
	$title="Domo main page";
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	$loginchecked=login_check($mysqli);
	
	$sql = "SELECT * FROM config where row= 'config'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$adminName = $row['admin'];
	if (($adminpage AND $adminName==$_SESSION['username'] AND $loginchecked) OR (!$adminpage AND $loginchecked)){
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<meta name="description" content="<?php echo $title; ?>">
		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/style.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<script type="text/javascript">
			<!--
			
				window.onclick = function(event) {
					if (event.target == document.getElementById("mySidenav")) {
					document.getElementById("mySidenav").style.width = "0";
					}
					if (event.target == modal) {
						modal.style.display = "none";
					}
				}	
				
				function openNav() {
					document.getElementById("mySidenav").style.width = "100%";
				}
			
				function closeNav() {
					document.getElementById("mySidenav").style.width = "0";
				}
			
				//-->
		</script>
		<?php include "includes/header.php"; ?>
		<!-- main page start here -->
		<?php
			$curroomname = "";

			$adminName = $row['admin'];
			if (isset($_COOKIE['room_domo_main_php'])) {
				$curroom = $_COOKIE['room_domo_main_php'];
				$curroom = substr($curroom, 4);
			} else {
				$curroom = '0';
			}
			if (isset($_COOKIE['select_domo_main_php'])) {
				$selected = " " . $_COOKIE['select_domo_main_php'];
			} else {
				$selected = " 12BLADO";
			}
			$sqlfloor = "";
			if (strpos($selected, '1')) {
				if ($sqlfloor != "") {
					$sqlfloor = $sqlfloor . " OR ";
				}
				$sqlfloor = $sqlfloor . "FloorID = 2";
			}

			if (strpos($selected, '2')) {
				if ($sqlfloor != "") {
					$sqlfloor = $sqlfloor . " OR ";
				}
				$sqlfloor = $sqlfloor . "FloorID = 4";
			}

			if (strpos($selected, 'B')) {
				if ($sqlfloor != "") {
					$sqlfloor = $sqlfloor . " OR ";
				}
				$sqlfloor = $sqlfloor . "FloorID = 1";
			}
			$sqltype = "";

			if (strpos($selected, 'L')) {
				$sqltype = $sqltype . "SelectorID = 1";
			}
			if (strpos($selected, 'A')) {
				if ($sqltype != "") {
					$sqltype = $sqltype . " OR ";
				}
				$sqltype = $sqltype . "SelectorID = 2";
			}
			if (strpos($selected, 'D')) {
				if ($sqltype != "") {
					$sqltype = $sqltype . " OR ";
				}
				$sqltype = $sqltype . "SelectorID = 3";
			}
			if (strpos($selected, 'O')) {
				if ($sqltype != "") {
					$sqltype = $sqltype . " OR ";
				}
				$sqltype = $sqltype . "SelectorID = 4";
			}
			if ($sqlfloor != "") {
				$sqltype = "(" . $sqlfloor . ') AND (' . $sqltype . ')';
			} else {
				$sqltype = '(' . $sqltype . ')';
			}

			if ($sqlfloor != "") {
				$sqlfloor = "WHERE " . $sqlfloor;
			}
			$roomselector = mysql_query('SELECT * FROM roomID ' . $sqlfloor);
			$j            = -1;
			while ($rowroom = mysql_fetch_row($roomselector, MYSQL_ASSOC)) {
				$j += 1;
				$RoomSel[$j]   = $rowroom['Room'];
				$RoomSelID[$j] = $rowroom['ID'];
				if ($RoomSelID[$j] == $curroom) {
					$curroomname = $RoomSel[$j];
				}
			}

			if ($curroomname == "") {
				$curroomname = "Allemaal";
				$curroom     = '0';
			}

			if ($curroom == '0' or $sqltype == '') {
				$sqlfloor = "";
			} else {
				$sqlfloor = " AND RoomID=" . $curroom;
			}

			$result = mysql_query('SELECT * FROM measurements WHERE ' . $sqltype . $sqlfloor . ' AND TileID=1');

			if (mysql_num_rows($result) == 0) {
				$result = mysql_query('SELECT * FROM measurements WHERE TileID=1');
			}

			$i = -1;
			while ($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
				$i += 1;
				$IDs[$i]          = $row['ID'];
				$Floors[$i]       = $row['FloorID'];
				$Analogs[$i]      = $row['AnalogID'];
				$Rooms[$i]        = $row['RoomID'];
				$Types[$i]        = $row['Type'];
				$Descriptions[$i] = $row['LongDescription'];
				$Graphs[$i]       = $row['GraphID'];
				$TrueText[$i]     = $row['TrueText'];
				$FalseText[$i]    = $row['FalseText'];
				$Interactions[$i] = $row['InteractionID'];
				$UoMs[$i]         = $row['Unit'];
				$ValueNumbers[$i] = $row['ValueNumber'];
			}



			for ($x = 0; $x <= $i; $x++) {
				$sqltext    = "SELECT Floor from floorID WHERE ID=" . $Floors[$x];
				$result     = mysql_query($sqltext);
				$row        = mysql_fetch_row($result);
				$Floors[$x] = $row[0];
				
				$sqltext   = "SELECT Room from roomID WHERE ID=" . $Rooms[$x];
				$result    = mysql_query($sqltext);
				$row       = mysql_fetch_row($result);
				$Rooms[$x] = $row[0];
				
				$sqltext     = "SELECT Analog from analogID WHERE ID=" . $Analogs[$x];
				$result      = mysql_query($sqltext);
				$row         = mysql_fetch_row($result);
				$Analogs[$x] = $row[0];
				
				$sqltext    = "SELECT Graph from graphID WHERE ID=" . $Graphs[$x];
				$result     = mysql_query($sqltext);
				$row        = mysql_fetch_row($result);
				$Graphs[$x] = $row[0];
				
				$sqltext          = "SELECT Interaction from interactionID WHERE ID=" . $Interactions[$x];
				$result           = mysql_query($sqltext);
				$row              = mysql_fetch_row($result);
				$Interactions[$x] = $row[0];
			}
			
			$IDs          = implode(",", $IDs);
			$Floors       = "'" . implode("','", $Floors) . "'";
			$Analogs      = "'" . implode("','", $Analogs) . "'";
			$Rooms        = "'" . implode("','", $Rooms) . "'";
			$Types        = "'" . implode("','", $Types) . "'";
			$Descriptions = "'" . implode("','", $Descriptions) . "'";
			$Graphs       = "'" . implode("','", $Graphs) . "'";
			$TrueText     = "'" . implode("','", $TrueText) . "'";
			$FalseText    = "'" . implode("','", $FalseText) . "'";
			$Interactions = "'" . implode("','", $Interactions) . "'";
			$UoMs         = "'" . implode("','", $UoMs) . "'";
			$ValueNumbers = implode(",", $ValueNumbers);
			$RoomSel      = "'" . implode("','", $RoomSel) . "'";
			$RoomSelID    = "'" . implode("','", $RoomSelID) . "'";
		?>
		<script>	
			function getValues(){
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var currentresult = this.responseText;
						currentresult = currentresult.split(',');
						document.getElementById("update").innerHTML="Last update: " + currentresult[0];
						var IDs= "<?php echo $IDs;?>";
						var valuepos = "<?php echo $ValueNumbers;?>";
						IDs=IDs.split(',');
						valuepos=valuepos.split(',');
						var i=0;
						for (i=0;i<IDs.length;i++){
							if (valuepos[i]>0){
								thisButton = $('#'+ String(IDs[i]));
							
								if (generalInfo['Analog'][i]=='BOTH'){
									var temp = $(thisButton.find('.value1'));
									if (currentresult[valuepos[i]]=="true"){
										$(temp).addClass('detected');
									} else{
										$(temp).removeClass('detected');
									}
									$(temp).html(gettext(currentresult[valuepos[i]],i));
									var temp = $(thisButton.find('.value2'));
									$(temp).html(currentresult[parseInt(valuepos[i])+1] + ' ' + generalInfo['UoM'][i]);
								}else if (generalInfo['Analog'][i]=='FALSE') {
									var temp = $(thisButton.find('.value1'));
									if (currentresult[valuepos[i]]=="true"){
										$(temp).addClass('detected');
									} else{
										$(temp).removeClass('detected');
									}
									$(temp).html(gettext(currentresult[valuepos[i]],i));
								} else{
									var temp = $(thisButton.find('.value1'));
									$(temp).removeClass('detected');
									$(temp).html(currentresult[valuepos[i]] + ' ' + generalInfo['UoM'][i]);
								}
							}
						}
					}
				};
				xmlhttp.open("GET", "getCurrentValues.php", true);
				xmlhttp.send();
			}
			
			$( document ).ready(function()  {
				if(self!=top){
					$("header").hide();
					$("footer").hide();
				}
				setSButtons();
				getValues();
				getGeneralInfo();
				setButtons();
				setInterval(getValues, 4000);
				setRoom();
				getRooms();
			});
				
				var generalInfo = {};
				var rooms = [];
				var curroom = 0;
				var curroomname = "";
				
				function getRooms(){
					var txt="";
					for (x=0;x<rooms.length;x++){
					txt+="<tr><td><button class='tablebutton' id='room"+roomsID[x]+"' onmouseup='clickRSButton(this)'>"+rooms[x]+"</button></td></tr>";
					}
					$('#rooms').html("<table>"+txt+ "</table>");
				}
				
				function setRoom(){
					$("#roomsel").html(curroomname);
					setCookie("room_domo_main.php","room"+curroom,365);
				}
				
				function addButton(ID){
					var tempEl = $( "#0" ).clone();
					$(tempEl).attr('id',ID);
					tempEl.appendTo( ".buttonarea" );
					return tempEl;
				}
				
				function gettext(status,i){
					if (status.trim()=='false') {
						return generalInfo['FalseText'][i];
					} else{
						return generalInfo['TrueText'][i];
					}
				}
				
				function clickTButton(elem) {
					if ($(elem).attr('id').substring(0,11)=='graphButton'){
						
						document.location.href = 'singlegraph.php?ID=' + $(elem).attr('id').substring(12);
					}else{
						alert("action clicked");
						alert($(elem).attr('id').substring(0,11));
					}
				}
			
				function getGeneralInfo(){
					generalInfo['ID'] = [<?php echo $IDs;?>];
					generalInfo['Floor'] = [<?php echo $Floors;?>];
					generalInfo['Analog'] = [<?php echo $Analogs;?>];
					generalInfo['Room'] = [<?php echo $Rooms;?>];
					generalInfo['Type'] = [<?php echo $Types;?>];
					generalInfo['Description'] = [<?php echo $Descriptions;?>];
					generalInfo['Graph'] = [<?php echo $Graphs;?>];
					generalInfo['TrueText'] = [<?php echo $TrueText;?>];
					generalInfo['FalseText'] = [<?php echo $FalseText;?>];
					generalInfo['Interaction'] = [<?php echo $Interactions;?>];
					generalInfo['UoM'] = [<?php echo $UoMs;?>];
					rooms=['Allemaal',<?php echo $RoomSel;?>];
					roomsID=['0',<?php echo $RoomSelID;?>];
					curroomname='<?php echo $curroomname;?>';
					curroom=<?php echo $curroom;?>;
				}
				
				function disableButton(temp){
					temp.attr('class', 'tablebuttondisabled');
					temp.attr('onmouseup', '');
					temp.prop("disabled",true);
				}
				
				function setButtons(){
					for (i=0;i<=generalInfo['ID'].length-1;i++){
						thisButton = addButton(generalInfo['ID'][i]);
						var temp = $(thisButton.find('.location'));
						$(temp).html(generalInfo['Floor'][i] + ' - ' + generalInfo['Room'][i]);
						var temp = $(thisButton.find('.measurement'));
						$(temp).html(generalInfo['Type'][i]);
						var temp = $(thisButton.find('.value1'));
						$(temp).html('<br>');
						var temp = $(thisButton.find('#graphButton'));
						if (generalInfo['Graph'][i]=="no") {
							disableButton(temp);
						}
						$(temp).attr('id','graphButton_'+generalInfo['ID'][i]);
						var temp = $(thisButton.find('#interactButton'));
						if (generalInfo['Interaction'][i]=="none") {
							disableButton(temp);
							temp.html(' - ');
						} else {
							temp.html(generalInfo['Interaction'][i]);
						}
						$(temp).attr('id','interactButton_'+generalInfo['ID'][i]);
					}
			
					$("#0").remove();
				}
				
			
				
				//-->
		</script>
		<div class="selecttable">
			<?php include "selecttable.php"; ?>
		</div>
		<hr>
		<p id="update">Last update: -</p>
		<hr>
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
						<td colspan=1>
							<div class="value1">
								<br>
							</div>
						</td>
						<td colspan=1>
							<div >
							</div>
						</td>
						<td colspan=1>
							<div class="value2">
								<br>
							</div>
						</td>
					</tr>
					<tr>
						<td> <button class="tablebutton" id="interactButton" onmouseup="clickTButton(this)">Interact</button></td>
						<td class="splitter"></td>
						<td> <button class="tablebutton" id="graphButton" onmouseup="clickTButton(this)">Graph</button></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="content">
		</div>
		<!-- main page ends here -->		
		<footer>
			<hr>
			<p>
				<?php include 'includes/footer.php'; ?>
			</p>
		</footer>
	</body>
</html>
<?php
	} else { 
		if ($adminpage AND $loginchecked){
			echo "<p><span class='error'>This is an ADMIN page. You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		} else { 
			echo "<p><span class='error'>You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		}
	} 
?>