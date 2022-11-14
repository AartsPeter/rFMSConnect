<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="SELECT
			audit.*,users.username as user,pages.page
		FROM
			audit
			LEFT JOIN users ON users.id=audit.user
			LEFT JOIN pages ON pages.id=audit.page
		ORDER BY
			AUDIT.id DESC ";
    $Q = $db->query($query);
    $Result = $Q->results();

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}

?>
	