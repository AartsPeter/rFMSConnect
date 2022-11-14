<?php
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$query="
		SELECT v.model, COUNT(v.vin) AS TotalPermodel
		FROM
		    vehicles v
			LEFT JOIN FAMReport F ON v.vin=F.vin ".$str3."
		WHERE
		   v.vehicleActive=true ".$str1." ".$str2."
		GROUP BY
		    v.model
		order by TotalPermodel DESC";

    $ResultQ =$db->query($query);
    $Result= $ResultQ->results();
    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }

?>