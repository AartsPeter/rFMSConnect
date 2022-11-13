<?php 
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 
if (!securePage($_SERVER['PHP_SELF'])){alert("No Access granted, reverting to dashboard");die();} 
// if($user->isLoggedIn()) { $thisUserID = $user->data()->id;} else { $thisUserID = 0; }
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
                        <div class="page-title pb-2">Tools - API-Collector </div>
				        <div class="col-12 card card-body border-0 py-3">
                            <div class="row">
                                <div class="col-12 tableAPI" id="ShowMap">
                                    <table class="display table responsive noWrap" id="tableAPI">	</table>
                                </div>
                            </div>
                            <div class="row col-12">
                                <button class="btn btn-primary  mr-1" data-toggle="modal" data-target="#NewReportModal" disabled >Add new API-Collector </button>
                                <button class="btn btn-primary" id="ShowAPIDaemon" >View API-Daemon monitor </button>
                            </div>
                        </div>
                        </div>
        		</div>
				<div id="NewReportModal" class="modal fade col-12" role="dialog">
					<div class="modal-dialog modal-lg modal-dialog-centered " role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">New API Collector</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body" id="EditReportDetails">
								<form class="form-signup" action="api_collector.php" method="POST" id="payment-form">
									<div class="card ">
										<div class="card-body p-3">
											<div class="row pt-3">
												<div class="col-4 p-1">
													<input class="form-control" type="text" name="name" id="name" placeholder="name" value="" required autofocus>
												</div>
												<div class="col-8 p-1">
													<input class="form-control" type="text" name="description" id="description" placeholder="Description" value="" >
												</div>
											</div>
											<div class="row">
												<div class="col-6 p-1 ">
													<label>Group</label>
													<select id="group" class="form-control" name="customer" value="" >
														<option class="DeActiveGroup" value="0" selected>Select a group</option>
												<?php foreach ($Group as $row){
													if ($row->accountnumber==$SCNumber){ ?>
														<option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>" selected > <?php echo ucfirst($row->name);?></option>
												<?php } else {?>
														<option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>" > <?php echo ucfirst($row->name);?></option>
												<?php }?>
													<?php }?>
													</select>
												</div>
											</div>
											<div class="row mt-4 ">
												<span class="p-0 pl-2"><b>Report details</b></span>
											</div>
											<div class="row  border mb-5 shadow-sm">
												<div class="col-6 pt-2">
													<label>Report type</label>
													<select id="report" class="form-control col-11" name="report_type" value="" >
														<option class="DeActiveGroup" value="0" selected disabled>Select a report</option>
													<?php foreach ($reports as $rowr){?>
														<option class="DeActiveGroup" value="<?php echo ucfirst($rowr->id);?>"  > <?php echo ucfirst($rowr->name);?></option>
													<?php }?>
													</select>
												</div>
												<div class="col-3 pt-2">
													<label>Reporting frequency</label>
													<select id="reportf" class="form-control col-11" name="reporting_frequency" value="" >
														<option class="DeActiveGroup" value="0" selected>Select a frequency</option>
													<?php foreach ($schedules as $rowf){?>
														<option class="DeActiveGroup" value="<?php echo ucfirst($rowf->count).' '.ucfirst($rowf->period);?>" > <?php echo ucfirst($rowf->name);?></option>
													<?php }?>
													</select>
												</div>
												<div class="col-3 pt-2">
													<label>Reporting period</label>
													<select id="reportp" class="form-control col-11" name="reporting_period" value="" >
														<option class="DeActiveGroup" value="0" selected>Select a frequency</option>
													<?php foreach ($schedules as $rowp){?>
														<option class="DeActiveGroup" value="<?php echo ucfirst($rowp->count).' '.ucfirst($rowp->period);?>"> <?php echo ucfirst($rowp->name);?></option>
													<?php }?>
													</select>
												</div>
												<div class="col-4 pt-2 pb-3">
													<label>Start date reporting</label>
													<div class='input-group date ' id='datetimepicker1'>
														<input type='text' class="form-control input-group text-left" value="<?php echo date('Y/m/d',strtotime('+1 days'));?>" name="nextRunDateTime" id='nextRunDateTime' required />
														<span class="input-group-addon">
															<span class="far fa-calendar-alt"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row col-12">
											<input type="hidden" value="<?=Token::generate();?>" name="csrf">
											<input class='btn btn-primary pull-right' type='submit' name='addReport' value="Create" />

										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

			</div>	
		</section>	
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script>
	window.onload=function(){
		ShowAPITable();
	};
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>