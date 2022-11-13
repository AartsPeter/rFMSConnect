
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
					<div class="page-title ">Reports - Display Warning  <small><small>/  Showing display warning of vehicles </small></small></div>
					<div class="col-12 p-0">
						<div class="row">
							<div class="col-12 col-lg-5">
								<div class="card alert-card">
								    <div class="card-header">vehicles</div>
									<div class="card-body">
									    Showing vehicles with display warnings for the last 7 days
									    <div id="VehicleATPH"><table id="GeofencesTable" class="display table noWrap " width="100%"></table></div>
									</div>
								</div>
							</div>
							<div class="col-12 col-lg-3 ">
                                <div class="card alert-card">
                                    <div class="card-header">location</div>
                                    <div class="card-body p-2">
                                        <div class="AlertMap ">
                                            <div id="map"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="col-12 col-lg-4">
								<div class="card alert-card">
								    <div class="card-header">details</div>
                                    <div class="card-body p-0 pt-3 ">
                                        <div class="nav-tabs-custom"  id="PDCTabs">
                                           <ul class="nav nav-tabs" >
                                               <li class="nav-item"> <a class="nav-link active" href="#PDCTAB1" data-toggle="tab" >vehicle / dealer </a></li>
                                               <li class="nav-item"> <a class="nav-link" href="#PDCTAB2" data-toggle="tab" >Advise </a></li>
                                               <li class="nav-item"> <a class="nav-link" href="#PDCTAB3" data-toggle="tab" >History</a></li>
                                           </ul>
                                           <div class="tab-content rounded-0 p-3">
                                               <div class="tab-pane active" id="PDCTAB1" role="tabpanel"> <div id="VehicleDetails" class="h-100"></div> </div>
                                               <div class="tab-pane"        id="PDCTAB2" role="tabpanel"> <div id="AdviseData" class="h-100 row"></div> </div>
                                               <div class="tab-pane"        id="PDCTAB3" role="tabpanel"> <table class="display table smalltable noWrap" id="Alertdata"></table>  </div>
                                           </div>
                                       </div>
                                    </div>
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

<script src="<?=$us_url_root?>js/rfms_report.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script src="<?=$us_url_root?>plugins/leaflet/leaflet.js"></script>
<script src="<?=$us_url_root?>js/leaflet.rotatedMarker.js"></script>
<script src="<?=$us_url_root?>js/leaflet.markercluster-src.js"></script>
<script src="<?=$us_url_root?>js/leaflet.awesome-markers.js"></script>
<script src="<?=$us_url_root?>js/easy-button.js"></script>
<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>


<script>
window.onload=function(){
	InitializeMAP();
	ShowDealersOnMap();
	ShowAlertReport();
	};
</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
