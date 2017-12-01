
// According to
// http://wiki.alioth.net/index.php/Oolite_planet_list
// X-stepping is 0.4 LY, and Y-stepping is 0.2 LY.


// The global variables for maps rendering

// Offset
var oo_maps_offset_x = 0;
var oo_maps_offset_y = 0;

// Zoom factor
var oo_maps_zoom = 1.0;

// One light year ratios
var oo_lightyear_ratio_x = 0.0;
var oo_lightyear_ratio_y = 0.0;

// Current dimensions
var oo_winW = 0;
var oo_winH = 0;
var oo_renderwinW = 0;
var oo_renderwinH = 0;

// Current galaxy
var oo_current_galaxy = 0;

var oo_svgNS = "http://www.w3.org/2000/svg";

// Keep track of mouse
var oo_mx = 0;
var oo_my = 0;

// For dragging
var oo_drag_mx;
var oo_drag_my;
var oo_elem_x;
var oo_elem_y;



// -----------------------------------------------------------------------------
// UTILITY CALLS
// -----------------------------------------------------------------------------


// Get us the maps container
function oo_get_maps_container()
{
	var c = document.getElementById("maps_container");
	return c;
}

// Get us the maps RENDER container
function oo_get_maps_render_container()
{
	var c = document.getElementById("maps_render");
	return c;
}

// Get us the box to show the system name
function oo_get_system_name_box()
{
	var c = document.getElementById("maps_system_name");
	return c;
}


// -----------------------------------------------------------------------------
// MAP DRAG THINGS
// -----------------------------------------------------------------------------

function oo_updateMouseLocation(e)
{
	oo_mx = e.pageX;
	oo_my = e.pageY;
}


document.onmousemove = function(e)
{
    oo_mx = e.pageX;
	oo_my = e.pageY;
}





// Now let's have a quick chat about this next line coming up. It's more awesome
// then sliced bread, the world series, and the theory of relativity combined.
// This next lines defines to keep listening for mouse movements inside the
// browser no matter what happens. That's quite important so that solar systems
// can show you what they are called. If you even hint at considering to remove
// this line, I know this very friendly entity living in the woods, and he's
// called Slender Man. Friendly chap. In fact so friendly, that he skips all
// that crazy nonsense of getting to know each other - he f's you right away.
// Hard. And chances are he likes you so much he won't stop f'ing you. So...
// do us all a favor and leave that line alone.
window.addEventListener('mousemove', oo_updateMouseLocation, true);


// -----------------------------------------------------------------------------
// ADJUSTMENT AND STYLE CALLS
// -----------------------------------------------------------------------------

function oo_scale_container()
{
	var winW = window.innerWidth;
	var winH = window.innerHeight;

	var c = oo_get_maps_container();

	c.style.width  = (winW * oo_maps_zoom) + "px;";
	c.style.height = (winH * oo_maps_zoom) + "px;";
	c.setAttribute("style","width:"+(winW * oo_maps_zoom)+"px; height: "+(winH * oo_maps_zoom)+"px;");

	oo_winW = winW;
	oo_winH = winH;


	// Now scale the render container according to zoom level
	c = oo_get_maps_render_container();
	c.style.width  = (winW * oo_maps_zoom) + "px;";
	c.style.height = (winH * oo_maps_zoom) + "px;";
	c.setAttribute("style","width:"+(winW * oo_maps_zoom) +"px; height: "+(winH * oo_maps_zoom)+"px;");
	c.setAttribute("viewBox","0 0 " + oo_winW + ", " + oo_winH);

	oo_renderwinW = (winW * oo_maps_zoom);
	oo_renderwinH = (winH * oo_maps_zoom);

	document.getElementById("maps_ruler").style.width = (winW * oo_maps_zoom) + "px;";
}



// -----------------------------------------------------------------------------
// ZOOMING
// -----------------------------------------------------------------------------

// This handles changes in the mouse wheel
function oo_mousewheel_event(e)
{
	var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
	oo_rerender(delta);
}



// Activate scroll wheel handling for zoom
function oo_enable_mousezoom()
{
	window.addEventListener('mousewheel', oo_mousewheel_event, true);
	window.addEventListener("DOMMouseScroll", oo_mousewheel_event, true);
}



// -----------------------------------------------------------------------------
// CALCULATIONS
// -----------------------------------------------------------------------------

