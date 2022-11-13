<?php
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 

if (!securePage($_SERVER['PHP_SELF'])){die();} 

//PHP Goes Here!
$errors = [];
$successes = [];

$reportId = Input::get('id');
$validation = new Validate();
//Check if selected user exists
//if(!reportIdExists($reportId)){
//  Redirect::to("report_scheduler.php"); die("Id is not known");
//}

$db = DB::getInstance();
$query =$db->query("SELECT *,users.lname,users.fname FROM report_planning LEFT JOIN users ON report_planning.creator=users.id WHERE report_planning.id=".$reportId);
$reportdetails =$query->first();
$groupQ = $db->query("SELECT * FROM CUSTOMERS  ORDER BY name ");
$customers = $groupQ->results();
$reportsQ = $db->query("SELECT * FROM reporting ORDER BY name");
$reports = $reportsQ->results();
$schedulesQ = $db->query("SELECT * FROM reporting_schedules ORDER BY name");
$schedules = $schedulesQ->results();

$views =['Day','Week','Month'];


//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
		die('Token doesn\'t match!');
    } else 
	{
		$form_valid=FALSE; // assume the worst
		$validation = new Validate();
		$validation->check($_POST,array(
			'name' => array('display' => 'Reportname','required' => true,'min' => 2,'max' => 128	),
			'description' => array('display' => 'Description','required' => false,'min' => 2,'max' => 128 ),
			'customer' => array('display' => 'Customer','required' => true ),
		));
		if($validation->passed()) {
			if(isset($_POST['status'])) {$status =1;} else {$status =0;}
			$form_valid=TRUE;
			try {
				// echo "Trying to update reporting";
				$fields=array(
				'name' => Input::get('name'),
				'description' => Input::get('description'),
				'report_type' => Input::get('report_type'),
				'reporting_frequency' => Input::get('reporting_frequency'),
				'reporting_period' => Input::get('reporting_period'),
				'reporting_view' => Input::get('reporting_view'),
				'nextRunDateTime' => Input::get('nextRunDateTime'),
				'customer' => Input::get('customer'),
				'status' => $status);
				$db->update('report_planning',$reportId,$fields);
				$successes[] = "Report updated";
                $query =$db->query("SELECT report_planning.*,users.lname,users.fname FROM report_planning LEFT JOIN users ON report_planning.creator=users.id WHERE report_planning.id=".$reportId);
                $reportdetails =$query->first();
			}
			catch (Exception $e) {
				die($e->getMessage());
			}
			$db = DB::getInstance();
		}
	}
}
?>
	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina ">
				    <div class='inner-pagina'>
                        <div class="page-title ">Report - Report scheduler - Edit details </div>
                        <div class="col-12 ">
                            <div class="row">
                                <div class="col-12 col-md-10 col-xl-10 p-0">
                                <form class="card form-signup"  action="" method="POST" id="payment-form">
                                    <div class="card-header d-flex">
                                        <div class="info-box-number text-primary large col p-0"><?php echo ucfirst($reportdetails->name);?></div>
                                        <div class="col-2 gray ml-auto">
                                            <div class="row">
                                                <div class="small text-right col-12 p-0" ><?php echo ucfirst($reportdetails->lname).', '.ucfirst($reportdetails->fname);?> <i class="fad fa-user" aria-hidden="true"></i> </div>
                                                <div class="small text-right col-12 p-0"><?php echo ucfirst($reportdetails->createdDateTime);?> <i class="fad fa-calendar" aria-hidden="true"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row p-0 mb-2">
                                            <div class="col">
                                                <div class="row p-0 mb-2">
                                                <div class="col-4 ">
                                                    <label>Report name</label>
                                                    <input class="form-control" type="text" name="name" id="name" placeholder="name" value="<?php echo ucfirst($reportdetails->name);?>" required autofocus>
                                                </div>
                                                <div class="col-2 col-lg-2 text-right ml-auto ">
                                                    <label>report active</label>
                                                    <div class="custom-control custom-switch custom-switch-sm">
                                                        <input type='checkbox' class="custom-control-input" id='status' name='status' <?php echo ($reportdetails->status==1 ? 'checked' : '');?>   >
                                                        <label class="custom-control-label" for="status"></label>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-8 ">
                                                    <label>Description</label>
                                                    <input class="form-control" type="text" name="description" id="description" placeholder="Description" value="<?php echo ucfirst($reportdetails->description);?>" >
                                                </div>
                                            </div>
                                            <div class="row p-0 mb-2">

                                                <div class="col-6 col-lg-4 ">
                                                    <label>Group</label>
                                                    <select id="customer" class="form-control" name="customer"  >
                                                    <?php foreach ($Group as $row){
                                                        if ($row->accountnumber==ucfirst($reportdetails->customer)){ ?>
                                                        <option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>" selected > <?php echo ucfirst($row->name);?></option>
                                                    <?php } else {?>?>
                                                        <option class="DeActiveGroup" value="<?php echo ucfirst($row->accountnumber);?>"  > <?php echo ucfirst($row->name);?></option>
                                                        <?php }?>
                                                    <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row p-0 mb-2">
                                                <div class="col-md-8">
                                                    <div class="row p-0">
                                                        <div class="col-6 ">
                                                            <label>Report type</label>
                                                            <select id="report" class="form-control " name="report_type" value="<?php echo ucfirst($reportdetails->report_type);?>" >
                                                        <?php foreach ($reports as $rowr){
                                                            if ($rowr->id==ucfirst($reportdetails->report_type)){ ?>
                                                            <option class="DeActiveGroup" value="<?php echo ucfirst($rowr->id);?>" selected > <?php echo ucfirst($rowr->name);?></option>
                                                            <?php } else {?>
                                                            <option class="DeActiveGroup" value="<?php echo ucfirst($rowr->id);?>"  > <?php echo ucfirst($rowr->name);?></option>
                                                            <?php }?>
                                                        <?php }?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row p-0 mb-2">
                                                        <div class="col-4 ">
                                                            <label>Reporting frequency</label>
                                                            <select id="reportf" class="form-control " name="reporting_frequency" value="<?php echo ucfirst($reportdetails->reporting_frequency);?>" >
                                                            <?php foreach ($schedules as $rowf){
                                                            if ( ucfirst($rowf->count).' '.ucfirst($rowf->period)==ucfirst($reportdetails->reporting_frequency)){ ?>
                                                                <option class="DeActiveGroup" value="<?php echo ucfirst($rowf->count).' '.ucfirst($rowf->period);?>" selected > <?php echo ucfirst($rowf->name);?></option>
                                                            <?php } else {?>
                                                                <option class="DeActiveGroup" value="<?php echo ucfirst($rowf->count).' '.ucfirst($rowf->period);?>"  > <?php echo ucfirst($rowf->name);?></option>
                                                            <?php }?>
                                                        <?php }?>
                                                            </select>
                                                        </div>
                                                        <div class="col-4 ">
                                                            <label>Reporting period</label>
                                                            <select id="reportp" class="form-control " name="reporting_period" value="<?php echo ucfirst($reportdetails->reporting_period);?>" >
                                                            <?php foreach ($schedules as $rowp){
                                                            if (ucfirst($rowp->count).' '.ucfirst($rowp->period)==ucfirst($reportdetails->reporting_period)){ ?>
                                                                <option class="DeActiveGroup" value="<?php echo ucfirst($rowp->count).' '.ucfirst($rowp->period);?>" selected  > <?php echo ucfirst($rowp->name);?></option>
                                                            <?php } else {?>
                                                                <option class="DeActiveGroup" value="<?php echo ucfirst($rowp->count).' '.ucfirst($rowp->period);?>"><?php echo ucfirst($rowp->name);?></option>
                                                            <?php }?>
                                                        <?php }?>
                                                            </select>
                                                        </div>
                                                        <div class="col-4 ">
                                                            <label>Reporting view</label>
                                                            <select id="reportv" class="form-control " name="reporting_view" value="<?php echo ucfirst($reportdetails->reporting_view);?>" >
                                                            <?php foreach ($views as $rowv){
                                                            if ($reportdetails->reporting_view==$rowv){ ?>
                                                                <option class="DeActiveGroup" value="<?php echo $rowv;?>" selected  > <?php echo $rowv;?></option>
                                                            <?php } else {?>
                                                                <option class="DeActiveGroup" value="<?php echo $rowv;?>" > <?php echo $rowv;?></option>
                                                            <?php }?>
                                                        <?php }?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row p-0 mb-2">
                                                        <div class="col-6">
                                                            <label>Start date report</label>
                                                            <div class='input-group date ' id='datetimepicker1'>
                                                                <input type='text' class="form-control input-group text-left" value="<?php echo ucfirst($reportdetails->nextRunDateTime);?>" name="nextRunDateTime" id='nextRunDateTime' required />
                                                                <span class="input-group-addon">
                                                                    <span class="far fa-calendar-alt btn btn-primary"></span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if (checkMenu(2,$user->data()->id)){  //Links for permission level 2 (default admin) ?>
                                                <div class="col-md-3 col-lg-2 card m-3 d-none d-md-block text-right ml-auto">
                                                    <div class="row card-header h-100 p-0" >
                                                        <div class="col-12 ">
                                                            <label>created date </label>
                                                            <div class="small"><?php echo ucfirst($reportdetails->createdDateTime);?></div>
                                                        </div>
                                                        <div class="col-12 ">
                                                            <label>last execute date </label>
                                                            <div class="small" ><?php echo ucfirst($reportdetails->changedDateTime);?></div>
                                                        </div>
                                                        <div class="col-12">
                                                            <label>next execute date </label>
                                                            <div class="small" ><?php echo ucfirst($reportdetails->nextRunDateTime);?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </div>
                                            <div class="row p-0">
                                                <div class="col">
                                                    <div class="col-6 p-0 ml-auto">	<?=resultBlock($errors,$successes);?><?=$validation->display_errors();?></div>
                                                </div>
                                            </div>
                                            <div class="row pt-4">
                                                <div class="col">
                                                    <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                                                    <input class="btn btn-primary " type='submit' name='changeReport' value="Update / Save" />
                                                    <a class="btn btn-secondary mx-2" href="report_scheduler.php">cancel / return</a>
                                                </div>
                                            </div>
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
<script>
	new SlimSelect({ select: '#reportp'});
	new SlimSelect({ select: '#reportf'});
	new SlimSelect({ select: '#report'})
	new SlimSelect({ select: '#reportv'});
	new SlimSelect({ select: '#status'});
	new SlimSelect({ select: '#customergroup'});
</script>
<script src="<?=$us_url_root?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
