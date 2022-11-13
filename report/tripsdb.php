
<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

if (!securePage($_SERVER['PHP_SELF'])){die();}

?>
	<main role="main"  >
		<section class="section section-full">
			<div class="container-fluid">
				<div class="pagina ">
					<div class="inner-pagina">
						<div class="mr-auto page-title ">Reports - Fleet trips </div>
						<div class="row">
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<div class='input-group date'>
									<input type='text' class="form-control input-group text-left " value="<?=$SD?>" id='SelectDate' />
									<span class="input-group-addon btn text-primary">
										<span class="fad fa-calendar-alt"></span>
									</span>
								</div>
							</div>
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<div class='input-group date'>
									<input type='text' class="form-control input-group" value="<?=$ED?>" id='SelectDateEnd' />
									<span class="input-group-addon btn text-primary">
										<span class="fad fa-calendar-alt"></span>
									</span>
								</div>
							</div>
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<input type="button" class="btn btn-primary col-12 " value="Search" onclick="SaveSelectedReportDate();ReadGroupTrips();" />
							</div>
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<div class="dropdown ">
								<?php if ($_SESSION['UGselected']!='*'){ ?>
									<button type="button" class="col-12 btn btn-primary dropdown-toggle " data-toggle="dropdown"> <i class='fad fa-fw fa-envelope'></i> - Report </button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" onclick="CreateReport('trips',false);" href="#">Now</a>
										<a class="dropdown-item" onclick="CreateReport('trips',true);" href="#">Schedule</a>
									</div>
								<?php } else { ?>
									<button type="button" class="col-12 btn btn-secondary dropdown-toggle disabled" title="to enable, first select a group" data-toggle="dropdown"> <i class='far fa-envelope'></i> - report  </button>
								<?php }?>
								</div>
							</div>
							<div class="form-group col ml-auto">
								<div class="message col-12 text-right" id="TripReport"></div>
							</div>
						</div>
						<div class="row" id="FleetSumReport"></div>
						<div class="row pt-3" >
                            <div class="FleetGraph col-12 col-xl-6 px-3" id="FleetTripReport"></div>
                            <div class="FleetGraph col-12 col-xl-6 px-3" id="FleetFuelReport"></div>
                            <div class="col-12" id="TripsTableHeaderPH"></div>
                            <div class="col-12" id="DetailsTableHeaderPH"></div>                            
                        </div>
					</div>
				</div>
			</div>			
		</section>
	</main>
	<div class="modal fade modal-center" id="EditTripModal" tabindex="-1" role="dialog" aria-labelledby="modalabout" aria-hidden="false">
		<div class="modal-dialog modal-xl">
			<div class="modal-content ">
				<div class="modal-header">
					<div class="card-title p-0" id="TimelineTitle">Trip Details</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>					
				<div class="modal-body">
                    <div class="card-header">Timeline</div>
                    <div id="TripDetailsTimeline" class="rFMSTripGraph2 p-3"></div>
                    <div class="card-header">Drivetime</div>
                    <div id="TripDriveTimeGraph" class="rFMSTripGraph2 p-3"></div>
                    <div class="card-header">Warnings</div>
                    <div id="TripTT" class="rFMSTripGraph2 p-3"></div>
				</div>	
			</div>
		</div>
	</div>	
	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
	<script src="<?=$us_url_root?>js/rfms_report.js"></script>	
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/modules/timeline.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/modules/xrange.src.js"></script>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
    <script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

	<script>
	window.onload=function(){
		ReadGroupTrips();
	};
	</script>


<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
