<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';
//	require_once('../plugins/PHPMailer/class.phpmailer.php');
//	require_once('../plugins/PHPMailer/class.smtp.php');
	//include ('config/sqlreporter.php');

    error_reporting(E_ALL);


	if (!securePage($_SERVER['PHP_SELF'])){header('HTTP/1.0 401 Unauthorized');die();}
	// user must be logged in to the environment
	if($user->isLoggedIn()) { $thisUserID = $user->data()->id;} else { die(); }

	// check the global variables (which vehicles group has been selected?)
	if (isset($_SESSION['UGtext'])){$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];}
	else {
		if ($user->data()->cust_id!='0'){$SCNumber=$user->data()->cust_id;$SCName=array_search($user->data()->cust_id, array_column($Group, 'name'));;}
		else {$SCName='All Groups';$SCNumber='';}
	}

	// Settings the variables
	if( isset ($_GET['type'] ) ) 	    {$RT = test_input($_GET['type']);} 	            else {$RT = "Vehicles";};
	if( isset ($_GET['EndDate'] ) ) 	{$ED = test_input($_GET['EndDate']);} 	        else {$ED = date("Y-m-d");};
	if( isset ($_GET['StartDate'] ) ) 	{$SD = test_input($_GET['StartDate']);} 	    else {$SD = date('Y-m-d',strtotime('last monday',strtotime('-13 day')));};
	if( isset ($_GET['Schedule'] ) ) 	{$Schedule = test_input($_GET['Schedule']);} 	else {$Schedule = false;};
	$today=new DateTime('now');
	$today=$today->format('Y-m-d H:i:s');

	// Get the proper report-id
	$queryRep="SELECT * FROM reporting WHERE name like '%".$RT."%'";
	$Qrep = $db->query($queryRep);
	$rep = $Qrep->results();

	// build the variables for the query using your priveleges
	// Execute the create report query for a recurring report in the planning(scheduler)
	if ($Schedule=='true'){
		$today=new DateTime('now');
		$reportday=date('Y-m-d H:i', strtotime('next monday'));
		$today=$today->format('Y-m-d H:i:s');
		$db = DB::getInstance();
		$query99="INSERT INTO report_planning (name,description,creator,customer,report_type,reporting_frequency,reporting_period,startDate,nextRunDateTime,lastRunDateTime) VALUES
				  ('Weekly ".$rep[0]->description."','Standard report','".$user->data()->id."','".$SCNumber."','".$rep[0]->id."','1 week','1 week','".$today."','".$reportday."','".$today."' )";
		$db->query($query99);
	}
	// Execute the create report query once (always performed with a recurring report to show the result of the selected report
	$db = DB::getInstance();
	$query="INSERT INTO report_planning (name,description,creator,customer,report_type,reporting_frequency,reporting_period,startDate,nextRunDateTime,lastRunDateTime) VALUES
			  ('".$rep[0]->description."','Standard report',".$user->data()->id.",'".$SCNumber."','".$rep[0]->id."','once','1 month','".$today."','".$today."','' )";
	$db->query($query);

    $Result = Array('report-scheduled'=>true, 'report sent'=> true);
	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query99,$Result,'',true);
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}
?>

