<?php

// Get database info
require_once("dbinfo.php");

// Utility calls
function get_economy_name($num)
{
	$eco = "";

	if ($num == 0) { $eco = "Rich Industrial"; }
	if ($num == 1) { $eco = "Average Industrial"; }
	if ($num == 2) { $eco = "Poor Industrial"; }
	if ($num == 3) { $eco = "Mainly Industrial"; }
	if ($num == 4) { $eco = "Mainly Aggricultural"; }
	if ($num == 5) { $eco = "Rich Aggricultural"; }
	if ($num == 6) { $eco = "Average Aggricultural"; }
	if ($num == 7) { $eco = "Poor Aggricultural"; }

	return $eco;
}
function get_gov_name($num)
{
	$gov = "";

	if ($num == 0) { $gov = "Anarchy"; }
	if ($num == 1) { $gov = "Feudal"; }
	if ($num == 2) { $gov = "Multi-Government"; }
	if ($num == 3) { $gov = "Dictatorship"; }
	if ($num == 4) { $gov = "Communist"; }
	if ($num == 5) { $gov = "Confederacy"; }
	if ($num == 6) { $gov = "Democracy"; }
	if ($num == 7) { $gov = "Corporate State"; }

	return $gov;
}

// The system ID to be queried
$sysid = $_GET['id'];

// Get the data
$qry = "SELECT * FROM oolite_maps WHERE id=".$sysid.";";
$sql = mysqli_query($GLOBALS['oo_DBLink'], $qry);
$data = mysqli_fetch_row($sql);

// OK so now pump nicely formatted HTML for the info sidebar
$systeminfo = "<center><br>";

// First show the name of the system
$systeminfo = $systeminfo."<font class='maps_sidebar_systemname'>".$data[20]."</font><br>";
$systeminfo = $systeminfo."<font class='maps_goveco_info'>".get_gov_name($data[14])." | ".get_economy_name($data[13])."</font><br>";

// Render out the HTML
echo $systeminfo;

?>