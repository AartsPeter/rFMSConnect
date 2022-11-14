<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';
    $query      = "
        SELECT
            count(me.id) AS `OnreadMessages`
        FROM
            messages me
            LEFT JOIN message_threads mt ON mt.id=me.msg_thread
            LEFT JOIN users u ON u.id = '".$user->data()->id."'
        WHERE
  		    (mt.msg_to=u.id OR mt.msg_from=u.id) AND
		    mt.archived!=u.id AND
		    me.msg_from!=u.id AND
		    me.msg_read=0 ";
    $Q 	       = $db->query($query);
	$Result   = $Q->results();

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'Find the messages for user',true);
	} else {
		echo json_encode($Result);
	}
?>
	