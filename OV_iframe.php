<?php 
	$title="Domo Dashboard";
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
				
				function openDet() {
					document.getElementById("showdetails").style.width = "100%";
				}
			
				function closeDet() {
					document.getElementById("showdetails").style.width = "0";
				}
			
				//-->
		</script>

		<!-- main page starts here -->	
<?php
	$OVINFO = mysql_query('SELECT * FROM OVINFO ');
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

	window.onclick = function(event) {
		if (event.target == document.getElementById("mySidenav")) {
		document.getElementById("mySidenav").style.width = "0";
		}
	}	
	
	function openNav() {
		document.getElementById("mySidenav").style.width = "100%";
	}

	function closeNav() {
		document.getElementById("mySidenav").style.width = "0";
	}

function getValues(ID){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var currentresult = JSON.parse(this.responseText);
			console.log(currentresult);
			getStuff(currentresult);
		}
	};
	xmlhttp.open("GET", "directions.php?ID="+ID, true);
	xmlhttp.send();
}

$( document ).ready(function()  {
	getValues(<?php echo $ID; ?>);
	if (window.self == window.top){
		setTimeout(function(){ window.location.href = "domo_dashboard.php"; }, 60000);
	}
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
		console.log(this.text);
	}
}

function addDetail(ID){
	var tempEl = $( "#OV_details" ).clone();
	$(tempEl).attr('id',"det_"+ID);
	tempEl.appendTo( "#OV" );
	return tempEl;
}

function fgowhen(seconds){
	if (seconds < 120){
		return "<b>vertrek nu!</b>";
	}
	return "vertrek over " + Math.round(seconds/60) + " minuten";
}

