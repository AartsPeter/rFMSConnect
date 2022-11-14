<?php
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $query=	"SELECT
            count(state),
            vehicles_telltales.state,
            v.customerVehicleName,
            vehicles_telltales.tellTale
        FROM
            vehicles_telltales USE INDEX (CountTellTales)
            LEFT JOIN vehicles v ON v.vin=vehicles_telltales.vin
            LEFT JOIN FAMReport f ON f.vin=vehicles_telltales.vin ".$str3."
        WHERE
            v.vehicleActive=true ".$str1." ".$str2." and
            vehicles_telltales.createdDateTime >= CURDATE() AND
            (STATE='RED' or STATE='YELLOW') AND
            vehicles_telltales.tellTale!='PARKING_BRAKE'
        GROUP BY
            vehicles_telltales.VIN";
    $TripsQ =$db->query($query);
    $Result=TripsQ->results();

    if (isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query, $Result,'', true);
    } else {
        echo json_encode($Result);
    }
 ?>