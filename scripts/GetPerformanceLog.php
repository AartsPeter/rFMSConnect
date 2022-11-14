<?php
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

	$EP=test_input($_GET["Endpoint"]);
	if(isset($_GET['endDate'])) 	{ $ED = test_input($_GET['endDate']);} 	    else { $ED = date("Y-m-d",strtotime('+ 1 day'));};
	if(isset($_GET['startDate'])) 	{ $SD = test_input($_GET['startDate']);} 	else { $SD = date('Y-m-d',strtotime('-12 hours'));};
	$Start = strtotime($SD);
	$End = strtotime($ED);
	$datediff = (($End - $Start)/ (60 * 60));  							// date difference in hours
	if ($datediff>172){ 
		$query ="SELECT DateTime, Endpoint,Status,AVG(rt) as rt,rz,ro,LD FROM performancelog WHERE DateTime BETWEEN '".$SD."' AND '".$ED."' AND Endpoint='".$EP."' GROUP by Date(DateTime),HOUR(DateTime)	ORDER BY DateTime ASC";
	} else {
		$query="SELECT * FROM performancelog WHERE DateTime BETWEEN '".$SD."' AND  '".$ED."' AND Endpoint='".$EP."' ORDER BY DateTime ASC ";
	}
	$Q      = $db->query($query);
	$Result = $Q->results();

    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'API monitoring Endpoints',true);
    } else {
        echo json_encode($Result);
    }
?>
