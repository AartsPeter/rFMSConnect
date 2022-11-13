<?php
    require_once '../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/header.php';
    require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
    if (!securePage($_SERVER['PHP_SELF'])){die();}
    if (isset($_GET['id'])) { $id = $_GET['id'];} 	        else { $id = 0;}
    echo $id;
    error_reporting(E_ALL);
?>
    <main role="main">
    	<section class="section section-mobile ">
			<div class="container-fluid center">
			    <div class="pagina">
				    <div class="pdc-card-pagina  d-flex">
                        <div id="pdc_main" class="pdcdetsidebar m-auto col-12 col-xl-4 " >
                            <div class="card border shadow h-100">
                                <div class="card-header">
                                    <div class="d-flex"><div><a href="../index.php"><i class="fas fa-arrow-left fa-fw larger"></i></a></div><div class="m-auto" id="titleTemplate">Pre Departure Check</div></div>
                                </div>
                                <div class="card-body pdc-pagina ">
                                    <div class="row">
                                        <div class="col-12 ">
                                            <div class='card shadow-sm mb-3'>
                                                <div class="card-title"><i class="fad fa-truck fa-fw"></i> vehicle</div>
                                                <div class="card-body py-0 " id="selectedVehicle"></div>
                                                <div class="card-body pt-0 " id="selectedVehicleFooter"></div>
                                            </div>
                                            <div class='card shadow-sm mb-3'>
                                                <div class="card-title "><i class="fad fa-trailer fa-fw"></i> trailer</div>
                                                <div class="card-body py-0 " id="selectedTrailer"></div>
                                                <div class="card-body pt-0" id="selectedTrailerFooter"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-lg btn-primary col-12" id="StartCheck" onclick="StartPDC();" ><i class="fad fa-list fa-fw"></i> Start Check</button>
                                </div>
                            </div>
                        <!--           <div class="col-12 bg-white" id="pdc_check"></div>-->
                        </div>
                        <div id="pdc_details"  class="pdcdetsidebar col-12 col-xl-4 hide"></div>
                        <div id="pdc_details2" class="pdcdetsidebar col-12 col-xl-4 hide"></div>
                        <div id="pdc_details3" class="pdcdetsidebar col-12 col-xl-4 hide" ></div>
                    </div>
                </div>
			</div>
		</section>
	</main>

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; ?>
<script src="<?=$us_url_root?>js/driver.js"></script>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script>new SlimSelect({select: '#groups'});	</script>
<script>
    window.onload=function(){
        closepdcDet();
        loadPDCtemplate('<?=$id?>');
    }
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php';  ?>
 