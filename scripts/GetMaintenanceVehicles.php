<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    $query ="
        SELECT v.VIN,
            v.customerVehicleName AS Name, v.LicensePlate, v.type,
            COUNT(p.damages) AS Damages,
            COUNT(p.critical_damages) AS CriticalDamages,
            if ((v.serviceDistance/1000) < 1000, true, false) as ServiceDue,
            round(v.serviceDistance / 1000) as serviceDistance,
            if (mr.next_service_milage > NOW() - INTERVAL 6 WEEK, true, false) AS MOTDue,
            mr.next_service_milage,
            if (mr.tachograph_revoke_date > NOW() - INTERVAL 6 WEEK, true, false) AS TachoDue,
            mr.tachograph_revoke_date,
            round(v.OdoMeter / 1000) as OdoMeter,
            co.name as Country, 
			dd.name AS Service_Homedealer
        FROM
            vehicles v
            LEFT JOIN FAMReport f ON f.vin = v.vin
            LEFT JOIN trailers t on t.vehicleVIN = v.vin
            LEFT JOIN pdc_register p ON p.vehicle = v.vin
            LEFT JOIN mot_register mr ON mr.vehicle = v.vin
            LEFT JOIN CUSTOMERS c ON c.accountnumber = f.client 
			LEFT JOIN dealers_daf dd on dd.location = c.service_Homedealer
			LEFT JOIN countries co ON co.id = c.country_id ".$str3."
        WHERE
            (v.serviceDistance <= 15000
            OR p.damages > 0
            OR  mr.next_service_milage > NOW() - INTERVAL 6 WEEK
            OR mr.tachograph_revoke_date > NOW() - INTERVAL 6 WEEK
            OR p.critical_damages > 0) ".$str1." ".$str2."
        GROUP BY
            v.vin
        ORDER BY
            v.customerVehicleName ASC ";

    $Q = $db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>
	