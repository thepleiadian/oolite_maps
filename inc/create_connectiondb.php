<?php

// No time limit
set_time_limit(0);


// Get database info
require_once("dbinfo.php");

// IN-GAME DISTANCE BETWEEN TWO SYSTEMS
function distance_between_systems($system1, $system2)
{
	// From Oolite's code:
	/*
	// a method used to determine interplanetary distances,
	// if accurate, it has to scale distance down by a factor of 7.15:7.0
	// to allow routes navigable in the original!
	OOINLINE double distanceBetweenPlanetPositions(int x1, int y1, int x2, int y2)
	{
		int dx = x1 - x2;
		int dy = (y1 - y2)/2;
		int dist = sqrt(dx*dx + dy*dy);	// N.b. Rounding error due to truncation is desired.
		return 0.4 * dist;
	}
	*/

	// Calculate distance, the Oolite code way.
	// -------------------------------------------------------------------------
	// A small comment on that:
	// Rounding error due to truncation is apparently... desired.
	// Although this results in inaccurate distance calculations according to
	// euclidean formulas, this is how the game is designed. For this reason
	// I need to apply this formula to retrieve the same values as they are
	// in-game.

	// Now extract coordinates
	$x1 = $system1[0];
	$y1 = $system1[1];
	$x2 = $system2[0];
	$y2 = $system2[1];

	(int)$x = $x1 - $x2;
	(int)$y = ($y1 - $y2) / 2;
	(int)$dist = (int)sqrt($x*$x + $y*$y);
    return 0.4 * $dist;
}


// Get all systems
$qry = "";
$sql = "";
$rows = 0;


// We store all solar system data in here
$systemdata = array();



// Acquires solar system data
function get_solar_system_data($galaxy)
{
	global $systemdata, $qry, $rows, $sql;

	$systemdata = array();

	$qry = "SELECT * FROM oolite_maps WHERE galaxy=".$galaxy.";";
	$sql = mysqli_query($GLOBALS['oo_DBLink'], $qry);
	$rows = mysqli_num_rows($sql);

	// Now go and render out each solar system
	for ($i=1; $i<=$rows; $i++)
	{
		// Get row
		$row = mysqli_fetch_row($sql);
		$systemdata[] = $row;
	}
}


function determine_connections()
{
	global $systemdata, $qry, $rows, $sql;

	// We iterate through each galaxy
	for ($g=1; $g<=8; $g++)
	{
		// Get system data
		get_solar_system_data($g-1);

		for ($i=1; $i<=count($systemdata); $i++)
		{
			$start = explode(" ", $systemdata[$i-1][8]);

			for ($j=1; $j<=count($systemdata); $j++)
			{
				if ($j-1 != $i-1)
				{
					$dest = explode(" ", $systemdata[$j-1][8]);
					$distance = distance_between_systems($start, $dest);

					if ($distance < 7.0)
					{
						// The travel time in hours
						$travel_time = ($distance * $distance);

						// Create a query to insert a "connection line"
						$connqry = "INSERT INTO oolite_connections (id, dest_id, distance, travel_time) VALUES (".$systemdata[$i-1][0].", ".$systemdata[$j-1][0].", ".$distance.", ".$travel_time.");";
						$connsql = mysqli_query($GLOBALS['oo_DBLink'], $connqry);
					}
				}
			}
		}
	}

	echo "Connections written.";
}

determine_connections();

?>