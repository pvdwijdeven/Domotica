<?php 
	$dummy=$_SERVER;
	$title="Domo - what happened?";
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
					if (event.target == modal2) {
						modal2.style.display = "none";
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
			$curroomname="";
			if (isset($_COOKIE['room_whathappened_php'])){
				$curroom = $_COOKIE['room_whathappened_php'];
				$curroom = substr($curroom, 4);
			}else{$curroom='0';}
			if (isset($_COOKIE['select_whathappened_php'])){
				$selected = " ".$_COOKIE['select_whathappened_php'];
			} else {
				$selected=" 12BLADO";
			}
			$sqlfloor="";
			if (strpos($selected,'1')){
				if ($sqlfloor!=""){
					$sqlfloor = $sqlfloor." OR ";
				}
				$sqlfloor = $sqlfloor."FloorID = 2";
			}
			
			if (strpos($selected,'2')){
				if ($sqlfloor!=""){
					$sqlfloor = $sqlfloor." OR ";
				}
				$sqlfloor = $sqlfloor."FloorID = 4";
			}
			
			if (strpos($selected,'B')){
				if ($sqlfloor!=""){
					$sqlfloor = $sqlfloor." OR ";
				}
				$sqlfloor = $sqlfloor."FloorID = 1";
			}
			$sqltype="";
			
			if (strpos($selected,'L')){
				$sqltype= $sqltype . "SelectorID = 1";
			}
			if (strpos($selected,'A')){
				if ($sqltype!=""){
					$sqltype = $sqltype." OR ";
				}
				$sqltype= $sqltype . "SelectorID = 2";
			}
			if (strpos($selected,'D')){
				if ($sqltype!=""){
					$sqltype = $sqltype." OR ";
				}
				$sqltype= $sqltype . "SelectorID = 3";
			}
			if (strpos($selected,'O')){
				if ($sqltype!=""){
					$sqltype = $sqltype." OR ";
				}
				$sqltype= $sqltype . "SelectorID = 4";
			}
			if ($sqlfloor!=""){
				$sqltype = "(".$sqlfloor.') AND ('. $sqltype . ')';
			}else{
				$sqltype = '('. $sqltype . ')';
			}
			
			if ($sqlfloor!=""){$sqlfloor = "WHERE ".$sqlfloor;}
			$roomselector = mysql_query('SELECT * FROM roomID '.$sqlfloor);
			$j=-1;
			while($rowroom = mysql_fetch_row($roomselector, MYSQL_ASSOC)) {
				$j+=1;
				$RoomSel[$j] = $rowroom['Room'];
				$RoomSelID[$j] = $rowroom['ID'];
				if ($RoomSelID[$j]==$curroom){
					$curroomname=$RoomSel[$j];
				}
			}
			
				$RoomSel = "'" . implode ( "','" , $RoomSel ) . "'";
				$RoomSelID = "'" . implode ( "','" , $RoomSelID ) . "'";
			
			if ($curroomname==""){
				$curroomname="Allemaal";
				$curroom='0';
			}
			
			if ($curroom=='0' or $sqltype==''){
				$sqlfloor="";
			} else {
				$sqlfloor=" AND RoomID=" . $curroom;
			}
			
			$result = mysql_query('SELECT * FROM measurements WHERE '.$sqltype.$sqlfloor.' AND TileID=1');
			
			if (mysql_num_rows($result)==0){
			$result = mysql_query('SELECT * FROM measurements WHERE TileID=1');
			
			}
			
			$meas=array();
			$total=-1;
			
			while($row = mysql_fetch_row($result, MYSQL_ASSOC))
				{
					$total+=1;
					$column[$total]='val'.$row['ValueNumber'];
					$meas['val'.$row['ValueNumber']]=$row;
			
			}
		?>
		<?php include "selecttable.php"; ?>	
		</div>
		<div id="myModal2" class="modal2">
			<!-- Modal content -->
			<div class="modal-content3">
				<span id="close2" class="close">&times;</span>
				<form action="whathappened.php">
					<table>
						<tr>
							<td>Create log from:</td>
							<td><input id="formfrom" type="datetime-local" name="from"></td>
						</tr>
						<tr>
							<td>Create log to:</td>
							<td><input id="formto" type="datetime-local" name="to"></td>
						</tr>
						<tr>
							<td><input type="submit" value="Send"></td>
							<td></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<script>
			$( document ).ready(function()  {
				if(self!=top){
					$("header").hide();
					$("footer").hide();
				}
				setSButtons();
				rooms=['Allemaal',<?php echo $RoomSel;?>];
				roomsID=['0',<?php echo $RoomSelID;?>];
				curroomname='<?php echo $curroomname;?>';
				curroom=<?php echo $curroom;?>;
				var dto = new Date();
				var diff = dto.getTimezoneOffset()
				dto.setMinutes ( dto.getMinutes() - diff );
				$('#formto').val(dto.toJSON().slice(0,16));
				var dfrom = new Date(dto);
				dfrom.setHours ( dto.getHours() - 1 );
				$('#formfrom').val(dfrom.toJSON().slice(0,16));
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
			setCookie("room_whathappened.php","room"+curroom,365);
			}
			
			var modal2 = document.getElementById('myModal2');
			var btn2 = document.getElementById("myBtn2");
			var span2 = document.getElementById("close2");
			
			function clickRangeButton() {
			modal2.style.display = "block";
			}
			
			span2.onclick = function() {
			modal2.style.display = "none";
			}
			
			function setRange(hours){
				var dto = new Date();
				var diff = dto.getTimezoneOffset()
				dto.setMinutes ( dto.getMinutes() - diff );
				var valto = (dto.toJSON().slice(0,16));
				var dfrom = new Date(dto);
				dfrom.setHours ( dto.getHours() - hours );
				var valfrom = (dfrom.toJSON().slice(0,16));
				window.location.href = "whathappened.php?from="+valfrom+"&to="+valto;
			}
			
		</script>
		<!-- The Modal -->
		<?php 
			$count=1;
			if (!empty($_GET['every'])){
			$count=$_GET['every'];}
			
			$to=time();
			if (!empty($_GET['to'])){
			$to=strtotime($_GET['to']);}
			
			$from=$to-(60*60);
			if (!empty($_GET['from'])){
			$from=strtotime($_GET['from']);}
			
			$sqlstring="set @row:=-1";
			$sqlstring2="SELECT measurement_values.* FROM measurement_values INNER JOIN (SELECT ID FROM (SELECT @row:=@row+1 AS rownum, ID FROM ( SELECT ID from measurement_values WHERE ID BETWEEN ".$from." AND ".$to." ORDER BY ID ) AS sorted ) as ranked WHERE rownum %" . $count." = 0 ) AS subset ON subset.ID = measurement_values.ID";
			
				echo "<table class='log'><tr><th>Datum</th><th>tijd</th><th>Meting</th><th>Waarde</th></tr>";
				
				$result = mysql_query($sqlstring) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
				$result = mysql_query($sqlstring2) or die('something went wrong with '.mysql_error(). ' sql-string: '.$sqlstring);
				$lastdate = "";
				$lasttime = "";
				while($row = mysql_fetch_row($result, MYSQL_ASSOC)) {
					for ($x=0;$x<=$total;$x++){
						if (array_key_exists($column[$x],$row)){
							if	(!is_null($row[$column[$x]])){
								if (date('Y-m-d',$row['ID'])==$lastdate){
									echo "<tr><td></td>";
								}else{
									echo "<tr><td>".date('Y-m-d',$row['ID'])."</td>";
									$lastdate = date('Y-m-d',$row['ID']);
								}
								if (date('H:i:s',$row['ID'])==$lasttime){
									echo "<td></td>";
								}else{
									echo "<td>".date('H:i:s',$row['ID'])."</td>";
									$lasttime = date('H:i:s',$row['ID']);
								}
								echo "<td>".$meas[$column[$x]]['LongDescription']."</td>";
								if ($meas[$column[$x]]['AnalogID']==1){
									echo "<td>".$row[$column[$x]].$meas[$column[$x]]['Unit']."</td></tr>";
								}else{
									if ($row[$column[$x]]==1){
										echo "<td>".$meas[$column[$x]]['TrueText']."</td></tr>";
									}else{
										echo "<td>".$meas[$column[$x]]['FalseText']."</td></tr>";
									}
								}
							}
						}
					}
			
				}
				echo "</table>"
		?>
		
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