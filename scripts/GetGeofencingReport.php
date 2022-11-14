<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';
    // select data based on query
    $query="SELECT
            	v.customerVehicleName,gfd.NAME AS GeofenceName,v.vin,c.NAME AS groupName,
            	gfr.STATUS AS CurrentStatus,JSON_UNQUOTE(gfr.registration) as registration,JSON_UNQUOTE(gfr.alert) AS alert, JSON_UNQUOTE(gfr.prio) as prio,
            	gfc.NAME AS categoryName,
            	(select count(gfl.id) from geofence_log gfl where gfl.vin=gfr.vin AND gfl.createdDateTime BETWEEN '".$SD."' AND '".$ED."' ) AS countTrigger,
            	gfr.latitude,gfr.longitude
            FROM
            	geofence_reg gfr
            	LEFT JOIN vehicles v ON v.vin = gfr.vin
            	LEFT JOIN famreport F ON F.VIN=V.VIN
            	LEFT JOIN customers c ON F.client=c.accountnumber
            	LEFT JOIN geofence_def gfd ON gfd.id = gfr.geofence_id
            	LEFT JOIN geofence_cat gfc ON gfc.id = gfd.category
            	".$str3."
            WHERE
            	v.vehicleActive=true ".$str1." ".$str2."
            ORDER BY
                gfr.geofence_id";
    $Q = $db->query($query);
    $Results = $Q->results();

    // returning the result
    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Results,'',true);
    } else {
        echo json_encode($Results);
    }
