<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $query      = "SELECT CONCAT(u.fname,' ',u.lname) AS contact,u.id FROM users u INNER JOIN rel_cust_user rcu ON rcu.User_ID=u.id where u.id!='".$user->data()->id."'GROUP BY U.id ORDER BY contact asc ";
    $Q 		    = $db->query($query);
	$Result     = $Q->results();


	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'Find the contacts for user',true);
	} else {
		echo json_encode($Result);
	}
?>
	