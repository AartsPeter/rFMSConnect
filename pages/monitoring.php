<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';

if (!securePage($_SERVER['PHP_SELF'])){die();} ?>

	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">	
					<div class="inner-pagina">	
                        <div class="col-12 page-title ">Tools - Monitoring interfaces </div>
                        <div class="col-12 pt-2">
                            <div class="row">
                                <div class="form-group col-md-4 col-xl-2 col-12 ">
                                    <select id="SelectHours" class="col-12 form-control" name="SelectedG" onchange="LoadMonitorData();" title="uren voor geslecteerde einddatum" >
                                        <option value="1"  >1 hour</option>
                                        <option value="2"  >2 hours</option>
                                        <option value="4" selected >4 hours</option>
                                        <option value="6"  >6 hours</option>
                                        <option value="12">12 hours</option>
                                        <option value="24">24 hours</option>
                                        <option value="48">48 hours</option>
                                        <option value="168">1 week</option>
                                        <option value="336">2 weeks</option>
                                        <option value="672">4 weeks</option>
                                    </select>
                                </div>
                                <div class="form-group col-xl-2 col-lg-4 col-6">
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input type='text' class="form-control input-group text-left" value="<?php echo date('Y-m-d H:i',strtotime('now'))?>" id='SelectDate' />
                                        <span class="input-group-addon btn btn-primary">
                                            <span class="fad fa-calendar-alt"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-xl-1 col-md-2 col-6">
                                    <button class="btn btn-primary btn-block" value="Search" onclick="LoadMonitorData();">Search </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
				            <div class="nav-tabs-custom"  id="MonitorBuilder"></div>
				        </div>
				    </div>
				</div>
	        </div>
		</section>
    </main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>
<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?=$us_url_root?>js/monitoring2.js"></script>
<script>
	window.onload=function(){
		//ReadPerformanceLog(2);
		ShowMonitor();
        HideNavBarGroup();
		setInterval(ShowMonitor(),120000);
		new SlimSelect({select: '#SelectHours',searchFocus: false,showSearch: false});
	}
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>