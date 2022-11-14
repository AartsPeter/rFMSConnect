<?php
require_once '../users/init.php';
require_once('../plugins/PHPMailer/class.phpmailer.php');
require_once('../plugins/PHPMailer/class.smtp.php');
include ('config/sqlreporter.php');

if (!securePage($_SERVER['PHP_SELF'])){die();}

if($user->isLoggedIn()) { $thisUserID = $user->data()->id;} else { die(); }

if (isset($_SESSION['UGtext'])){$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];}
else {
	if ($user->data()->cust_id!='0'){$SCNumber=$user->data()->cust_id;$SCName=array_search($user->data()->cust_id, array_column($Group, 'name'));;}
	else {$SCName='All Groups';$SCNumber='';}
}
$db = DB::getInstance();
$customer=$_SESSION['UGselected'];
if ($customer=='*') {
	$CustSelect="";}
else {$CustSelect=" FAMREPORT.CLIENT='".$customer."' AND";}								// selected 'all groups' or a specific customer?\
if ($user->data()->cust_id=='0'){
	$CustRel="";}
else {
	$CustRel="	INNER JOIN REL_CUST_USER ON rel_cust_user.Cust_ID=FAMREPORT.CLIENT ";
	$CustSelect ="rel_cust_user.User_ID='".$user->data()->id."' AND"; 	// are you a superuser?\
}
if ($_GET["Schedule"]=='true'){
	$today=new DateTime('now');
	$reportday=date('Y-m-d H:i', strtotime('next monday'));
	$today=$today->format('Y-m-d H:i:s');
	$db = DB::getInstance();
	$query99="INSERT INTO report_planning (name,description,creator,customer,report_type,reporting_frequency,reporting_period,startDate,nextRunDateTime,lastRunDateTime) VALUES
			  ('Weekly Vehicle Statusreport','Standard report',".$user->data()->id.",'".$SCNumber."',1,'1 week','1 week','".$today."','".$reportday."','".$today."' )";
	$db->query($query99);
}
$today=new DateTime('now');
$today=$today->format('Y-m-d H:i:s');
$db = DB::getInstance();
$query="INSERT INTO report_planning (name,description,creator,customer,report_type,reporting_frequency,reporting_period,startDate,nextRunDateTime,lastRunDateTime) VALUES
          ('Vehicle Statusreport','Standard report',".$user->data()->id.",'".$SCNumber."',1,'once','1 week','".$today."','".$today."','' )";
$db->query($query);


}

?>

