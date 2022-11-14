<?php 

    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	if (checkMenu(3,$user->data()->id)){ 
		if ( $user->data()->cust_id == '0' ){  $str1 = "";$str2 = "";$str3 = "";}
		$query="SELECT c.name as groupName,n.*
				FROM
					notification n
					LEFT JOIN famreport f on f.client=n.messagegroup
					LEFT JOIN customers c ON c.accountnumber=f.client 
					".$str3."
				WHERE
					n.archive=false 
				GROUP BY n.id
				ORDER BY n.StartPublish DESC";
		$Q = $db->query($query);
		$Result = $Q->results();

		if(isset($_GET['SQL'])) {
			echo HTMLHeader();
			echo ShowDebugQuery($query,$Result,'',true);
		} else {
			echo json_encode($Result);
		}
	}
?>