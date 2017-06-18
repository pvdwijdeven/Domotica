

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<meta name="author" content="Pascal van de Wijdeven">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="css/style.css?v=3.0">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>

		
		
		<canvas id="myCanvas" width="1200" height="600" style="border:1px solid #d3d3d3;">
		Your browser does not support the HTML5 canvas tag.</canvas>

		<script>

			var c = document.getElementById("myCanvas");
			var ctx = c.getContext("2d");
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
			console.log([xstart,ystart]);
			ctx.lineWidth=parseInt(3/scaleX);
			ctx.strokeStyle="#FFFFFF";
			ctx.beginPath();
			ctx.scale(scale,scale);
			ctx.translate(xstart,ystart);
			/*tuin*/
			ctx.fillStyle="#002200";
			ctx.fillRect(0,0,24910,10000);
			/*BG*/
			ctx.fillStyle="#000000";
			ctx.fillRect(4000,0,12110,5100);
			ctx.rect(4000,0,12110,5100);
			/*garage*/
			ctx.fillRect(10420,5100,5690,3100);
			ctx.rect(10420,5100,5690,3100);
			ctx.stroke();

		</script>

	</body>
</html>



