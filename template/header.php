<html>
<head>
<title>Oolite Maps</title>
<link rel="stylesheet" type="text/css" href="css/maps.css">
<script src="js/maps.js"></script>
<script src="js/jquery-1.12.4.js"></script>
<script src="js/jquery-ui.js"></script>
</head>

<body>

<div id="maps_header" class="maps_header">
<table width=100% height=64 border=0 cellspacing=0 cellpadding=0 align=left>
<tr>
<td width=50></td>
<td width=355 valign=middle align=left>
<input placeholder="Type to search..." class="maps_search_box" id="maps_search_box"></input>
</td>
<td width=10></td>
<td valign=middle align=left width=60>
<select class="maps_galaxy_box" id="maps_galaxy_box" onchange="oo_switchGalaxy();">
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
<option>7</option>
<option>8</option>
</select>
</td>
<td width=20></td>
<td valign=middle align=left width=100>
	<font class="maps_header_filter">TL:</font>
	<select onchange="oo_filterByTechlevel();" class="maps_tl_box" id="maps_filter_techlevel">
	<option selected>-</option>
	<option>1</option>
	<option>2</option>
	<option>3</option>
	<option>4</option>
	<option>5</option>
	<option>6</option>
	<option>7</option>
	<option>8</option>
	<option>9</option>
	<option>10</option>
	<option>11</option>
	<option>12</option>
	<option>13</option>
	<option>14</option>
	</select>
</td>
<td valign=middle align=left width=100>
	<input id="tlandup" type="radio" checked />
	<label for="tlandup"><span></span>And up</label><br>
	<input id="tlonly" type="radio" />
	<label for="tlonly"><span></span>Only</label>
</td>
<td valign=middle align=left>
<font class="maps_header_filter">Economy:</font>
</td>
<td valign=middle align=left>
<font class="maps_header_filter">Government:</font>
</td>
<td width=*></td>
<td valign=middle align=right>
	<a class="maps_header_links" href="#">About Maps</a>
</td>
<td width=20></td>
</tr>
</table>
</div>