// Determine the light year ratio
function oo_determine_ly_ratio()
{
	var ly_step_x = (oo_winW / 256) * oo_maps_zoom;
	var ly_step_y = (oo_winH / 256) * oo_maps_zoom;

	oo_lightyear_ratio_x = ly_step_x;
	oo_lightyear_ratio_y = ly_step_y;
}



// -----------------------------------------------------------------------------
// RENDERING
// -----------------------------------------------------------------------------

// Clears the div... needed for zoom in/out
function oo_clear_map()
{ oo_get_maps_container().innerHTML = ""; }


function oo_show_systemname(sysid)
{
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			oo_get_system_name_box().innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET","inc/systemname.php?id="+sysid, true);
	xmlhttp.send();


	oo_get_system_name_box().style.display = "inline-block";
	oo_get_system_name_box().setAttribute("style", "display: inline-block; left: " + (oo_mx + 20) + "; top: " + (oo_my - 30) + ";");
}

// Hides the system name box
function oo_hide_systemname()
{
	oo_get_system_name_box().innerHTML = "";
	oo_get_system_name_box().style.display = "none";
	oo_get_system_name_box().setAttribute("style", "display: none");
}



// Gets us the stepping for rendering of certain items
function oo_get_stepping()
{
	// How much of the grid steps we skip
	var stepping = 0;

	// Depending on how zoomed in we are, we render more or less ruler labels
	if (oo_maps_zoom >= 0.8) { stepping = 16; }
	if (oo_maps_zoom >= 1.5) { stepping = 8; }
	if (oo_maps_zoom >= 2.2) { stepping = 4; }
	if (oo_maps_zoom >= 3.0) { stepping = 2; }

	return stepping;
}


function oo_render_ruler()
{
	// How much of the grid steps we skip
	var stepping = oo_get_stepping();

	var cur_x = 0;
	var xpos = 0;
	var step_x = (oo_lightyear_ratio_x * stepping);

	var numlines = 256 / stepping;

	for (i=1; i<=numlines; i++)
	{
		oo_add_ruler_element(i, cur_x, xpos, step_x);
		if (i==1) { cur_x = 1;}
		cur_x = (i * stepping);
		xpos = xpos + step_x;
	}



	var cur_y = 0;
	var ypos = 0;
	var step_y = (oo_lightyear_ratio_y * stepping);

	for (i=1; i<=numlines; i++)
	{
		oo_add_ruler_element_v("_v"+i, cur_y, ypos, step_y);
		if (i==1) { cur_y = 1;}
		cur_y = (i * stepping);
		ypos = ypos + step_y;
	}


	// Move rulers into place
	var ruler_h = document.getElementById("maps_ruler");
	ruler_h.style.left = oo_get_maps_render_container().offsetLeft + "px;";
}


function oo_add_ruler_element(ruler_step, text, xpos, w)
{
	var iDiv = document.createElement('div');
	iDiv.id = 'oo_ruler_text_' + ruler_step;
	iDiv.className = 'maps_ruler_text';
	iDiv.innerHTML = text;
	iDiv.style.left = xpos + "px;";
	iDiv.style.width = w + "px;";
	iDiv.setAttribute("style", "left: " + xpos + "px; width: " + w + "px; display: inline-block;");
	document.getElementById("maps_ruler").appendChild(iDiv);
}


function oo_add_ruler_element_v(ruler_step, text, ypos, h)
{
	var iDiv = document.createElement('div');
	iDiv.id = 'oo_ruler_text_' + ruler_step;
	iDiv.className = 'maps_ruler_text';
	iDiv.innerHTML = text;
	iDiv.style.top = ypos + "px;";
	iDiv.style.height = h + "px;";
	iDiv.setAttribute("style", "top: " + ypos + "px; height: " + h + "px; display: inline-block;");
	document.getElementById("maps_ruler_v").appendChild(iDiv);
	var br = document.createElement('br');
	document.getElementById("maps_ruler_v").appendChild(br);
}


