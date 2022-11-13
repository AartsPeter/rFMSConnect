<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

if (!securePage($_SERVER['PHP_SELF'])){die();} 


?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="mr-auto page-title">Reports - VehicleActivity </div>
                        <div class="row">
                            <div class="form-group col-xl-2 col-md-4 col-6"  >
                                <div class='input-group date'>
                                    <input type='text' class="form-control input-group text-left" value="<?=$SD?>" id='SelectDate' />
                                    <span class="input-group-addon btn text-primary">
                                        <span class="fad fa-calendar-alt"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-xl-2  col-md-4 col-6 " >
                                <div class='input-group date '>
                                    <input type='text' class="form-control input-group text-left" value="<?=$ED?>" id='SelectDateEnd' />
                                    <span class="input-group-addon btn text-primary">
                                        <span class="fad fa-calendar-alt"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-xl-2 col-md-4 col-6">
                                <input type="button" class="btn btn-primary col-12" value="Search" onclick="SaveSelectedReportDate();ShowVehicleActivityTable();"/>
                            </div>
                            <?php if ($_SESSION['UGselected']!='*'){ ?>
                            <div class="form-group col-xl-2 col-lg-4 col-6 ">
                                <div class="dropdown ">
                                    <button type="button" class="btn btn-secondary btn-blocky dropdown-toggle shadow-sm col-12" data-toggle="dropdown"> <i class='far fa-envelope'></i> - report  </button>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" onclick="CreateReport('VehicleActivity',false);" href="#">Mail Now</a>
                                    <a class="dropdown-item" onclick="CreateReport('VehicleActivity',true);" href="#">Schedule</a>
                                  </div>
                                </div>
                            </div>
                            <?php }?>
                            <div class="form-group ml-auto ">
                                <div class="message col-12 text-right" id="TripReport"></div>
                            </div>
                        </div>
                        <div class="col-12" id="content">
                            <div class="row ">
                                <div  class="card card-body border-0 col-12" >
                                    <table id="tableDriverActivity" class="display table responsive noWrap" width="100%"></table>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</section>
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; ?>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
	<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script>
		window.onload=function(){
			ShowVehicleActivityTable();
		};
	</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php';  ?>