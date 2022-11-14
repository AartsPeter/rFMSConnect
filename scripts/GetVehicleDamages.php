<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

$query="
	SELECT p.createdDateTime,p.severity,
	    pdc_categories.cat_name,
	    pdc_subcategories.subcat_name ,
		IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS driver,
		pdc_damageitems.title as description,p.repairStatus
	FROM pdc_damage p
		LEFT JOIN FAMReport f ON F.vin=p.VIN
		LEFT JOIN vehicles v on v.vin=p.VIN
		LEFT JOIN driver d ON p.driver=d.tachoDriverIdentification
		LEFT JOIN pdc_damageitems ON pdc_damageitems.ID=p.eventID
		LEFT JOIN pdc_categories ON pdc_categories.ID=pdc_damageitems.cat_id
		LEFT JOIN pdc_subcategories ON pdc_subcategories.ID=pdc_damageitems.subcat_id ".$str3."
	WHERE v.id='".$id."' AND pdc_categories.cat_type_id='1' ".$str1." ".$str2."
	ORDER BY p.repairStatus ASC";
$VehicleQ = $db->query($query);
$Vehicles = $VehicleQ->results();

if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Vehicles,'',true);
} else {
    echo json_encode($Vehicles);
}
?>
	