<?php 
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    if ($id<>'-') {  $str5="WHERE a_s.collector_id=".$id; } else { $str5='';}

    $query="SELECT
          a_s.*,
          ast.name,
          ast.Description as Description,
          ast.Script
        FROM
          api_scheduler a_s
          LEFT JOIN api_script_type ast ON ast.Id = a_s.scripttypeId  ".$str5;
    $Q = $db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'API monitoring Endpoints',true);
    } else {
    echo json_encode($Result);
    }

?>
	