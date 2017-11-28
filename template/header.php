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
<td width=* valign=middle align=left>
<input placeholder="Type to search..." class="maps_search_box" id="maps_search_box" style="display: inline-block;"></input>
</td>
</table>
</div>
    
<div class="maps_filter_box" id="maps_filterbox">

</div>