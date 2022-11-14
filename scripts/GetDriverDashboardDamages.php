<?php 
//**********************************************************************
// file used vin parameter '?vin=xxx'
// file will return a array to show within 'rfms.js'
// Author : Peter Aarts QNH 2020
// License free 
//**********************************************************************
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$localDate=new DateTime('now');
	$StartMonth=new DateTime('first day of this month');
	$DriverId=$user->data()->driver_id;
	$query="
	SELECT 
		COUNT(pr.id) AS Checks, 
	    c.name AS CustomerName,
		v.customerVehicleName,
		t.trailerName, 
		SUM(pr.damages) AS Damages,
        pt.templateName AS Template,
        pt.`group`
	FROM pdc_register pr
		LEFT JOIN vehicles v ON v.VIN=pr.vehicle
		LEFT JOIN trailers t ON t.vin=pr.trailer
		LEFT JOIN famreport f on f.VIN=pr.vehicle
		LEFT JOIN customers c on c.accountnumber=f.client
		LEFT JOIN pdc_template pt ON pt.id= pr.template ".$str3."
	WHERE
	    pr.driver='".$DriverId."' AND
	    date(pr.createdDate)=current_date() ".$str1." ".$str2."
	GROUP BY
    	v.customerVehicleName";
	$db = DB::getInstance();
	$PDCQ= $db->query($query);
	$Result = $PDCQ->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>
