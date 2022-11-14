<?php

	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';
	$query="
        SELECT
            v.customerVehicleName,
            v.odometer,
            v.id,v.vin,
            v.LicensePlate,
            v.brand,v.model
        FROM
            vehicles v
            LEFT JOIN famreport f on f.vin=v.VIN ".$str3."
        WHERE
            v.vehicleActive=true ".$str1." ".$str2."
        GROUP BY
            v.VIN";
	$Q 		= $db->query($query);
	$Result = $Q->results();

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}

?>

