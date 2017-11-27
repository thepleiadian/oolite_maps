<?php

error_reporting(0);

// Get database info
require_once("dbinfo.php");

// Core data required to render
$oo_zoom = $_GET['zoom'];
$oo_mapw = $_GET['mapw'];
$oo_maph = $_GET['maph'];
$oo_curg = $_GET['galaxy'];
$oo_filter = $_GET['filter'];
// The light year stepping in each direction
$oo_ly_step_x = ($oo_mapw * $oo_zoom) / 256;
$oo_ly_step_y = ($oo_maph * $oo_zoom) / 256;

// This is the stepping for grid etc
$oo_stepping = 0;

$svg_systems = "";

// Some globals we need
$qry = "";
$sql = "";
$rows = 0;

// We store all solar system data in here
$systemdata = array();
// This is the connection data
$connectiondata = array();

// This contains the code for each solar system connection line
$connlines = "";



// Gets the stepping to render certain things with
function get_stepping()
{
	global $oo_zoom, $oo_stepping;
	if ($oo_zoom >= 0.8) { $oo_stepping = 16; }
	if ($oo_zoom >= 1.5) { $oo_stepping = 8; }
	if ($oo_zoom >= 2.2) { $oo_stepping = 4; }
	if ($oo_zoom >= 3.0) { $oo_stepping = 2; }
}



