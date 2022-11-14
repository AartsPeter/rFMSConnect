<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query = "	
		SELECT
        	pp.*, u.username as reportuser,g.name as 'GroupName'
        FROM
        	pdc_policies pp
        	LEFT JOIN users u ON pp.createdby=u.id
        	LEFT JOIN customers g ON pp.group=g.accountnumber
        	LEFT JOIN pdc_template pt on pt.id=pp.template_id
        ORDER BY
        	pp.name";

	$Q = $db->query($query);
	$Result = $Q->results();

	//show result (return JSON or HTML response for debugging
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?> 