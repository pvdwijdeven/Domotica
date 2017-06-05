<script type="text/javascript">
	<!--
	
	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires;
	}
	

	
	function setSButtons(){
		var selected = getCookie("select_<?php echo basename($_SERVER['PHP_SELF']);?>");;
		if (selected==""){selected="12BOLAD";}
		if (selected.indexOf('1') == -1){
			$("#select1e").attr('class','select-buttonpressed');
		}
				if (selected.indexOf('2') == -1){
			$("#select2e").attr('class','select-buttonpressed');
		}
				if (selected.indexOf('B') == -1){
			$("#selectBG").attr('class','select-buttonpressed');
		}
				if (selected.indexOf('O') == -1){
			$("#selectO").attr('class','select-buttonpressed');
		}
				if (selected.indexOf('L') == -1){
			$("#selectL").attr('class','select-buttonpressed');
		}
				if (selected.indexOf('A') == -1){
			$("#selectA").attr('class','select-buttonpressed');
		}
				if (selected.indexOf('D') == -1){
			$("#selectD").attr('class','select-buttonpressed');
		}
	}
	
	function clickSButton(elem) {
		if ($(elem).attr('class')=='select-buttonpressed'){
			$(elem).attr('class', 'select-button');
		}else{
			$(elem).attr('class', 'select-buttonpressed');
		}
		var selectval = ""
		if ($("#selectBG").attr('class')=='select-button'){
			selectval+="B"
		}
		if ($("#select1e").attr('class')=='select-button'){
			selectval+="1"
		}
		if ($("#select2e").attr('class')=='select-button'){
			selectval+="2"
		}
		if ($("#selectL").attr('class')=='select-button'){
			selectval+="L"
		}
		if ($("#selectA").attr('class')=='select-button'){
			selectval+="A"
		}
		if ($("#selectD").attr('class')=='select-button'){
			selectval+="D"
		}
		if ($("#selectO").attr('class')=='select-button'){
			selectval+="O"
		}
		setCookie("select_<?php echo basename($_SERVER['PHP_SELF']);?>",selectval,365);
		location.reload();
	}
	
	//-->
</script>
<div id="myModal" class="modal2">
	<!-- Modal content -->
	<div class="modal-content2">
		<span id="close1" class="close">&times;</span>
		<div id='rooms'></div>
	</div>
</div>
<table class="select-table">
	<tr>
		<th class="select-button" colspan=5>Verdieping</th>
		<th class="select-split"></th>
		<th class="select-button" colspan=7>Type</th>
		<th class="select-split"></th>
		<th class="select-button" colspan=3>Kamer</th>
	</tr>
	<tr>
		<td id="selectBG" class="select-button" onmousedown="clickSButton(this)">BG</td>
		<td class="select-bsplit"></td>
		<td id="select1e" class="select-button" onmousedown="clickSButton(this)">1e</td>
		<td class="select-bsplit"></td>
		<td id="select2e" class="select-button" onmousedown="clickSButton(this)">2e</td>
		<td class="select-split"></td>
		<td id="selectL" class="select-button" onmousedown="clickSButton(this)">L</td>
		<td class="select-bsplit"></td>
		<td id="selectA" class="select-button" onmousedown="clickSButton(this)">A</td>
		<td class="select-bsplit"></td>
		<td id="selectD" class="select-button" onmousedown="clickSButton(this)">D</td>
		<td class="select-bsplit"></td>
		<td id="selectO" class="select-button" onmousedown="clickSButton(this)">O</td>
		<td class="select-split"></td>
		<td colspan=3 id="roomsel" class="select-button" onmousedown="clickRoomButton()">
			<div id="getpos">Allemaal</div>
		</td>
	</tr>
	<?php
		if (basename($_SERVER['PHP_SELF'])=="whathappened.php"){
		echo '<tr><td colspan=17 id="rangesel" class="select-button" onmousedown="clickRangeButton()"><div id="getpos">Tijdslot</div></td>';
		echo "<tr><td colspan=17><table width='100%'><tr><td width='25%' class='select-button' onmousedown='setRange(1)'>1 uur</td><td class='select-bsplit'></td><td width='25%' class='select-button' onmousedown='setRange(3)'>3 uur</td><td class='select-bsplit'></td><td width='25%' class='select-button' onmousedown='setRange(24)'>1 dag</td><td class='select-bsplit'></td><td width='25%' class='select-button' onmousedown='setRange(168)'>7 dagen</td></tr></table></td></tr>";
		}
	?>
	</tr>
</table>
<script>
	var modal = document.getElementById('myModal');
	var btn = document.getElementById("myBtn");
	var span = document.getElementById("close1");
	
	function clickRoomButton() {
		modal.style.display = "block";
	}
	
	span.onclick = function() {
		modal.style.display = "none";
	}
	
	
	function clickRSButton(temp){
	modal.style.display = "none";
	setCookie("room_<?php echo basename($_SERVER['PHP_SELF']);?>",$(temp).attr('id'),365);
	location.reload();
	}
</script>