<?php

    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';


	$Today=date('Y-m-d', time());

	$StartDT= new DateTime($SD,new DateTimeZone('UTC'));
    $EndDT  = new DateTime($SD,new DateTimeZone('UTC'));
	$StartDT->setTime(0,0,0);
	if ($Today!=$EndDT->format('Y-m-d ') ) {
        $EndDT->setTime(23,59,59);}
    else {
        $EndDT=new DateTime();}

	$query = "
		SELECT 
			ds.createdDateTime, ds.workingstate 
		FROM 
			driverstatus ds 
			LEFT JOIN driver d ON d.tachoDriverIdentification=ds.driverId 
		WHERE 
			d.id='".$id."' AND createdDateTime>= '".$StartDT->format('Y-m-d H:i:s ')."' AND  createdDateTime<= '".$EndDT->format('Y-m-d H:i:s ')."' 
		ORDER 
			BY createdDateTime ASC";
	$Q      = $db->query($query);
	$Result = $Q->results();


	foreach ($Result as $var){
		$d	= new DateTime($var->createdDateTime,new DateTimeZone('UTC'));
		$var->createdDateTime= $d->format('Y-m-d H:i T');
	}

	$firstDateRecord=new DateTime($Result[0]->createdDateTime );
	if ($Q->count() == 0) {
        $Result[] = array('createdDateTime'=>$StartDT->format('Y-m-d H:i T'),workingstate=>'REST');
        $Result[] = array('createdDateTime'=>$EndDT->format('Y-m-d H:i T'),workingstate=>'REST');
	}
	else {
        $Result[0]->createdDateTime = $StartDT->format('Y-m-d 00:00 T');
        $Result[0]->workingstate = 'REST';
        $Result[] = array('createdDateTime' => $EndDT->format('Y-m-d H:i T '),'workingstate' => 'DRIVER_AVAILABLE');
  //      $Result[] = array('createdDateTime' => $EndDT->format('Y-m-d 23:59 T'),'workingstate' => 'DRIVER_AVAILABLE');
	}
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
 ?>