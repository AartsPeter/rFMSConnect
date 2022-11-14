<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="SELECT vs.createdDateTime, vs.GNSS_latitude, vs.GNSS_longitude, vs.GNSS_heading 
		FROM `vehiclestatus` 	vs
			LEFT JOIN vehicles v ON v.VIN=vs.vin
			LEFT JOIN FAMReport f ON f.vin=vs.vin ".$str3." 
		WHERE v.id='".$id."' ".$str1." ".$str2." AND (vs.triggerType='TIMER' OR vs.triggerType='DISTANCE_TRAVELLED') 
		ORDER BY vs.createdDateTime DESC 
		LIMIT 0,11";
	$Q = $db->query($query);
	$Result=$Q->results();

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}
?>

