<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';
	
	$query=	"
		SELECT
			IF(driver.Lastname='',driver.tachoDriverIdentification,concat(driver.Lastname,', ',driver.Surname)) AS driver,
			date(t.StartDate) as date,
			t.startdate as StartDate,t.enddate as EndDate,t.Trip_NO,t.TripActive,
			v.customerVehicleName,
			v.licenseplate,
			driver.id as DriverId,
			COUNT(t.Trip_NO) AS amountTrips, 
			ROUND(SUM(t.Distance),0) AS Distance, 
			ROUND(SUM(t.FuelUsed),0) AS FuelUsed, 
			ROUND(100/(SUM(t.Distance)/SUM(t.FuelUsed)),1) AS FuelUsage,
			ROUND(SUM(t.CO2_emission),0) AS CO2_emission, 
			DATE_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(t.Duration))),'%H:%i') AS Duration,
			DATE_FORMAT(SEC_TO_TIME(SUM(DriveTime)),'%H:%i') AS DriveTime,
			DATE_FORMAT(SEC_TO_TIME(SUM(IdleTime)),'%H:%i') AS IdleTime,
			(	SELECT DATEDIFF(pdc_register.createdDate,t.startdate) FROM PDC_REGISTER
			    WHERE date(pdc_register.createdDate)<date(t.startdate) AND pdc_register.vehicle=t.vin ORDER BY pdc_register.createdDate DESC LIMIT 1) AS DAYS_NO_PDC,
			(	SELECT round(t.end_odometer/1000)-pdc_register.vehicle_odometer	FROM PDC_REGISTER
				WHERE date(pdc_register.createdDate)<date(t.startdate) AND pdc_register.vehicle=t.vin ORDER BY pdc_register.createdDate DESC LIMIT 1) AS KM_NO_PDC,
			(	SELECT COUNT(id) FROM PDC_REGISTER
				WHERE date(pdc_register.createdDate)=date(t.startdate) AND pdc_register.vehicle=t.vin) AS has_PDC,
			(	SELECT COUNT(damages) FROM PDC_REGISTER
				WHERE date(pdc_register.createdDate)=date(t.startdate) AND pdc_register.vehicle=t.vin) AS COUNT_Damages
		FROM trips t 
			use index(idx_driverTrips)
			LEFT JOIN driver ON driver.tachoDriverIdentification=t.Driver1ID
			LEFT JOIN vehicles v ON v.VIN=t.VIN
			LEFT JOIN FAMReport f ON f.vin=t.VIN 
			".$str3."
		WHERE  
			v.vehicleActive=true ".$str1." ".$str2." AND v.id='".$id."' and t.distance>1 AND t.StartDate BETWEEN '".$SD."' AND '".$ED."'
		GROUP BY 
			t.vin,date(t.StartDate),t.driver1ID
		ORDER BY 
			t.vin,startdate";


	$Q = $db->query($query);
	$Results = $Q->results();

	if (sizeof($Results)==0) {
		$query="
			SELECT
				IF(driver.Lastname='',driver.tachoDriverIdentification,concat(driver.Lastname,', ',driver.Surname)) AS driver,
				date(t.StartDate) as date,
				t.startdate as StartDate,t.enddate as EndDate,t.Trip_NO,t.TripActive,
				v.customerVehicleName,v.licenseplate,driver.id as DriverId,
				COUNT(t.Trip_NO) AS amountTrips, 
				ROUND(SUM(t.Distance),0) AS Distance, 
				ROUND(SUM(t.FuelUsed),0) AS FuelUsed, 
				ROUND(100/(SUM(t.Distance)/SUM(t.FuelUsed)),1) AS FuelUsage,
				ROUND(SUM(t.CO2_emission),0) AS CO2_emission, 
				DATE_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(t.Duration))),'%H:%i') AS Duration,
				DATE_FORMAT(SEC_TO_TIME(SUM(DriveTime)),'%H:%i') AS DriveTime,
				DATE_FORMAT(SEC_TO_TIME(SUM(IdleTime)),'%H:%i') AS IdleTime,
				(	SELECT DATEDIFF(pdc_register.createdDate,t.startdate) FROM PDC_REGISTER
					WHERE date(pdc_register.createdDate)<date(t.startdate) AND pdc_register.vehicle=t.vin ORDER BY pdc_register.createdDate DESC LIMIT 1) AS DAYS_NO_PDC,
				(	SELECT round(t.end_odometer/1000)-pdc_register.vehicle_odometer	FROM PDC_REGISTER
					WHERE date(pdc_register.createdDate)<date(t.startdate) AND pdc_register.vehicle=t.vin ORDER BY pdc_register.createdDate DESC LIMIT 1) AS KM_NO_PDC,
				(	SELECT COUNT(id) FROM PDC_REGISTER
					WHERE date(pdc_register.createdDate)=date(t.startdate) AND pdc_register.vehicle=t.vin) AS has_PDC,
				(	SELECT COUNT(damages) FROM PDC_REGISTER
					WHERE date(pdc_register.createdDate)=date(t.startdate) AND pdc_register.vehicle=t.vin) AS COUNT_Damages
			FROM trips t use index(idx_driverTrips)
				LEFT JOIN driver ON driver.tachoDriverIdentification=t.Driver1ID
				LEFT JOIN vehicles v ON v.VIN=t.VIN
				LEFT JOIN FAMReport f ON f.vin=t.VIN 
				".$str3."
			WHERE  
				v.vehicleActive=true ".$str1." ".$str2." and t.distance>1 AND v.id='".$id."' 
			GROUP BY
				Trip_NO
			order by 
				t.startdate DESC
			LIMIT 10";
		$Q = $db->query($query);
		$Results = $Q->results();
	}

	foreach ($Results as $row ){
		if ($row->Distance>5 && $row->FuelUsed!=0){$row->FuelUsage= round(100/($row->Distance/$row->FuelUsed),1);}
		else {$row->FuelUsage='';}
		if ($row->driver==null){$row->driver='-';}
	}
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Results,'',true);
    } else {
        echo json_encode($Results);
    }

