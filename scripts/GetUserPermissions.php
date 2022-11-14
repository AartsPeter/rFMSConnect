<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    //check current operator permission level
	$permOp     = CheckPermissionLevel();

    $query0     = "SELECT id,name FROM permissions WHERE id>=".$permOp;
	$permQ      = $db->query($query0);
	$permissions= $permQ->results();
    $query      = "SELECT permission_id as `id` FROM user_permission_matches WHERE user_id='".$id."' ORDER BY permission_id";
	$Q 		    = $db->query($query);
	$selected   = $Q->results();
    foreach ($permissions as $perm) {
        $perm->selected = 0;
        foreach ($selected as $val){
            if ($val->id == $perm->id) { $perm->selected = 1;}
        }
    }
    $Result = $permissions;

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query0,$Result,'',true);
		echo ShowDebugQuery($query,$selected,'',true);
	} else {
		echo json_encode($Result);
	}
?>
	