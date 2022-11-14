<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

//**********************************************************************
// file used with '?customer=' parameter
// file will return a array to show within 'rfms.js'
// Author : Peter Aarts QNH 2022
// License free
//**********************************************************************

$db = DB::getInstance();
$query="
	SELECT pdc_damage.*, pdc_damageitems.*,
	    date(pdc_damage.createdDateTime) AS DamageCreated,
	    date(pdc_damage.repairDateTime) AS DamageRepaired,
	    pdc_categories.cat_name,
	    pdc_subcategories.subcat_name ,
		IF(driver.Lastname='',driver.tachoDriverIdentification,concat(driver.Lastname,', ',driver.Surname)) AS driver
	FROM pdc_damage
		LEFT JOIN FAMReport f ON F.vin=pdc_damage.VIN
		LEFT JOIN vehicles on vehicles.vin=pdc_damage.VIN
		LEFT JOIN driver ON pdc_damage.driver=driver.tachoDriverIdentification
		LEFT JOIN pdc_damageitems ON pdc_damageitems.ID=pdc_damage.eventID
		LEFT JOIN pdc_categories ON pdc_categories.ID=pdc_damageitems.cat_id
		LEFT JOIN pdc_subcategories ON pdc_subcategories.ID=pdc_damageitems.subcat_id ".$str3."
	WHERE vehicles.id='".$id."' AND repairstatus=1 ".$str1." ".$str2."
	ORDER BY pdc_damageitems.severity ASC";
$VehicleQ = $db->query($query);
$Vehicles = $VehicleQ->results();

if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Vehicles,'',true);
} else {
    echo json_encode($Vehicles);
}
?>
	