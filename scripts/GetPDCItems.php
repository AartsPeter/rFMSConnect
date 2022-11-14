<?php
require_once '../users/init.php';
require_once 'lib/scriptHeader.php';

    $query="SELECT p.check,id,maincategory FROM pdc_checkitems p WHERE maincategory='".$id."'";
	$Q = $db->query($query);
	$Results = $Q->results();

	//show result (return JSON or HTML response for debugging
	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Results,'PreDepatureCheck-Items',true);
	} else {
		echo json_encode($Results);
	}


?>
	