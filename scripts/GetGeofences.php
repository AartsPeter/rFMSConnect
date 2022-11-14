<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="(SELECT gfd.active,gfd.name,gfd.id,gfd.description,gfd.type_id,gfd.scope,gfd.client,if (gfd.createdBy='".$user->data()->id."','true','false') AS editable,
            	gft.name As geofence_type, gft.description, gfc.name as categoryName, 	v.customerVehicleName,
            	(SELECT COUNT(vin) FROM geofence_reg WHERE geofence_id = gfd.id) AS counted
            FROM
                geofence_def gfd
                LEFT JOIN geofence_reg gfr ON gfr.geofence_id = gfd.id
                LEFT JOIN geofence_type gft ON gft.id = gfd.type_id
                LEFT JOIN geofence_cat gfc ON gfc.id = gfd.category
                LEFT JOIN FAMReport f ON f.vin = gfr.vin
                LEFT JOIN vehicles v ON v.vin=gfr.vin
		WHERE gfd.scope = 1 AND gfd.createdBy='".$user->data()->id."' GROUP BY gfd.id)
        UNION
        (SELECT
            gfd.active, gfd.name, gfd.id, gfd.description, gfd.type_id, gfd.scope, gfd.client, if (gfd.createdBy = '".$user->data()->id."', 'true', 'false'  ) AS editable,
            gft.name As geofence_type, gft.description, gfc.name as categoryName, v.customerVehicleName,
            (SELECT COUNT(vin) FROM geofence_reg WHERE geofence_id = gfd.id) AS counted
         FROM
             geofence_def gfd
             LEFT JOIN geofence_reg gfr ON gfr.geofence_id = gfd.id
             LEFT JOIN geofence_type gft ON gft.id = gfd.type_id
             LEFT JOIN geofence_cat gfc ON gfc.id = gfd.category
             LEFT JOIN vehicles v ON v.vin=gfr.vin
         WHERE gfd.scope!=1 GROUP BY gfd.id)  ";
	$Q = $db->query($query);
	$Result = $Q->results();
	$geofences=[];
	$geofences["system"]=array();
	$geofences["public"]=array();
	$geofences["personal"]=Array();
	foreach ($Result as $val){
	    if ($val->scope == 0){ array_push($geofences["system"],$val);}
	    if ($val->scope == 1){ array_push($geofences["personal"],$val);}
	    if ($val->scope == 2){ array_push($geofences["public"],$val);}
    }
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$geofences,'',true);
    } else {
        echo json_encode($geofences);
    }
?>
	