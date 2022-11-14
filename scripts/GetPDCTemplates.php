<?php 
	require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	$query = "	
		SELECT
            pt.id,pt.active,pt.templateName,pt.shortdescription, u.username,
            c.NAME AS `group`,
            (SELECT COUNT(pc.id) FROM pdc_categories pc WHERE pc.template_id = pt.id AND active=true) AS `CountedCat`,
            (SELECT COUNT(psc.id) FROM pdc_subcategories psc WHERE psc.template_id = pt.id AND active=true) AS `CountedSubCat`,
            (SELECT COUNT(pci.id) FROM pdc_checkitems pci WHERE pci.template_id = pt.id) AS `CountedCheckItems`,
            (SELECT COUNT(pdi.id) FROM pdc_damageitems pdi WHERE pdi.template_id = pt.id) AS `CountedDamageItems`
        FROM
            pdc_template pt
            LEFT JOIN users u ON pt.createdby=u.id
            LEFT JOIN customers c ON c.accountnumber=pt.group
        order BY pt.templateName";

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