<?php
    require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

    $settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
    $settings = $settingsQ->first();
    $days=$settings->daysStatistics;
    if(isset($_GET['EndDate'])) 	{$ED=$_GET['EndDate'];} 	else {$ED=date("Y-m-d",strtotime('+ 1 day'));};
    if(isset($_GET['StartDate'])) 	{$SD=$_GET['StartDate'];} 	else {$SD=date('Y-m-d',strtotime('-'.$days.' day'));};

	$query="
		SELECT
		     createDate, COUNT(vehicle_stat_km.vin) AS Active
		FROM
		    vehicle_stat_km
		    LEFT JOIN vehicles V ON v.VIN =vehicle_stat_km.vin
			LEFT JOIN FAMReport f ON vehicle_stat_km.vin=F.vin ".$str3."
		WHERE
		    v.vehicleActive=true AND
		    createDate BETWEEN '".$SD."' AND '".$ED."'  ".$str1." ".$str2."
		GROUP BY createDate";
	$query1 = "
		SELECT
		     gvc.createDate,sum(VehicleCount) as VehicleCount,
		     '0' as Active
		FROM 
			groupvehiclecount gvc
			LEFT JOIN rel_cust_user rcu ON rcu.Cust_ID = gvc.accountnumber 
		WHERE
		    gvc.createDate BETWEEN '".$SD."' AND '".$ED."'  ".$str5." ".$str2."
	    GROUP BY gvc.createDate";
    # process queries
	$VehicleQ		= $db->query($query);
    $ActiveCount	= $VehicleQ->results();
    $GroupsCountQ	= $db->query($query1);
    $GroupsCount	= $GroupsCountQ->results();
    
	# combine data from queries
	foreach ($GroupsCount as $val){
        foreach ($ActiveCount as $vals){
            if ($val->createDate == $vals->createDate){
                $val->Active = $vals->Active;
                break;
			}
        }
    }
	$Results= $GroupsCount;
	# output Debug-HTML or JSON
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$ActiveCount,'',true);
            echo ShowDebugQuery($query1,$GroupsCount,'',true);
			echo ShowDebugQuery('',$Results,'',true);
    } else {
        echo json_encode($Results);
    }

?>