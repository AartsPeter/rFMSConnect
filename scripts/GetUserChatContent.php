<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';
    $query0     = "UPDATE messages me SET me.msg_read = '1' WHERE me.msg_thread = '".$id."' AND me.msg_from = '".$contactId."'";
    $Q 		    = $db->query($query0);
    $query0     = "SELECT u.id,u.avatar, CONCAT(u.fname,' ',u.lname ) AS chatContact,mt.id as tread_id
                    FROM
                        message_threads mt
                        LEFT JOIN users u ON u.id = '".$contactId."'
                        LEFT JOIN message_thread_pinned mtp on mtp.thread_id=mt.id
                    WHERE
                        (mt.msg_to='".$user->data()->id."' OR mt.msg_from='".$user->data()->id."') AND mt.id='".$id."' AND mt.archived!='".$user->data()->id."' AND mt.deleted='0'  ";
    $Q0         = $db->query($query0);
	$Data0      = $Q0->results();
    $query1     = "SELECT
                        me.id,me.msg_from,me.msg_read,me.deleted,me.sent_on,
                        if(me.deleted=1,'this message has been deleted',me.msg_body) AS msg_body,
                        u.id as user_id,u.avatar,
                        CONCAT(u.fname,' ',u.lname ) AS chatContact
                    FROM
                        messages me
                        LEFT JOIN message_threads mt ON mt.id=me.msg_thread
                        LEFT JOIN users u ON u.id = '".$contactId."'
                    WHERE
                        (mt.msg_to='".$user->data()->id."' OR mt.msg_from='".$user->data()->id."') AND me.msg_thread='".$id."' AND mt.archived!='".$user->data()->id."' AND mt.deleted='0'
                    ORDER BY
                        me.sent_on ASC";
    $Q1         = $db->query($query1);
    $Data1      = $Q1->results();
    $Result     =[];
    $Result['thread']   = $Data0;
    $Result['messages'] = $Data1;

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query0,$Result,'Find the messages forchat',true);
	} else {
		echo json_encode($Result);
	}
?>
	