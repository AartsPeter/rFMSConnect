<?php 
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	if(isset($_GET['desktop']))	        {$Desktop=' and desktop='.$_GET['desktop'];} else {$Desktop=' and desktop=false';}
    if(isset($_GET['public']))	        {$Public=' and public='.$_GET['public'];} else {$Public=' and public=false';}

	$localDate=new DateTime('now');
    $query="
        (SELECT 
        c.name as groupName,n.*
        FROM 
            notification n 
            LEFT JOIN famreport f on f.client=n.messagegroup 
            LEFT JOIN customers c ON c.accountnumber=f.client ".$str3."
        WHERE 
            n.archive=false and 
            desktop=true and public=false 
            AND CURDATE() BETWEEN StartPublish AND EndPublish ".$str1." ".$str2." 
        GROUP BY ID  )
        UNION
        (SELECT 
            '-' as groupName,	n.* 
        FROM 
            notification n 
        WHERE 
            n.archive=false ".$Desktop."\n ".$Public."\n and n.messagegroup='0' AND CURDATE() BETWEEN StartPublish AND EndPublish GROUP BY ID  )
        ORDER BY
            EndPublish ASC";
    $Q = $db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>
