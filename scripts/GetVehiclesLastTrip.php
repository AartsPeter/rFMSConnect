<?php require_once '../users/init.php'; ?>
<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
	//**********************************************************************
	// file used with parameter "?vin=xx"
	// file will return the last trip for the selected  all vehicle details to show within 'rfms_report.js'
	// Author : Peter Aarts QNH 2017
	// License free 
	//**********************************************************************
	
	// Check connection
	header("Content-Type: text/html; charset=utf-8mb4");
	ini_set("default_charset","UTF-8");
	mb_internal_encoding("UTF-8");
	iconv_set_encoding("internal_encoding","UTF-8");
	iconv_set_encoding("output_encoding","UTF-8");
	$db = DB::getInstance();
	$VehicleQ = $db->query("
	SELECT trips.StartDate, trips.TripActive, trips.start_odometer FROM rfms_reading.trips 
	WHERE trips.VIN='".$_GET["vin"]."'
	order by trips.StartDate desc limit 1");
	$Vehicles = $VehicleQ->results();
	echo json_encode($Vehicles);

?> 