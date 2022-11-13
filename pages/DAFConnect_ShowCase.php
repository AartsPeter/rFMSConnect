<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
//if (!securePage($_SERVER['PHP_SELF'])){die();} 
?>
		<section class="section section-full ">
			<div class="container-demo ">
				<div class="pagina p-0">
			        <div class="demopad">
				        <div id="map" class="row-fluid some" ></div>
			        </div>
			        <div class="row col-12 overlay progressbar">
				        <div class="span1" id="DemoProgress" ></div>
			        </div>
				</div>
			</div>
		</div>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer_demo.php'; // the final html footer copyright row + the external js calls ?>

	<script src="<?=$us_url_root?>plugins/leaflet/leaflet-src.js"></script>
	<script src="<?=$us_url_root?>js/easy-button.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.rotatedMarker.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.markercluster-src.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.awesome-markers.js"></script>


<script>

	window.onload=function(){
		ShowSpinnerBig("DemoInfo");
		InitializeDemoMAP();
		ShowSpinnerBig("DemoInfo");
		ShowLatestDemo();
		setInterval(ShowLatestDemo,60000);
	}
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
 