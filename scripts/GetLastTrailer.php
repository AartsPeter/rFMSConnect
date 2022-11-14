<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="
		SELECT 
			t.trailerName,t.id,t.vin,t.LicensePlate,
			DATE(d.LastDriverActivity) AS lastTrip 
		FROM driver d
			LEFT JOIN trailers t on t.vehicleVIN=d.LastVehicle
			LEFT JOIN famreport f on f.vin=d.LastVehicle ".$str3."
		WHERE 
			d.tachoDriverIdentification='".$user->data()->driver_id."' and
			d.LastDriverActivity>(CURDATE() - INTERVAL 2 week) ".$str2." ".$str1."
		UNION
		SELECT t.trailerName,t.id,t.vin,t.LicensePlate,Date(trips.StartDate) AS lastTrip
		FROM trips
			LEFT JOIN trailers t ON t.vin=trips.trailerVIN
			LEFT JOIN famreport f on f.vin=trips.vin ".$str3."
		WHERE 
			trips.Driver1ID='".$user->data()->driver_id."' AND
			trips.trailerVIN!='' AND
			trips.StartDate>(CURDATE() - INTERVAL 2 week) ".$str2." ".$str1."
		 GROUP BY trips.VIN
		 LIMIT 0,5 ";
	$Q = $db->query($query);
	$Result = $Q->results();

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}


	?>

