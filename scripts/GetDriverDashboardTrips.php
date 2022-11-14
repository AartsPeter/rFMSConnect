<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="SELECT 
		COUNT(t.Trip_NO)   AS amountTrips,
		ROUND(SUM(t.Distance),0)  AS Distance,
		ROUND(SUM(t.FuelUsed),0)  AS FuelUsed,
		ROUND(100/(SUM(t.Distance)/SUM(t.FuelUsed)),1) AS FuelUsage,
		ROUND(SUM(t.CO2_emission),0)   AS CO2_emission,
		SEC_TO_TIME(SUM(TIME_TO_SEC(t.Duration)))  AS Duration,
		SEC_TO_TIME(SUM(t.DriveTime)) AS DriveTime
	FROM trips t 
		LEFT JOIN vehicles v ON v.VIN=t.VIN
		LEFT JOIN famreport f  ON f.vin=t.vin  ".$str3."
	WHERE  t.Driver1ID='".$user->data()->driver_id."' AND t.EndDate>=Date(NOW())  ".$str1." ".$str2;

	$Q = $db->query($query);
	$Result = $Q->results();

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}
?> 