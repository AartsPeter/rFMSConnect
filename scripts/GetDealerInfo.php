<?php

	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query = "SELECT * FROM dealers_daf WHERE id='".$id."' ";

	$Q = $db->query($query);
	$Result = $Q->results();

	foreach ($Result as $val) {
		$val->Address           = json_decode($val->Address);
		$val->Communication     = json_decode($val->Communication);
		$val->Person            = json_decode($val->Person);
		$val->CompanyBusiness   = json_decode($val->CompanyBusiness);
		$val->Openinghour       = json_decode($val->Openinghour);
	}

	if (isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query, $Result,'', true);
	} else {
		echo json_encode($Result);
	}
?> 