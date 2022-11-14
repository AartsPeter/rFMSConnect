<?php
require_once '../users/init.php';
require_once 'lib/scriptHeader.php';

	$query = "SELECT pci.*,	pc.cat_name,psc.subcat_name,pct.cat_type_name as cat_type,pt.templateName as templateName,
			l.Name as languageName,	u.username as createdby
		FROM
		    pdc_checkitems pci
			LEFT JOIN pdc_categories pc ON pci.cat_id=pc.id
			LEFT JOIN pdc_subcategories psc ON pci.cat_id=psc.id
			LEFT JOIN pdc_categories_types pct on pc.cat_type_id=pct.id
			LEFT JOIN language l on l.id=pc.language
			LEFT JOIN pdc_template pt on pt.id=pc.template_id
			LEFT JOIN users u on u.id=pc.createdby
		WHERE pc.active=1
		order BY pc.cat_name";
	$ReportsQ = $db->query($query);
	$Results = $ReportsQ->results();

	//show result (return JSON or HTML response for debugging
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Results,'',true);
    } else {
        echo json_encode($Results);
    }
?> 