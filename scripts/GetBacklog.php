<?php 
	require_once '../users/init.php'; 
	require_once 'lib/scriptHeader.php';

	$query  = "SELECT * FROM backlog
		       WHERE CONVERT_TZ(requestDateTime,'+00:00','+02:00') BETWEEN '".$_GET["StartDate"]."' AND '".$_GET["EndDate"]."' AND Endpoint='".$_GET["Endpoint"]."'
		       ORDER BY backlog.requestDateTime ASC ");
	$Q      = $db->query($query);
	$Result = $Q->results();

    if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}



?>

