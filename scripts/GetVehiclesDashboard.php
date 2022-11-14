<?php 
require_once '../users/init.php';
require_once 'lib/scriptHeader.php';

if (!securePage($_SERVER['PHP_SELF'])){
    header("HTTP/1.1 401 Unauthorized");
    sleep(10);
    exit;die();
}
error_reporting(E_ALL);
//**********************************************************************
// file used customername parameter '?customer=xxx'
// file will return a array to show within 'rfms.js'
// Author : Peter Aarts QNH 2021
// License free 
//**********************************************************************
//header('Content-Type: text/plain');
function Check_telltale_warning($arr){
		$warning=0;
//		if ($arr->TT_PAR_BRA=='YELLOW' )	{$warning++;}
//		if ($arr->TT_FUE_LEV=='YELLOW' )	{$warning++;}
//		if ($arr->TT_ENG_COO_TEM=='YELLOW' ){$warning++;}
//		if ($arr->TT_ENG_MIL_IND=='YELLOW' ){$warning++;}
//		if ($arr->TT_ENG_EMI_FAI=='YELLOW' ){$warning++;}
//		if ($arr->TT_ENG_OIL=='YELLOW')		{$warning++;}
//		if ($arr->TT_ADB_LEV=='YELLOW')		{$warning++;}		
		if ($arr->TT_PAR_BRA=='RED' )		{$warning++;}
		if ($arr->TT_FUE_LEV=='RED' )		{$warning++;}
		if ($arr->TT_ENG_COO_TEM=='RED' )	{$warning++;}
		if ($arr->TT_ENG_MIL_IND=='RED' )	{$warning++;}
		if ($arr->TT_ENG_EMI_FAI=='RED' )	{$warning++;}
		if ($arr->TT_ENG_OIL=='RED')		{$warning++;}
		if ($arr->TT_ADB_LEV=='RED')		{$warning++;}
		return $warning;
}

	$query="
	    SELECT 	v.id,v.VIN,customerVehicleName,LicensePlate,currentSpeed,serviceDistance,v.Driver1_ID,Driver2_ID,driver.id as DriverId,
			LastActivity,last_Latitude,last_Longitude,last_Heading,tripActive,c.name,
			TT_PAR_BRA,TT_FUE_LEV,TT_ENG_COO_TEM,TT_ENG_OIL,TT_ENG_MIL_IND,TT_ENG_EMI_FAI,TT_ADB_LEV,
			if (AddDateTime> NOW() - INTERVAL 2 WEEK,true, false) as NewVehicle,
			if (gfr.createTrigger=1, true, false) as Geofence,
			IF(driver.Lastname='',driver.tachoDriverIdentification,concat(driver.Lastname,', ',driver.Surname)) AS Driver,
			(SELECT SEC_TO_TIME(36000-drivetimes.drive) FROM drivetimes WHERE drivetimes.driverId = v.Driver1_ID AND DriveDate=CURDATE() ) AS RemainingDriveToday,
			(SELECT trailerName FROM trailers WHERE trailers.vehicleVIN = v.vin  AND copplingStatus=1 LIMIT 1) AS `TrailerName`,
			(SELECT id FROM trailers WHERE trailers.vehicleVIN = v.vin  AND copplingStatus=1 LIMIT 1) AS `TrailerId`,
			(SELECT count(vin) FROM pdc_damage WHERE pdc_damage.vin = v.vin  AND pdc_damage.repairStatus=0 AND pdc_damage.severity=0 ) AS `DamageCount`,
			(SELECT ROUND(sum(t.distance),0) FROM trips t WHERE t.vin = v.vin AND date(t.enddate)=CURDATE()  ) AS `TotDistanceToday`,
			(SELECT ROUND(sum(t.fuelused),0) FROM trips t WHERE t.vin = v.vin AND date(t.enddate)=CURDATE()  ) AS `TotFuelUsedToday`
        FROM
            vehicles v
            LEFT JOIN FAMReport f ON v.VIN=f.vin
            LEFT JOIN customers c on c.accountnumber=f.client
            LEFT JOIN geofence_reg gfr on gfr.vin=v.VIN
            LEFT JOIN driver ON driver.tachoDriverIdentification=v.Driver1_ID ".$str3."
        WHERE
            v.vehicleActive=true  ".$str1." ".$str2."
        GROUP BY
            v.VIN
        ORDER BY
            v.LastActivity DESC";
	$VehicleQ = $db->query($query);
	$Vehicles = $VehicleQ->results();
	if ($VehicleQ->count()>0){
		$counter=0;
		$rdt = new DateTime('Now');	$CurrentDT = new DateTime('Now');
		$rdt = strtotime($rdt->format('Y-m-d H:i:s'));	
		foreach ($Vehicles as $val){
			$StatusArray=Array();															// definition of variables per truck before checking vehicle data
			$StatusArray['delayed']=false;
			$StatusArray['alert']=false;
			$StatusArray['driving']=false;
			$StatusArray['stopped']=false;
			$StatusArray['overspeeding']=false;
			$StatusArray['paused']=false;
			$StatusArray['nolocation']=false;
			$StatusArray['geofence']=$val->Geofence;
			$StatusArray['drivingtoday']=false;
			if ($val->LastActivity<>""){
				$localDate = new DateTime($val->LastActivity, new DateTimeZone('Europe/Amsterdam'));
				$val->LastActivity=$localDate->format('Y-m-d H:i:s');
				$VDT=$localDate->format('Y-m-d');
				$CDT=$CurrentDT->format('Y-m-d');
				if ($VDT==$CDT){
					$StatusArray['drivingtoday']=true;
				}
			}
            if ($val->TotDistanceToday<5){
                $val->Driver='';
                $val->RemainingDriveToday='10:00:00';
            }
			if ($val->currentSpeed>0){
				$cdt = strtotime($val->LastActivity);
				$StatusArray['driving']=true;
				$dteDiff  = (($rdt-$cdt)/60)-120; 													// Determine $dteDiff in minutes minus timezone difference UTC vs Europe/Amsterdam	
				if ($dteDiff>30){
					$StatusArray['delayed']=true; 
				}
			}
			else {
			    if ($val->tripActive==true){$StatusArray['paused']=true;}						// Vvehicle paused
             	else{$StatusArray['stopped']=true;	}											// Vvehicle stopped
   			}
			if ($val->serviceDistance<1000){$val->Maintenance="danger";}
			else {if($val->serviceDistance<15000){$val->Maintenance="warning";} else{$val->Maintenance="hide";}}
			if ($val->currentSpeed>85){ $StatusArray['overspeeding']=true;}

			if (Check_telltale_warning($val)>0){ $StatusArray['alert']=true;	}				// check if vehicle is an acive tell-tale
			if ($val->last_Latitude==0){ $StatusArray['nolocation']=true;}						// determine vehicle has no valid GPS location			
			$val->status=$StatusArray;
			$val->CountWarning=Check_telltale_warning($val);
			if ($val->RemainingDriveToday==null){$val->RemainingDriveToday='10:00:00';}
		}
	}

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Vehicles,'',true);
    } else {
        echo json_encode($Vehicles);
    }
