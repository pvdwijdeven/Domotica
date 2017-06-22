

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/style.css?v=3.0">
		<link rel="stylesheet" href="css/floorplan.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
	
		<script>
			var curFloor = "Tuin";
			function showTuin(){
				curFloor="Tuin";
				var c = document.getElementById("myCanvas");
				c.width = window.innerWidth-138;
				var ctx = c.getContext("2d");
				ctx.clearRect(0, 0, c.width, c.height);
				maxwidth=24910;
				maxheight=10000;
				scaleX=parseInt($("#myCanvas").css("width"))/maxwidth;
				scaleY=parseInt($("#myCanvas").css("height"))/maxheight;
				if (scaleX>scaleY){
					scale=scaleY;
					ystart=0;
					xstart=((parseInt($("#myCanvas").css("width"))/scale)-maxwidth)/2;
				}else{
					scale=scaleX;
					xstart=0;
					ystart=((parseInt($("#myCanvas").css("height"))/scale)-maxheight)/2;
				}
				//console.log([xstart,ystart]);
				ctx.lineWidth=parseInt(3/scale);
				ctx.scale(scale,scale);
				ctx.strokeStyle="#FFFFFF";
				ctx.beginPath();
				
				ctx.translate(xstart,ystart);
				/*tuin*/
				ctx.fillStyle="#003300";
				ctx.fillRect(0,0,24910,10000);
				/*BG*/
				ctx.fillStyle="#000000";
				ctx.fillRect(4000,0,12110,5100);
				ctx.rect(4000,0,12110,5100);
				/*garage*/
				ctx.fillRect(10420,5100,5690,3100);
				ctx.rect(10420,5100,5690,3100);
				ctx.stroke();
			}

			function showBG(){
				curFloor="BG";
				var c = document.getElementById("myCanvas");
				c.width = window.innerWidth-138;
				var ctx = c.getContext("2d");
				ctx.clearRect(0, 0, c.width, c.height);
				maxwidth=12110;
				maxheight=5100;
				scaleX=parseInt($("#myCanvas").css("width"))/maxwidth;
				scaleY=parseInt($("#myCanvas").css("height"))/maxheight;
				if (scaleX>scaleY){
					scale=scaleY;
					ystart=0;
					xstart=((parseInt($("#myCanvas").css("width"))/scale)-maxwidth)/2;
				}else{
					scale=scaleX;
					xstart=0;
					ystart=((parseInt($("#myCanvas").css("height"))/scale)-maxheight)/2;
				}
				//console.log([xstart,ystart]);
				ctx.lineWidth=parseInt(3/scale);
				ctx.scale(scale,scale);
				ctx.strokeStyle="#FFFFFF";
				ctx.beginPath();
				
				ctx.translate(xstart,ystart);
				/*BG*/
				ctx.fillStyle="#000000";
				ctx.fillRect(0,0,12110,5100);
				ctx.rect(0,0,12110,5100);
				/* toilet + mk */
				ctx.rect(1100,0,2250,920);
				ctx.rect(1662,0,1250,920);
				/* hall */
				ctx.moveTo(0,2100);
				ctx.lineTo(4305,2100);
				ctx.lineTo(4305,1000);
				ctx.rect(3350,0,3100,1000);			
				
				ctx.stroke();
			}

			function show1e(){
				curFloor="1e";
				var c = document.getElementById("myCanvas");
				c.width = window.innerWidth-138;
				var ctx = c.getContext("2d");
				ctx.clearRect(0, 0, c.width, c.height);
				maxwidth=9710;
				maxheight=5100;
				scaleX=parseInt($("#myCanvas").css("width"))/maxwidth;
				scaleY=parseInt($("#myCanvas").css("height"))/maxheight;
				if (scaleX>scaleY){
					scale=scaleY;
					ystart=0;
					xstart=((parseInt($("#myCanvas").css("width"))/scale)-maxwidth)/2;
				}else{
					scale=scaleX;
					xstart=0;
					ystart=((parseInt($("#myCanvas").css("height"))/scale)-maxheight)/2;
				}
				//console.log([xstart,ystart]);
				ctx.lineWidth=parseInt(3/scale);
				ctx.scale(scale,scale);
				ctx.strokeStyle="#FFFFFF";
				ctx.beginPath();
				
				ctx.translate(xstart,ystart);

				/*1e*/
				ctx.fillStyle="#222222";
				ctx.fillRect(0,0,9710,5100);
				ctx.rect(0,0,9710,5100);
				/*hal+toilet*/
				ctx.fillRect(1640,0,4740,2150);
				ctx.rect(1640,0,4740,2150);
				ctx.moveTo(1640,990);
				ctx.lineTo(1640+4740,990);
				/*toilet*/
				ctx.fillRect(1640,0,1250,920);
				ctx.rect(1640,0,1250,920);
				ctx.fillRect(1640,0,1250+390,920);
				ctx.rect(1640,0,1250+390,920);
				/*trap*/
				ctx.fillRect(3280,0,3100,920);
				ctx.rect(3280,0,3100,920);
				/*slaapkamer 3*/
				ctx.fillRect(9710,0,-3260,2150);
				ctx.rect(9710,0,-3260,2150);
				/*slaapkamer 2*/
				ctx.fillRect(9710,5100,-4330,-2880);
				ctx.rect(9710,5100,-4330,-2880);
				/*badkamer*/
				ctx.fillRect(3480,2220,1830,2880);
				ctx.rect(3480,2220,1830,2880);
				/*slaapkamer 1*/
				ctx.moveTo(1570,0);
				ctx.lineTo(1570,2220);
				ctx.lineTo(3410,2220);
				ctx.lineTo(3410,2220+2880);
				
				
				ctx.stroke();
			}

			function show2e(){
				curFloor="2e";
				var c = document.getElementById("myCanvas");
				c.width = window.innerWidth-138;
				var ctx = c.getContext("2d");
				ctx.clearRect(0, 0, c.width, c.height);
				maxwidth=7960;
				maxheight=5100;
				scaleX=parseInt($("#myCanvas").css("width"))/maxwidth;
				scaleY=parseInt($("#myCanvas").css("height"))/maxheight;
				if (scaleX>scaleY){
					scale=scaleY;
					ystart=0;
					xstart=((parseInt($("#myCanvas").css("width"))/scale)-maxwidth)/2;
				}else{
					scale=scaleX;
					xstart=0;
					ystart=((parseInt($("#myCanvas").css("height"))/scale)-maxheight)/2;
				}
				//console.log([xstart,ystart]);
				ctx.lineWidth=parseInt(3/scale);
				ctx.scale(scale,scale);
				ctx.strokeStyle="#FFFFFF";
				ctx.beginPath();
				
				ctx.translate(xstart,ystart);

				/*1e*/
				ctx.fillStyle="#000000";
				ctx.fillRect(0,0,7960,5100);
				ctx.rect(0,0,7960,5100);
				
				ctx.stroke();
			}

			
			$('document').ready(function(){
				showTuin();
			});
			
			window.onresize = function(event) {
				if (curFloor=="Tuin"){showTuin();}
				if (curFloor=="BG"){showBG();}
				if (curFloor=="1e"){show1e();}
				if (curFloor=="2e"){show2e();}
			};
				
		</script>

		<div id="wrapper">
		<div id="button_area"><button class="floorsel" id="Tuin" onclick="showTuin()">Tuin</button><button class="floorsel" id="BG" onclick="showBG()">Begane Grond</button><button class="floorsel" id="1e" onclick="show1e()">1e Verdieping</button><button class="floorsel" id="2e" onclick="show2e()">2e Verdieping</button></div><canvas id="myCanvas" width="1000" height="500"></canvas>
		</div>

	</body>
</html>
