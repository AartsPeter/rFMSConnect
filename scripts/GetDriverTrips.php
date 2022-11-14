<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	If (isset($_GET["date"])) {
		$localDate = new DateTime(test_input($_GET["date"]));}
	else {
		$localDate= new DateTime();	}
	If (isset($_GET["id"])) {
		$driver = "d.id='".test_input($_GET["id"])."' ";}
	else {
	    if (isset($user->data()->driver_id)) {
			$driver="d.tachoDriverIdentification='".$user->data()->driver_id."' ";}
	    else {
			$driver=''; die();}
	}
    // setup query to collect data

	$query="SELECT
		t.Trip_NO,
		TIME_FORMAT(t.StartDate,'%H:%i') as StartDate,
		t.TripActive,
		TIME_FORMAT(SEC_TO_TIME(DriveTime),'%H:%i') AS DriveTime,
		TIME_FORMAT(SEC_TO_TIME(IdleTime),'%H:%i') AS IdleTime,
		TIME_FORMAT(SEC_TO_TIME(PreDepIdle),'%H:%i') AS PreDepIdle,
		round(t.Distance,1) as Distance,
		TIME_FORMAT(t.Duration,'%H:%i') AS Duration,
		round(t.FuelUsed,1) as FuelUsed,
		round(t.FuelUsage,1) as FuelUsage,
		TIME_FORMAT(t.EndDate,'%H:%i') as EndDate,
		t.start_odometer,
		IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS driver,
		v.customerVehicleName
	FROM trips t USE INDEX (idx_driverTrips)
		LEFT JOIN vehicles v ON v.vin=t.vin
		LEFT JOIN driver d ON t.Driver1ID=d.tachoDriverIdentification
		LEFT JOIN Famreport f ON f.vin=t.vin	".$str3."
	WHERE ".$driver." AND t.TripActive=false  AND date(t.EndDate)='".$localDate->format('Y-m-d')."' ".$str1." ".$str2."
	ORDER BY t.Trip_NO";
    $Q = $db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'Trips',true);
    } else {
        echo json_encode($Result);
    }
?>
