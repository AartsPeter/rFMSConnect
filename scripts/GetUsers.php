<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
    $settings = $settingsQ->first();

    if (CheckPermissionLevel()>2) { $Restrict=" WHERE u.account_owner='".$user->data()->id."'";} else { $Restrict ="";}

	$query="
	    SELECT
	        u.*,
	        CONCAT(m.lname, ', ', m.fname) AS 'creator',
	        GROUP_CONCAT(p.name) as roles
	    FROM
	        users u
	        INNER JOIN users m ON m.id = u.account_owner
		    INNER JOIN user_permission_matches upm ON u.id=upm.user_id
    	    INNER JOIN permissions p ON p.id=upm.permission_id 
		".$Restrict."
    	GROUP BY u.id";
    $UsersQ = $db->query($query);
	$Result = $UsersQ->results();
	$localDate=new DateTime('now');
	foreach ($Result as $val){
	    $val->pwa=0;
	    if ($val->last_passwordreset=="0000-00-00 00:00:00"){
	        $a=new DateTime($val->join_date);
	    }
	    else {
	        $a=new DateTime($val->last_passwordreset);
	    }
	    $pwa=$localDate->diff($a);
	    $pwa=$pwa->format('%a');
	    if ($pwa>$settings->max_pwa){ $val->pwa=1;}
	}
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>
