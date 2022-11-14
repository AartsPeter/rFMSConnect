<?php 
//**********************************************************************
// file will return a array to show within 'rfms_report.js'
// Author : Peter Aarts QNH 2017
// License free 
//**********************************************************************
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="
	select
        v.vin,
        v.TripActive,
        COUNT(tt.state) AS CountedRed
    FROM
	    VEHICLES_TELLTALES tt
        LEFT JOIN vehicles v ON tt.vin = v.VIN  ".$str3."
    WHERE
        DATE(tt.createdDateTime)= CURDATE() AND
        tt.state = 'RED' and
        v.vehicleActive = TRUE ".$str1." ".$str2."
    GROUP BY
        tt.vin";
	$VehicleQ = $db->query($query);
	$Result = $VehicleQ->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>