function getsymbols(text){
		text = text.replace(/WALK/g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/walk.png' height='15' width='15'></img>");
		text = text.replace(/Bus /g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/bus2.png' height='15' width='15'></img>");
		text = text.replace(/Tram /g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/tram2.png' height='15' width='15'></img>");
		return text;
}

function showtable(routes,routedesc){
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
	maxsteps=0;
	for (x=0;x<routes.length;x++){
		maxsteps=Math.max(routes[x].length,maxsteps);
	}
	text="<table class='OV' style='text-align: center;'>";
	text+="<tr><td colspan="+(maxsteps+1)+"><button id='OV_header' class='menubutton' onclick='openDet()'></button></td></tr>";
	for (x=0;x<routes.length;x++){
		if(routes[x].length>0){
			text+="<tr><td colspan="+(maxsteps+1)+">";
			text+="Van " + routedesc[x][0] + " tot " + routedesc[x][1] + " ("+routedesc[x][2]+") " + routedesc[x][3];
			text+="</td></tr><tr>";
			for (y=0;y<routes[x].length;y++){
				text+="<td>"+getsymbols(routes[x][y][0])+"</td>";
			}
			if (routes[x].length<maxsteps){
				for (z=routes[x].length;z<maxsteps;z++){
					text+="<td></td>";
				}
			}
			text+="<td>"+routedesc[x][4]+"</td></tr><tr>";
			for (y=0;y<routes[x].length;y++){
				text+="<td>"+routes[x][y][1]+"</td>";
			}
			if (routes[x].length<maxsteps){
				for (z=routes[x].length;z<maxsteps;z++){
					text+="<td></td>";
				}
			}
			text+="<td>"+routedesc[x][5]+"</td></tr>";		
		}	
	}
	text+="</table>";
	$("#OVtable").html(text);
	$('#OV_header').html("OV van " + sourcename + " naar " + destname);
	$("#OV_header").width="100%";
	$("#detail_text").html($("#det_1").html());
}

function getStuff(data_directions_tram){
		$("#OV").empty();
		//data is the JSON string
		var det =  new Array();
		var desc = new Array();
		console.log(data_directions_tram);

		
		if (data_directions_tram.status=="OK"){
			//determine max steps
			var max_steps=0;
			for (x=0; x<data_directions_tram.routes.length; x++){
				max_steps=(max_steps,data_directions_tram.routes[x].legs[0].steps.length);
			}
			var routes=[];
			var routedesc=[];
			
			for (x=0; x<data_directions_tram.routes.length; x++){
				var curroute=[];
				var curroutedesc=[];
				if (data_directions_tram.routes[x].legs[0].steps.length>1){
					firststop=false;
					objdet = addDetail(x);
					var starttime = data_directions_tram.routes[x].legs[0].departure_time.text;
					var arrivaltime = data_directions_tram.routes[x].legs[0].arrival_time.text;
					var duration = data_directions_tram.routes[x].legs[0].duration.text;
					var startepoch = data_directions_tram.routes[x].legs[0].departure_time.value;
					var currentepoch = Math.ceil(new Date().getTime() / 1000);
					var gowhen = fgowhen(startepoch-currentepoch);
					curroutedesc=[starttime,arrivaltime,duration,gowhen];
					
					det[x]= new Detail(objdet);
					//det[x].log("Optie "+(x+1)+": van " + sourcename + " naar " + destname);
					det[x].log("Vertrek om: "+data_directions_tram.routes[x].legs[0].departure_time.text);

					for (y=0; y<data_directions_tram.routes[x].legs[0].steps.length; y++){
						if (data_directions_tram.routes[x].legs[0].steps[y].travel_mode=="WALKING"){
							tram_text =data_directions_tram.routes[x].legs[0].steps[y].html_instructions;
							tram_text+=' ('+data_directions_tram.routes[x].legs[0].steps[y].duration.text+')';
							det[x].log(tram_text);
							curroute.push(['WALK',data_directions_tram.routes[x].legs[0].steps[y].duration.text]);
						}
						if (data_directions_tram.routes[x].legs[0].steps[y].travel_mode=="TRANSIT"){
							tram_text="Neem om ";
							tram_text+=data_directions_tram.routes[x].legs[0].steps[y].transit_details.departure_time.text;
							tram_text+=' bij halte '+data_directions_tram.routes[x].legs[0].steps[y].transit_details.departure_stop.name;
							tram_text+=' '+data_directions_tram.routes[x].legs[0].steps[y].transit_details.line.vehicle.name;
							tram_text+=' '+data_directions_tram.routes[x].legs[0].steps[y].transit_details.line.short_name;
							tram_text+=' richting '+data_directions_tram.routes[x].legs[0].steps[y].transit_details.headsign;
							tram_text+=', aankomst om '+data_directions_tram.routes[x].legs[0].steps[y].transit_details.arrival_time.text;
							tram_text+=' ('+data_directions_tram.routes[x].legs[0].steps[y].duration.text+').';
							det[x].log(tram_text);
							curroute.push([data_directions_tram.routes[x].legs[0].steps[y].transit_details.line.vehicle.name+' ' +data_directions_tram.routes[x].legs[0].steps[y].transit_details.line.short_name,data_directions_tram.routes[x].legs[0].steps[y].duration.text]);
							if (!firststop){
								firststop=true;
								var firststop = data_directions_tram.routes[x].legs[0].steps[y].transit_details.departure_stop.name
								var firststoptime = data_directions_tram.routes[x].legs[0].steps[y].transit_details.departure_time.text
								console.log(firststop);
								curroutedesc.push(firststop,firststoptime);
							}
						}
					}
					det[x].log('Aankomsttijd: '+data_directions_tram.routes[x].legs[0].arrival_time.text+', reisduur: '+data_directions_tram.routes[x].legs[0].duration.text)
					det[x].print(max_steps);
					//desc[x].print();
				}
			routedesc.push(curroutedesc);
			routes.push(curroute);
			}
		console.log(routes);
		console.log(routedesc);
		showtable(routes,routedesc);
		} else {det[x].log(data_directions_tram.status);
		}
		
setTimeout(function(){ getValues(<?php echo $ID; ?>); }, 60000);		
	};


</script>

<div id="showdetails" class="OV_details">
	<a href="javascript:void(0)" class="closebtn" onclick="closeDet()">&times;</a>
	<div id="detail_text"></div>
</div>

<div id="OVhidden" style="display: none;">
	<div id="OV_details" style="display: none;"></div>
</div>
<div id="OVtable"></div>
<div id="OV">

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
