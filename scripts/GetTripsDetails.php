<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';


   // CheckVehicleAuthorisation($Afields);

	$query="
	SELECT 	
		v.customerVehicleName, v.id,
		t.*,
		TIME_FORMAT(Duration,'%H:%i') as Duration,
		TIME_FORMAT(SEC_TO_TIME(DriveTime),'%H:%i') AS DriveTime,
		TIME_FORMAT(SEC_TO_TIME(IdleTime),'%H:%i') AS IdleTime,
		TIME_FORMAT(SEC_TO_TIME(PreDepIdle),'%H:%i') AS PreDepIdle,
		ROUND(t.Distance,1) as Distance,
		ROUND(t.FuelUsed,1) as FuelUsed,
		ROUND(t.FuelUsage,1) as FuelUsage,
		IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS Driver
	FROM
	    trips t
	    INNER JOIN vehicles v ON v.VIN=t.VIN
	    INNER JOIN FAMReport f ON f.vin=v.VIN
	    LEFT JOIN driver d ON d.tachoDriverIdentification=t.Driver1ID ".$str3."
	WHERE
	    v.id='".$id."' and
	    t.Distance>1 and
	    v.vehicleActive=true ".$str1." ".$str2." AND
	    t.StartDate between '".$SD."' AND '".$ED."'
	GROUP BY t.Trip_NO
	ORDER BY t.StartDate ASC";
	$Q = $db->query($query);
	$Result = $Q->results();
	if ($Q->count()>0){
		foreach ($Result as $val){
			$val->start_odometer = intval($val->start_odometer/1000);
			$val->end_odometer   = intval($val->end_odometer/1000);
		}
	}
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>
