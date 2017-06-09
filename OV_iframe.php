<?php 
	$title="Domo OV_iframe";
	$adminpage=false;
	include_once 'includes/db_connect.php';
	include_once 'includes/functions.php';
	
	if (array_key_exists ('HTTP_REFERER',$_SERVER)){
		$loginchecked=true;
	}else{
		$loginchecked=login_check($mysqli);
	};
	
	if (!$adminpage AND $loginchecked){
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
		<link rel="stylesheet" href="css/OV.css?v=3.0">
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
					$("#showdetails").css('width', $(OV_mainframe).css('width'));
					$("#showdetails").css('height', $(OV_mainframe).css('height'));
					setTimeout(function(){closeDet(); }, 60000);
				}
			
				function closeDet() {
					$("#detail_text").html("");
					document.getElementById("showdetails").style.width = "0";
					document.getElementById("showdetails").style.height = "0";
				}
				
				function openSel(x) {
					$("#showroutes").css('z-index', '50');
					$("#showroutes").css('width', $(OV_mainframe).css('width'));
					$("#showroutes").css('height', $(OV_mainframe).css('height'));
					setTimeout(function(){closeSel(); }, 300000);
				}
			
				function closeSel() {
					document.getElementById("showroutes").style.width = "0";
					document.getElementById("showroutes").style.height = "0";
				}
				
				function routeSel(tempEl) {
					ID = $(tempEl).attr('id').substr(6);
					window.location.href = "OV_iframe.php?ID="+ID;
					
				}
				
				function showOpt() {
					document.getElementById("showquick").style.width = "0%";
					setTimeout(function(){hideOpt(); }, 300000);
				}
				
				function hideOpt() {
					document.getElementById("showquick").style.width = "100%";
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
function getValues(ID){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var currentresult = JSON.parse(this.responseText);
			//console.log(currentresult);
			getStuff(currentresult);
		}
	};
	xmlhttp.open("GET", "directions.php?ID="+ID, true);
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
	//if (window.self == window.top){
	//	setTimeout(function(){ window.location.href = "domo_dashboard.php"; }, 60000);
	//}
	
	if (<?php echo $ID; ?> != 0){
		setTimeout(function(){ window.location.href = "OV_iframe.php"; }, 3600000);
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

function fgowhen(seconds){
	if (seconds < 120){
		return "<b>vertrek nu!</b>";
	}
	return "vertrek over " + Math.round(seconds/60) + " minuten";
}

function fgowhenquick(text){
	if (text=="<b>vertrek nu!</b>"){
		return "<tr class='evenrow'><td colspan="+(maxsteps+2)+"><div>vertrek</div><div class='OV_emphasize'>nu!</div></td></tr>";
	}
	return "<tr class='evenrow'><td colspan="+(maxsteps+2)+"><div>vertrek over</div><div class='OV_emphasize'>"+ parseInt(text.substring(13)) + " minuten</div></td></tr>";
}

function getsymbols(text,size=8){
		text = text.replace(/WALK/g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/walk.png' height='"+size+"' width='"+size+"'></img>");
		text = text.replace(/Bus /g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/bus2.png' height='"+size+"' width='"+size+"'></img>");
		text = text.replace(/Tram /g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/tram2.png' height='"+size+"' width='"+size+"'></img>");
		text = text.replace(/Trein /g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/rail2.png' height='"+size+"' width='"+size+"'></img>");
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
	quick="<table class='OV' style='text-align: center; margin: 0 auto;'>";
	text="<table class='OV' style='text-align: center;'>";

	text+="<tr><td colspan="+(maxsteps+2)+"><button id='OV_header' class='OVselectbutton' onclick='openSel()'></button></td></tr>";
	evenrow="oddrow";
	shortest=99999999;
	shortestID=0;
	for (x=0;x<routes.length;x++){
		if(routes[x].length>0){
			if (shortest>routedesc[x][6]){
				shortest=routedesc[x][6];
				shortestID=x
			}
		}
	}
	if (shortest==99999999){
		quick+="<tr><td colspan="+routes[shortestID].length+"><button id='OV_header1' class='OVselectbutton' onclick='openSel()'></button></td></tr><tr><td>Er is momenteel helaas geen OV beschikbaar voor deze route!!</td></tr></table>";
		$("#quick_text").html(quick);
		$('#OV_header').html("OV van " + sourcename + " naar " + destname);
		$('#OV_header1').html("OV van " + sourcename + " naar " + destname);
		$("#OV_header").width="100%";
		$("#OV_header1").width="100%";
		while (parseInt($("#OV_header1").css("height"))>25){
			curfont = parseInt($("#OV_header1").css("font-size"));
			curfont-=1;
			$("#OV_header1").css("font-size",curfont+"px");
			$("#OV_header").css("font-size",curfont+"px");
			//console.log($("#traffic_header").css("height"));
		}
	}else{
		quick+="<tr><td colspan="+routes[shortestID].length+"><button id='OV_header1' class='OVselectbutton' onclick='openSel()'></button></td></tr>";
		quick+=fgowhenquick(routedesc[shortestID][3]);
		quick+="<tr class='oddrow'><td style='white-space: nowrap' colspan="+routes[shortestID].length+"><div id='OV_halte'>naar halte<BR><div class='emphasize2'>"+routedesc[shortestID][4]+"</div></div></td></tr>";
		quick+="<tr class='evenrow'><td colspan="+routes[shortestID].length+"><div style='width: 294px'>vertrek: "+routedesc[shortestID][0]+" - aankomst: "+routedesc[shortestID][1]+" ("+routedesc[shortestID][2]+")</div></td></tr><tr  class='oddrow'>";

		for (x=0;x<routes.length;x++){
			if(routes[x].length>0){
				if (evenrow=="evenrow"){
					evenrow="oddrow";
				}else{
					evenrow="evenrow";
				}

				text+="<tr class="+evenrow+"><td colspan="+(maxsteps+2)+">";
				text+="Van " + routedesc[x][0] + " tot " + routedesc[x][1] + " ("+routedesc[x][2]+") " + routedesc[x][3];
				text+="</td></tr><tr class="+evenrow+">";
				for (y=0;y<routes[x].length;y++){
					text+="<td>"+getsymbols(routes[x][y][0])+"</td>";
					if (shortestID==x){
						quick+="<td>"+getsymbols(routes[x][y][0],15)+"</td>";
					}
				}
				if (routes[x].length<maxsteps){
					for (z=routes[x].length;z<maxsteps;z++){
						text+="<td></td>";
					}
				}
				if (shortestID==x){
					quick+="</tr><tr class='evenrow'>";
				}
				text+="<td colspan=2>"+routedesc[x][4]+"</td></tr><tr class="+evenrow+">";
				for (y=0;y<routes[x].length;y++){
					text+="<td>"+routes[x][y][1]+"</td>";
					if (shortestID==x){
						quick+="<td>"+routes[x][y][1]+"</td>";
					}
				}
				if (routes[x].length<maxsteps){
					for (z=routes[x].length;z<maxsteps;z++){
						text+="<td></td>";
					}
				}
				text+="<td><button class='OVdetbutton' onclick='openDet("+x+")'>Details</button></td><td>"+routedesc[x][5]+"</td></tr>";		
			}	
		}
		text+="</table>";
		quick+="</tr><tr><td colspan="+routes[shortestID].length+"><button id='showoptions' class='OVdetbutton' onclick='showOpt()'>Laat opties zien</button></td></tr></table";
		$("#quick_text").html(quick);
		$("#OVtable").html(text);
		$('#OV_header').html("OV van " + sourcename + " naar " + destname);
		$('#OV_header1').html("OV van " + sourcename + " naar " + destname);
		while (parseInt($("#OV_header1").css("height"))>25){
			curfont = parseInt($("#OV_header1").css("font-size"));
			curfont-=1;
			$("#OV_header1").css("font-size",curfont+"px");
			$("#OV_header").css("font-size",curfont+"px");
			//console.log($("#traffic_header").css("height"));
		}
		$("#OV_header").css('width',"294px");
		$("#OV_header1").css('width',"294px");	
		while (parseInt($('#OV_halte').css('width'))>=300){
			curfont = parseInt($('#OV_halte').css("font-size"));
			curfont-=1;
			temp=$('#OV_halte').css('font-size',curfont+"px")
		}
	}

	
}

function getStuff(data_directions_tram){
		$("#OV").empty();
		//data is the JSON string
		var det =  new Array();
		var desc = new Array();
		//console.log(data_directions_tram);

		
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
					var durationvar = data_directions_tram.routes[x].legs[0].duration.value;
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
								//console.log(firststop);
								curroutedesc.push(firststop,firststoptime,durationvar);
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
		//console.log(routes);
		//console.log(routedesc);
		showtable(routes,routedesc);
		} else {det[x].log(data_directions_tram.status);
		}
		
setTimeout(function(){ getValues(<?php echo $ID; ?>); }, 60000);		
	};


</script>
<div id="OV_mainframe">
<div id="showquick" class="OV_quick">
	<div id="quick_text">
	</div>
</div>

<div id="showdetails" class="OV_details" onclick="closeDet()">
	<div id="detail_text"></div>
</div>

<div id="showroutes" class="OV_details" onclick="closeSel()"></div>

<div id="OVhidden" style="display: none;">
	<div id="OV_details" style="display: none;"></div>
	<button id="route0" class="OV_menubutton" onclick="routeSel(this)">Route1</button>
</div>
<div id="OVoverview"></div>
<div id="OVtable"></div>
<div id="OV">
</div>
</div>


	</body>
</html>
<?php
	} else { 
		if ($adminpage AND $loginchecked){
			echo "<p><span class='error'>This is an ADMIN page. You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
		} else { 
			echo "<p><span class='error'>You are not authorized to access this page.</span> Please <a href='index.php'>login</a></p>";
			echo substr($_SERVER['HTTP_REFERER'],-18);
			echo $loginchecked;
			echo $adminpage;
		}
	} 
?>
