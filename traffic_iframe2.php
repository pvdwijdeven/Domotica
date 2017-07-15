<?php 
	$dummy=$_SERVER;
	$title="Domo traffic_iframe";
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
		<link rel="stylesheet" href="css/traffic2.css?v=3.0">
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
					document.getElementById("showdetails").style.width = $(traffic_mainframe).css('width');
					document.getElementById("showdetails").style.height = $(traffic_mainframe).css('height');
					setTimeout(function(){closeDet(); }, 300000);
				}
			
				function closeDet() {
					$("#detail_text").html("");
					document.getElementById("showdetails").style.width = "0";
				}
				
				function openSel(x) {
					$("#showroutes").css('z-index', '50');
					$("#showroutes").css('width', $(traffic_mainframe).css('width'));
					$("#showroutes").css('height', $(traffic_mainframe).css('height'));
					setTimeout(function(){closeSel(); }, 300000);
				}
			
				function closeSel() {
					document.getElementById("showroutes").style.width = "0";
				}
				
				function routeSel(tempEl) {
					ID = $(tempEl).attr('id').substr(6);
					window.location.href = "traffic_iframe2.php?ID="+ID;
					
				}
			
			
				//-->
		</script>

		<!-- main page starts here -->	
<?php
	$trafficINFO = mysql_query('SELECT * FROM trafficINFO ');
	$j            = -1;
	$traffic_table="[";
	while ($traffic_info = mysql_fetch_row($trafficINFO, MYSQL_ASSOC)) {
		$j += 1;
		$traffic_table.= "[".$traffic_info['ID'].",'".$traffic_info['Source']."','".$traffic_info['Destination']."','".$traffic_info['SourceName']."','".$traffic_info['DestinationName']."','".$traffic_info['DefaultYNID']."'],";
	}
	$traffic_table=substr($traffic_table, 0, -1);
	$traffic_table.="]";
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
	var source_dest = <?php echo $traffic_table; ?>;
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
		setTimeout(function(){ window.location.href = "traffic_iframe2.php"; }, 3600000);
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
	var tempEl = $( "#traffic_details" ).clone();
	$(tempEl).attr('id',"det_"+ID);
	tempEl.appendTo( "#traffic" );
	return tempEl;
}

function showtable(routedesc){
	var ID = <?php echo $ID; ?>;
	var source_dest = <?php echo $traffic_table; ?>;
	fastest=-1
	fastestval=999999
	shortest=-1
	shortestval=999999
	for (x=0;x<routedesc.length;x++){
		temp=routedesc[x][5].replace(',', '.')
		temp=parseFloat(temp.substring(0,temp.length-3));
		//console.log(temp);
		if (temp<shortestval){
			shortestval=temp;
			shortest=x;
		}
		temp=parseInt(routedesc[x][4]);
		if (temp<fastestval){
			fastestval=temp;
			fastest=x;
		}
	}
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
	text="<table class='traffic_table'>";
	evenrow="oddrow";
	text+="<tr><td colspan='2'><button id='traffic_header' class='trafficselectbutton' onclick='openSel()'></button></td></tr>";
	for (x=0;x<routedesc.length;x++){
		diff=parseInt(routedesc[x][4])-parseInt(routedesc[x][2]);
		diff=Math.round(diff/60);
		col=$("body").color;
		if (diff<0){col="green";} else
		if (diff>0 & diff<15){col="orange";} else
		if (diff>=15){col="red";}
		difftxt="<span style='color:"+col+"'><b>"+diff+" min.</b></span>";
		faststr="";
		if (fastest==x){
			faststr=" <b>(snelste route)</b>";
		}
		shortstr="";
		if (shortest==x){
			shortstr=" <b>(kortste route)</b>";
		}
		if (evenrow=="evenrow"){
			evenrow="oddrow";
		}else{
			evenrow="evenrow";
		}
		text+="<tr class="+evenrow+"><td>Route ("+routedesc[x][5]+"):"+shortstr+"</td><td><button class='trafficdetbutton' onclick='openDet("+x+")'>"+routedesc[x][0]+"</button></td></tr>";
		text+="<tr class="+evenrow+"><td>Reistijd huidig verkeer: ("+difftxt+")</td><td class='leftcol'>"+routedesc[x][3]+"</td></tr>";
		var t = new Date();
		t.setSeconds(t.getSeconds() + parseInt(routedesc[x][4]));
		text+="<tr class="+evenrow+"><td>Aankomsttijd:"+faststr+"</td><td>"+t.toLocaleTimeString().substr(0,5)+"</td></tr>";
		text+="<tr class="+evenrow+"><td>Reistijd normaal verkeer:</td><td>"+routedesc[x][1]+"</td></tr>";
	}
	text+="<tr><td colspan='2' style='height:100%'></td></tr></table>";
	$("#traffictable").html(text);
	if (sourcename=="thuis"){
		$('#traffic_header').html("verkeer naar " + destname);
	}else{
		$('#traffic_header').html("verkeer van " + sourcename + " naar " + destname);
	}
	$(".traffic_menubutton").css("width",$(traffic_mainframe).css('width'));
	$("#traffic_header").css("width",(parseInt($(traffic_mainframe).css('width'))-10)+'px');

	while (parseInt($("#traffic_header").css("height"))>43){
		curfont = parseInt($("#traffic_header").css("font-size"));
		curfont-=1;
		$("#traffic_header").css("font-size",curfont+"px");
		console.log($("#traffic_header").css("height"));
	}
	$("#traffic_header").css("width","532px");
	$(".trafficdetbutton").each(function( index ) {
	while (parseInt($(this).css("height"))>69){
		curfont = parseInt($(this).css("font-size"));
		curfont-=1;
		$(this).css("font-size",curfont+"px");
		console.log($(this).css("height"));
	}
	});
	
}

function getStuff(data_traffic){
		$("#traffic").empty();
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
		
setTimeout(function(){ getValues(<?php echo $ID; ?>); }, 280000);		
	};


</script>
<div id="traffic_mainframe">
<div id="showdetails" class="traffic_details" onclick="closeDet()"><div id="detail_text"></div>
</div>

<div id="showroutes" class="traffic_details" onclick="closeSel()"></div>

<div id="traffichidden" style="display: none;">
	<div id="traffic_details"></div>
	<button id="route0" class="traffic_menubutton" onclick="routeSel(this)">Route1</button>
</div>
<div id="trafficoverview"></div>
<div id="traffictable"></div>
<div id="traffic" style="display: none;">

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
