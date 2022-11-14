<?php

	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';


	$query="SELECT d.id,d.tachoDriverIdentification,d.RemaingDriveToday,
			IF(driver.Lastname='',driver.tachoDriverIdentification,concat(driver.Lastname,', ',driver.Surname)) AS driver
		FROM driver d
			LEFT JOIN vehicles v ON f.vin=driver.LastVehicle
			LEFT JOIN FAMREPORT f ON f.vin=driver.LastVehicle ".$str3."
		WHERE id='".$id."' ".$str1." ".$str2;

	$Q = $db->query($query);
	$Result = $Q->results();

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}


?> 