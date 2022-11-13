<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

if (!securePage($_SERVER['PHP_SELF'])){die();} 


?>
	<main role="main">
		<section class="section section-full" >
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="mr-auto page-title">Reports - Trip Analysis per vehicle</div>
                        <div class="row">
                            <div class="form-group col-xl-2 col-lg-4 col-12">
                                <select id="vehicles" class="form-control col-12 " name="SelectedV"  onchange="ClearVehicleMessage();FilterDBVehicle();" >
                                <option>Select Vehicle</option>
                                </select>
                            </div>
                            <div class="form-group col-xl-2 col-lg-4 col-6">
                                <div class='input-group date ' id='datetimepicker11'>
                                    <input type='text' class="form-control input-group input-date" value="<?=$SD?>" id='SelectDate' />
                                    <span class="input-group-addon btn text-primary">
                                        <span class="fad fa-calendar-alt"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-xl-2 col-lg-4 col-6">
                                <div class='input-group date' id='datetimepicker12'>
                                    <input type='text' class="form-control input-group " value="<?=$ED?>" id='SelectDateEnd' />
                                    <span class="input-group-addon btn text-primary">
                                        <span class="fad fa-calendar-alt"></span>
                                    </span>
                                </div>
                            </div>
                            <div class=" col-xl-2 col-lg-4 col-6">
                                <input type="button" class="btn btn-primary btn-block col-12" value="Search" onclick="SaveSelectedReportDate();FilterDBVehicle();" />
                            </div>
                            <div class="form-group m-auto">
                                <div class="message" id="TripReport"></div>
                            </div>
                        </div>
                        <div class="row pb-1" id="TripSumReport"></div>
                        <div class="row">
                             <div class="col-md-8 col-12 ">
                                <div class="reportpad card p-3 hide" id="reportarea">
                                    <div class="px-3 py-1 mb-2"><table id="TripTable" class="display table noWrap " width="100%"></table></div>
                                    <div class="nav-tabs-custom hide" id="TripTabs">
                                        <ul class="nav nav-tabs pl-3" >
                                            <li class="nav-item"> <a class="nav-link active" href="#TRTAB1" data-toggle="tab">Timeline </a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#TRTAB2" data-toggle="tab">Drivingtime</a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#TRTAB3" data-toggle="tab">Display Warnings</a></li>
                                        </ul>
                                        <div class="tab-content px-3 py-1">
                                            <div class="tab-pane active" id="TRTAB1" role="tabpanel"><div id="TripDetailsTimeline"  class="col-12 p-0 rFMSTripGraph2"></div> </div>
                                            <div class="tab-pane"        id="TRTAB2" role="tabpanel"><div id="TripDriveTimeGraph"   class="col-12 p-0 rFMSTripGraph2"></div> </div>
                                            <div class="tab-pane"        id="TRTAB3" role="tabpanel"><div id="TripTT"               class="rFMSTripGraph2 ">          </div> </div>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                            <div class="col-md-4 col-12 d-none d-xl-block">
                                <div class="reportpad card" >
                                    <div id="map" class="border"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </main>

	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
	<script src="<?=$us_url_root?>js/rfms_report.js"></script>	
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts-more.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/modules/timeline.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/modules/xrange.src.js"></script>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
	<script src="<?=$us_url_root?>plugins/leaflet/leaflet-src.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.rotatedMarker.js"></script>
	<script src="<?=$us_url_root?>js/leaflet.markercluster-src.js"></script>
	<script src="<?=$us_url_root?>js/easy-button.js"></script>
	<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script>
        window.onload=function(){
            SelectCustomerVehicles();
            InitializeMAP();
            ShowDBButtons('true');
            FilterDBVehicle();
        };
        new SlimSelect({select: '#vehicles'	});
	</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
