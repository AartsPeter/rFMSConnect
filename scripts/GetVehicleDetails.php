<?php 
    require_once '../users/init.php';
    require_once 'lib/SqlFormatter.php';
    require_once 'lib/JSON.php';
    if (!securePage($_SERVER['PHP_SELF'])){
        header("HTTP/1.1 401 Unauthorized");
        sleep(10);
        exit;die();
    }

	//**********************************************************************
	// file used with parameter "?id=xx" vehicle-id
	// file will return a array of 1 row for all vehicle details to show within 'rfms_report.js'
	// Author : Peter Aarts QNH 2021
	// License free 
	//**********************************************************************
	// Check connection
	header("Content-Type: text/html; charset=utf-8mb4");
	error_reporting(E_ALL);
	ini_set("default_charset","UTF-8");
	mb_internal_encoding("UTF-8");
	if (isset($_SESSION['UGtext'])){
		$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];
	}
	else {
		if ($user->data()->cust_id!='0'){ $SCNumber=$user->data()->cust_id;}
		else { $SCNumber='*';	}
	}	
	$str1="";$str2="";$str3="";
	if ($user->data()->cust_id!='0'){
		$str2="and rel_cust_user.User_ID='".$user->data()->id."'";
		$str3="	LEFT JOIN REL_CUST_USER ON rel_cust_user.Cust_ID=f.client";}
	if ($SCNumber!='*') {
		$str1="and f.client='".$SCNumber."'";
	}
	$db = DB::getInstance();
	$query="
		SELECT
		    v.*, c.name,f.*,
		(SELECT trailerName FROM trailers WHERE trailers.vehicleVIN = v.vin  AND copplingStatus=1 LIMIT 1) AS `TrailerName`,
		IF (d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS Driver,
		IF (d2.Lastname='',d2.tachoDriverIdentification,concat(d2.Lastname,', ',d2.Surname)) AS Driver2,
		d.id as DriverId,d2.id as DriverId2,
		(SELECT count(vin) FROM pdc_damage WHERE pdc_damage.vin = v.vin  AND pdc_damage.repairStatus=0) AS `DamageCount`	
		FROM vehicles v
			LEFT JOIN FAMReport f ON f.vin=v.VIN 
			LEFT JOIN customers c ON c.accountnumber=f.client
			LEFT JOIN driver d ON d.tachoDriverIdentification=v.Driver1_ID
			LEFT JOIN driver d2 ON d2.tachoDriverIdentification=v.Driver2_ID ".$str3."
		WHERE v.id='".$_GET["id"]."' AND v.vehicleActive=true ".$str1." ".$str2;
	$VehicleQ = $db->query($query);
	$Result=$VehicleQ->results();
	if ($VehicleQ->count()>0){
		$StatusArray=Array();														    	// definition of variables per truck before checking vehicle data
        $StatusArray['alert']=false;
        $StatusArray['driving']=false;
        $StatusArray['stopped']=false;
        $StatusArray['paused']=false;
        if ($Result[0]->currentSpeed>0){$StatusArray['driving']=true;}						// check if vehicle is driving
        else {
            if ( $Result[0]->tripActive == true ) { $StatusArray['paused'] = true; }		// Vehicle paused
            else { $StatusArray['stopped']=true;	}										// Vehicle stopped
        }
        $Result[0]->status=$StatusArray;
    }
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?> 