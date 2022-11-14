<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query  = "SELECT createdDate,HOUR(receivedDateTime) as HourReg,COUNT(vin) as CountVin
	            FROM vehicle_delayed
               	WHERE createdDate BETWEEN '".$_GET["StartDate"]."' AND '".$_GET["EndDate"]."'
               	GROUP BY createdDate, HOUR(receivedDateTime) div 1000";

	$Q =$db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }

?>

