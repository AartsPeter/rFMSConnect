<?php 
	require_once '../../users/init.php';
	require_once 'lib/scriptHeader.php';


	if(isset($_GET['vin'])) 	        { $vin = "vs.vin='".test_input($_GET['vin'])."' AND ";} 	        else { $vin = '';}
    $Result=[];
    $query="
	    SELECT * FROM vehiclestatus vs WHERE ".$vin." vs.receivedDateTime between '".$SD."' AND '".$ED."' ORDER BY vs.createdDateTime ASC LIMIT 200";
	$Q = $db->query($query);
	$VehicleStatus = $Q->results();
	error_reporting(E_ALL);

    $collect = [];
    
	foreach ($VehicleStatus as $row ){
		$vs = [];$sn=[];$d1=[];$ud=[];$ad=[];$gnss=[];$tt=[];
		$vs['vin'] 								= $row->vin;
		// set TriggerType values
		$tt['triggerType']						= $row->triggerType;
		$tt['context']							= $row->triggerContext;
		if (strlen($row->triggerInfo)>0) {			
			$tl=[];
			$pieces	= explode("->", $row->triggerInfo);
			$tl['tellTale']						= $pieces[0];	
			$tl['state']						= $pieces[1];	
			$tt['tellTaleInfo']					= $tl;
		}			
		$vs['triggerType'] 						= $tt;
		//
		$vs['createdDateTime'] 					= date('Y-m-d\TH:i:s.u\Z',strtotime($row->createdDateTime));
		$vs['receivedDateTime'] 				= date('Y-m-d\TH:i:s.u\Z',strtotime($row->receivedDateTime));
		$vs['hrTotalVehicleDistance'] 			= intval($row->hrTotalVehicleDistance);	
		if (isset($row->totalEngineHours)) {			
			if ($row->triggerType != 'DRIVER_1_WORKING_STATE_CHANGED'){
				$vs['totalEngineHours'] 				= intval($row->totalEngineHours);		
			}
		}
		$vs['driver1Id']						= array("tachoDriverIdentification" => $row->driver1Id_TDI);
		// set Accumulated values
		$ad['engineTotalFuelUsed']				= intval($row->engineTotalFuelUsed);
		if ($row->engineTotalFuelUsed>0) {			
			$vs['accumulatedData']					= $ad;}
		//
		
		// set snapshotData values
		// set gnssPosition in SnapshotData
		$gnss['latitude']						= floatval($row->GNSS_latitude);
		$gnss['longitude']						= floatval($row->GNSS_longitude);
		if ($row->GNSS_heading>0) {			
			$gnss['heading'] 						= intval($row->GNSS_heading);}
		if ($row->GNSS_altitude!=0) {			
			$gnss['altitude'] 						= intval($row->GNSS_altitude);}
		if (isset($row->GNSS_posDateTime)) {			
			$gnss['positionDateTime'] 				= date('Y-m-d\TH:i:s\Z',strtotime($row->GNSS_posDateTime));}
		$sn['gnssPosition']						= $gnss;
		//
		if ($row->triggerType != 'DRIVER_1_WORKING_STATE_CHANGED'){
			$sn['wheelBasedSpeed']					= floatval($row->wheelBasedSpeed);
			$sn['tachographSpeed']					= floatval($row->tachographSpeed);
			$sn['grossCombinationVehicleWeight']	= intval($row->grossCombinationVehicleWeight);
			$sn['fuelLevel1']						= intval($row->fuelLevel1);
			$sn['catalystFuelLevel']				= intval($row->catalystFuelLevel);
			$sn['ambientAirTemperature']			= intval($row->ambientAirTemperature);
		}
		$sn['driver1WorkingState']				= $row->driver1Id_WSC;
		$vs['snapshotData'] 					= $sn;		
		if (isset($row->serviceDistance)) {
			if ($row->triggerType == 'ENGINE_ON' || $row->triggerType == 'ENGINE_OFF'){
				$ud['serviceDistance']					= intval($row->serviceDistance);
			}
		}
		$ud['engineCoolantTemperature']			= intval($row->engineCoolantTemperature);
		$ud['serviceBrakeAirPressureCircuit1']	= intval($row->serviceBrakeAirPressureCircuit1);
		$ud['serviceBrakeAirPressureCircuit2']	= intval($row->serviceBrakeAirPressureCircuit2);	
		if ($row->triggerType != 'DRIVER_1_WORKING_STATE_CHANGED' || $row->triggerType != 'DRIVER_2_WORKING_STATE_CHANGED'){
			$vs['uptimeData']						= $ud; 
		}
		$collect[] = $vs;
	}
	
	$Result['vehicleStatus'] 				= $collect;
	if (sizeof($collect)>=200)	{ $Result['moreDataAvailable'] = TRUE;} else { $Result['moreDataAvailable'] = FALSE;}
	$Result['requestServerDateTime'] 		= date('Y-m-d\TH:i:s.u\Z');

	
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
