<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    $query="
	SELECT
		c.*,d.LastVehicle,
		COUNT(distinct v.vin) AS TotalVehicles,
		COUNT(d.LastVehicle) AS TotalDrivers
	FROM vehicles v
		LEFT JOIN FAMReport f ON v.VIN=f.vin 
		LEFT JOIN customers c ON c.accountnumber=f.client ".$str3."
		LEFT JOIN driver d ON d.LastVehicle=v.VIN
	GROUP BY c.name";
    $Q =$db->query($query);

    $Result = $Q->results();
    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
    ?>