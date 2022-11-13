<?php 
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 
if (!securePage($_SERVER['PHP_SELF'])){die();} 
if($user->isLoggedIn()) { $thisUserID = $user->data()->id;} else { $thisUserID = 0; }

$VehicleQ =$db->query("
	SELECT
	customers.*,driver.LastVehicle,
	COUNT(distinct vehicles.vin) AS TotalVehicles,
	COUNT(driver.LastVehicle) AS TotalDrivers
	FROM vehicles
	INNER JOIN FAMReport ON vehicles.VIN=FAMReport.vin 
	INNER JOIN customers ON customers.accountnumber=FAMReport.client 
	LEFT JOIN driver ON driver.LastVehicle=vehicles.VIN
	WHERE vehicles.vehicleActive=true
	GROUP BY customers.name;");
$Drivers = $VehicleQ->results();
?>
<div class="content-wrapper" id="mainpage">
    <section class="content-header">
		<h1>Enterprise Group (Customer) view
			<small>showing groups </small></h1>
		<ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li> Administration</li>
        <li class="active">Groups</li>
    </section>
    <section class="content container-fluid" id="MAIN_CONTENT">
	<div class="row">
		<div class="col-md-12">
			<div class="tableGroups">
				<table class="display compact" id=tableGroups>	</table>
			</div>
		</div>
	</div>
</div>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<script src="<?=$us_url_root?>plugins/jQuery/jquery-3.1.1.min.js"></script>
<script src="<?=$us_url_root?>js/rfms.js"></script>
<!--<script src="<?=$us_url_root?>js/rfms_report.js"></script>	-->
<script src="<?=$us_url_root?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/js/buttons.flash.min.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/js/jszip.min.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/js/pdfmake.min.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/js/vfs_fonts.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/js/buttons.html5.min.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/js/buttons.print.min.js"></script>
<script src="<?=$us_url_root?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?=$us_url_root?>report/js/bootstrap-datetimepicker.js"></script>
<script>
window.onload=function(){
	ShowGroupTable(<?php echo json_encode($Drivers);?>);
	};
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
