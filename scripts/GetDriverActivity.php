<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	if(isset($_GET['id'])) 	{
	    $VS=" AND driver.id='".$_GET['id']."'";}
	else {
	    if ($user->data()->driver_id!=''){
	        $VS=" AND T.driver1id='".$user->data()->driver_id."' ";}
	    else {
	        $VS='';
	    }
	}

	$query="
		SELECT
			IF(driver.Lastname='',driver.tachoDriverIdentification,concat(driver.Lastname,', ',driver.Surname,' (',driver.tachoDriverIdentification,')')) AS driver, 
			date(t.StartDate) as date,
			vehicles.customerVehicleName,
			vehicles.licenseplate,
			vehicles.id as VehicleId,
			COUNT(t.Trip_NO) AS amountTrips,
			ROUND(SUM(t.Distance),0) AS Distance,
			ROUND(SUM(t.FuelUsed),0) AS FuelUsed,
			ROUND(100/(SUM(t.Distance)/SUM(t.FuelUsed)),1) AS FuelUsage,
			ROUND(SUM(t.CO2_emission),0) AS CO2_emission,
			DATE_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(t.Duration))),'%H:%i') AS Duration,
			DATE_FORMAT(SEC_TO_TIME(SUM(DriveTime)),'%H:%i') AS DriveTime,
			DATE_FORMAT(SEC_TO_TIME(SUM(IdleTime)),'%H:%i') AS IdleTime,
			(SELECT COUNT(id) FROM PDC_REGISTER WHERE date(pdc_register.createdDate)=date(t.startdate) AND pdc_register.driver=t.Driver1ID) AS has_PDC,
			(SELECT COUNT(damages) FROM PDC_REGISTER WHERE date(pdc_register.createdDate)=date(t.startdate) AND pdc_register.driver=t.Driver1ID) AS COUNT_Damages
		FROM
		    trips t use index(idx_driverTrips)
		    LEFT JOIN driver ON t.Driver1ID=driver.tachoDriverIdentification
		    LEFT JOIN vehicles ON t.VIN=vehicles.VIN
		    LEFT JOIN FAMReport f ON t.VIN=F.vin
		WHERE
		    vehicles.vehicleActive=true ".$str1." ".$VS." AND
		    t.StartDate BETWEEN '".$SD."' AND '".$ED."'
		GROUP BY
		    t.Driver1ID,date(t.StartDate),t.vin
		ORDER BY
		    driver.tachoDriverIdentification,startdate";
	$Q = $db->query($query);
	$Results = $Q->results();

	if (sizeof($Results)==0) {
			$query="
		SELECT
			IF(driver.Lastname='',driver.tachoDriverIdentification,concat(driver.Lastname,', ',driver.Surname,' (',driver.tachoDriverIdentification,')')) AS driver, 
			date(t.StartDate) as date,
			vehicles.customerVehicleName,
			vehicles.licenseplate,
			vehicles.id as VehicleId,
			COUNT(t.Trip_NO) AS amountTrips,
			ROUND(SUM(t.Distance),0) AS Distance,
			ROUND(SUM(t.FuelUsed),0) AS FuelUsed,
			ROUND(100/(SUM(t.Distance)/SUM(t.FuelUsed)),1) AS FuelUsage,
			ROUND(SUM(t.CO2_emission),0) AS CO2_emission,
			DATE_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(t.Duration))),'%H:%i') AS Duration,
			DATE_FORMAT(SEC_TO_TIME(SUM(DriveTime)),'%H:%i') AS DriveTime,
			DATE_FORMAT(SEC_TO_TIME(SUM(IdleTime)),'%H:%i') AS IdleTime,
			(SELECT COUNT(id) FROM PDC_REGISTER WHERE date(pdc_register.createdDate)=date(t.startdate) AND pdc_register.driver=t.Driver1ID) AS has_PDC,
			(SELECT COUNT(damages) FROM PDC_REGISTER WHERE date(pdc_register.createdDate)=date(t.startdate) AND pdc_register.driver=t.Driver1ID) AS COUNT_Damages
		FROM
		    trips t use index(idx_driverTrips)
		    LEFT JOIN driver ON t.Driver1ID=driver.tachoDriverIdentification
		    LEFT JOIN vehicles ON t.VIN=vehicles.VIN
		    LEFT JOIN FAMReport f ON t.VIN=F.vin
		WHERE
		    vehicles.vehicleActive=true ".$str1." ".$VS."		    
		GROUP BY
		    t.Driver1ID,t.vin
		ORDER BY
		    startdate DESC
		LIMIT 15";
		$Q = $db->query($query);
		$Results = $Q->results();
	}

	foreach ($Results as $row ){
		if ($row->Distance>2 && $row->FuelUsed!=0){$row->FuelUsage= round(100/($row->Distance/$row->FuelUsed),1);}
		else {$row->FuelUsage='';}
		if ($row->driver==''){$row->driver='_No DriverCard';}
	}
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Results,'',true);
    } else {
        echo json_encode($Results);
    }

?>
