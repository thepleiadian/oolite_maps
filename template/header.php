<html>
<head>
<title>Oolite Maps</title>
<link rel="stylesheet" type="text/css" href="css/maps.css">
<script src="js/maps.js"></script>
<script src="js/jquery-1.12.4.js"></script>
<script src="js/jquery-ui.js"></script>
</head>

<body>

<div class="maps_main_searchbox">
<table width=100% border=0 cellspacing=0 cellpadding=0 align=left height=40>
<tr>
<td valign=top align=left width=32 height=40>
<img src="img/maps_filters.png" onclick="oo_show_filterbox();" style="display: inline-block;">
</td>
<td width=300 valign=middle align=left>
<input placeholder="Type to search..." class="maps_search_box" id="maps_search_box" style="display: inline-block;"></input>
</td>
<td width=5></td>
<td valign=left>
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
<td width=32 valign=middle align=right>
<img src="img/maps_directions.png">
</td>
</table>
</div>

<div class="maps_filter_box" id="maps_filterbox">

</div>
