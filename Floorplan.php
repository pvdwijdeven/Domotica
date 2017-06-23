

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
			var curFloor = "BG";
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
				
				/*floors*/
				ctx.fillStyle="#222222";
				ctx.fillRect(0,0,12110,5100);

				/*walls*/
				ctx.fillStyle="#FFFFFF";
				ctx.fillRect(0,2100,4375,70);				
				ctx.fillRect(4305,2100,70,-1070);
				ctx.fillRect(4305,1000,6450-4305,70);
				ctx.fillRect(6450,1000,-70,-1000);
				ctx.fillRect(1100,0,70,990);
				ctx.fillRect(6450-3100-70,0,-70,990);
				ctx.fillRect(1570,0,70,990);
				ctx.fillRect(1570+70+1250,0,70,990);
				ctx.fillRect(1100,990,2180,-70);		ctx.stroke();

				/*doors*/
				ctx.fillStyle="#333333";
				ctx.strokeStyle="#333333";
				ctx.lineWidth=parseInt(3/scale);
				/*toilet*/
				ctx.fillRect(2800,919,-915,72);
				ctx.beginPath();
				ctx.arc(2800-915,919+72,915,0,0.5*Math.PI);
				ctx.lineTo(2800-915,919+72);
				ctx.stroke();
				/*meterkast*/
				ctx.fillRect(1099,5,72,915);
				ctx.beginPath();
				ctx.arc(1099,5,915,0.5*Math.PI,Math.PI);
				ctx.lineTo(1099,5);
				ctx.stroke();
				/*meterkast*/
				ctx.fillRect(4304,1130,72,915);
				ctx.beginPath();
				ctx.arc(4304,1130,915,0.5*Math.PI,0,true);
				ctx.lineTo(4304,1130);
				ctx.stroke();
				/*trapkast*/
				ctx.fillRect(6450-70,999,-915,72);
				ctx.beginPath();
				ctx.arc(6450-70,999,915,Math.PI,0.5*Math.PI,true);
				ctx.lineTo(6450-70,999);
				ctx.stroke();
				
				/*keuken*/
				ctx.fillRect(0,5100,3971,-600);
				ctx.fillRect(918,2170,1221+1836,600);
				
				/*stairs*/
				ctx.fillStyle="#333333";
				ctx.strokeStyle="#333333";
				ctx.lineWidth=parseInt(3/scale);
				ctx.beginPath();
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280+990,0);
				ctx.moveTo(3280,990);
				ctx.lineTo(3280+3100,990);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280,990-Math.tan(18/360*2*Math.PI)*990);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280,990-Math.tan(36/360*2*Math.PI)*990);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280+Math.tan(18/360*2*Math.PI)*990,0);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280+Math.tan(36/360*2*Math.PI)*990,0);
				ctx.moveTo(3280+990+198,990);
				ctx.lineTo(3280+990+198,0);
				ctx.moveTo(3280+990+2*224,990);
				ctx.lineTo(3280+990+2*224,0);
				ctx.moveTo(3280+990+3*224,990);
				ctx.lineTo(3280+990+3*224,0);
				ctx.moveTo(3280+990+4*224,990);
				ctx.lineTo(3280+990+4*224,0);
				ctx.moveTo(3280+990+5*224,990);
				ctx.lineTo(3280+990+5*224,0);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990,990-Math.tan(18/360*2*Math.PI)*990);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990,990-Math.tan(36/360*2*Math.PI)*990);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990-Math.tan(18/360*2*Math.PI)*990,0);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990-Math.tan(36/360*2*Math.PI)*990,0);
				ctx.stroke();
				
				/*outerwalls*/
				ctx.beginPath();
				ctx.lineWidth=parseInt(3/scale);
				ctx.strokeStyle="#FFFFFF";
				ctx.rect(0,0,12110,5100);
				ctx.stroke();
				
				
			}

			function show1e(){
				curFloor="1e";
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
				
				ctx.scale(scale,scale);
				ctx.beginPath();
				ctx.translate(xstart,ystart);

				/*floors*/
				ctx.fillStyle="#222222";
				ctx.fillRect(0,0,9710,5100);

				/*walls*/
				ctx.fillStyle="#FFFFFF";
				ctx.fillRect(1570,2150,4880+3260,70);				
				ctx.fillRect(1570,0,70,2220);				
				ctx.fillRect(1570,920,1250+390+70,70);				
				ctx.fillRect(1570+70+1250,0,70,920);				
				ctx.fillRect(1570+1250+390,0,70,920);				
				ctx.fillRect(6380,0,70,2220);				
				ctx.fillRect(3410,2220,70,2880);				
				ctx.fillRect(5310,2220,70,2880);

				/*doors*/
				ctx.fillStyle="#333333";
				ctx.strokeStyle="#333333";
				ctx.lineWidth=parseInt(3/scale);
				/*toilet*/
				ctx.fillRect(2800,919,-915,72);
				ctx.beginPath();
				ctx.arc(2800-915,919+72,915,0,0.5*Math.PI);
				ctx.lineTo(2800-915,919+72);
				ctx.stroke();
				/*slaapkamer1*/
				ctx.fillRect(3390,2220-69,-915,72);
				ctx.beginPath();
				ctx.arc(3390,2220+3,915,Math.PI,0.5*Math.PI,true);
				ctx.lineTo(3390,2220+3);
				ctx.stroke();
				/*badkamer*/
				ctx.fillRect(3390+1830+70,2220-69,-915,72);
				ctx.beginPath();
				ctx.arc(3390+1830+70,2220-69,915,Math.PI,1.5*Math.PI);
				ctx.lineTo(3390+1830+70,2220-69);
				ctx.stroke();
				/*slaapkamer2*/
				ctx.fillRect(5400,2220-69,915,72);
				ctx.beginPath();
				ctx.arc(5400,2220+3,915,0,0.5*Math.PI);
				ctx.lineTo(5400,2220+3);
				ctx.stroke();				
				/*slaapkamer3*/
				ctx.fillRect(9710-3259,2130,-72,-915);
				ctx.beginPath();
				ctx.arc(9710-3259,2130,915,1.5*Math.PI,2*Math.PI);
				ctx.lineTo(9710-3259,2130);
				ctx.stroke();			
				
				/*stairs*/
				ctx.fillStyle="#333333";
				ctx.strokeStyle="#333333";
				ctx.lineWidth=parseInt(3/scale);
				ctx.beginPath();
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280+990,0);
				ctx.moveTo(3280,990);
				ctx.lineTo(3280+3100,990);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280,990-Math.tan(18/360*2*Math.PI)*990);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280,990-Math.tan(36/360*2*Math.PI)*990);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280+Math.tan(18/360*2*Math.PI)*990,0);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280+Math.tan(36/360*2*Math.PI)*990,0);
				ctx.moveTo(3280+990+198,990);
				ctx.lineTo(3280+990+198,0);
				ctx.moveTo(3280+990+2*224,990);
				ctx.lineTo(3280+990+2*224,0);
				ctx.moveTo(3280+990+3*224,990);
				ctx.lineTo(3280+990+3*224,0);
				ctx.moveTo(3280+990+4*224,990);
				ctx.lineTo(3280+990+4*224,0);
				ctx.moveTo(3280+990+5*224,990);
				ctx.lineTo(3280+990+5*224,0);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990,990-Math.tan(18/360*2*Math.PI)*990);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990,990-Math.tan(36/360*2*Math.PI)*990);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990-Math.tan(18/360*2*Math.PI)*990,0);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990-Math.tan(36/360*2*Math.PI)*990,0);
				ctx.stroke();
				
				/*outerwalls*/
				ctx.beginPath();
				ctx.lineWidth=parseInt(3/scale);
				ctx.strokeStyle="#FFFFFF";
				ctx.rect(0,0,9710,5100);
				ctx.stroke();
				
			}

			function show2e(){
				curFloor="2e";
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

				/*floors*/
				ctx.fillStyle="#222222";
				ctx.moveTo(0,5100-242);
				ctx.lineTo(0,5100-242-3000);
				ctx.lineTo(3280-1485,5100-242-3000);
				ctx.lineTo(3280-1485,0);
				ctx.lineTo(7960,0);
				ctx.lineTo(7960,5100);
				ctx.lineTo(3280-1485,5100);
				ctx.lineTo(3280-1485,5100-242);
				ctx.lineTo(0,5100-242);
				ctx.fill();

				/*walls*/
				ctx.fillStyle="#FFFFFF";
				ctx.fillRect(3210,0,70,1060);
				ctx.fillRect(3210,990,5415-3210+70,70);
				ctx.fillRect(5415,1060,70,4030);
				ctx.fillRect(7960-1465-70,0,70,1970);
				ctx.fillRect(7960-1465,1900,1465,70);

				/*doors*/
				ctx.fillStyle="#333333";
				ctx.strokeStyle="#333333";
				ctx.lineWidth=parseInt(3/scale);
				/*berging*/
				ctx.fillRect(7960-1465+20,1899,915,72);
				ctx.beginPath();
				ctx.arc(7960-1465+20,1899+72,915,0,0.5*Math.PI);
				ctx.lineTo(7960-1465+20,1899+72);
				ctx.stroke();

				/*slaapkamer4*/
				ctx.fillRect(5414,1080,72,915);
				ctx.beginPath();
				ctx.arc(5414,1080+72,915,0.5*Math.PI,Math.PI);
				ctx.lineTo(5414,1080+72);
				ctx.stroke();

				/*stairs*/
				ctx.fillStyle="#333333";
				ctx.strokeStyle="#333333";
				ctx.lineWidth=parseInt(3/scale);
				ctx.beginPath();
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280+990,0);
				ctx.moveTo(3280,990);
				ctx.lineTo(3280+3100,990);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280,990-Math.tan(18/360*2*Math.PI)*990);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280,990-Math.tan(36/360*2*Math.PI)*990);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280+Math.tan(18/360*2*Math.PI)*990,0);
				ctx.moveTo(3280+990,990);
				ctx.lineTo(3280+Math.tan(36/360*2*Math.PI)*990,0);
				ctx.moveTo(3280+990+198,990);
				ctx.lineTo(3280+990+198,0);
				ctx.moveTo(3280+990+2*224,990);
				ctx.lineTo(3280+990+2*224,0);
				ctx.moveTo(3280+990+3*224,990);
				ctx.lineTo(3280+990+3*224,0);
				ctx.moveTo(3280+990+4*224,990);
				ctx.lineTo(3280+990+4*224,0);
				ctx.moveTo(3280+990+5*224,990);
				ctx.lineTo(3280+990+5*224,0);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990,990-Math.tan(18/360*2*Math.PI)*990);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990,990-Math.tan(36/360*2*Math.PI)*990);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990-Math.tan(18/360*2*Math.PI)*990,0);
				ctx.moveTo(3280+990+1120,990);
				ctx.lineTo(3280+990+1120+990-Math.tan(36/360*2*Math.PI)*990,0);
				ctx.stroke();

				
				/*outerwalls*/
				ctx.beginPath();
				ctx.strokeStyle="#FFFFFF";
				ctx.moveTo(0,5100-242);
				ctx.lineTo(0,5100-242-3000);
				ctx.lineTo(3280-1485,5100-242-3000);
				ctx.lineTo(3280-1485,0);
				ctx.lineTo(7960,0);
				ctx.lineTo(7960,5100);
				ctx.lineTo(3280-1485,5100);
				ctx.lineTo(3280-1485,5100-242);
				ctx.lineTo(0,5100-242);
				
				ctx.stroke();

			}
			
			$('document').ready(function(){
				window.onresize();
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
