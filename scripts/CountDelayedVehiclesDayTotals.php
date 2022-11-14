<?php
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="
	    SELECT
	        HOUR(stat_delayed_count.createdDate) AS Hours, Count
	    FROM
	        stat_delayed_count
	    WHERE
	        stat_delayed_count.createdDate BETWEEN '".$_GET["StartDate"]."' AND '".date('Y-m-d', strtotime($_GET["StartDate"]. ' + 1 days'))."'
	    GROUP BY HOUR(stat_delayed_count.createdDate)";
	$ResultQ =$db->query($query);
	$Result = $ResultQ->results();


    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }

?>