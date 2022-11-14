<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	$query  = " SELECT
	                vehicles.vin,vehicles.customerVehicleName,customers.name,famReport.obu_sw_version,famReport.OBU_Serial,
	                vehicle_delayed.createdDate,vehicle_delayed.add_toDB,vehicle_delayed.latitude,vehicle_delayed.longitude
                FROM
                    vehicle_delayed
            	    INNER JOIN vehicles ON vehicles.vin=vehicle_delayed.vin
            	    INNER JOIN FAMReport ON FAMReport.vin=vehicle_delayed.VIN
            	    INNER JOIN customers ON customers.accountnumber=FAMReport.client
            	WHERE
            	    createdDate BETWEEN '".$StartDate."' AND '".$EndDate."'
            	GROUP BY
            	    vehicle_delayed.createdDate,vehicle_delayed.VIN ";
	$Q      = $db->query($query);
	$Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }

?>

