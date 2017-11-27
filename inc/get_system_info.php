<?php

// Get database info
require_once("dbinfo.php");

// The system ID to be queried
$sysid = $_GET['id'];

// Get the data
$qry = "SELECT * FROM oolite_maps WHERE id=".$sysid.";";
$sql = mysqli_query($GLOBALS['oo_DBLink'], $qry);
$data = mysql_fetch_row($sql);

// OK so now pump nicely formatted HTML for the info sidebar


?>