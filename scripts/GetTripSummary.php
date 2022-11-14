<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="SELECT 			
			v.id, 
			v.customerVehicleName,
			COUNT(t.Trip_NO) AS amountTrips,
			ROUND(SUM(t.Distance),0) AS Distance,			
			ROUND(SUM(t.FuelUsed),0) AS FuelUsed,
			ROUND(100/(SUM(t.Distance)/SUM(t.FuelUsed)),1) AS FuelUsage,
			ROUND(SUM(t.CO2_emission),0) AS CO2_emission, 
			CONCAT( FLOOR(SUM(TIME_TO_SEC(t.Duration))/3600),':', FLOOR(MOD(SUM(TIME_TO_SEC(t.Duration)),3600)/60),':',MOD(SUM(TIME_TO_SEC(t.Duration)),60))  AS Duration,
			CONCAT( FLOOR(sum(DriveTime)/3600),':', FLOOR(MOD(SUM(DriveTime),3600)/60),':',MOD(sum(DriveTime),60))  AS DriveTime
		FROM
		    trips t USE INDEX(CustomerTripCountPerDay)
		    LEFT JOIN vehicles v ON v.VIN=t.VIN
		    LEFT JOIN FAMReport f ON f.vin=v.VIN
		    LEFT JOIN customers c ON c.accountnumber=f.client ".$str3."
		WHERE
		    v.vehicleActive=true and
		    t.Distance>1  ".$str1." ".$str2." and
		    t.StartDate between'".$SD."' AND '".$ED."'
		GROUP BY
		    v.VIN
		ORDER BY
		    v.customerVehicleName ASC ";
	$Q 		= $db->query($query);
	$Result = $Q->results();
	foreach ($Result as $val){
		if ($val->FuelUsed==0 || $val->Distance==0){
			$fuelusage=0;}
		else {
			$fuelusage=100/($val->Distance/$val->FuelUsed);}
		if ($fuelusage>1 && $fuelusage<100){
			$val->FuelUsage=number_format($fuelusage,2);}
		else {
			$val->FuelUsage='';}
	}

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}



?>
	