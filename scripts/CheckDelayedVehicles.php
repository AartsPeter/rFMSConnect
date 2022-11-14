<?php 
//**********************************************************************
// // file will update the vehicle_delayed table
// Author : Peter Aarts QNH 2019
// License free 
//**********************************************************************
//header('Content-Type: text/plain');

function Checkdelayed(){ 
	echo "\n\r > Check for delayed vehicles and update table \n\r";
	$json_a = json_decode(file_get_contents("config.json"), true);
	$current_date= new DateTime($json_a['lastUpdateDateTime']);
	$newTime=$current_date;
	$newTime->sub(new DateInterval('PT15M'));
	$db = new mysqli('localhost', 'root', 'DAFConn3ct2018!', 'rfms_reading');
	$VehicleQ = $db->query("
		SELECT 	VIN,currentSpeed,LastActivity, last_Latitude,last_Longitude
		FROM vehicles 
		WHERE vehicles.vehicleActive=TRUE AND vehicles.currentSpeed>1 AND vehicles.LastActivity<'".$newTime->format('Y-m-d H:i:s')."'");
	echo "\n\r   - ".$VehicleQ->num_rows." at  ".$current_date->format('Y-m-d H:i:s')."\n\r";
	$counter=0;
	if ($VehicleQ->num_rows>0){
		$sqlD=' ';
		echo "   ";
		$Vehicles=mysqli_fetch_all($VehicleQ,MYSQLI_ASSOC);
		foreach ($Vehicles as $val){
			if ($val["last_Latitude"]<>0){
				$sqlD .="('".$val['VIN']."','".$current_date->format('Y-m-d')."','".$val["LastActivity"]."','".$val["last_Latitude"]."','".$val["last_Longitude"]."'),";
				$counter++;
				echo ".";
			}
		}
		$sqlD=rtrim($sqlD,',');
		$sqlD ="INSERT IGNORE into vehicle_delayed (VIN,createdDate,receivedDateTime,latitude,longitude) values \n\r".$sqlD.";";
		mysqli_query($db,$sqlD);
		$sqlD ="INSERT IGNORE into stat_delayed_count (createdDate,count) values ('".$current_date->format('Y-m-d H:i:s')."','".$VehicleQ->num_rows."')" ;
		echo "\n\r".$sqlD;
		mysqli_query($db,$sqlD);
		mysqli_close($db);
	}
	echo "\n\r   - Delayed vehicles inserted : ".$counter."\n\r \n\r";
}
?>