<?php 
//**********************************************************************
// file will return a array to show within 'rfms_report.js'
// Author : Peter Aarts QNH 2021
// License free 
//**********************************************************************
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';
    if (!securePage($_SERVER['PHP_SELF'])){die();}

	$query="
	    SELECT	tt.*,'' as selected
		FROM
		    vehicles_telltales tt USE INDEX (TellTales_Search)
			LEFT JOIN vehicles v ON v.VIN=tt.vin
			LEFT JOIN FAMReport F ON F.vin=tt.vin
			".$str3."
			WHERE
			v.id='".$id."' AND ( state='YELLOW' OR state='RED') AND createdDateTime BETWEEN '".$SD."' AND '".$ED."'  ".$str1.$str2;
	$TripsQ = $db->query($query);
	$Result = $TripsQ->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result);
    } else {
        echo json_encode($Result);
    }

?>