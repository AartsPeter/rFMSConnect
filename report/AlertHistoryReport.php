<?php
    require_once '../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/header.php';
    require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
    if (!securePage($_SERVER['PHP_SELF'])){die();}
    $startdate=date('Y/m/d');
    $enddate=date('Y/m/d ', strtotime("+1 day"));
    $calc=date("d");
//    if ($calc<13){ 	$startdate=date("Y/m/j", strtotime("-14 days"));}
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina ">
					<div class=" inner-pagina">
						<div class="mr-auto page-title col-12">Reports - Alert History</div>
						<div class="d-flex">
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<div class='input-group date'>
									<input type='text' class="form-control input-group" value="<?=$SD?>" id='SelectDate'>
                                    <span class="input-group-addon btn text-primary">
                                        <span class="fad fa-calendar-alt"></span>
                                    </span>
								</div>
							</div>
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<div class='input-group date'>
									<input type='text' class="form-control input-group" value="<?=$ED?>" id="SelectDateEnd"  />
                                    <span class="input-group-addon btn text-primary">
                                        <span class="fad fa-calendar-alt"></span>
                                    </span>
								</div>
							</div>
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<input type="button" class="btn btn-primary col-12 " value="Search" onclick="SaveSelectedReportDate();ShowAlertHistoryTable();" />
							</div>
							<?php if ($_SESSION['UGselected']!='*'){ ?>
							<div class="form-group col-xl-2 col-lg-4 col-6">
								<div class="dropdown ">
									<button type="button" class="btn btn-secondary dropdown-toggle col-12 " data-toggle="dropdown"> <i class='far fa-envelope'></i> - report  </button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" onclick="CreateReport('vehiclealerts',false);" href="#">Now</a>
										<a class="dropdown-item" onclick="CreateReport('vehiclealerts',true);" href="#">Schedule</a>
									</div>
								</div>
							</div>
							<?php }?>
							<div class="form-group col-xl-2 col-lg-4 col-6 ml-auto">
								<div class="message col-12 text-right" id="TripReport"></div>
							</div>
						</div>
						<div class="col-12">
							<div class="card card-body border-0 tableVehicles" id="ShowMap" >
								<table class="display responsive table noWrap" id="tableAlertHistory"></table>
						</div>
					</div>
				</div>			
			</div>
		</section>
	</main>
	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php';?>
	<script src="<?=$us_url_root?>js/rfms_report.js"></script>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
	<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script>
	window.onload=function(){
		ShowAlertHistoryTable('<?=$SCNumber?>');
	};
	</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
