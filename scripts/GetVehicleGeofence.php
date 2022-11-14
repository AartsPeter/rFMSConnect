<?php 

	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';
		
	$query=	"
        SELECT
            gr.vin, gr.status,gr.prio,gr.registration,gr.alert,
            gd.name
        FROM
            geofence_reg gr
            LEFT JOIN vehicles v ON v.vin=gr.vin
            LEFT JOIN geofence_def gd ON gr.geofence_id=gd.id
            LEFT JOIN FAMReport f ON f.vin=v.VIN ".$str3."
        WHERE v.id='".$id."' " .$str1." ".$str2;

	$Q = $db->query($query);
	$Result = $Q->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result);
    } else {
        echo json_encode($Result);
    }
?>