// Makes sure only one of radios is checked
function oo_tlfilter(filter)
{
	if (filter==1) { document.getElementById("tlandup").checked = true; document.getElementById("tlonly").checked = false; }
	if (filter==2) { document.getElementById("tlandup").checked = false; document.getElementById("tlonly").checked = true; }
}
function oo_ecfilter(filter)
{
	if (filter==1) { document.getElementById("ecandup").checked = true; document.getElementById("econly").checked = false; }
	if (filter==2) { document.getElementById("ecandup").checked = false; document.getElementById("econly").checked = true; }
}
function oo_govfilter(filter)
{
	if (filter==1) { document.getElementById("govandup").checked = true; document.getElementById("govonly").checked = false; }
	if (filter==2) { document.getElementById("govandup").checked = false; document.getElementById("govonly").checked = true; }
}



// RENDER MAP!
function oo_render_map()
{
	// We render into this container
	var m = oo_get_maps_render_container();
	// In case we swiched galaxies...
	m.innerHTML = "";

	// Renders lines, systems, etc
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			oo_get_maps_render_container().innerHTML = this.responseText;
		}
	};

	// Depending on whether or not there are filters set, the map is rendered
	// differently
	xmlhttp.open("GET","inc/rendermap.php?zoom="+oo_maps_zoom+"&mapw="+oo_winW+"&maph="+oo_winH+"&lyrx="+oo_lightyear_ratio_x+"&lyry="+oo_lightyear_ratio_y+"&galaxy="+oo_current_galaxy, true);
	xmlhttp.send();
}



// SWITCHES A GALAXY
function oo_switchGalaxy()
{
	oo_current_galaxy = document.getElementById("maps_galaxy_box").value-1;
	oo_render_map();
}


// Filters by selected filters
function oo_applyFilter()
{
	// Create a filter string
	var filterStr = "";
	var empty = true;

	// Depending on whether or not there are filters set, the map is rendered
	// differently... but only if we have an actual number to filter by.
	if (document.getElementById("maps_filter_techlevel").value != "-")
	{
		var filtertype = 0;
		if (document.getElementById("tlandup").checked == true) { filtertype = 1; }
		if (document.getElementById("tlonly").checked == true) { filtertype = 2; }

		filterStr += "techlevel|"+document.getElementById("maps_filter_techlevel").value + "|" + filtertype;

		empty = false;
	}

	if (document.getElementById("maps_filter_economy").value != "-")
	{
		var filtertype = 0;
		if (document.getElementById("ecandup").checked == true) { filtertype = 1; }
		if (document.getElementById("econly").checked == true) { filtertype = 2; }

		// Make sure correct dividers are added
		if (filterStr != "") { filterStr += "|"; }

		filterStr += "economy|"+document.getElementById("maps_filter_economy").value + "|" + filtertype;

		empty = false;
	}

	if (document.getElementById("maps_filter_government").value != "-")
	{
		var filtertype = 0;
		if (document.getElementById("govandup").checked == true) { filtertype = 1; }
		if (document.getElementById("govonly").checked == true) { filtertype = 2; }

		// Make sure correct dividers are added
		if (filterStr != "") { filterStr += "|"; }

		filterStr += "government|"+document.getElementById("maps_filter_government").value + "|" + filtertype;

		empty = false;
	}

	if (empty == false)
	{
		// We render into this container
		var m = oo_get_maps_render_container();
		// In case we swiched galaxies...
		m.innerHTML = "";

		// Renders lines, systems, etc
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				oo_get_maps_render_container().innerHTML = this.responseText;
			}
		};

		xmlhttp.open("GET","inc/rendermap.php?zoom="+oo_maps_zoom+"&mapw="+oo_winW+"&maph="+oo_winH+"&lyrx="+oo_lightyear_ratio_x+"&lyry="+oo_lightyear_ratio_y+"&galaxy="+oo_current_galaxy+"&filters="+filterStr, true);
			xmlhttp.send();
	}
	// The render normally
	else { oo_render_map(); }
}


// Show the location marker at a specific location
function oo_showLocationMarkerAt(x, y)
{
	// We need to adjust the location of the marker as it is an image file
	var m1 = document.getElementById("oo_lm_1");
	var m2 = document.getElementById("oo_lm_2");
	m1.setAttribute("cx", x);
	m2.setAttribute("cx", x);
	m1.setAttribute("cy", y);
	m2.setAttribute("cy", y);
	m1.style.visibility = "visible";
	m2.style.visibility = "visible";
}

