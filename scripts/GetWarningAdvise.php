<?php require_once '../users/init.php'; ?>
<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php	
	//**********************************************************************
	// file will return a array to show within 'rfms_report.js'
	// Author : Peter Aarts QNH 2017
	// License free 
	//**********************************************************************
	
	// Check connection
		header("Content-Type: text/html; charset=utf-8mb4");
	ini_set("default_charset","UTF-8");
	mb_internal_encoding("UTF-8");
	iconv_set_encoding("internal_encoding","UTF-8");
	iconv_set_encoding("output_encoding","UTF-8");

	$db = DB::getInstance();
	$WarningQ = $db->query("SELECT * FROM vehicles_warning_advise WHERE TT_name='".$_GET["warning"]."' AND status='".$_GET["status"]."' AND Language='EN'" );
	echo json_encode($WarningQ->results());
?>