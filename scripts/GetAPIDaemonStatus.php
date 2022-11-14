<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';
    error_reporting(E_ALL);

	$query="SELECT * from settings_api";
	$Q  = $db->query($query);
	$Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>
	