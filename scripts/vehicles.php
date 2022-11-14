<?php 
	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';


	if(isset($_GET['vin'])) 	        { $vin = "v.vin='".test_input($_GET['vin'])."' AND ";} 	        else { $vin = '';}
    $Result=[];
    $query="
	    SELECT * FROM vehicles v LIMIT 200";
	$Q = $db->query($query);
	$VehicleStatus = $Q->results();

    header("Content-Type: application/json; rfms=vehicles.v4.0");
    $collect = [];
    
	foreach ($VehicleStatus as $row ){
		$vs = [];$sn=[];$d1=[];$ud=[];$ad=[];$gnss=[];$tt=[];
		$vs['vin'] 					= $row->VIN;
		$vs['customerVehicleName']	= $row->customerVehicleName;
		$vs['brand'] 				= $row->brand;
		$vs['type'] 				= $row->Type;
		$vs['model'] 				= $row->model;
		$vs['productionDate']		= $row->Year;
		if (is_null($row->emissionLevel)) { } else{
			$vs['emissionLevel']		= $row->emissionLevel;
		}
		if (is_null($row->numberOfAxles)) { } else{
			$vs['numberOfAxles']		= $row->numberOfAxles;
		}
		if (is_null($row->TankVolume)) { } else{
			$vs['TankVolume']			= $row->TankVolume;
		}		
		if (is_null($row->tachographType)) { } else{
			$vs['tachographType']		= $row->tachographType;
		}				
		if (is_null($row->gearboxType)) { } else{
			$vs['gearboxType']			= $row->gearboxType;
		}		
		if (is_null($row->possibelFuelType)) { } else{
			$vs['possibleFuelType']		= $row->possibelFuelType;
		}		
		
		$collect[] = $vs;
	}
	
	$Result['vehicles'] 			= $collect;
	if (sizeof($collect)>=200)	{ $Result['moreDataAvailable'] = TRUE;} else { $Result['moreDataAvailable'] = FALSE;}
	$Result['requestServerDateTime']= date('Y-m-d\TH:i:s.u\Z');
	
    if(isset($_GET['SQL'])) {
            echo HTMLHeader();
            echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }
