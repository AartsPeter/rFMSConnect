<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    if (isset($_GET["id"]))
        { $search=" AND api_collector.id='".test_input($_GET["id"])."' ";}
    else
        { $search ='';}
	$query="
	SELECT api_collector.*,
		IF (CHAR_LENGTH(api_collector.username)>2,TRUE, FALSE) AS Account,
		at.name as TypeName,
		(SELECT COUNT(api_scheduler.id) FROM api_scheduler WHERE api_scheduler.collector_id=api_collector.id) AS EndPoints,
		(SELECT COUNT(api_scheduler.id) FROM api_scheduler WHERE api_scheduler.collector_id=api_collector.id AND api_scheduler.lastStatus!=200 ) AS `CountFI`
	FROM api_collector 
		LEFT JOIN api_scheduler ON api_scheduler.collector_id=api_collector.id
		LEFT JOIN api_type at on at.id=api_collector.typeId
	WHERE archived=0 ".$search."
	GROUP BY api_collector.name
	order BY api_collector.typeId ";
	$APIsQ  = $db->query($query);
	$Result = $APIsQ->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>
	