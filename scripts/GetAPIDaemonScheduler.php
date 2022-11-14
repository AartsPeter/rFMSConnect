<?php 
    require '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $query="SELECT a_s.*,
            	ast.protocol,ast.version,ast.installed,ac.active as CollectorActive
            FROM api_scheduler a_s
            	LEFT JOIN api_script_type ast on ast.id=a_s.scriptTypeId
            	LEFT JOIN api_collector ac on ac.id=a_s.collector_id
            ORDER BY lastExecution DESC  ";
    $Q = $db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'API monitoring Endpoints',true);
    } else {
        echo json_encode($Result);
    }

?>
	