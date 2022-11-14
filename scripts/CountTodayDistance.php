<?php 
//**********************************************************************
// file will return a array to show within 'rfms_report.js'
// Author : Peter Aarts QNH 2017
// License free 
//**********************************************************************
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';


    $localDate=new DateTime('now');

    $query="
        SELECT
            sum((v.OdoMeter-vehicle_stat_km.odometer)/1000) AS VehicleDD
        FROM
            vehicle_stat_km
            LEFT JOIN vehicles v ON vehicle_stat_km.VIN=v.VIN
            LEFT JOIN FAMReport f ON f.vin=vehicle_stat_km.vin ".$str3."
        WHERE
            v.vehicleActive=true ".$str1." ".$str2." and
            Date(createDate)='".$localDate->format('Y-m-d')."'";

    $Q =$db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>