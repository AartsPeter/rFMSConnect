<?php 
require_once '../users/init.php'; 
if (!securePage($_SERVER['PHP_SELF'])){die();} 

//**********************************************************************
// file will return a array to show within 'rfms.js'
// Author : Peter Aarts QNH 2017
// License free 
//**********************************************************************
//header('Content-Type: text/plain');

	header("Content-Type: text/html; charset=utf-8");
	$db = DB::getInstance();
	$VehicleQ = $db->query("SELECT last_Latitude AS La,last_Longitude AS Lo FROM vehicles_rfms1 WHERE  last_Latitude!=0");
	$Vehicles = $VehicleQ->results();
	echo json_encode($Vehicles); 