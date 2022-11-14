<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';


    $query="...your custom database query (MySQL)";
	$Q = $db->query($query);
	$Results = $Q->results();

	// manipulate your query data
	foreach ($Results as $row ){
		...
	}

	//show result (return JSON or HTML response for debugging using parameter ...'SQL'
	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Results,'your title',true);
	} else {
		echo json_encode($Results);
	}

?>
	