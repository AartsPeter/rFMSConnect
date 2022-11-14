<?php 

    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
	$settings = $settingsQ->first();
	$SD=date("Y-m-d", strtotime("- ".($settings->daysStatistics-1)." days"));

	$db = DB::getInstance();
	$query="
		select
		(	SELECT
		        COUNT(TRIPS.VIN)
			FROM
		        trips
		        LEFT JOIN vehicles V ON v.VIN =TRIPS.vin
		        LEFT JOIN FAMReport F ON F.vin=trips.VIN  ".$str3."
			WHERE
			    v.vehicleActive=true AND
			    trips.StartDate>='".$SD."' ".$str1." ".$str2.")  AS TotTripsToday,
		(	SELECT
		        sum((v.TotalFuelUsed-vehicle_stat_fuel.totalFuelUsed)/1000)
			FROM
			    vehicle_stat_fuel
			    LEFT JOIN FAMReport F ON F.vin=vehicle_stat_fuel.vin
			    LEFT JOIN vehicles v ON v.VIN =vehicle_stat_fuel.vin ".$str3."
			WHERE
			    v.vehicleActive=true AND
			    vehicle_stat_fuel.createDate>='".$SD."' ".$str1." ".$str2.") AS TotFuelToday,
		( 	SELECT
		        sum((v.OdoMeter-vehicle_stat_km.odometer )/1000)
			FROM vehicle_stat_km	
			INNER JOIN FAMReport F ON F.vin=vehicle_stat_km.vin
			LEFT JOIN vehicles V ON v.VIN =vehicle_stat_km.vin ".$str3."
			WHERE v.vehicleActive=true AND vehicle_stat_km.createDate>='".$SD."' ".$str1." ".$str2." ) AS TotDistanceToday";
	$TripsQ =$db->query($query);
	$Result= $TripsQ->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }


?>