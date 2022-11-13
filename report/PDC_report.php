
<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
if (!securePage($_SERVER['PHP_SELF'])){die();} 
$startdate=date('Y/m/d',strtotime(date('Y/m/1')));
$enddate=date('Y/m/d ', strtotime("+1 day"));
$calc=date("d");
if ($calc<13){
	$startdate=date("Y/m/j", strtotime("-14 days"));
}
?>
	<main role="main"  >
		<section class="section section-full">
			<div class="container-fluid">
				<div class="pagina">
					<div class="inner-pagina">
						<div class="mr-auto page-title">Reports - Pre-Departure Checks </div>
						<div class="row ">
                            <div class="form-group col-xl-2 col-lg-4 col-6">
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' class="form-control input-group text-left " value="<?=$SD?>" id='SelectDate' />
                                    <span class="input-group-addon btn text-primary">
                                        <span class="fad fa-calendar-alt"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-xl-2 col-lg-4 col-6">
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' class="form-control input-group" value="<?=$ED?>" id='SelectDateEnd' />
                                    <span class="input-group-addon btn text-primary">
                                        <span class="fad fa-calendar-alt"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-xl-2 col-lg-4 col-6">
                                <input type="button" class="btn btn-primary col-12 " value="Search" onclick="SaveSelectedReportDate();ShowPDCTable();" />
                            </div>
                            <?php if ($_SESSION['UGselected']!='*'){ ?>
                            <div class="form-group col-xl-2 col-lg-4 col-6">
                                <div class="dropdown ">
                                    <button type="button" class="col-12 btn btn-secondary dropdown-toggle " data-toggle="dropdown"> <i class='far fa-envelope'></i> - report  </button>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" onclick="CreateReport('trips',false);" href="#">Now</a>
                                    <a class="dropdown-item" onclick="CreateReport('trips',true);" href="#">Schedule</a>
                                  </div>
                                </div>
                            </div>
                            <?php }?>
                            <div class="form-group col-xl-2 col-lg-4 col-6 ml-auto">
                                <div class="message col-12 text-right" id="TripReport"></div>
                            </div>

						</div>
						<div class="col-12" id="TripSumReport"></div>
						<div class="col-12 card border-0 card-body ">
							<div class="tableVehicles p-0" >
								<table class="display table noWrap" id="tablePDC"></table>
							</div>
						</div>
					</div>
				</div>			
			</div>
		</section>
	</main>
	<div class="modal fade modal-center" id="PDFReport" tabindex="-1" role="dialog" aria-labelledby="modal_PDFReport" aria-hidden="false">
		<div class="modal-dialog modal-xl">
			<div class="modal-content ">
				<div class="modal-header card-header" id="modal_PDFReport">Your PDC-Report
					<span id="modalCreatePDFButton"></span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body VehicleList  "  id="Show_PDF_report"></div>
			</div>
		</div>
	</div>	
	<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
	<script src="<?=$us_url_root?>js/rfms_report.js"></script>	
	<script src="<?=$us_url_root?>plugins/highcharts/js/highcharts.src.js"></script>
	<script src="<?=$us_url_root?>plugins/highcharts/modules/timeline.js"></script>
	<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
	<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
	window.onload=function(){
		ShowPDCTable();
	};
	</script>


<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