// Acquires solar system data
function get_solar_system_data()
{
	// We need those:
	global $systemdata, $qry, $sql, $rows, $oo_curg;

	// Clear systemdata
	$systemdata = array();

	$qry = "SELECT * FROM oolite_maps WHERE galaxy=".$oo_curg.";";
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


// Acquires the connections for the selected galaxy
function get_galaxy_connections()
{
	global $systemdata, $connectiondata, $qry, $sql, $rows;

	$connectiondata = array();

	for ($i=1; $i<=$rows; $i++)
	{
		$connqry = "SELECT * FROM oolite_connections WHERE id=".$systemdata[$i-1][0].";";
		$connsql = mysqli_query($GLOBALS['oo_DBLink'], $connqry);
		$connrows = mysqli_num_rows($connsql);
		for ($j=1; $j<=$connrows; $j++)
		{
			$row = mysqli_fetch_row($connsql);
			$connectiondata[] = $row;
		}
	}
}



// This renders out the grid
function render_grid()
{
	global $oo_ly_step_x, $oo_ly_step_y, $oo_stepping, $oo_mapw, $oo_maph, $oo_zoom;

	// Render the grid depending on the zoom factor
	$cur_x = 0;
	$cur_y = 0;

	// Alright let's bounce
	$gridlines = "";

	// Number of lines to draw
	$numlines = 256 / $oo_stepping;
	
	// Horizontal first
	for ($i=1; $i<=$numlines+1; $i++)
	{
		$gridlines = $gridlines . "<line x1=0 y1=".$cur_y." x2=".($oo_mapw * $oo_zoom)." y2=".$cur_y." style='stroke:#777777; stroke-width:0.5' />";
		$cur_y = $cur_y + $oo_ly_step_y * $oo_stepping;
	}

	// Vertical
	for ($i=1; $i<=$numlines+1; $i++)
	{
		$gridlines = $gridlines . "<line x1=".$cur_x." y1=0 x2=".$cur_x." y2=".($oo_maph * $oo_zoom)." style='stroke:#777777; stroke-width:0.5' />";
		$cur_x = $cur_x + $oo_ly_step_x * $oo_stepping;
	}

	echo $gridlines;
}



// This renders out the connection lines
function render_connlines()
{
	global $systemdata, $connectiondata, $qry, $sql, $rows, $oo_ly_step_x, $oo_ly_step_y, $connlines;

	// First we draw the lines.
	
	// For that we, once again, need to iterate over the current galaxy:
	for ($i=1; $i<=$rows; $i++)
	{
		// Get IN-GAME starting coordinates:
		$systempos = explode(" ", $systemdata[$i-1][8]);
		// Translate them into on-screen coordinates:
		$system_os_x = $systempos[0] * $oo_ly_step_x;
		$system_os_y = $systempos[1] * $oo_ly_step_y;

		// OK so now we now the on-screen starting point. We now need to
		// determine the endpoints for each line.
		for ($j=1; $j<=count($connectiondata); $j++)
		{
			if ($connectiondata[$j-1][0] == $systemdata[$i-1][0])
			{
				// Get target system:
				$targetsystem = $connectiondata[$j-1][1];

				// Find that system:
				$targetarrpos = 0;
				for ($k=1; $k<=count($systemdata); $k++)
				{
					if ($systemdata[$k-1][0] == $targetsystem)
					{ $targetarrpos = $k-1; break;}
				}

				// Get IN-GAME location of target system:
				$targetpos = explode(" ", $systemdata[$targetarrpos][8]);

				// Translate
				$target_os_x = $targetpos[0] * $oo_ly_step_x;
				$target_os_y = $targetpos[1] * $oo_ly_step_y;

				// AND RENDER THAT LINE!
				$connlines = $connlines . "<line x1=".$system_os_x." y1=".$system_os_y." x2=".$target_os_x." y2=".$target_os_y." style='stroke:#AAAAAA; stroke-width:0.5' />";
			}
		}
	}

	// Render out the lines
	echo $connlines;
}


// This renders out the single solar systems
function render_systems()
{
	global $systemdata, $oo_ly_step_x, $oo_ly_step_y, $svg_systems;
	$defsWritten = false;

	for ($i=1; $i<=count($systemdata); $i++)
	{
		// Get raw coordinates
		$coords_raw = explode(" ", $systemdata[$i-1][8]);
		// Give us the ints
		$coord_x = (int)$coords_raw[0];
		$coord_y = (int)$coords_raw[1];

		// Convert coordinates into pixel coordinates
		$system_x = ($coord_x * $oo_ly_step_x);
		$system_y = ($coord_y * $oo_ly_step_y);

		// Render all systems as normal
		$svg_systems = $svg_systems . "<circle id='system_ball_".$i."' onmouseover='oo_show_systemname(".$systemdata[$i-1][0].");' onmouseout='oo_hide_systemname();' cx=".$system_x." cy=".$system_y." r='5' stroke='none' stroke-width='2' fill='white' />";
	}

	// Depending on defined filters, we need to render a star differently.
	if ($_GET['filters'] != "")
	{
		// Get filter data
		$filters = explode("|", $_GET['filters']);

		// Go through filters
		for ($i=1; $i<=count($filters); $i++)
		{
			if ($filters[$i-1] == "techlevel")
			{
				$andAbove = false;

				// Determine how to filter
				if ($filters[$i+1] == 1) { $andAbove = true; }
				if ($filters[$i+1] == 2) { $andAbove = false; }

				// Add the stars with highlight
				for ($j=1; $j<=count($systemdata); $j++)
				{
					if ($andAbove == true)
					{
						if ($systemdata[$j-1][39] >= $filters[$i-0])
						{
							// Get raw coordinates
							$coords_raw = explode(" ", $systemdata[$i-1][8]);
							// Give us the ints
							$coord_x = (int)$coords_raw[0];
							$coord_y = (int)$coords_raw[1];

							// Convert coordinates into pixel coordinates
							$system_x = ($coord_x * $oo_ly_step_x);
							$system_y = ($coord_y * $oo_ly_step_y);

							// Render all systems as normal
							$svg_systems = $svg_systems . "<circle id='system_ball_".$i."' onmouseover='oo_show_systemname(".$systemdata[$i-1][0].");' onmouseout='oo_hide_systemname();' cx=".$system_x." cy=".$system_y." r='10' stroke='none' stroke-width='2' fill='green' />";
						}
					}
					if ($andAbove == false)
					{
						if ($systemdata[$j-1][39] == $filters[$i-0])
						{
							// Get raw coordinates
							$coords_raw = explode(" ", $systemdata[$i-1][8]);
							// Give us the ints
							$coord_x = (int)$coords_raw[0];
							$coord_y = (int)$coords_raw[1];

							// Convert coordinates into pixel coordinates
							$system_x = ($coord_x * $oo_ly_step_x);
							$system_y = ($coord_y * $oo_ly_step_y);

							// Render all systems as normal
							$svg_systems = $svg_systems . "<circle id='system_ball_".$i."' onmouseover='oo_show_systemname(".$systemdata[$i-1][0].");' onmouseout='oo_hide_systemname();' cx=".$system_x." cy=".$system_y." r='10' stroke='none' stroke-width='2' fill='green' />";
						}
					}
				}

				// Advance filtering
				$i = $i+3;
			}
		}
	}

	echo $svg_systems;
}



// Now do the calls in this order:
get_stepping();
get_solar_system_data();
get_galaxy_connections();
render_grid();
render_connlines();
render_systems();



/*
// Now go and render out each solar system
for ($i=1; $i<=$rows; $i++)
{
	// Get row
	$row = mysqli_fetch_row($sql);

	// Get raw coordinates
	$coords_raw = explode(" ", $row[8]);
	// Give us the ints
	$coord_x = (int)$coords_raw[0];
	$coord_y = (int)$coords_raw[1];

	// Convert coordinates into pixel coordinates
	$system_x = ($coord_x * $oo_lyrx);
	$system_y = ($coord_y * $oo_lyry);

	// Now render
	$svg_systems = $svg_systems . "<circle cx=".$system_x." cy=".$system_y." r='10' stroke='none' stroke-width='2' fill='white' />";
}

echo $svg_systems;
*/

?>