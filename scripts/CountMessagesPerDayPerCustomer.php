<?php 
//**********************************************************************
// file used with parameter "?Date=xx"
// file will return a array to show within 'rfms_report.js'
// Author : Peter Aarts QNH 2022
// License free 
    //**********************************************************************
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $query="
        SELECT
            customers.name, COUNT(vehiclestatus.vin) as TotalMessages
        FROM
            vehiclestatus
            LEFT JOIN FAMReport ON FAMReport.vin=vehiclestatus.VIN
            LEFT JOIN customers ON customers.accountnumber=FAMReport.client
        WHERE
            createdDateTime LIKE '%".$_GET["date"]."%'
        GROUP BY
            customers.name
        ORDER BY
            customers.name ASC  ";
    $VehicleQ =$db->query($query);
    $Result = $VehicleQ->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>