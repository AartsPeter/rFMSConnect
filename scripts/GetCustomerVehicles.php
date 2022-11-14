<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	$query="
		SELECT v.id, v.vehicleActive,v.VIN,v.customerVehicleName, v.LicensePlate
		FROM vehicles v
			INNER JOIN famreport f ON f.vin=v.VIN  ".$str3."
		WHERE  v.vehicleActive=true ".$str1." ".$str2;
	$Q      = $db->query($query);
	$Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>