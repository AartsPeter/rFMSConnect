<?php
//**********************************************************************
// file will return a array to show within 'rfms_report.js'
// Author : Peter Aarts QNH 2017
// License free 
//**********************************************************************
    require_once '../users/init.php';
    require_once 'lib/SqlFormatter.php';
    require_once 'lib/JSON.php';
    if (!securePage($_SERVER['PHP_SELF'])){header('HTTP/1.0 401 Unauthorized');die();}// Check connection
    header("Content-Type: text/html; charset=utf-8mb4");
    ini_set("default_charset","UTF-8");


    if (isset($_SESSION['UGselected']))
    {$SCNumber=$_SESSION['UGselected'];}
    else
    {$SCNumber='*';}
    $str1="";$str2="";$str3="";
    if ($user->data()->cust_id!='0'){
        $str2="and rel_cust_user.User_ID='".$user->data()->id."'";
        $str3=" left JOIN REL_CUST_USER ON rel_cust_user.Cust_ID=f.client";}
    if ($SCNumber!='*') { $str1="and f.client='".$SCNumber."'";}


    $localDate=new DateTime('now');

    $db = DB::getInstance();
    $query="SELECT
            sum((v.TotalFuelUsed-vehicle_stat_fuel.totalFuelUsed)/1000) AS TotFuelToday
        FROM
            vehicle_stat_fuel
            LEFT JOIN vehicles v ON vehicle_stat_fuel.VIN=v.VIN
            LEFT JOIN FAMReport f ON f.vin=vehicle_stat_fuel.vin ".$str3."
        WHERE
            v.vehicleActive=true ".$str1." ".$str2." and
            Date(createDate)='".$localDate->format('Y-m-d')."'";

    $Q =$db->query($query);
    $Result = $Q->results();

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
?>