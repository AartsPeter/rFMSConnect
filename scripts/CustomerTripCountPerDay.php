<?php
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
	$settings = $settingsQ->first();
	$days=$settings->daysStatistics;
	if(isset($_GET['EndDate'])) 	{$ED=$_GET['EndDate'];} 	else {$ED=date("Y-m-d",strtotime('+ 1 day'));};
	if(isset($_GET['StartDate'])) 	{$SD=$_GET['StartDate'];} 	else {$SD=date('Y-m-d',strtotime('-'.$days.' day'));};


	$EndDT = strtotime($ED. ' + 1 day');
	$query="
			SELECT COUNT(trips.vin) AS Total,sum(trips.fuelused) AS Fuelused, sum(trips.distance) AS Distance ,DATE(trips.StartDate) as Date
			FROM trips USE INDEX (CustomerTripCountPerDay)
			LEFT JOIN vehicles ON vehicles.vin=trips.vin
			LEFT JOIN FAMReport f ON trips.vin=f.vin ".$str3."
			WHERE trips.distance>2 and vehicleActive=true ".$str1." ".$str2." AND trips.StartDate>='".$SD."' AND trips.EndDate<='".$ED."'
			GROUP BY DATE(StartDate)
			";
	$VehicleQ = $db->query($query);	
	$Result = $VehicleQ->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>