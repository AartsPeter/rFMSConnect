<?php 
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    If (isset($_GET["id"]))
        { $search=" AND a_c.id='".$_GET["id"]."' ";}
    else
        { $search ='';}

	$query="
	 SELECT
       a_c.*,
       a_t.name as TypeName,
	   aat.auth_type as auth_type,
       (SELECT COUNT(a_s.id) FROM api_scheduler a_s WHERE  a_s.collector_id = a_c.id ) AS EndPoints,
       (SELECT COUNT(a_s.id) FROM api_scheduler a_s WHERE  a_s.collector_id = a_c.id AND a_s.lastStatus != 200  ) AS `CountFI`
     FROM
     	api_collector a_c
     	LEFT JOIN api_scheduler a_s ON a_s.collector_id=a_c.id
        LEFT JOIN api_type a_t on a_t.id=a_c.typeId
		LEFT JOIN api_auth_type aat on aat.id=a_c.auth_type
     WHERE
     	archived=0 ".$search."
     GROUP BY
     	a_c.name
     order BY
     	a_c.typeId
	 ";
	$Q = $db->query($query);
	$Result = $Q->results();

    if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}
?>
	