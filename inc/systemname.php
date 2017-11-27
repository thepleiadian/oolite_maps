<?php

error_reporting(0);

// Get database info
require_once("dbinfo.php");

$qry = "SELECT * FROM oolite_maps WHERE id=".$_GET['id'];
$sql = mysqli_query($GLOBALS['oo_DBLink'], $qry);

$row = mysqli_fetch_row($sql);

echo $row[20];

?>