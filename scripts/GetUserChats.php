<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $query0     = "DELETE t1 FROM message_threads t1 LEFT JOIN messages t2 ON t1.id = t2.msg_thread WHERE t2.msg_from IS NULL AND TIMESTAMPDIFF(MINUTE, t1.last_update, CURTIME())>5";
    $Q0		    = $db->query($query0);
	$Result     = $Q0->results();
    $query      = "
        SELECT
            mt.*,
            u.id as my_id,
            if (mt.msg_from = u.id,  CONCAT (ut.fname, ' ',   ut.lname  ),CONCAT(uf.fname,' ',uf.lname )) AS chatContact,
           if(mt.msg_from=u.id,mt.msg_to,mt.msg_from) AS chatContactId,
           if(mt.msg_from=u.id,ut.avatar,uf.avatar) AS chatContactAvatar,
           (SELECT COUNT(me.id) FROM messages me WHERE me.msg_thread=mt.id AND me.msg_from=u.id AND me.msg_read=0 ) AS `messageUnread`,
           (SELECT COUNT(me.id) FROM messages me WHERE me.msg_thread=mt.id AND me.msg_from!=u.id AND me.msg_read=0 ) AS `messageUnreadTo`,
           (SELECT COUNT(uo.user_id) FROM users_online uo WHERE uo.user_id=ChatContactId ) AS `active`,
           (SELECT msg_body FROM messages WHERE msg_thread=mt.id ORDER BY sent_on DESC LIMIT 1 ) AS `lastMessage`,
           (SELECT msg_from FROM messages WHERE msg_thread=mt.id ORDER BY sent_on DESC LIMIT 1 ) AS `lastSender`,
           (SELECT id FROM messages WHERE msg_thread=mt.id ORDER BY sent_on DESC LIMIT 1 ) AS `lastMessageId`,
           (SELECT count(id) FROM message_thread_pinned mtp WHERE mtp.thread_id=mt.id AND mtp.user_id=u.id  ) AS `pinned`,
           (SELECT group_concat(id) FROM messages WHERE msg_thread=mt.id and msg_read=0 ) AS `OnreadMessages`
        FROM
           message_threads mt
           LEFT JOIN message_thread_pinned mtp ON mtp.thread_id=mt.id
            LEFT JOIN users u ON u.id = '".$user->data()->id."'
            LEFT JOIN users ut ON ut.id = mt.msg_to
            LEFT JOIN users uf ON uf.id = mt.msg_from
        WHERE 	(mt.msg_to=u.id OR mt.msg_from=u.id) AND mt.archived!='".$user->data()->id."'
        GROUP BY chatContactId
        ORDER BY pinned DESC,mt.last_update DESC";
    $Q 		    = $db->query($query);
	$Result   = $Q->results();

	if(isset($_GET['SQL'])) {
		echo HTMLHeader();
		echo ShowDebugQuery($query,$Result,'Find the messages for user',true);
	} else {
		echo json_encode($Result);
	}
?>
	