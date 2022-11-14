<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    //create connection and query
	$query  = "	SELECT
                    rq.id,rq.report_id,rq.queryName,rq.queryDescription,rq.sequence,rq.active,concat(u.fname,'',u.lname) as Creator,
                    r.name as ReportName
                FROM
                    reporting_queries rq
                    LEFT JOIN reporting r ON rq.report_id=r.id
                    LEFT JOIN users u ON rq.createdBy=u.id
                ORDER BY rq.report_id,rq.sequence";
	$Q     = $db->query($query);
	$Result = $Q->results();

	//show result (return JSON or HTML response for debugging
    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?> 