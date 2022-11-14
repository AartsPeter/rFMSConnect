<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	$query  = " SELECT COUNT(vehicle_delayed.vin), createdDate
                	FROM vehicle_delayed
                	WHERE Date(createdDate)>='".$_GET["StartDate"]."' and Date(createdDate)<='".$_GET["EndDate"]."'
                	GROUP BY vehicle_delayed.createdDate,vehicle_delayed.vin  ";
	$Q      = $db->query($query);
	$Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }

?>