function oo_hideLocationMarker()
{
	var m1 = document.getElementById("oo_lm_1");
	var m2 = document.getElementById("oo_lm_2");
	m1.style.visibility = "hidden";
	m2.style.visibility = "hidden";
}


// -----------------------------------------------------------------------------
// SHOW SOLAR SYSTEM INFO
// -----------------------------------------------------------------------------

function oo_show_systeminfo(systemid)
{
	var s = document.getElementById("maps_info_sidebar");

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			s.innerHTML = this.responseText;
		}
	};

	xmlhttp.open("GET","inc/get_system_info.php?id="+systemid, true);
		xmlhttp.send();

	s.style.display = "inline-block";
}

function oo_hide_systeminfo()
{
	var s = document.getElementById("maps_info_sidebar");
	s.style.display = "none";
	s.innerHTML = "";
}


// -----------------------------------------------------------------------------
// RENDER
// -----------------------------------------------------------------------------

function oo_render()
{
	oo_scale_container();
	oo_determine_ly_ratio();
	oo_enable_mousezoom();
	oo_render_ruler();
	oo_render_map();
}


// Mostly called on zoom in/out. But makes sure we don't zoom into monstrous
// proportions, and not too far out.
function oo_rerender(zoomadjust)
{
	zoomadjust = Math.round(zoomadjust);

	if (oo_maps_zoom >= 0.8 && oo_maps_zoom <= 5.0)
	{
		if (zoomadjust < 0)
		{ oo_maps_zoom = oo_maps_zoom - 0.2; }
		if (zoomadjust > 0)
		{ oo_maps_zoom = oo_maps_zoom + 0.2; }

		if (oo_maps_zoom > 5.0) { oo_maps_zoom = 5.0; }
		if (oo_maps_zoom < 0.8) { oo_maps_zoom = 0.8; }

		//oo_get_maps_render_container().innerHTML = "";
		document.getElementById("maps_ruler").innerHTML = "";
		document.getElementById("maps_ruler_v").innerHTML = "";

		oo_determine_ly_ratio();
		oo_render_ruler();
		oo_scale_container();
	}

	//oo_render();
}



function oo_window_resize(e)
{
	oo_get_maps_render_container().innerHTML = "";
	document.getElementById("maps_ruler").innerHTML = "";
	document.getElementById("maps_ruler_v").innerHTML = "";

	oo_render();
}


function oo_starfield_parallax(e)
{
	var s = document.getElementById("maps_starfield");
	s.style.left = (oo_get_maps_container().offsetLeft / 4) - 2000;
	s.style.top = (oo_get_maps_container().offsetTop / 4) - 2000;
}


function oo_adjust_rulers(e)
{
	hr = document.getElementById("maps_ruler");
	hv = document.getElementById("maps_ruler_v");
	hr.style.left = oo_get_maps_container().offsetLeft + "px;";
	hv.style.top  = oo_get_maps_container().offsetTop  + "px;";
	hr.setAttribute("style", "left: " + oo_get_maps_container().offsetLeft + "px;");
	hv.setAttribute("style", "top: "  + oo_get_maps_container().offsetTop  + "px;");
}


// Display the filter box
function oo_show_filterbox()
{
    document.getElementById("maps_filterbox").style.visibility = "visible";
}

function oo_hide_filterbox()
{
    document.getElementById("maps_filterbox").style.visibility = "hidden";
}


// Re-render on resizing
window.addEventListener('resize', oo_window_resize, true);
// Move parallax
window.addEventListener('mousemove', oo_starfield_parallax, true);
// Adjust rulers
window.addEventListener('mousemove', oo_adjust_rulers, true);



// Add a starfield!
function getRandom(min, max) {
	return Math.floor(Math.random() * (max - min + 1)) + min;
}
function oo_add_starfield()
{
	var canvas = document.getElementById('maps_starfield'),
	context = canvas.getContext('2d'),
	stars = 50000,
	colorrange = [0,60,240];
	for (var i = 0; i < stars; i++) {
	var x = Math.random() * 10000;
	y = Math.random() * 10000,
	radius = Math.random() * 1.2,
	hue = colorrange[getRandom(0,colorrange.length - 1)],
	sat = getRandom(50,100);
	context.beginPath();
	context.arc(x, y, radius, 0, 360);
	context.fillStyle = "hsl(" + hue + ", " + sat + "%, 88%)";
	context.fill();
	}
}
