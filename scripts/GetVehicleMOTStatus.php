<?php 
//**********************************************************************
// file will return a array to show within 'rfms_report.js'
// Author : Peter Aarts QNH 2021
// License free 
//**********************************************************************
require_once '../users/init.php'; 
require_once 'lib/scriptHeader.php';

	$query="SELECT * FROM mot_register mr
			LEFT JOIN vehicles v ON v.VIN=mr.vehicle
			LEFT JOIN FAMReport F ON F.vin=mr.vehicle
			".$str3."
			WHERE  v.id='".$_GET["id"]."' ".$str1." ".$str2;
	$Q =$db->query($query);
    $Results = $Q->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Results,'',true);
    } else {
        echo json_encode($Results);
    }
?>