<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $query  = "SELECT id,Category,name,its_24hours,its_trailer,its_bus ,address	FROM dealers_daf d";

	$Q =$db->query($query);
    $Result = $Q->results();

    foreach ($Result as $val){
		foreach (json_decode(strip_tags($val->address)) as $adr){
			if ($adr->adc_code=='LC'){
				$val->adr_gps_latitude =$adr->adr_gps_latitude;
				$val->adr_gps_longitude=$adr->adr_gps_longitude;
				$val->adr_address_1=$adr->adr_address_1;
				$val->adr_postalcode=$adr->adr_postalcode;
				$val->adr_city=$adr->adr_city;
				$val->cnt_description=$adr->cnt_description;
				$val->address='';
			}
		}
	}

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}

?>

