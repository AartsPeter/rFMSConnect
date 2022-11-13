<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
if (!securePage($_SERVER['PHP_SELF'])){die();} 
?>
    <main role="main">
    	<section class="section section-mobile ">
			<div class="container-fluid center">
				<div class="pdc-card-pagina pt-3 d-flex">
				    <div id="pdc_main" class="pdcdetsidebar m-auto col-12 col-xl-4 " >
				        <div class="card h-100 shadow ">
                            <div class="card-header">
                                <div class="d-flex"><div><a href="../index.php"><i class="fas fa-arrow-left fa-fw"></i></a></div><div class="m-auto">Accident registration </div></div>
                            </div>
                            <div class="card-body pdc-pagina">
                                <div class="row">
                                    <div class="col-12 ">
                                        <div class='card  shadow-sm mb-3'>
                                            <div class="card-header"><i class="fad fa-truck-moving fa-fw"></i> vehicle</div>
                                            <div class="card-body" id="selectedVehicle">
                                                <div class="row ">
                                                    <div class="col-12">
                                                        <button class="btn btn-primary " onclick="SelectVehicle();" data-toggle="modal" data-target="#NewVehicleModal">ADD other vehicle </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='card shadow-sm mb-3'>
                                            <div class="card-header "><i class="fad fa-trailer fa-fw"></i> trailer</div>
                                            <div class="card-body " id="selectedTrailer">
                                                <div class="row ">
                                                    <div class="col-12">
                                                        <button class="btn btn-primary " onclick=" SelectTrailer();" data-toggle="modal" data-target="#NewVehicleModal">ADD a trailer </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 card p-0 mb-3" id="TempDescription"></div>
                                        <div class="card-footer">
                                            <button class="btn btn-lg btn-secondary col-12" id="StartCheck"  onclick="StartPDC();" ><i class="fad fa-list fa-fw"></i> Start Registration</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="pdc_details"  class="pdcdetsidebar m-auto col-12 col-xl-4 hide"></div>
                        <div id="pdc_details2" class="pdcdetsidebar col-12 col-xl-4 hide"></div>
                        <div id="pdc_details3" class="pdcdetsidebar col-12 col-xl-4 hide" ></div>
                    </div>
                </div>
			</div>
		</section>
	</main>

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script src="<?=$us_url_root?>js/driver.js"></script>
<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script>new SlimSelect({select: '#groups'});	</script>
<script>
    window.onload=function(){
        closepdcDet();
        loadPDCtemplate('99');
    }
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
 