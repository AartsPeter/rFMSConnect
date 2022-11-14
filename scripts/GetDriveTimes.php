<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	if(isset($_GET['id'])) 	{
        $VS=$id;}
    else {
        header("HTTP/1.0 405 Not Found");die();}
	$datetime1 = date_create($SD);
    $datetime2 = date_create($ED);
	$interval = date_diff($datetime1, $datetime2);
	$interval=ROUND($interval->format('%a'));

	$DriveTime=[];$Totals=[];$DT=0;$WT=0;$AT=0;$RT=0;$CET=0;$CSR=0;$CI=0;$WD=0;$TC=0;
	for( $i = 0; $i<=$interval; $i++ ) {
	    $WD++;
		$D=date('Y-m-d', strtotime($SD."+ ".$i." days"));
		$query="
		    SELECT
		        dt.DriveDate,dt.drive,dt.work,dt.available,(86400-dt.drive-dt.work) as rest,
		        IF(32400-dt.drive<0,1,0) AS EXTRATIME,
		        IF(86400-dt.drive-dt.work<32400,1,0) AS SHORTREST,
		        dt.INFRIGMENT as INFRIGMENT,
		        d.id as DriverId
		    FROM
		        drivetimes dt
		        LEFT JOIN driver d ON d.tachoDriverIdentification=dt.driverId
		        LEFT JOIN FAMReport f ON d.LastVehicle=F.vin ".$str3."
		    WHERE
		    d.Id='".$VS."' AND
		    dt.DriveDate ='".$D."' ".$str1.''.$str2;
		$MarkersQ = $db->query($query);

		if ($MarkersQ->count()>0){
			$DriveTime[] = $MarkersQ->first();
			$DriveTime[$i]->COMP=1;
			$DriveTime[$i]->DriveDate=$D;
			$DriveTime[$i]->INFRIGMENT=0;
			if ($DriveTime[$i]->drive>32400){$DriveTime[$i]->EXTRATIME=1;}
			if ($DriveTime[$i]->rest< 39600){$DriveTime[$i]->SHORTREST=1;}
			if ($DriveTime[$i]->drive>36000){$DriveTime[$i]->INFRIGMENT=1;$DriveTime[$i]->COMP=0;}
			if ($DriveTime[$i]->rest< 32400){$DriveTime[$i]->INFRIGMENT=1;$DriveTime[$i]->COMP=0;}
			$activeToday=TimeToSec($DriveTime[$i]->drive)+TimeToSec($DriveTime[$i]->work)+TimeToSec($DriveTime[$i]->available);
			if ($D==date("Y-m-d")){
			    $DriveTime[$i]->rest=TimeToSec(date("H:i:s"))-$activeToday; // adjust rest calculation for current day
			}
            //$DriveTime[$i]->DriveDate=date("Y-m-d, l",$DriveTime[$i]->DriveDate);
            $DT+=$DriveTime[$i]->drive; $WT+=$DriveTime[$i]->work; $AT+=$DriveTime[$i]->available; $RT+=$DriveTime[$i]->rest; $CET+=$DriveTime[$i]->EXTRATIME; $CSR+=$DriveTime[$i]->SHORTREST; $CI+=$DriveTime[$i]->INFRIGMENT;
            $DriveTime[$i]->drive=SecToTime($DriveTime[$i]->drive);
            $DriveTime[$i]->work=SecToTime($DriveTime[$i]->work);
            $DriveTime[$i]->available=SecToTime($DriveTime[$i]->available);
            $DriveTime[$i]->rest=SecToTime($DriveTime[$i]->rest);
            $DriveTime[$i]->Total=0;
		} else {
		    $RT+=86400;
			$DriveTime[] = array(
			"DriveDate" => date("Y-m-d", strtotime($SD."+ ".$i." days")),
			"drive" => "00:00","work" => "00:00","available" => "00:00","rest" => "24:00","EXTRATIME"=>0,"SHORTREST"=>0,"INFRIGMENT"=>0,"DriverId"=>$VS,"COMP"=>1,"Total"=>0);
			if ($D==date("Y-m-d")){
				$DriveTime[$i]['rest']=date("H:i");
			}
		}
	    if ($WD==7){
	        if ($CI>0){$CC=0;} else {$CC=1;}
	        $TC++;
            $Totals[] = array(
            	"DriveDate" => $D,"drive" => SecToTime($DT),"work" => SecToTime($WT),"available" => SecToTime($AT),"rest" => SecToTime($RT),"EXTRATIME"=>$CET,"SHORTREST"=>$CSR,"INFRIGMENT"=>$CI,"COMP"=>$CC,"Total"=>1);
            $DT=0;$WT=0;$AT=0;$RT=0;$CET=0;$CSR=0;$CI=0;$WD=0;
		}
	}
	$lr=sizeof($Totals)-1;
	if ($Totals[$lr]['Total']==0) {
	    $Totals[] = array("DriveDate" => date("Y-m-d"),"drive" => SecToTime($DT),"work" => SecToTime($WT),"available" => SecToTime($AT),"rest" => SecToTime($RT),"EXTRATIME"=>$CET,"SHORTREST"=>$CSR,"INFRIGMENT"=>$CI,"COMP"=>1,"Total"=>1);
    }
	$Result=array_merge($DriveTime, $Totals);

    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);
    } else {
        echo json_encode($Result);
    }

 ?>