<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';


    $query="SELECT
        D.*,
       IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS driver,
        V.customerVehicleName,
        (SELECT SEC_TO_TIME(32400-drivetimes.drive) FROM drivetimes WHERE drivetimes.driverId = d.tachoDriverIdentification AND DriveDate=CURDATE() ) AS RemainingDriveToday
    FROM
        driver D
        LEFT JOIN vehicles V ON D.LastVehicle=V.VIN
        LEFT JOIN famreport F ON D.LastVehicle=F.vin ".$str3."
    WHERE vehicleActive=true ".$str1." ".$str2."
    GROUP BY d.id
    ORDER BY d.LastDriverActivity DESC";

    $Q = $db->query($query);
    $Results = $Q->results();

    if ($Q->count()>0){
        foreach ($Results as $val){
            if ($val->RemainingDriveToday==null){$val->RemainingDriveToday='10:00:00';}
        }
    }

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Results,'',true);
    } else {
        echo json_encode($Results);
    }
