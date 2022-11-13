
<?php

require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

if (!securePage($_SERVER['PHP_SELF'])){die();} 
if($user->isLoggedIn()) { $thisUserID = $user->data()->id;} else { $thisUserID = 0; }
if (isset($_SESSION['UGtext'])){$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];}
else {
	if ($user->data()->cust_id!='0'){$SCNumber=$user->data()->cust_id;$SCName=array_search($user->data()->cust_id, array_column($Group, 'name'));;}
	else {$SCName='All Groups';$SCNumber='*';}
}

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina ">
				    <div class="inner-pagina">
					<div class="page-title ">Manage Geofences  <small><small>/  Create, change or delete registered geofences </small></small></div>
					<div class="col-12 p-0">
						<div class="row">
							<div class="col-12 col-lg-6" id="FencesWindow">
								<div class="card alert-card">
								    <div class="card-header">geofences </div>
									<div class="card-body p-0">
									    <div class="p-3">Showing different type of geofences</div>
									    <div class="nav-tabs-custom"  id="PDCTabs">
                                           <ul class="nav nav-tabs" >
                                               <li class="nav-item"> <a class="nav-link active" href="#TAB1" data-toggle="tab" >Personal / Group</a></li>
                                               <li class="nav-item"> <a class="nav-link" href="#TAB2" data-toggle="tab" >Public </a></li>
                                               <li class="nav-item"> <a class="nav-link" href="#TAB3" data-toggle="tab" >System</a></li>
                                           </ul>
                                           <div class="tab-content rounded-0 p-3">
                                               <div class="tab-pane active" id="TAB1" role="tabpanel"> <table id="geofencesTablePersonal" class="display table noWrap " width="100%"></table> </div>
                                               <div class="tab-pane"        id="TAB2" role="tabpanel"> <table id="geofencesTablePublic" class="display table noWrap " width="100%"></table> </div>
                                               <div class="tab-pane"        id="TAB3" role="tabpanel"> <table id="geofencesTableSystem" class="display table noWrap " width="100%"></table> </div>
                                           </div>
                                       </div>
									</div>
								</div>
							</div>
							<div class="col-12 col-lg-6 " id="MapWindow">
                                <div class="card alert-card ">
                                    <div class="card-header d-flex">Map view &nbsp;&nbsp;<div class="d-flex" id="loadprogress">&nbsp;</div></div>
                                    <div class="AlertMap p-0 border-0">
                                        <div id="map" class="border-0 rounded-0"></div>
                                        <div class="hide">
                                            <div class=" col-12 px-1 text-left collapsed" href="#" id="VehicleFilter" role="button" data-toggle="collapse" data-target="#collapseMapFilter" aria-expanded="false" aria-controls="collapseMapFilter">
                                                <div class="d-flex">
                                                    <form class="d-none d-sm-inline-block  mr-auto  mw-100 p-0">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control small" placeholder="Search for..." aria-label="Search"  id='filtername' onfocus="this.value=''" placeholder="search..." onClick="document.getElementById('filtername').value = ''" onKeyUp="ShowLatest();">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary" type="button">
                                                                    <i class="fas fa-search fa-sm"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div class="btn btn-primary ml-1 text-right"><i class="fas fa-filter"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-1 pt-3 ">
                                        <div class="col-12 row pr-0">
                                            <div class="col-12" id="info">
                                                <li>create your geofence of POI by clicking a draw-button on the map-menu</li>
                                                <span> or</span>
                                                <li>select a geofence to manage vehicle relationship or edit geofence settings</li>
                                            </div>
                                            <div class="col-8">
                                                <input class="form-control hide" type="text"  id="nameGeofence"  placeholder="name of your geofence" value="" required autofocus>
                                            </div>
                                            <div class="col-4 px-0 ">
                                                <select class="form-control hide"  id="GeofenceCat"  placeholder="type of your geofence" value="" required></select>
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="btn btn-secondary mr-2 hide" id="delete">Reset / Clear</div>
                                                <div class="btn btn-primary hide" id="export" >Save Geofence</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="col-12 col-lg-3 hide" id="ActionWindow">
								<div class="card alert-card">
								    <div class="card-header d-flex"><div class="col-8 p-0">define action for selected geofence</div><div class="ml-auto"><a class="pointer" onclick="HideActionWindow();"><i class="fad fa-times-square fa-fw"></i></a></div></div>
                                    <div class="card-body" id="SetActionGeofences"> </div>
                                </div>
							</div>

						</div>
					</div>	
				</div>
			</div>
		</div>
	</section>
</div>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<script src="<?=$us_url_root?>js/rfms_geofence.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script src="<?=$us_url_root?>plugins/leaflet/leaflet.js"></script>
<script src="<?=$us_url_root?>plugins/leaflet.draw/leaflet.draw.js"></script>
<script src="<?=$us_url_root?>js/leaflet.rotatedMarker.js"></script>
<script src="<?=$us_url_root?>js/leaflet.markercluster-src.js"></script>
<script src="<?=$us_url_root?>js/leaflet.awesome-markers.js"></script>
<script src="<?=$us_url_root?>js/easy-button.js"></script>
<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
    window.onload=function(){
        Manage_Geofences();
        LoadGeofences();
        LoadGeofencesType();
        setInterval(LoadLatest('2'),120);
    };
</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
