<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';


    $query0     = "SELECT '0'as `selected`, accountnumber,name FROM customers";
	$query      = "SELECT Cust_ID FROM rel_cust_user WHERE User_ID='".$id."' ORDER BY Cust_ID";
	// create array of all groups
	$groupsQ    = $db->query($query0);
	$relations  = $groupsQ->results();
    // create array of selected groups
	$Q 		    = $db->query($query);
	$selected   = $Q->results();
    // process relation of selected groups in group array
    foreach ($relations as $rel) {
        foreach ($selected as $val){
            if ($val->Cust_ID == $rel->accountnumber) { $rel->selected = 1;}
        }
    }
    $Result = $relations;
    // in case selected groups more than 1 add an option to select all groups
//    if (count($selected) >1){
//        $Result[] = array("selected"=>1,"accountnumber" => 0, "IsDealer" =>0, "id" => 0, "name" => "All Groups");
//        sort($Result);
//    }

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query0,$Result,'all groups',true);
		echo ShowDebugQuery($query,$selected,'selected group relations',true);
	} else {
		echo json_encode($Result);
	}
?>
	