<?php 
//**********************************************************************
// Author : Peter Aarts QNH 20201
// License free 
//**********************************************************************
	require_once '../users/init.php';
	require_once 'lib/JSON.php';

	if (!securePage($_SERVER['PHP_SELF'])){die();} 
	// Check connection
	header("Content-Type: text/html; charset=utf-8mb4");
	ini_set("default_charset","UTF-8");
	mb_internal_encoding("UTF-8");

	if (isset($_SESSION['UGselected']))
		{$SCNumber=$_SESSION['UGselected'];}
	else
		{$SCNumber='*';}
	$db = DB::getInstance();
	$str1="";$str2="";$str3="";
	if ($user->data()->cust_id!='0'){
		$str2=" AND rel_cust_user.User_ID='".$user->data()->id."'";
		$str3=" LEFT JOIN rel_cust_user rcu ON rcu.Cust_ID=f.client";}
	if ($SCNumber!='*') { $str1=" AND f.client='".$SCNumber."'";}

	$query=" SELECT * FROM test ".$str3."WHERE ".$str1." ".$str2." ";

	$db = DB::getInstance();
	$Q->query($query);
	$Results = $Q->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Results);
    } else {
        echo json_encode($Results);
    }
?>
