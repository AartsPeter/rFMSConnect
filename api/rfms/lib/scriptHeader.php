<?php
    require_once 'lib/SqlFormatter.php';
    require_once 'lib/JSON.php';
    require_once 'lib/rfms_calc.php';
    if (!securePage($_SERVER['PHP_SELF'])){
        header('HTTP/1.0 403 Unauthorized');die();
    }
    if(isset($_GET['SQL'])) {
        error_reporting(E_ALL);
        echo HTMLHeader();
    }
    header("Content-Type: application/json; rfms=vehiclepositions.v4.0");
    header("Access-Control-Allow-Origin: *");
    // Check connection
    ini_set("default_charset","UTF-8");
    date_default_timezone_set('CET');

    //setup connection
    $db = DB::getInstance();

    if (isset($_SESSION['UGselected'])) {
        $SCNumber=$_SESSION['UGselected'];}
    else {
        $SCNumber='*';}

    $str1 = "";$str2 = "";$str3 = "";
    $str4 =" C.NAME AS customerName,";
    if ($user->data()->cust_id!='0'){
        $str2=" AND rcu.User_ID='".$user->data()->id."'";
        $str3=" LEFT JOIN rel_cust_user rcu ON rcu.Cust_ID=f.client";
    }
    if ($SCNumber!='*') {
        $str1=" AND f.client='".$SCNumber."'";
        $str4=" '-' AS customerName, ";
    }
    $settingsQ = $db->query("(Select * FROM settings WHERE domain='".$_SERVER['SERVER_NAME']."') UNION (SELECT * FROM settings WHERE id=1) LIMIT 1");
    $settings = $settingsQ->first();
    $days=$settings->daysStatistics;
    if(isset($_GET['endDate'])) 	{ $ED = test_input($_GET['endDate']);} 	    else { $ED = date("Y-m-d",strtotime('+ 1 day'));};
    if(isset($_GET['startDate'])) 	{ $SD = test_input($_GET['startDate']);} 	else { $SD = date('Y-m-d',strtotime('-'.$days.' day'));};

    if(isset($_GET['EndDate'])) 	{ $ED = test_input($_GET['EndDate']);}
    if(isset($_GET['StartDate'])) 	{ $SD = test_input($_GET['StartDate']);}
    else { $SD = date('Y-m-d',strtotime('1 Monday ago'));$SD1 = date('Y-m-d',strtotime('1 Monday ago'));};
    if(isset($_GET['id'])) 	        { $id = test_input($_GET['id']);} 	        else { $id = '-';}
    if(isset($_GET['tripno'])) 	    { $tripno = test_input($_GET['tripno']);}   else { $tripno = '';}
    if(isset($_GET['date'])) 	    { $SD = test_input($_GET['date']); }
    if(isset($_GET['contactId']))   { $contactId = test_input($_GET['contactId']);}        else { $contactId = '-';}
    $Afields=Array('str1' => $str1, 'str2' => $str2, 'str3' => $str3, 'id' => $id);


?>