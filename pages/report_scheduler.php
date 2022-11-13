<?php 
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 
if (!securePage($_SERVER['PHP_SELF'])){die();} 
if($user->isLoggedIn()) { $thisUserID = $user->data()->id;} else {Redirect::to("../users/login.php"); die(); }

if (isset($_SESSION['UGtext'])){$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];}
else {
	if ($user->data()->cust_id!='0'){$SCNumber=$user->data()->cust_id;$SCName=array_search($user->data()->cust_id, array_column($Group, 'name'));;}
	else {$SCName='All Groups';$SCNumber='*';}
}

$reportsQ = $db->query("SELECT * FROM reporting ORDER BY name");
$reports = $reportsQ->results();
$schedulesQ = $db->query("SELECT * FROM reporting_schedules ORDER BY count");
$schedules = $schedulesQ->results();

//Forms posted
if (!empty($_POST)) {
		$token = $_POST['csrf'];
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	} else {		
		if(!empty($_POST['addReport'])) {
			$join_date = date("Y-m-d H:i:s");
			$form_valid=FALSE; // assume the worst
			$validation = new Validate();
			$validation->check($_POST,array(
				'name' => array('display' => 'Reportname','required' => true,'min' => $settings->min_un,'max' => $settings->max_un),
				'description' => array('display' => 'Description','required' => true),
				'customer' => array('display' => 'Group','required' => true),
			));
			if($validation->passed()) {
				$form_valid=TRUE;
				try {
					$fields=array(
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'report_type' => Input::get('report_type'),
					'reporting_frequency' => Input::get('reporting_frequency'),
					'reporting_period' => Input::get('reporting_period'),
					'nextRunDateTime' => Input::get('nextRunDateTime'),
					'customer' => Input::get('customer'),
					'status' => 1,
					'creator' => $user->data()->id);
					$db->insert('report_planning',$fields);
					$theNewId=$db->lastId();
					$successes[] = lang("ACCOUNT_REPORT_ADDED");
				} catch (Exception $e) {
				die($e->getMessage());
				}
			}
		}
	//	Redirect::to("report_scheduler.php"); die();
	}
}
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina">
				    <div class="inner-pagina">
                        <div class="d-flex page-title">Reports » Scheduled reports </div>
                        <div class="col-12 card card-body border-0 py-3">
                            <table class="display table noWrap" id="tableReport"></table>
                        </div>
                        <div class="d-flex d-flex pt-3">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#NewReportModal" >Add new Report </button>
                        </div>
                    </div>
				</div>
				<div id="NewReportModal" class="modal fade col-12" role="dialog">
					<div class="modal-dialog modal-lg modal-dialog-centered " role="document">
						<div class="modal-content card">
						    <div class="modal-header">
								<div class="card-header p-0" id="exampleModalLongTitle">New Report</div>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body" id="EditReportDetails">	
								<form class="form-signup" action="report_scheduler" method="POST" id="report_scheduler_form">
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-4">
												<label>name</label>
                                                <input class="form-control" type="text" name="name" id="name"  required autofocus>
                                            </div>
                                            <div class="col-8">
												<label>description</label>
                                                <input class="form-control" type="text" name="description" id="description" placeholder="" value="" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <label>group</label>
											<?php 
												if (count($Group)>1){ 
													echo '<select id="group" class="form-control" name="customer" value="" >';
													echo '	<option class="DeActiveGroup" value="0" selected>Select a group</option>';
													foreach ($Group as $row){
														if ($row->accountnumber==$SCNumber){ echo '<option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>" selected > <?php echo ucfirst($row->name);?></option>';} 
														else {								echo '<option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>" > <?php echo ucfirst($row->name);?></option>'; }
													} 
													echo '</select>';
												} 
												else {
													echo '<div class="form-control-ro bold" >'.$Group[0]->name. '</div>';
												}
											?>
                                            </div>
                                            <div class="col-6 ">
                                                <label>report type</label>
                                                <select id="report" class="form-control " name="report_type" value="" >
                                                    <option class="DeActiveGroup" value="0" selected disabled>Select a report</option>
                                                <?php foreach ($reports as $rowr){?>
                                                    <option class="DeActiveGroup" value="<?php echo ucfirst($rowr->id);?>"  > <?php echo ucfirst($rowr->name);?></option>
                                                <?php }?>
                                                </select>
                                            </div>
                                            <div class="col-6 pt-2">
                                                <label>create report </label>
                                                <select id="reportf" class="form-control " name="reporting_frequency" value="" >
                                                    <option class="DeActiveGroup" value="0" selected>Select a frequency</option>
                                                <?php foreach ($schedules as $rowf){?>
                                                    <option class="DeActiveGroup" value="<?php echo ucfirst($rowf->count).' '.ucfirst($rowf->period);?>" > <?php echo ucfirst($rowf->name);?></option>
                                                <?php }?>
                                                </select>
                                            </div>
                                            <div class="col-6 pt-2">
                                                <label>reporting period</label>
                                                <select id="reportp" class="form-control " name="reporting_period" value="" >
                                                    <option class="DeActiveGroup" value="0" selected>Select a period</option>
                                                <?php foreach ($schedules as $rowp){?>
                                                    <option class="DeActiveGroup" value="<?php echo ucfirst($rowp->count).' '.ucfirst($rowp->period);?>"> <?php echo ucfirst($rowp->name);?></option>
                                                <?php }?>
                                                </select>
                                            </div>
                                            <div class="col-4 pt-2 pb-3">
                                                <label>start date</label>
                                                <div class='input-group date ' id='datetimepicker1'>
                                                    <input type='text' class="form-control input-group text-left" value="<?php echo date('Y/m/d',strtotime('+1 days'));?>" name="nextRunDateTime" id='nextRunDateTime' required />
                                                    <span class="input-group-addon btn text-primary">
                                                        <span class="fad fa-calendar-alt"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer ">
                                    <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                                    <input class='btn btn-primary pull-right' type='submit' name='addReport' value="Create" />

                                </div>
                            </form>

						</div>
					</div>
				</div>

			</div>
		</section>
	</main>
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
<script src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>
<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script>
    new SlimSelect({ select: '#reportp'});
    new SlimSelect({ select: '#reportf'});
  //  new SlimSelect({ select: '#group'});
    new SlimSelect({ select: '#report'});
    window.onload=function(){ ShowReportTable(); 	};
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
