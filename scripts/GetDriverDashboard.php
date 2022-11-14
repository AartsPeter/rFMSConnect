<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    if ($id==''){header('HTTP/1.0 401 Unauthorized');die();}
	
	$query=" SELECT d.id,d.tachoDriverIdentification,d.Phone_Mobile,d.email,
            (select dt.drive FROM drivetimes dt LEFT JOIN driver d ON d.tachoDriverIdentification=dt.driverId wHERE d.id='".$id."' AND dt.DriveDate=Date(NOW()) ) AS DriveToday,
            (SELECT trailerName FROM trailers WHERE trailers.vehicleVIN = d.LastVehicle AND copplingStatus=1 LIMIT 1) AS `TrailerName`,
            (SELECT vin FROM trailers WHERE trailers.vehicleVIN = d.LastVehicle AND copplingStatus=1 LIMIT 1) AS `TrailerVin`,
            (SELECT customerVehicleName FROM vehicles WHERE vehicles.VIN = d.LastVehicle ) AS `LastVehicleName`,
            (SELECT vin FROM vehicles WHERE vehicles.VIN = d.LastVehicle ) AS `LastVehicle`,
            (SELECT ROUND(odometer/1000,0) FROM vehicles WHERE vehicles.VIN = d.LastVehicle ) AS `odometer`,
            d.LastVehicle,
            IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS Driver
        FROM driver d
            LEFT JOIN famreport f on f.vin=d.LastVehicle
           ".$str3."
        WHERE
            d.id='".$id."' ".$str1.$str2;
	$DriverQ	= $db->query($query);
	$Driver 	= $DriverQ->first();
	if ( $Driver=='') {
        $Driver->LastVehicleName  = '';
        $Driver->TrailerName  = 'select a trailer';
    }
	$Driver->RemainingDriveToday    = SecToTime(32400 - $Driver->DriveToday);

    if(isset($_GET['SQL'])){
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Driver,'Current DriverInfo',true);
    } else {
        echo json_encode($Driver,JSON_NUMERIC_CHECK);
    }
?> 