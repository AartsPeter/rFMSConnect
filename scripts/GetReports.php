<?php 
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    //create connection and query
	$query = "	
		SELECT 
			rp.*,reporting.Name AS ReportName, customers.name AS CustomerName, concat(users.lname,', ',users.fname) as owner,upm.permission_id as level
		FROM 
			report_planning rp
			LEFT JOIN reporting ON rp.report_type=reporting.id
			LEFT JOIN users ON rp.creator=users.id
			LEFT JOIN user_permission_matches upm ON user_id=users.id
			LEFT JOIN customers ON customers.accountnumber=rp.customer
		WHERE upm.permission_id <4 and users.id=".$user->data()->id." and rp.reporting_frequency!='once'
		GROUP BY rp.id
		ORDER BY CustomerName ASC";
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