<?php
require_once '../users/init.php';
require_once 'lib/scriptHeader.php';

	$query = "	
		SELECT 
			pdi.*,
			pc.cat_name,
			psc.subcat_name,
			pct.cat_type_name as cat_type,
			pt.templateName as templateName,
			l.Name as languageName,
			u.username as createdby
		FROM
		    pdc_damageitems pdi
			LEFT JOIN pdc_categories pc ON pdi.cat_id=pc.id
			LEFT JOIN pdc_subcategories psc ON pdi.subcat_id=psc.id
			LEFT JOIN pdc_categories_types pct on pc.cat_type_id=pct.id
			LEFT JOIN language l on l.id=pc.language
			LEFT JOIN pdc_template pt on pt.id=pc.template_id
			LEFT JOIN users u on u.id=pc.createdby
		order BY pc.cat_name";
	$Q = $db->query($query);
	$Results = $Q->results();

	//show result (return JSON or HTML response for debugging
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Results,'PreDepatureCheck-DamageItems',true);
    } else {
        echo json_encode($Results);
    }
?> 