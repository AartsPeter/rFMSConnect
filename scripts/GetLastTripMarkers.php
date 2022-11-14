<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	$query0="SELECT StartDate,EndDate,TripActive FROM trips t WHERE t.Trip_No='".$tripno."'";
	$Q = $db->query($query0);
    $Trip=$Q->first();

    if ($Trip->EndDate==''){ $EndDate=date('Y-m-d H:i:s');} else {$EndDate=$Trip->EndDate;}
	$query="SELECT GNSS_latitude,GNSS_longitude,createdDateTime,vs.vin,receivedDateTime,GNSS_heading,
	        GNSS_altitude,hrTotalVehicleDistance,triggerType,triggerInfo,driver1Id_TDI,driver2Id_TDI,driver1Id_WSC,driver2Id_WSC,wheelBasedSpeed
			FROM vehiclestatus vs 
				LEFT JOIN TRIPS t on t.VIN=vs.vin
				LEFT JOIN famreport f on f.vin=vs.vin ".$str3."
			WHERE
			    t.Trip_No='".$tripno."'  ".$str1." ".$str2." AND
			    createdDateTime BETWEEN t.StartDate AND '".$EndDate."'";
	$Q = $db->query($query);
	$Result=$Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query0,$Trip,'TripControle Query',true);
        echo ShowDebugQuery($query,$Result,'VehicleStatus data of trip',true);
    } else {
        echo json_encode($Result);
    }
?>