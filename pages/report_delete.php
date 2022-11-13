<?php
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 

$validation = new Validate();
//PHP Goes Here!
$errors = [];
$successes = [];
$reportId = Input::get('id');
//Check if selected report exists
if(!IdExists($reportId,'report_planning')){
  Redirect::to("report_scheduler.php"); die();
}
if(!empty($_POST)) {
	$token = $_POST['csrf'];
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	} else {			
		if (!empty($_POST['delete'])){
			$db = DB::getInstance();
			$query="DELETE FROM report_planning WHERE creator='". ucfirst($user->data()->id)."'  AND id ='".$reportId."'";
			echo $query;
			$query1 = $db->query($query);
		}
			Redirect::to("report_scheduler.php"); die();
	} 
}
$db = DB::getInstance();
$query =$db->query("SELECT *,users.lname,users.fname FROM report_planning LEFT JOIN users ON report_planning.creator=users.id WHERE report_planning.id=".$reportId);
$reportdetails =$query->first();
$groupQ = $db->query("SELECT * FROM CUSTOMERS  ORDER BY name ");
$customers = $groupQ->results();
$reportsQ = $db->query("SELECT * FROM reporting ORDER BY name");
$reports = $reportsQ->results();
$schedulesQ = $db->query("SELECT * FROM reporting_schedules ORDER BY name");
$schedules = $schedulesQ->results();

?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="page-title pb-2">Report - Reports scheduler - details </div>
					<div class="col-12">
						<div class="row">
							<form class="form-signup col-10" action="" method="POST" id="payment-form">
								<div class="page-title">Update report</div>
								<div class="card-body p-3">
									<div class="row pt-3">
										<div class="col-6 col-lg-3 p-1">
											<label>Report name</label>
											<input class="form-control" type="text" name="name" id="name" placeholder="name" value="<?php echo ucfirst($reportdetails->name);?>" required autofocus>
										</div>
										<div class="col-6 col-lg-3 p-1">
											<label>Description</label>
											<input class="form-control" type="text" name="description" id="description" placeholder="Description" value="<?php echo ucfirst($reportdetails->description);?>" >
										</div>
										<div class="col-6 col-lg-3 p-1 ">
											<label>Group</label>
											<select id="group" class="form-control" name="customer" value="<?php echo ucfirst($reportdetails->customer);?>" >
											<?php foreach ($Group as $row){
												if ($row->accountnumber==ucfirst($reportdetails->customer)){ ?>
												<option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>" selected > <?php echo ucfirst($row->name);?></option>	
											<?php } else {?>?>
												<option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>"  > <?php echo ucfirst($row->name);?></option>	
												<?php }?>
											<?php }?> 
											</select>	
										</div>
										<div class="col-2 col-lg-2 p-1 ml-auto ">
											<label>report active</label>
											<select id="status" class="form-control" name="status" >
												<?php if ($reportdetails->status=='0'){ ?>
												<option class="DeActiveGroup" value="0" selected >no</option>
												<option class="DeActiveGroup" value="1" >YES</option>
												<?php } else {?>
												<option class="DeActiveGroup" value="0"  >no</option>
												<option class="DeActiveGroup" value="1" selected >YES</option>
												<?php } ?>
											</select>	
										</div>
									</div>
									<div class="row mt-4 ">
										<span class="p-1 "><b>Reporting settings</b></span>
									</div>
									<div class="row border py-3 mb-5 ">
										<div class="col-6 col-lg-3 pt-2">
											<label>Report type</label>
											<select id="report" class="form-control " name="report_type" value="<?php echo ucfirst($reportdetails->report_type);?>" >
									  <?php foreach ($reports as $rowr){
											if ($row->report_type==ucfirst($reportdetails->report_type)){ ?>
											<option class="DeActiveGroup" value="<?php echo ucfirst($rowr->id);?>" selected > <?php echo ucfirst($rowr->name);?></option>	
											<?php } else {?>
											<option class="DeActiveGroup" value="<?php echo ucfirst($rowr->id);?>"  > <?php echo ucfirst($rowr->name);?></option>	
											<?php }?> 												
										<?php }?> 
											</select>
										</div>	
										<div class="col-6 col-lg-3 pt-2">
											<label>Reporting frequency</label>
											<select id="reportf" class="form-control " name="reporting_frequency" value="<?php echo ucfirst($reportdetails->reporting_frequency);?>" >
											<?php foreach ($schedules as $rowf){
											if ($row->reporting_frequency==ucfirst($reportdetails->reporting_frequency)){ ?>
												<option class="DeActiveGroup" value="<?php echo ucfirst($rowf->name);?>" selected > <?php echo ucfirst($rowf->name);?></option>	
											<?php } else {?>
												<option class="DeActiveGroup" value="<?php echo ucfirst($rowf->name);?>"  > <?php echo ucfirst($rowf->name);?></option>	
											<?php }?> 												
										<?php }?> 
											</select>
										</div>	
										<div class="col-6 col-lg-3 pt-2">
											<label>Reporting period</label>
											<select id="reportp" class="form-control " name="reporting_period" value="<?php echo ucfirst($reportdetails->reporting_period);?>" >
											<?php foreach ($schedules as $rowp){
											if ($row->reporting_period==ucfirst($reportdetails->reporting_period)){ ?>
												<option class="DeActiveGroup" value="<?php echo ucfirst($rowp->name);?>" selected  > <?php echo ucfirst($rowp->name);?></option>	
											<?php } else {?>
												<option class="DeActiveGroup" value="<?php echo ucfirst($rowp->name);?>"  > <?php echo ucfirst($rowp->name);?></option>	
											<?php }?> 												
										<?php }?> 
											</select>
										</div>	
										<div class="col-6 col-lg-3 pt-2">
											<label>Start date report</label>
											<div class='input-group date ' id='datetimepicker11'>
												<input type='text' class="form-control input-group text-left" value="<?php echo date('Y/m/d',strtotime('+1 days'));?>" name="nextRunDateTime" id='nextRunDateTime' required />
												<span class="input-group-addon">
													<span class="far fa-calendar-alt"></span>
												</span>			
											</div>
										</div>
									</div>									
									<div class="row">
										<a class="btn btn-secondary mx-2" href="report_scheduler.php">Cancel / return to overview</a>
									</div>										
								</div>	
							</form>													
						</div>
					</div>
				</div>
				<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">Delete Report ?</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-footer">
								<form class="form" name='delete' action='report_delete.php?id=<?=$reportId?>' method='post'>	
									<input type="hidden" name="csrf" value="<?=Token::generate();?>" />
									<input class="btn btn-primary submit mr-auto" type="submit" name="delete" value='Yes - Delete' />
									<a class="btn btn-secondary mx-2" href="report_scheduler.php">Cancel / return to overview</a>									
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#DeleteModal').modal('show');
    });
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
