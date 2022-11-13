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
		<section class="section section-full" >
			<div class="container-fluid">
				<div class="pagina">
					<div class="inner-pagina ">
						<div class="col-12 mr-auto page-title">Reports -  Delayed vehicles</div>
						<div class="row">							
						<div class="col-12 col-md-6 ">
							<div class="row">							
								<div class="form-group col-md-4 col-6">
									<div class='input-group date col-12 p-0'>
										<input type='text' class="form-control input-group" value="<?=$SD?>" id='SelectDate' />
										<span class="input-group-addon btn btn-primary">
											<span class="far fa-calendar"></span>
										</span>													
									</div>
								</div>
								<div class="col-md-4 col-6">
									<div class='input-group date col-12 p-0'>
										<input type='text' class="form-control input-group" value="<?=$ED?>" id='SelectDateEnd' />
										<span class="input-group-addon btn btn-primary">
											<span class="far fa-calendar"></span>
										</span>															
									</div>
								</div>														
								<div class="col-md-4 col-6 ">
									<div class='input-group col-12 p-0' >
										<input type="button" class="btn btn-primary input-group  " value="Search" onclick="SaveSelectedReportDate();FilterDBDelayed();"/>
									</div>
								</div>
							</div>
							<div class="row">							
								<div class="col-12">
									<div class="display reportpad p-0">
										<div id="map" class="shadow-rfms"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6 ">
							<div class="row">
								<div class="col-12">
									<div class="card mb-3">
										<div class="card-header" id='TripReport'></div>
										<div class="dgpad align-self-end " id="GraphDelayedVehicles"></div>  
									</div>
								</div>
								<div class="col-12">
									<div class="card">
										<div class="card-header" id='TitleDay'></div>
										<div class="dgpad align-self-end " id="DelayedVehicles"></div>  
									</div>
								</div>
							</div>
						</div>
					</div>
					</div>
				</div>	
			</div>	
		</section>
	</main>
	
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; 	// the final html footer copyright row + the external js calls ?>

<script src="<?=$us_url_root?>plugins/leaflet/leaflet-src.js"></script>
<script src="<?=$us_url_root?>js/easy-button.js"></script>
<script src="<?=$us_url_root?>js/leaflet-heat.js"></script>
<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
window.onload=function(){
	InitializeHeatMAP();
 	<?php 
	$GroupQ =$db->query("
	SELECT vehicle_delayed.*,vehicles.customerVehicleName, customers.name
	FROM vehicle_delayed
	INNER JOIN vehicles ON vehicles.vin=vehicle_delayed.vin
	INNER JOIN FAMReport ON FAMReport.vin=vehicle_delayed.vin
	INNER JOIN customers ON customers.accountnumber=FAMReport.client 
	WHERE createdDate BETWEEN '".date('Y/m/d',strtotime('-0 days'))."'  AND'".date('Y/m/d ')."' 
	GROUP BY vehicle_delayed.VIN ");
	$Vehicles = $GroupQ->results();
	?> 
	ShowHeatMap(<?php echo json_encode($Vehicles);?>);
	FilterDBDelayed();
	};
</script>


<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
