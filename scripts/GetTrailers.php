<?php 
require_once '../users/init.php'; 
if (!securePage($_SERVER['PHP_SELF'])){die();} 

//**********************************************************************
// file used with '?customer=' parameter 
// file will return a array to show within 'rfms.js'
// Author : Peter Aarts QNH 2017
// License free 
//**********************************************************************
//header('Content-Type: text/plain');

	header("Content-Type: text/html; charset=utf-8mb4");
	ini_set("default_charset","UTF-8");
	mb_internal_encoding("UTF-8");
	iconv_set_encoding("internal_encoding","UTF-8");
	iconv_set_encoding("output_encoding","UTF-8");

	header("Content-Type: text/html; charset=utf-8");
	if (isset($_SESSION['UGtext'])){$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];}
	else {
		if ($user->data()->cust_id!='0'){$SCNumber=$user->data()->cust_id;$SCName=array_search($user->data()->cust_id, array_column($Group, 'name'));;}
		else {$SCName='All Groups';$SCNumber='*';}
	}	
	if ($user->data()->cust_id=='0'){
		$str2="";$str3="";} 
	else {
		$str2="and rel_cust_user.User_ID='".$user->data()->id."'";
		$str3="INNER JOIN REL_CUST_USER ON rel_cust_user.Cust_ID=famreport.client";}
	if ($SCNumber=='*')	{
		$str1="";} 
	else {
		$str1="and famreport.client='".$SCNumber."'";}

	$db = DB::getInstance();
	$query="
			SELECT trailers.*,vehicles.customerVehicleName,vehicles.licenseplate As VehicleLicensePlate,
			(SELECT count(trailer) FROM pdc_damage WHERE pdc_damage.trailer = trailers.vin AND pdc_damage.repairStatus=0) AS `DamageCount`	
			FROM trailers 
				LEFT JOIN vehicles on vehicles.vin=trailers.vehicleVIN
				LEFT JOIN FAMReport ON FAMReport.vin=trailers.vehicleVIN ".$str3."
			WHERE vehicles.vehicleActive=true ".$str1." ".$str2;
			$Q = $db->query($query);
	$Trailers = $Q->results();		
	echo json_encode($Trailers);
	if(isset($_GET['SQL'])) {echo "<BR><BR>".$query;}	
?>
	