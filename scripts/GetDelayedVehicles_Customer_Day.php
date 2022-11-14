<?php

	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query = "
		SELECT
		    vehiclestatus.*, customers.name, vehicles.customerVehicleName
		FROM
		    vehiclestatus
		    INNER JOIN FAMReport ON vehiclestatus.VIN=FAMReport.vin
		    INNER JOIN customers ON customers.accountnumber=FAMReport.client
		WHERE
		    customers.accountnumber='".$_GET["CustomerID"]."' and createdDate BETWEEN '".$_GET["StartDate"]."' AND '".$_GET["EndDate"]."'
		GROUP BY
		    vehicles.vin";
	$Q = $db->query($query);
	$Result = $Q->results();

    if (isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query, $Result,'', true);
    } else {
        echo json_encode($Result);
    }
?>