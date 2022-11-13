<?php 
//**********************************************************************
// Author : Peter Aarts 2022
// License free 
//**********************************************************************
require_once 'init.php'; 
if (!securePage($_SERVER['PHP_SELF'])){die();} 

if (isset($_GET['choiceId'])) 
{
	$_SESSION['UGselected']=$_GET['choiceId'];  
	$_SESSION['UGtext']=$_GET['choiceText'];
    $fields=array('cust_id'=>$_GET['choiceId']);
    $db = DB::getInstance();
	$db->update('users',$user->data()->id,$fields);
}
if (isset($_GET['choiceStart'])) 
{
	$_SESSION['UStartDate']=$_GET['choiceStart'];  
	$_SESSION['UEndDate']=$_GET['choiceEnd'];  
}

?>