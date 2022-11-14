<?php 
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    If (isset($_GET["id"]))
        { $search=" AND api_collector.id='".$_GET["id"]."' ";}
    else
        { $search ='';}

	$query="
        SELECT
            a_c.id, a_c.NAME AS Name,
            a_t.name as TypeName,
            (SELECT COUNT(a_s.id) FROM api_scheduler a_s WHERE  a_s.collector_id = a_c.id AND a_s.active=1) AS ActiveEndPoints,
            (SELECT COUNT(a_s.id) FROM api_scheduler a_s WHERE  a_s.collector_id = a_c.id AND a_s.active=0) AS InActiveEndPoints,
            (SELECT COUNT(a_s.id) FROM api_scheduler a_s WHERE  a_s.collector_id = a_c.id AND a_s.active=1 AND a_s.lastStatus != 200  ) AS `FailedEndPoints`,
            (SELECT COUNT(s_a.id) FROM settings_api s_a WHERE  s_a.active=1 ) AS `ActiveDaemon`
        FROM
            api_collector a_c
            LEFT JOIN api_scheduler a_s ON a_s.collector_id=a_c.id
            LEFT JOIN api_type a_t on a_t.id=a_c.typeId
        WHERE
            archived=0  AND a_c.active=1
        GROUP BY
            a_c.name
        order BY
            a_c.typeId ";
	$Q = $db->query($query);
	$Result = $Q->results();
	if(isset($_GET['detail'])) {
	    if ($_GET['detail']=='false'){
            $AEP=0;$IAEP=0;$FEP=0;$counter=0;$AD=0;
            foreach ($Result as $val){
                $AEP    += $val->ActiveEndPoints;
                $IAEP   += $val->InActiveEndPoints;
                $FEP    += $val->FailedEndPoints;
                $AD      = intval($val->ActiveDaemon);
                $counter++;
            }
            $Result=[[$AEP],[$IAEP],[$FEP],[$counter],[$AD]];
	    }
	}

    if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}
?>
	