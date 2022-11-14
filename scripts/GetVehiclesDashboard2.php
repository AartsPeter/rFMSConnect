<?php 
require_once '../users/init.php'; 
if (!securePage($_SERVER['PHP_SELF'])){die();} 

//**********************************************************************
// file used customername parameter '?customer=xxx'
// file will return a array to show within 'rfms.js'
// Author : Peter Aarts QNH 2017
// License free 
//**********************************************************************
//header('Content-Type: text/plain');
function Check_telltale_warning($arr){
		$warning=0;
		if ($arr->TT_PAR_BRA=='YELLOW' )	{$warning++;}
		if ($arr->TT_FUE_LEV=='YELLOW' )	{$warning++;}
		if ($arr->TT_ENG_COO_TEM=='YELLOW' ){$warning++;}
		if ($arr->TT_ENG_MIL_IND=='YELLOW' ){$warning++;}
		if ($arr->TT_ENG_EMI_FAI=='YELLOW' ){$warning++;}
		if ($arr->TT_ENG_OIL=='YELLOW')		{$warning++;}
		if ($arr->TT_ADB_LEV=='YELLOW')		{$warning++;}		
		if ($arr->TT_PAR_BRA=='RED' )		{$warning++;}
		if ($arr->TT_FUE_LEV=='RED' )		{$warning++;}
		if ($arr->TT_ENG_COO_TEM=='RED' )	{$warning++;}
		if ($arr->TT_ENG_MIL_IND=='RED' )	{$warning++;}
		if ($arr->TT_ENG_EMI_FAI=='RED' )	{$warning++;}
		if ($arr->TT_ENG_OIL=='RED')		{$warning++;}
		if ($arr->TT_ADB_LEV=='RED')		{$warning++;}
		return $warning;
}

	header("Content-Type: text/html; charset=utf-8");
	$db = DB::getInstance();
	if ($_GET["customer"]=='*'){
		$VehicleQ = $db->query("
		SELECT 	customers.name,vehicles.VIN,vehicles.customerVehicleName,vehicles.model,vehicles.Odometer,vehicles.triggerType,vehicles.currentSpeed,FuelLevel,LicensePlate,
				vehicles.Driver1_ID,vehicles.Driver2_ID,vehicles.Driver1_WS,vehicles.Driver2_WS,
				vehicles.LastActivity,vehicles.last_Latitude,vehicles.last_Longitude,vehicles.vehicleActive,
				TT_PAR_BRA,TT_FUE_LEV,TT_ENG_COO_TEM,TT_ENG_OIL,TT_ENG_MIL_IND,TT_ENG_EMI_FAI,TT_ADB_LEV, 
				FAMReport.obu_sw_version,driver.Lastname,driver.Surname,driver.RemaingDriveToday,driver.RemainingRestToday
		FROM vehicles 
		INNER JOIN FAMReport ON FAMReport.vin=vehicles.VIN 
		INNER JOIN customers ON customers.accountnumber=FAMReport.client  
		LEFT JOIN driver ON driver.tachoDriverIdentification=vehicles.Driver1_ID
		WHERE vehicles.vehicleActive=true
		GROUP BY vehicles.VIN 
		ORDER BY vehicles.LastActivity DESC");
	}
	else {
		$VehicleQ = $db->query("
		SELECT 	customers.name,vehicles.VIN,vehicles.customerVehicleName,vehicles.model,vehicles.Odometer,vehicles.triggerType,vehicles.currentSpeed,FuelLevel,LicensePlate,
				vehicles.Driver1_ID,vehicles.Driver2_ID,vehicles.Driver1_WS,vehicles.Driver2_WS,
				vehicles.LastActivity,vehicles.last_Latitude,vehicles.last_Longitude,vehicles.vehicleActive,
				TT_PAR_BRA,TT_FUE_LEV,TT_ENG_COO_TEM,TT_ENG_OIL,TT_ENG_MIL_IND,TT_ENG_EMI_FAI,TT_ADB_LEV, 
				FAMReport.obu_sw_version,driver.Lastname,driver.Surname,driver.RemaingDriveToday,driver.RemainingRestToday
		FROM vehicles 
		INNER JOIN FAMReport ON FAMReport.vin=vehicles.VIN 
		INNER JOIN customers ON customers.accountnumber=FAMReport.client 
		LEFT JOIN driver ON driver.tachoDriverIdentification=vehicles.Driver1_ID
		WHERE vehicles.vehicleActive=true and customers.accountnumber='".$_GET["customer"]."'		
		GROUP BY vehicles.VIN 
		ORDER BY vehicles.LastActivity DESC");
	}
	$Vehicles = $VehicleQ->results();
if ($VehicleQ->count()>0){
	$counter=0;
	$Counters=Array();
	$Counters['CountedMoving']=0;$Counters['CountedStopped']=0;$Counters['CountedAlert']=0;$Counters['UnknownVehicles']=0;$Counters['CountedNoLocation']=0;
	$Counters['CountedToday']=0;$Counters['CountedDriver1']=0;$Counters['CountedDriver2']=0;$Counters['CountedTotal']=0;
	foreach ($Vehicles as $val){
		$StatusArray=Array();															// definition of variables per truck before checking vehicle data
		$StatusArray['delayed']=false;
		$StatusArray['alert']=false;
		$StatusArray['driving']=false;
		$StatusArray['stopped']=false;
		$StatusArray['nolocation']=false;
		$StatusArray['drivingtoday']=false;
		if ($val->LastActivity<>NULL){
			$localDate = new DateTime($val->LastActivity, new DateTimeZone('Europe/Amsterdam'));
			$CurrentDT = new DateTime("now", new DateTimeZone('Europe/Amsterdam'));
			$val->LastActivity=$localDate->format('Y-m-d H:i:s');
		}
 		$CalcDate  = $localDate->add(new DateInterval('PT15M')); 
		$VDT=$localDate->format('Y-m-d');
		$CDT=$CurrentDT->format('Y-m-d');
		if ($VDT==$CDT){
			$StatusArray['drivingtoday']=true;$Counters['CountedToday']++;
		}
		if ($val->currentSpeed>1){$StatusArray['driving']=true;$Counters['CountedMoving']++;}							// check if vehicle is driving
		else {$StatusArray['stopped']=true;$Counters['CountedStopped']++;	}												// Vvehicle stopped
		if (Check_telltale_warning($val)>0){ $StatusArray['alert']=true;$Counters['CountedAlert']++;	}				// check if vehicle is an acive tell-tale
/*  		if ($val->currentSpeed>0){															// determine if truck has a delay in the communication (temporarily solution pending rfms 2 ph2)
			if ($CalcDate<$CurrentDT ){	$StatusArray['delayed']=true;$Counters['UnknownVehicles']++	;}
		}   */
		if ($val->last_Latitude==0){ $StatusArray['nolocation']=true;$Counters['CountedNoLocation']++;}						// determine vehicle has no valid GPS location			
		$val->status=$StatusArray;
		$val->CountWarning=Check_telltale_warning($val);
		$Counters['CountedTotal']++;
	 }
	 $MainArray=Array();
	 $MainArray['Vehicles']=$Vehicles;
	 $MainArray['Counters']=$Counters;
}
echo json_encode($MainArray); 
?>
