<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    If (isset($_GET["id"]))
        { $search=" AND ac.id='".$_GET["id"]."' ";}
    else
        { $search ='';}

	$query="SELECT a_s.* FROM api_scheduler a_s LEFT JOIN api_collector ac ON a_s.collector_id=ac.id WHERE archived=0 AND a_s.active=TRUE   ".$search;

	$APIsQ = $db->query($query);
	$Result = $APIsQ->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'API monitoring Endpoints',true);
    } else {
        echo json_encode($Result);
    }
?>
	