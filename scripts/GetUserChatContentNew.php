<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    if(isset($_GET['lastMessageId']))
        { $lastMessageId = test_input($_GET['lastMessageId']);}
    else
        { header('HTTP/1.0 412 PreCondition failed');echo json_encode($Error['Error'] = "failed : Id of last received chat message is missing");die();}

    $query0     = "UPDATE messages me SET me.msg_read = '1' WHERE me.msg_thread = '".$id."' AND me.msg_from = '".$contactId."'";
    $Q 		    = $db->query($query0);

    $query1     = "SELECT
                        me.id,me.msg_from,me.msg_read,me.deleted,me.sent_on,
                        if(me.deleted=1,'this message has been deleted',me.msg_body) AS msg_body,
                        u.id as user_id
                    FROM
                        messages me
                        LEFT JOIN message_threads mt ON mt.id=me.msg_thread
                        LEFT JOIN users u ON u.id = '".$contactId."'
                    WHERE
                        (mt.msg_to='".$user->data()->id."' OR mt.msg_from='".$user->data()->id."') AND
                        me.msg_thread='".$id."' AND
                        mt.archived!='".$user->data()->id."' AND
                        me.msg_from='".$contactId."' AND
                        mt.deleted='0' AND
                        me.id > ".$lastMessageId."
                    ORDER BY
                        me.sent_on ASC;";
    $Q1         = $db->query($query1);
    $Data1      = $Q1->results();
    $Result     =[];
    $Result['messages'] = $Data1;

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query1,$Result,'Find the messages for chat',true);
	} else {
		echo json_encode($Result);
	}
?>
	