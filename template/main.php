<canvas id="maps_starfield" class="maps_starfield" width=10000 height=10000></canvas>

<div id="maps_container" class="maps_container" >
	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="maps_render" id="maps_render"></svg>
</div>

<script> $( function() { $( "#maps_container" ).draggable(); } ); </script>

<div id="maps_system_name" class="maps_system_name"></div>

<div id="maps_ruler" class="maps_ruler"></div>
<div id="maps_ruler_v" class="maps_ruler_v"></div>
<div class="maps_ruler_placeholder"></div>
<div class="maps_ruler_placeholder_v"></div>
<div class="maps_info_sidebar" id="maps_info_sidebar"></div>

<script>oo_add_starfield(); oo_render();</script>