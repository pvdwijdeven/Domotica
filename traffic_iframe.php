<?php 
	$title="Domo traffic_iframe";
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
					if (event.target == document.getElementById("showdetails")) {
					document.getElementById("showdetails").style.width = "0";
					}
				}	
				
				function openDet(x) {
					$("#detail_text").html($("#det_"+x).html());
					document.getElementById("showdetails").style.width = "100%";
					setTimeout(function(){closeDet(); }, 300000);
				}
			
				function closeDet() {
					$("#detail_text").html("");
					document.getElementById("showdetails").style.width = "0";
				}
				
				function openSel(x) {
					$("#showroutes").css('z-index', '50');
					$("#showroutes").css('width', '100%');
					setTimeout(function(){closeDet(); }, 300000);
				}
			
				function closeSel() {
					document.getElementById("showroutes").style.width = "0";
				}
				
				function routeSel(tempEl) {
					ID = $(tempEl).attr('id').substr(6);
					window.location.href = "traffic_iframe.php?ID="+ID;
					
				}
			
			
				//-->
		</script>

		<!-- main page starts here -->	
<?php
	$OVINFO = mysql_query('SELECT * FROM trafficINFO ');
	$j            = -1;
	$ov_table="[";
	while ($ov_info = mysql_fetch_row($OVINFO, MYSQL_ASSOC)) {
		$j += 1;
		$ov_table.= "[".$ov_info['ID'].",'".$ov_info['Source']."','".$ov_info['Destination']."','".$ov_info['SourceName']."','".$ov_info['DestinationName']."','".$ov_info['DefaultYNID']."'],";
	}
	$ov_table=substr($ov_table, 0, -1);
	$ov_table.="]";
	$ID=0;
	if (!empty($_GET['ID'])){
	$ID=$_GET['ID'];}
?>
		
<script>
function getValues(ID){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var currentresult = JSON.parse(this.responseText);
			console.log(currentresult);
			getStuff(currentresult);
		}
	};
	xmlhttp.open("GET", "traffic.php?ID="+ID, true);
	xmlhttp.send();
}

function addButton(ID){
	var tempEl = $( "#route0" ).clone();
	$(tempEl).attr('id',ID);
	tempEl.appendTo( "#showroutes" );
	return tempEl;
}

function setRouteMenu(){
	var source_dest = <?php echo $ov_table; ?>;
	for (x=0;x<source_dest.length;x++){
		tempEl=addButton("route_"+source_dest[x][0]);
		if (source_dest[x][3]=="thuis"){
			tempEl.html("naar "+ source_dest[x][4]);
		}else{
			tempEl.html("van "+ source_dest[x][3] + " naar "+ source_dest[x][4]);
		}
	}
}

$( document ).ready(function()  {
	getValues(<?php echo $ID; ?>);
	if (window.self == window.top){
		setTimeout(function(){ window.location.href = "domo_dashboard.php"; }, 3600000);
	}
	
	if (<?php echo $ID; ?> != 0){
		setTimeout(function(){ window.location.href = "traffic_iframe.php"; }, 3600000);
	}
	setRouteMenu();
});


class Detail {
	constructor(obj) {
		this.text = "";
		this.obj = obj;
	}
    log(line) {
		this.text+=line+"<br>";
    }
	print() {
		this.obj.html(this.text);
		//console.log(this.text);
	}
}

function addDetail(ID){
	var tempEl = $( "#OV_details" ).clone();
	$(tempEl).attr('id',"det_"+ID);
	tempEl.appendTo( "#OV" );
	return tempEl;
}

