<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="
	    SELECT
	        date(p.createdDate) as date,p.id,
			IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS driver,
			v.customerVehicleName, t.TrailerName,c.name as groupName,
			pt.templateName,
			p.critical_damages,p.damages,p.add_notes,p.signature, p.checked, p.approvedFm, p.approvedDate,
            IF(u.lname='',u.username,concat(u.lname,', ',u.fname)) AS FleetManager
		FROM pdc_register p
            LEFT JOIN driver d ON p.driver=d.tachoDriverIdentification
            LEFT JOIN vehicles v ON p.vehicle=v.VIN
            LEFT JOIN pdc_template pt on p.template=pt.id
            LEFT JOIN trailers t ON p.trailer=t.VIN
            LEFT JOIN USERS u on p.fm_Id=u.id
            LEFT JOIN FAMReport f ON p.vehicle=f.vin
            LEFT JOIN Customers c ON f.client=c.accountnumber
		".$str3."
		WHERE
		    v.vehicleActive=true ".$str1." ".$str2." AND p.createdDate BETWEEN '".$SD."' AND '".$ED."'
		ORDER BY
		    Date ASC";
	$Q = $db->query($query);
	$Result = $Q->results();

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}
?>
