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
                        <div class="mr-auto page-title">Reports - DriveTime Management<small><small> showing driver tachograph usage BI-weekly </small></small></div>
                        <div class="row">
                                <div class="form-group col-xl-2 col-md-4  col-12">
                                    <select id="drivers" class="form-control col-12 no-padding"  name="SGDrivers" value="" onChange="SaveSelectedReportDate();GetDriveTime();">
                                    <?php $selected='selected';
                                    foreach ($Driver as $row){ ?>
                                        <option value="<?=$row->DriverId?>" <?=$selected?> > <?=$row->Driver?></option>}
                                        <?php if ($selected=='selected'){ $selected='';} ?>
                                    <?php }?>
                                    </select>
                                </div>
                                <div class="form-group col-xl-2 col-lg-4 col-6">
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input type='text' class="form-control input-group text-left " value="<?=$SD?>" id='SelectDate' />
                                        <span class="input-group-addon btn text-primary"><i class="fad fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                                <div class="form-group col-xl-2 col-lg-4 col-6">
                                    <div class='input-group date ' id='datetimepicker2'>
                                        <input type='text' class="form-control input-group" value="<?=$ED?>" id='SelectDateEnd' />
                                        <span class="input-group-addon btn text-primary">
                                            <span class="fad fa-calendar-alt "></span>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-xl-2 col-md-4 col-6">
                                    <input type="button" class="btn btn-primary col-12" value="Search" onclick="SaveSelectedReportDate();GetDriveTime();"/>
                                </div>
                                <div class="form-group col-xl-2 col-lg-4 col-6 ">
                                    <div class="message col-12 text-right" id="TripReport"></div>
                                </div>
                                <?php if ($_SESSION['UGselected']!='*'){ ?>
                                <div class="form-group col-xl-2 col-lg-4 col-6 ml-auto">
                                    <div class="dropdown ">
                                        <button type="button" class="btn btn-secondary btn-blocky dropdown-toggle shadow-sm col-12" data-toggle="dropdown"> <i class='far fa-envelope'></i> - report  </button>
                                      <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" onclick="MailTripReport();" href="#">Now</a>
                                        <a class="dropdown-item" onclick="MailTripSchedule();" href="#">Schedule</a>
                                      </div>
                                    </div>
                                </div>
                                <?php }?>

                        </div>
                        <div class="row col-6 pt-2" id="SumReport"></div>
                        <div class="row" id="content">
                            <div class="col-xl-6 col-12" id="DriveTimeDiv">
                                <div class="card  DriveTimeList">
                                    <div class="card-header">DriveTime activity</div>
                                    <div class="card-body">
                                        <table id="DriveTimeTable" class="display tabel no-wrap" width="100%"></table>
                                    </div>
                                </div>
                            </div>
                            <div  class="col-xl-6 col-12">
                                <div class="card mb-3">
                                    <div class="card-header">Tachograph </div>
                                    <div  class="col-12" id="DriveTimeSummary"><div class="m-auto">select a row for detailed information</div></div>
                                    <div  class="col-12 px-3" id="DriveTimeGraph"></div>

                                </div>
                                <div class="card">
                                    <div class="card-header">Trips</div>
                                    <div class="card-body">
                                        <table id="tableDriverTrips" class="display table noWrap " width="100%"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</section>
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; ?>
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts-more.src.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/modules/xrange.src.js"></script>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
	<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script>
		document.getElementById("drivers").focus();
		new SlimSelect({select: '#drivers'	});
		window.onload=function(){
            SelectDrivers();
            setTimeout(function(){ GetDriveTime();},1000);
            setInterval(function(){ SelectDrivers();},120000);
		};

	</script>
	

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php';  ?>