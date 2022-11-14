<?php 
	//**********************************************************************
	// file used with parameter "?vin=xx"
	// file will return a array to show  1 specific trip of vehicle'
	// Author : Peter Aarts QNH 2019
	// License free 
	//**********************************************************************
	require_once '../users/init.php'; 
	if (!securePage($_SERVER['PHP_SELF'])){die();} 
		
	// Check connection
	header("Content-Type: text/html; charset=utf-8mb4");
	ini_set("default_charset","UTF-8");
	mb_internal_encoding("UTF-8");
	iconv_set_encoding("internal_encoding","UTF-8");
	iconv_set_encoding("output_encoding","UTF-8");

	$db = DB::getInstance();
	$MarkersQ = $db->query("
		SELECT createdDateTime,GNSS_latitude,GNSS_longitude,GNSS_heading FROM vehiclestatus 
		WHERE vin='".$_GET["vin"]."' AND vehiclestatus.createdDateTime>='".$_GET["StartDate"]."' AND vehiclestatus.createdDateTime<='".$_GET["EndDate"]."' 
		ORDER BY createdDateTime DESC");
	$Markers=$MarkersQ->results();
	echo json_encode($Markers);
?>