function showtable(routedesc){
	var ID = <?php echo $ID; ?>;
	var source_dest = <?php echo $ov_table; ?>;
	if (ID==0){
		for (j=0;j<source_dest.length;j++){
			if (source_dest[j][5]==1){
				var sourcename=source_dest[j][3];
				var destname=source_dest[j][4];
			}
		}
	} else {
		for (j=0;j<source_dest.length;j++){
			if (source_dest[j][0]==ID){
				var sourcename=source_dest[j][3];
				var destname=source_dest[j][4];
			}
		}
	}
	text="<table class='traffic' style='text-align: center; margin: 0 auto;'>";
	evenrow="oddrow";
	text+="<tr><td colspan='2'><button id='OV_header' style='width: 300px'class='OVselectbutton' onclick='openSel()'></button></td></tr>";
	for (x=0;x<routedesc.length;x++){
		if (evenrow=="evenrow"){
			evenrow="oddrow";
		}else{
			evenrow="evenrow";
		}
		text+="<tr class="+evenrow+"><td>Route ("+routedesc[x][5]+"):</td><td><button class='OVdetbutton' onclick='openDet("+x+")'>"+routedesc[x][0]+"</button></td></tr>";
		text+="<tr class="+evenrow+"><td>Reistijd huidig verkeer:</td><td>"+routedesc[x][3]+"</td></tr>";
		var t = new Date();
		t.setSeconds(t.getSeconds() + parseInt(routedesc[x][4]));
		text+="<tr class="+evenrow+"><td>Aankomsttijd:</td><td>"+t.toLocaleTimeString().substr(0,5)+"</td></tr>";
		text+="<tr class="+evenrow+"><td>Reistijd normaal verkeer:</td><td>"+routedesc[x][1]+"</td></tr>";
	}
	text+="</table>";
	$("#traffictable").html(text);
	if (sourcename=="thuis"){
		$('#OV_header').html("verkeer naar " + destname);
	}else{
		$('#OV_header').html("verkeer van " + sourcename + " naar " + destname);
	}
	$("#OV_header").width="100%";
}

function getStuff(data_traffic){
		$("#OV").empty();
		//data is the JSON string
		var det =  new Array();
		//console.log(data_traffic);

		
		if (data_traffic.status=="OK"){
			//determine max steps
			var routes=[];
			var routedesc=[];
			
			for (x=0; x<data_traffic.routes.length; x++){
				var curroutedesc=[];
				if (data_traffic.routes[x].legs[0].steps.length>1){
					objdet = addDetail(x);
					var duration = data_traffic.routes[x].legs[0].duration.text;
					var durationvar = data_traffic.routes[x].legs[0].duration.value;
					var durationtraffic = data_traffic.routes[x].legs[0].duration_in_traffic.text;
					var durationtrafficvar = data_traffic.routes[x].legs[0].duration_in_traffic.value;
					var summary = data_traffic.routes[x].summary
					var distance = data_traffic.routes[x].legs[0].distance.text;
					curroutedesc=[summary,duration,durationvar,durationtraffic,durationtrafficvar,distance];
					
					det[x]= new Detail(objdet);

					for (y=0; y<data_traffic.routes[x].legs[0].steps.length; y++){
						if (data_traffic.routes[x].legs[0].steps[y].travel_mode=="DRIVING"){
							tram_text =data_traffic.routes[x].legs[0].steps[y].html_instructions;
							tram_text+=' ('+data_traffic.routes[x].legs[0].steps[y].duration.text+')';
							det[x].log(tram_text);
						}
					}
					det[x].print();
				}
			routedesc.push(curroutedesc);
			}
		//console.log(routes);
		//console.log(routedesc);
		showtable(routedesc);
		} else {det[x].log(data_traffic.status);
		}
		
setTimeout(function(){ getValues(<?php echo $ID; ?>); }, 300000);		
	};


</script>

<div id="showdetails" class="OV_details" onclick="closeDet()">
	<a href="javascript:void(0)" class="closebtn" onclick="closeDet()">&times;</a>
	<div id="detail_text"></div>
</div>

<div id="showroutes" class="OV_details" onclick="closeSel()">
	<a href="javascript:void(0)" class="closebtn" onclick="closeSel(this)">&times;</a>
</div>

<div id="OVhidden" style="display: none;">
	<div id="OV_details"></div>
	<button id="route0" class="menubutton" onclick="routeSel(this)">Route1</button>
</div>
<div id="OVoverview"></div>
<div id="traffictable"></div>
<div id="OV" style="display: none;">

</div>


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