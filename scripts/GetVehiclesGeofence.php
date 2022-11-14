<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $Result=[];
    $query0="
	    SELECT
	        v.vin,v.customerVehicleName,v.licensePlate,v.id,
	        if(gfr.geofence_id='".$id."',true,false) AS Selected
        FROM
            vehicles v
            LEFT JOIN geofence_reg gfr ON gfr.vin=v.vin
            LEFT JOIN FAMReport f ON v.VIN=f.vin
            ".$str3."
        WHERE
            vehicleActive=true  ".$str1." ".$str2."
        ORDER BY
            v.customerVehicleName DESC";
	$VehicleQ = $db->query($query0);
	$Vehicles = $VehicleQ->results();

	$query1="
	    SELECT
	        gfd.NAME, gfd.description,gfd.scope,
	        gfr.prio AS prio,
	        gfr.registration AS registration,
	        gfr.alert AS alert
	    FROM
	        geofence_def gfd
	        LEFT JOIN geofence_reg gfr ON gfr.geofence_id = gfd.id
	    WHERE
	        gfd.id = ".$id."
	    GROUP BY
	    gfd.id ";
	$GFQ = $db->query($query1);
	$Geofence = $GFQ->results();
    $Result['geofence'] = $Geofence;
    $Result['vehicles'] = $Vehicles;
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query1,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
