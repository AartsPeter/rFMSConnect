<?php
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query ="
        SELECT
            customers.*,
            COUNT(vehicles.vin) AS Total
        FROM vehicles
            INNER JOIN FAMReport ON vehicles.VIN=FAMReport.vin
            INNER JOIN customers ON customers.accountnumber=FAMReport.client
        GROUP BY customers.name;";
	$VehicleQ =$db->query($query);
	$Result = $VehicleQ->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
}
	
	
?>