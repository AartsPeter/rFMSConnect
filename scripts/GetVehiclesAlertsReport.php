<?php 
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

    if (!securePage($_SERVER['PHP_SELF'])){
        header("HTTP/1.1 401 Unauthorized");
        sleep(10);
        exit;die();
    }
//    error_reporting(E_ALL);

    function Check_telltale_warning($arr){
		$warning=0;
		if ($arr->TT_PAR_BRA=='YELLOW' )	{$warning++;}
		if ($arr->TT_FUE_LEV=='YELLOW' )	{$warning++;}
		if ($arr->TT_ENG_COO_TEM=='YELLOW' ){$warning++;}
		if ($arr->TT_ENG_MIL_IND=='YELLOW' ){$warning++;}
		if ($arr->TT_ENG_EMI_FAI=='YELLOW' ){$warning++;}
		if ($arr->TT_ENG_OIL=='YELLOW')		{$warning++;}
		if ($arr->TT_ADB_LEV=='YELLOW')		{$warning++;}		
		if ($arr->TT_PAR_BRA=='RED' )		{$warning++;}
		if ($arr->TT_FUE_LEV=='RED' )		{$warning++;}
		if ($arr->TT_ENG_COO_TEM=='RED' )	{$warning++;}
		if ($arr->TT_ENG_MIL_IND=='RED' )	{$warning++;}
		if ($arr->TT_ENG_EMI_FAI=='RED' )	{$warning++;}
		if ($arr->TT_ENG_OIL=='RED')		{$warning++;}
		if ($arr->TT_ADB_LEV=='RED')		{$warning++;}
		return $warning;
    }

    if(isset($_GET['startDate'])) 	{ $SD = test_input($_GET['startDate']);} 	else { $SD = date('Y-m-d',strtotime('- 5 day'));};
    $db = DB::getInstance();
	$query = "
	    SELECT
	        v.id,
	        v.VIN,
	        customerVehicleName,
	        LastDriver,
	        LastActivity,
	        last_Latitude,
	        last_Longitude,
	        vehicleActive,
			TT_PAR_BRA,TT_FUE_LEV,TT_ENG_COO_TEM,TT_ENG_OIL,TT_ENG_MIL_IND,TT_ENG_EMI_FAI,TT_ADB_LEV,
            SUM(CASE WHEN vt.state='YELLOW' THEN 1 ELSE 0 END) AS YellowWarnings,
            SUM(CASE WHEN vt.state='RED' THEN 1 ELSE 0 END) AS RedWarnings,
			IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS Driver
		FROM
		    vehicles v
		    LEFT JOIN FAMReport f ON f.vin=v.VIN
		    LEFT JOIN vehicles_telltales vt on vt.vin=v.vin
    	    LEFT JOIN driver d ON d.tachoDriverIdentification=v.LastDriver ".$str3."
		WHERE
		    v.vehicleActive=true AND
		    (vt.state = 'YELLOW' or vt.state='RED') AND
		    vt.createdDateTime between '".$SD."' and '".$ED."'
		    ".$str1." ".$str2."
		GROUP BY v.VIN
		ORDER BY v.LastActivity DESC";
	$VehicleQ = $db->query($query);
	$Vehicles = $VehicleQ->results();

    if ($VehicleQ->count()>0){
        $counter=0;
        foreach ($Vehicles as $val){
            $StatusArray=Array();
            $StatusArray['alert']=false;
            $val->CountedWarning=Check_telltale_warning($val);
            if ($val->CountedWarning>0){
                $StatusArray['alert']=true;
            }
            $val->status=$StatusArray;
        }
    }
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Vehicles,'',true);
    } else {
        echo json_encode($Vehicles);
    }

?>
