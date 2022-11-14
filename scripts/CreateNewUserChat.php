<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	error_reporting(E_ALL);
	date_default_timezone_set('Europe/Amsterdam');

    // insert new
    $query="select * from message_threads mt where (mt.msg_to='".$id."' AND mt.msg_from='".$user->data()->id."') or (mt.msg_from='".$id."' AND mt.msg_to='".$user->data()->id."')";
    //check if thread already exist between to id's
    $Q      = $db->query($query);
    $Result = $Q->results();
    if ($Q->count()==0){
        // new thread can be created
        $fields = Array('msg_to' => $id,'msg_from' => $user->data()->id);
        $db->insert('message_threads',$fields);
        $Q      = $db->query($query);
        $Result = $Q->results();
    }
	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'',true);
	} else {
		echo json_encode($Result);
	}
?>