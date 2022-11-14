<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    //create connection and query
	$query = "SELECT * FROM reporting";
	$ReportsQ = $db->query($query);
	$Results = $ReportsQ->results();

	//show result (return JSON or HTML response for debugging
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Results);
    } else {
        echo json_encode($Results);
    }
?> 