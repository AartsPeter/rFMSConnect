<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    //create connection and query
	$query  = "	SELECT
	                vehicles_telltales.tellTale, vehicles_telltales.state,vehicles_telltales.createdDateTime
                FROM
                    vehicles_telltales
                WHERE
                    vin='".$_GET["vin"]."' AND createdDateTime BETWEEN '".$StartDT->format('Y-m-d')."' AND '".$EndDT->format('Y-m-d')."'
                ORDER BY
                    createdDateTime ASC";
	$RQ     = $db->query($query);
	$Result = $Q->results();

	//show result (return JSON or HTML response for debugging
    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Results);
    }
?>

