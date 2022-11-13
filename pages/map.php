<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
if (!securePage($_SERVER['PHP_SELF'])){die();} 
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid ">
				<div class="pagina p-0 bg-trans">
					<div class="shadow-sm mb-0">
						<div class="row">
							<div class="pad col col-xs-12" id="ShowMap" ><div id="map" class="" ></div></div>
							<div class="card" id="VehicleDetails" ></div>
							<div class="card" id="VehicleList" >
                                <div class="py-1">
                                    <div class=" col-12 px-2 text-left " href="#" id="VehicleFilter" >
                                        <div class="d-flex">
                                            <form class="d-none d-sm-inline-block  mr-auto  mw-100 p-0">
                                                <div class="input-group">
                                                    <input type="text" class="form-control border small" placeholder="Search for..." aria-label="Search"  id='filtername' onfocus="this.value=''" placeholder="search..." onClick="document.getElementById('filtername').value = ''" onKeyUp="ShowLatest();">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" type="button">
                                                            <i class="fas fa-search fa-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                            <button class="btn btn-primary ml-1 text-right" type="button" data-toggle="collapse" data-target="#collapseMapFilter" aria-expanded="true" aria-controls="collapseMapFilter" ><i class="fas fa-filter"></i></button>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapseMapFilter">
                                        <div class="card border shadow m-1 p-0 pt-2">
                                            <div class="col-12 "><b>Total Vehicles</b> [ <b><span id="CTTO"></span></b> ]</div>
                                            <div class="col-12 pl-0 ">
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch custom-switch-sm">
                                                        <input type='checkbox' class="custom-control-input" id='driving' name='filter' onclick='ShowLatest();'  value='Driving' checked="checked">
                                                        <label class="custom-control-label secondary" for="driving"><i class="fas fa-play fa-fw "></i> Driving [ <b><span  id="CTDR"></span></b> ]</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch custom-switch-sm">
                                                        <input type='checkbox' class="custom-control-input" id='idle' name='filter' onclick='ShowLatest();'  value='Pause' checked="checked">
                                                        <label class="custom-control-label secondary" for="idle"><i class="fas fa-pause fa-fw "></i> Idle [ <b><span  id="CTID"></span></b> ]</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="custom-control custom-switch custom-switch-sm">
                                                        <input type='checkbox' class="custom-control-input" id='stopped' name='filter' onclick='ShowLatest();'  value='Stoped' checked="checked">
                                                        <label class="custom-control-label secondary" for="stopped"><i class="fas fa-stop fa-fw "></i> Stopped [ <b><span  id="CTST"></span></b> ]</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <div class="col-12">
                                                <div class="custom-control custom-switch custom-switch-sm">
                                                    <input type='checkbox' class="custom-control-input" id='alert' name='filter' onclick='ShowLatest();' value='Alert'   checked>
                                                    <label class="custom-control-label" for="alert"><i class="fas fa-exclamation fa-fw danger"></i> Alert ( <b><span id="CTAL"></span></b> )</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="custom-control custom-switch custom-switch-sm">
                                                    <input type='checkbox' class="custom-control-input" id='NoGPS' name='filter' onclick='ShowLatest();' value='NoGPS' >
                                                    <label class="custom-control-label" for="NoGPS"><i class="far fa-question-circle fa-fw warning"></i> No location ( <b><span id="CTNL"></span></b> )</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="custom-control custom-switch custom-switch-sm">
                                                    <input type='checkbox' class="custom-control-input" id='Delayed' name='filter' onclick='ShowLatest();' value='Delayed'   checked>
                                                    <label class="custom-control-label" for="Delayed"><i class="fas fa-history fa-fw warning"></i> Delayed ( <b><span id="CTDE"></span></b> )</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="custom-control custom-switch custom-switch-sm">
                                                    <input type='checkbox' class="custom-control-input" id='Geofence' name='filter' onclick='ShowLatest();' value='Geofence'   >
                                                    <label class="custom-control-label" for="Geofence"><i class="fas fa-fw fa-draw-polygon warning"></i> Geofence ( <b><span id="CTGE"></span></b> )</label>
                                                </div>
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <div class="col-12">
                                                <div class="custom-control custom-switch custom-switch-sm">
                                                    <input type='checkbox' class="custom-control-input" id='Today' name='filter' onclick='ShowLatest();' value='Today' >
                                                    <label class="custom-control-label" for="Today"><i class="fa fa-fw"></i> Driving Today ( <b><span id="CTDT"></span></b> )</label>
                                                </div>
                                            </div>
                                            <div class="ml-auto">
                                                <a class="btn text-primary" data-toggle="collapse" data-target="#collapseMapFilter">close <i class="fas fa-chevron-up"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="VehicleList " id="VehicleInfoD">
                                </div>
							</div>
						</div>
					</div>
				</div>  
			</div>
		</section>
	</main>  

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; ?>
    <script async src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts-more.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/modules/xrange.src.js"></script>
	<script src="<?=$us_url_root?>plugins/leaflet/leaflet.js"></script>
	<script src="<?=$us_url_root?>plugins/leaflet/L.TileLayer.NoGap.js"></script>
	<script src="<?=$us_url_root?>js/easy-button.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.rotatedMarker.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.markercluster-src.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.awesome-markers.js"></script>

<script>

    window.onload=function(){
		InitializeMAP();
		ShowLatestButtons();
		ShowDBButtons('true');
		setInterval(LoadLatest('1'),120);
		ShowDealersOnMap();
	}
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
 