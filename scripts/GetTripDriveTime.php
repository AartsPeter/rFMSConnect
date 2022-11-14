<?php 
	require_once '../users/init.php';
	require_once 'lib/JSON.php';
	if (!securePage($_SERVER['PHP_SELF'])){die();} 
	
	//**********************************************************************
	// file used with parameter "driverId & tripid"
	// file will return a array to show within 'rfms_report.js'
	// Author : Peter Aarts QNH 2021
	// License free 
	//**********************************************************************
	error_reporting(E_ALL);
	// Check connection
	header("Content-Type: text/html; charset=utf-8mb4");
	ini_set("default_charset","UTF-8");
	mb_internal_encoding("UTF-8");
	date_default_timezone_set('UTC');

	if (isset($_SESSION['UGselected']))
		{$SCNumber=$_SESSION['UGselected'];}
	else
		{$SCNumber='*';}

	$str1="";$str2="";$str3="";
	if ($user->data()->cust_id!='0'){
		$str2="and rel_cust_user.User_ID='".$user->data()->id."'";
		$str3=" left JOIN REL_CUST_USER ON rel_cust_user.Cust_ID=f.client";}
	if ($SCNumber!='*') { $str1="and f.client='".$SCNumber."'";}

 	if (isset($_GET["trip"])) {} else { http_response_code(412); die();}

	$db = DB::getInstance();
	$queryT = "
		SELECT
			t.StartDate, t.EndDate
        FROM
            trips t
            LEFT JOIN FAMReport f ON f.VIN=t.vin
            LEFT JOIN customers on customers.accountnumber=f.client
            LEFT JOIN driver d on d.tachoDriverIdentification=t.Driver1ID ".$str3."
		WHERE
		    t.trip_NO='".$_GET["trip"]."' ".$str1." ".$str2;
    $TripQ = $db->query($queryT);
    $Trip = $TripQ->results();
    $StartDate=$Trip[0]->StartDate;
    $EndDate=$Trip[0]->EndDate;

    if ($TripQ->count()>0){
        $query = "
            SELECT
                ds.createdDateTime,
                ds.workingstate
            FROM
                driverstatus ds
            WHERE
                ds.createdDateTime between '".$StartDate."' and  '".$EndDate."'
            ORDER BY
                createdDateTime";
        $ResultQ = $db->query($query);
        $Results = $ResultQ->results();
        if(isset($_GET['SQL'])) {
                echo HTMLHeader();
                echo ShowDebugQuery($queryT,$Trip);
                echo ShowDebugQuery($query,$Results);
        } else {
            echo json_encode($Results);
        }
    } else {
        http_response_code(404);
    }

 ?>