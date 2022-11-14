<?php 
//**********************************************************************
// file used with parameter "?Date=xx"
// file will return a array to show within 'rfms_report.js'
// Author : Peter Aarts QNH 2022
// License free 
//**********************************************************************
require_once '../users/init.php';
require_once 'lib/scriptHeader.php';

	$settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
	$settings = $settingsQ->first();
	$SD=date("Y-m-d", strtotime("- ".($settings->daysStatistics-1)." days"));

    $query="
        SELECT
            COUNT(TRIPS.VIN) as CountedTrips
        FROM
            trips
            LEFT JOIN vehicles V ON v.VIN =TRIPS.vin
            LEFT JOIN FAMReport F ON F.vin=trips.VIN  ".$str3."
        WHERE
            v.vehicleActive=true AND
                trips.StartDate>='".$SD."' ".$str1." ".$str2;
    $VehicleQ =$db->query($query);
    $Result=$VehicleQ->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>