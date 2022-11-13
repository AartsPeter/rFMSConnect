<?php

    require_once '../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/header.php';
    require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

    if (!securePage($_SERVER['PHP_SELF'])){die();}
    if($user->isLoggedIn()) { $thisUserID = $user->data()->id;} else { $thisUserID = 0; }

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
					<div class="inner-pagina">
						<div class="mr-auto page-title">Reports - Fleet utilisation</div>
						<div class="row ">
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<div class='input-group date'>
									<input type='text' class="form-control " value="<?=$SD?>" id='SelectDate' />
									<div class="input-group-addon btn text-primary">
										<i class="fad fa-calendar-alt fa-fw"></i>
									</div>
								</div>
							</div>
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<div class='input-group date'>
									<input type='text' class="form-control input-group text-left" value="<?=$ED?>" id='SelectDateEnd' />
									<span class="input-group-addon btn text-primary">
										<span class="fad fa-calendar-alt"></span>
									</span>
								</div>
							</div>
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<input type="button" class="btn btn-primary  col-12" value="Search" onclick="SaveSelectedReportDate();FleetUtil();"/>
							</div>
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<div class="dropdown ">
								<?php if ($_SESSION['UGselected']!='*'){ ?>
									<button type="button" class="col-12 btn btn-secondary dropdown-toggle " data-toggle="dropdown"> <i class="far fa-envelope"></i> - report  </button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" onclick="CreateReport('fleetutil',false);" href="#">Now</a>
										<a class="dropdown-item" onclick="CreateReport('fleetutil',true);" href="#">Schedule</a>
									</div>
								<?php } else { ?>
									<button type="button" class="col-12 btn btn-secondary dropdown-toggle hide" title="to enable, first select a group" data-toggle="dropdown"> <i class="far fa-envelope"></i> - report  </button>
								<?php }?>
								</div>
							</div>
							<div class="form-group ml-auto ">
								<div class="message col-12 text-right" id="TripReport"></div>
							</div>
						</div>
						<div class="row " id="FleetSumReport"></div>
						<div class="row px-3">
							<div class="col-12 pt-3">
								<div class="row">
									<div class="FleetGraph col-12 col-md-6" id="FleetUtilReport"></div>
									<div class="FleetGraph col-12 col-md-6" id="FleetmodelChart"></div>
									<div class="FleetGraph col-12 col-md-6" id="FleetTripReport"></div>
									<div class="FleetGraph col-12 col-md-6" id="FleetFuelReport"></div>
									<div class="col-12 pb-4 card card-body border-0" id="FleetTripTable"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; 	?>
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
    <script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script>
		window.onload=function(){
			FleetUtil();
		};
	</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>