<?php
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
    $settings = $settingsQ->first();
    $days=$settings->daysStatistics;
    if(isset($_GET['EndDate'])) 	{$ED=test_input($_GET['EndDate']);} 	else {$ED=date("Y-m-d",strtotime('+ 1 day'));};
    if(isset($_GET['StartDate'])) 	{$SD=test_input($_GET['StartDate']);} 	else {$SD=date('Y-m-d',strtotime('-'.$days.' day'));};

    $customer=$_SESSION['UGselected'];

	$query="	
		SELECT 
			v.customerVehicleName,
			COUNT(trips.vin) AS TripsTotal, 
			SEC_TO_TIME( SUM( TIME_TO_SEC(trips.duration) ) ) AS TripTime,
			round(sum(trips.distance),0) as TripDistance,
			round(sum(trips.fuelused),1) as TripFuelUsed,
			SEC_TO_TIME(sum(trips.drivetime)) as TripDriveTime,
			SEC_TO_TIME(sum(trips.idletime)) as TripIdleTime,
			sum(trips.co2_emission) as TripCO2
		FROM trips 
			USE INDEX(CustomerTripCountPerDay)
			LEFT JOIN FAMReport f ON trips.vin=F.vin 
			LEFT JOIN vehicles v ON trips.vin=v.vin  ".$str3."
		WHERE
		    StartDate BETWEEN '".$SD."' AND '".$ED."' AND
		    trips.TripActive=false AND trips.distance>1 ".$str1." ".$str2."
		GROUP BY trips.vin	";
    $VehicleQ = $db->query($query);
    $Result = $VehicleQ->results();
    foreach ($Result as $val){
        if ($val->TripFuelUsed!=0){
            $val->TripFuelUsage=number_format(100/($val->TripDistance/$val->TripFuelUsed),2);
        } else {$val->TripFuelUsage=0;}
        $val->TripAverageSpeed=number_format($val->TripDistance/(TimeToSec($val->TripTime)/3600),2);
    }

    if(isset($_GET['SQL'])){
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result,JSON_NUMERIC_CHECK);
    }

?>