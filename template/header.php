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
	<select onchange="oo_applyFilter();" class="maps_tl_box" id="maps_filter_techlevel">
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
	<option>15</option>
	</select>
</td>
<td valign=middle align=left width=100>
	<input id="tlandup" type="radio" onclick="oo_tlfilter(1); oo_applyFilter();" />
	<label for="tlandup"><span></span>And up</label><br>
	<input id="tlonly" type="radio" onclick="oo_tlfilter(2); oo_applyFilter();" />
	<label for="tlonly"><span></span>Only</label>
</td>
<td width=10></td>
<td width=80 valign=middle align=left>
<font class="maps_header_filter">Economy:</font>
</td>
<td width=100 valign=middle align=left>
<select onchange="oo_applyFilter();" class="maps_goveco_box" id="maps_filter_economy">
<option selected>-</option>
<option value=0>Rich Industrial</option>
<option value=1>Average Industrial</option>
<option value=2>Poor Industrial</option>
<option value=3>Mainly Industrial</option>
<option value=4>Mainly Aggricultural</option>
<option value=5>Rich Aggricultural</option>
<option value=6>Average Aggricultural</option>
<option value=7>Poor Aggricultural</option>
</select>
</td>
<td width=10></td>
<td valign=middle align=left width=100>
	<input id="ecandup" type="radio" onclick="oo_ecfilter(1); oo_applyFilter();" />
	<label for="ecandup"><span></span>And up</label><br>
	<input id="econly" type="radio" onclick="oo_ecfilter(2); oo_applyFilter();" />
	<label for="econly"><span></span>Only</label>
</td>
<td width=30></td>
<td valign=middle align=left width=100>
<font class="maps_header_filter">Government:</font>
</td>
<td width=100 valign=middle align=left>
<select onchange="oo_applyFilter();" class="maps_goveco_box" id="maps_filter_government">
<option selected>-</option>
<option value=0>Anarchy</option>
<option value=1>Feudal</option>
<option value=2>Multi-Gov.</option>
<option value=3>Dictatorship</option>
<option value=4>Communist</option>
<option value=5>Confederacy</option>
<option value=6>Democracy</option>
<option value=7>Corporate St.</option>
</select>
</td>
<td width=10></td>
<td valign=middle align=left width=100>
	<input id="govandup" type="radio" onclick="oo_govfilter(1); oo_applyFilter();" />
	<label for="govandup"><span></span>And up</label><br>
	<input id="govonly" type="radio" onclick="oo_govfilter(2); oo_applyFilter();" />
	<label for="govonly"><span></span>Only</label>
</td>
<td width=*></td>
<td valign=middle align=right>
	<a class="maps_header_links" href="#">About Maps</a>
</td>
<td width=20></td>
</tr>
</table>
</div>