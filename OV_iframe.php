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
			
				//-->
		</script>

		<!-- main page starts here -->	


<script>

function getValues(){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var currentresult = JSON.parse(this.responseText);
			console.log(currentresult);
			getStuff(currentresult);
		}
	};
	xmlhttp.open("GET", "directions.php", true);
	xmlhttp.send();
}

$( document ).ready(function()  {
	getValues();
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

class Desc {
	constructor(obj) {
		this.text = "";
		this.arr="";
		this.dep="";
		this.steps=[];
		this.obj = obj;
		this.stepsize=-1;
		this.transport="";
		this.stp="";
		this.stpname="";
		this.maxsteps=0;
	}
	
    logstep(transport,dur) {
		this.transport=transport;
		this.transport = this.transport.replace(/WALK/g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/walk.png' height='15' width='15'></img>");
		this.transport = this.transport.replace(/Bus /g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/bus2.png' height='15' width='15'></img>");
		this.transport = this.transport.replace(/Tram /g,"<img src='https://maps.gstatic.com/mapfiles/transit/iw2/6/tram2.png' height='15' width='15'></img>");

		this.stepsize+=1;
		this.steps[this.stepsize]=[this.transport,dur];
    }
	duration(line) {
		this.duration=line;
    }
	arrival(line) {
		this.arr=line;
    }
	departure(line) {
		this.dep=line;
    }

	stoptime(line) {
		this.stp=line;
		console.log(this.stp);
    }
	
	stopname(line) {
		this.stpname=line;
    }
	
	getmaxstep(mainobj){
		for (this.x=0; this.x<mainobj.routes.length; this.x++){
			if (mainobj.routes[x].legs[0].steps.length>this.maxsteps){
				this.maxsteps=mainobj.routes[x].legs[0].steps.length;
			}
		}
	}
	
	print(){
		this.text="<table class='OV' style='text-align: center;'><tr><td>"+this.dep+"</td><td>"+this.duration+"</td>";
		for (this.x=0;this.x<=this.stepsize;this.x++){
			this.text+="<td>"+this.steps[this.x][0]+"</td>";
		}
		for (this.x=this.stepsize;this.x<this.maxsteps;this.x++){
			this.text+="<td></td>";
		}
		this.text+="<td>Eerste halte:"+this.stp+"</td>";
		this.text+="</tr><tr><td>"+this.arr+"</td><td>button</td>";
		for (this.x=0;this.x<=this.stepsize;this.x++){
			this.text+="<td>"+this.steps[this.x][1]+"</td>";
		}
		this.text+="<td>"+this.stpname+"</td>";
		this.text+="</tr></table>";
		this.obj.html(this.text);
	}
}

function addOpt(ID){
	var tempEl = $( "#OV_optie" ).clone();
	$(tempEl).attr('id',"optie_"+ID);
	tempEl.html("Optie " + (ID+1) + ":");
	tempEl.appendTo( "#OV" );
	return tempEl;
}

function addDesc(ID){
	var tempEl = $( "#OV_description" ).clone();
	$(tempEl).attr('id',"desc_"+ID);
	tempEl.appendTo( "#OV" );
	return tempEl;
}

function addDetail(ID){
	var tempEl = $( "#OV_details" ).clone();
	$(tempEl).attr('id',"det_"+ID);
	tempEl.appendTo( "#OV" );
	return tempEl;
}

function getStuff(data_directions_tram){
		$("#OV").empty();
		//data is the JSON string
		var det =  new Array();
		var desc = new Array();
		console.log(data_directions_tram);
		
		if (data_directions_tram.status=="OK"){
			for (x=0; x<data_directions_tram.routes.length; x++){
				firststop=false;
				objopt = addOpt(x);
				objdesc = addDesc(x);
				objdesc.html("testttt"+x);
				objdet = addDetail(x);
				objdet.html("testrrr"+x);
				
				det[x]= new Detail(objdet);
				desc[x] = new Desc(objdesc);
				det[x].log("Optie "+(x+1)+":");
				det[x].log("Vertrek om: "+data_directions_tram.routes[x].legs[0].departure_time.text);
				desc[x].departure(data_directions_tram.routes[x].legs[0].departure_time.text);
				for (y=0; y<data_directions_tram.routes[x].legs[0].steps.length; y++){
					if (data_directions_tram.routes[x].legs[0].steps[y].travel_mode=="WALKING"){
						tram_text =data_directions_tram.routes[x].legs[0].steps[y].html_instructions;
						tram_text+=' ('+data_directions_tram.routes[x].legs[0].steps[y].duration.text+')';
						det[x].log(tram_text);
						desc[x].logstep('WALK',data_directions_tram.routes[x].legs[0].steps[y].duration.text);
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
						desc[x].logstep(data_directions_tram.routes[x].legs[0].steps[y].transit_details.line.vehicle.name+' ' +data_directions_tram.routes[x].legs[0].steps[y].transit_details.line.short_name,data_directions_tram.routes[x].legs[0].steps[y].duration.text);
						if (!firststop){
							firststop=true;
							console.log("hierzo");
							console.log(data_directions_tram.routes[x].legs[0].steps[y].transit_details.departure_stop.name);
							desc[x].stopname(data_directions_tram.routes[x].legs[0].steps[y].transit_details.departure_stop.name);
							desc[x].stoptime(data_directions_tram.routes[x].legs[0].steps[y].transit_details.departure_time.text);
						}
					}
				}
			det[x].log('Aankomsttijd: '+data_directions_tram.routes[x].legs[0].arrival_time.text+', reisduur: '+data_directions_tram.routes[x].legs[0].duration.text)
			desc[x].arrival(data_directions_tram.routes[x].legs[0].arrival_time.text);
			desc[x].duration(data_directions_tram.routes[x].legs[0].duration.text);
			det[x].print();
			desc[x].print();
			}
		} else {det[x].log(data_directions_tram.status);
		}
setTimeout(function(){ getValues(); }, 60000);		
	};


</script>
<div id="OVhidden" style="display: none;">
	<div id="OV_optie"></div>
	<div id="OV_description"></div>
	<div id="details" style="display: none;"></div></div>
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
