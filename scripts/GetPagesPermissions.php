<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	$query = "
        SELECT pages.*, GROUP_CONCAT(permissions.name SEPARATOR ', ') as Permissions
        FROM pages
            left JOIN permission_page_matches ON pages.id=permission_page_matches.page_id
            left JOIN permissions ON permissions.id=permission_page_matches.permission_id
        GROUP BY pages.id
        ORDER BY pages.id DESC";
    $Q = $db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>