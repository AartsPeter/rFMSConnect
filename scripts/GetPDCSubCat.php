<?php

require_once '../users/init.php';
require_once 'lib/scriptHeader.php';

	$query = "SELECT 
			psc.*,
			pc.cat_name as cat_name,
			pt.templateName as templateName,
			l.Name as languageName,
			u.username as createdby
		FROM 
			pdc_subcategories psc
			LEFT JOIN pdc_categories pc on psc.cat_id=pc.id
			LEFT JOIN pdc_categories_types pct on pc.cat_type_id=pct.id
			LEFT JOIN language l on l.id=psc.language
			LEFT JOIN pdc_template pt on pt.id=psc.template_id
			LEFT JOIN users u on u.id=pc.createdby
		WHERE pc.active=1
		order BY pt.templateName,pc.cat_name";
	$Q = $db->query($query);
	$Result = $Q->results();

	//show result (return JSON or HTML response for debugging
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?> 