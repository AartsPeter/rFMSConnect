<?php 
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    if ($id<>'-') {  $str5="WHERE a_s.collector_id=".$id; } else { $str5='';}
    $query="
		SELECT
			ast.*,          
			at.name as ApiTypeName
		FROM
			api_script_type ast
			LEFT JOIN api_type at ON at.id = ast.typeId
			".$str5;
    $Q = $db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'API Script Types',true);
    } else {
		echo json_encode($Result);
    }
?>
	