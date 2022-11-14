<?php 
//**********************************************************************
// file will return a array to show within 'rfms_report.js'
// Author : Peter Aarts QNH 2017
// License free 
//**********************************************************************
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    $query="
        SELECT
            date(tt.createdDateTime) AS DATE,
            v.customerVehicleName as vehicle,LicensePlate,v.brand, v.model,".$str4."
            sum(if(tt.state= 'YELLOW', 1, 0)) AS CountedYellow,
            sum(if(tt.state= 'RED', 1, 0)) AS CountedRed
        FROM
            vehicles_telltales tt
            LEFT JOIN vehicles v ON tt.vin=v.vin
            LEFT JOIN FAMReport f ON F.vin=v.vin
            LEFT JOIN CUSTOMERS C ON C.ACCOUNTNUMBER=F.CLIENT ".$str3."
        WHERE
            v.vehicleActive=true AND
            (tt.state='RED' OR tt.state='YELLOW') AND
            tt.createdDateTime BETWEEN '".$SD."' AND '".$ED."' ".$str1." ".$str2."
        GROUP BY tt.vin";
    $Q =$db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>