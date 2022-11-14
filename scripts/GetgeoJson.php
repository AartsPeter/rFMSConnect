<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="SELECT gfd.NAME, gfd.description,gfd.scope,gfr.prio AS prio,gfr.registration AS registration,gfr.alert AS alert,gfd.geojson FROM geofence_def gfd LEFT JOIN geofence_reg gfr ON gfr.geofence_id = gfd.id WHERE GFD.ID = ".$id." GROUP BY gfd.id ";
	$Q = $db->query($query);
	$Result = $Q->first();
    $Result = json_decode($Result->geojson,true);
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {

        echo json_encode($Result);
    }
?>
	