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
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="mr-auto page-title">Reports - Vehicle Geofencing </div>
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
                                    <a class="dropdown-item" onclick="CreateReport('Geofencing',false);" href="#">Mail Now</a>
                                    <a class="dropdown-item" onclick="CreateReport('Geofencing',true);" href="#">Schedule</a>
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
                                    <table id="tableGeofencingActivity" class="display table responsive noWrap" width="100%"></table>
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
	<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script>
		window.onload=function(){
			ShowVehicleGeofencingTable();
		};
	</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php';  ?>