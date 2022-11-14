<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $query="SELECT * FROM performancelog p WHERE  p.DATETIME > ADDDATE( CURRENT_TIMESTAMP(), INTERVAL -60 MINUTE )  ORDER BY	p.DateTime ASC ";
    $Q = $db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'API Logging Endpoints',true);
    } else {
        echo json_encode($Result);
    }

?>
	