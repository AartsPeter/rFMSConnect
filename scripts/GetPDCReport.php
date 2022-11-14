<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	if(isset($_GET['id'])) 			{$VS=" AND driver.id='".test_input($_GET['id'])."' ";} 	else {$VS="";}
	$query="
		SELECT
			IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS driver, 
			date(t.StartDate) as `date`,
			v.customerVehicleName,
			v.licenseplate,
			COUNT(t.Trip_NO) AS amountTrips, 
			ROUND(SUM(t.Distance),0) AS Distance, 
			ROUND(SUM(t.FuelUsed),0) AS FuelUsed, 
			ROUND(100/(SUM(t.Distance)/SUM(t.FuelUsed)),1) AS FuelUsage,
			ROUND(SUM(t.CO2_emission),0) AS CO2_emission, 
			SEC_TO_TIME(SUM(TIME_TO_SEC(t.Duration))) AS Duration,
			SEC_TO_TIME(SUM(DriveTime)) AS DriveTime
		FROM trips t 
			use index(idx_driverTrips)
			LEFT JOIN driver d ON t.Driver1ID=d.tachoDriverIdentification
			LEFT JOIN vehicles v ON t.VIN=v.VIN
			LEFT JOIN FAMReport f ON t.VIN=f.vin ".$str3."
		WHERE 
			t.Distance>1 AND 
			v.vehicleActive=true ".$str1." ".$str2." ".$VS." 
			 AND t.StartDate BETWEEN '".$SD."' AND '".$ED."'
		GROUP BY 
			t.Driver1ID,date(t.StartDate),t.vin
		ORDER BY 
			d.tachoDriverIdentification,t.startdate";
	//execute query
	$Q = $db->query($query);
	$Result = $Q->results();
	//process query data
	foreach ($Result as $row ){
		if ($row->Distance>5 && $row->FuelUsed!=0){
			$row->FuelUsage = round(100/($row->Distance/$row->FuelUsed),1);}
		else {
			$row->FuelUsage='';}
	}
	//expose query data
	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}

?>
