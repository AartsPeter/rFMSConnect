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
				<div class="pagina p-1">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-xl-7 p-3">
                                <div class="card border-0 mb-3">
                                    <div class="card-header d-flex"><div class="h5"><i class="fad fa-tasks "></i><b> API-Scheduler</b></div> <div class="ml-auto" id="DaemonStatus"></div> <div class=" text-right mx-2 fa fa-fw" id="result">...</div></div>
                                    <div class="card-body" >
                                        <div class="row" id="APIScheduler"></div>
                                    </div>
                                </div>
                                <div class="card border-0 mb-3">
                                    <div class="card-header h5"><i class="fad fa-clipboard-list"></i> System Scheduler</div>
                                    <div class="card-body" >
                                        <div class="row " id="SystemScheduler"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="LoggingBar" class="col d-md-block  p-3 ">
                                <div class="card border-0 mb-3">
                                    <div class="card-header d-flex "><div class="h5"><i class="fad fa-clipboard "></i><b> Logging</b></div> <div class="text-right" id="resultlog"></div></div>
                                    <div class="card-body pb-5" id="logging"><table class="table display responsive noWrap text-secondary smalltable" id="tableLogging"></table></div>
                                </div>
                                <div class="card border-0">
                                    <div class="card-header d-flex"><div class="h5"><i class="fad fa-monitor-heart-rate"></i><b> Status Interface</b></div> <div class="ml-auto text-right" id="resultm"></div></div>
                                    <div class="card-body p-0" id="monitoring"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </section>
    </main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final page html footer copyright row + the external js calls ?>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script src="<?=$us_url_root?>js/rfms_api.min.js"></script>
<script src="<?=$us_url_root?>js/monitoring2.js"></script>
<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>

    <script>
    window.onload=function(){
       HideNavBarGroup();
       ReadSchedule();
       ReadLogging();
       setInterval(function(){ ReadSchedule();},5000);
       setInterval(function(){ ReadLogging(); CheckDaemon(); },20000);

    }
    </script>

</body>
</html>