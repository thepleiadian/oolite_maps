<?php

// -----------------------------------------------------------------------------
// DATABASE INFO - ADJUST TO YOUR NEEDS!
// -----------------------------------------------------------------------------
$OO_DB_HOST = "localhost";
$OO_DB_USER = "root";
$OO_DB_PASS = "Password12";

// The database to store map data into
$OO_DB_DB   = "oolite_data";
$OO_DB_TBL	= "oolite_maps";

// -----------------------------------------------------------------------------
// NO TRESPASSING BEYOND THIS POINT!
// (unless you know what you're doing)
// -----------------------------------------------------------------------------

// The links
$oo_DBLink   = mysqli_connect($GLOBALS['OO_DB_HOST'], $GLOBALS['OO_DB_USER'], $GLOBALS['OO_DB_PASS']);
$oo_DBSelect = mysqli_select_db($GLOBALS['oo_DBLink'], $GLOBALS['OO_DB_DB']);

?>