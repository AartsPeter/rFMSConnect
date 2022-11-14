<?php
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="
		SELECT
		    HOUR(vehicle_delayed.add_toDB) AS Hours,
		    COUNT(*) AS `new` ,
		    `Count`.count
		FROM
		    vehicle_delayed INNER JOIN
			(SELECT HOUR(stat_delayed_count.createdDate) AS Hours, stat_delayed_count.COUNT FROM stat_delayed_count WHERE stat_delayed_count.createdDate BETWEEN '".$_GET["StartDate"]."' AND '".date('Y-m-d', strtotime($_GET["StartDate"]. ' + 1 days'))."' GROUP BY HOUR(stat_delayed_count.createdDate)) as `Count`
    		ON  HOUR(vehicle_delayed.add_toDB) = `Count`.Hours
		WHERE
		    vehicle_delayed.add_toDB BETWEEN '".$_GET["StartDate"]."' AND '".date('Y-m-d', strtotime($_GET["StartDate"]. ' + 1 days'))."'
		GROUP BY
		    HOUR(vehicle_delayed.add_toDB)";
	$ResultQ =$db->query($query);
	$Result = $ResultQ->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }


?>