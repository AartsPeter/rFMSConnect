<?php
    require_once '../users/init.php';
    require_once 'lib/scriptHeader.php';

	//error_reporting(E_ALL);

    $ED= date("Y-m-d",strtotime('+ 1 day'));
    $SD= date('Y-m-d',strtotime('1 Monday ago'));
    if(isset($_GET['id'])) 	        { $id = test_input($_GET['id']);} 	        else { $id = $user->data()->driver_id;}

	$query=" SELECT d.id,d.tachoDriverIdentification,
            (select dt.drive FROM drivetimes dt LEFT JOIN driver d ON d.tachoDriverIdentification=dt.driverId wHERE d.tachoDriverIdentification='".$id."' AND dt.DriveDate=Date(NOW()) ) AS DriveToday,
            (SELECT trailerName FROM trailers WHERE trailers.vehicleVIN = d.LastVehicle AND copplingStatus=1) AS `TrailerName`,
            (SELECT vin FROM trailers WHERE trailers.vehicleVIN = d.LastVehicle AND copplingStatus=1 ) AS `TrailerVin`,
            (SELECT customerVehicleName FROM vehicles WHERE vehicles.VIN = d.LastVehicle ) AS `LastVehicleName`,
            (SELECT ROUND(odometer/1000,0) FROM vehicles WHERE vehicles.VIN = d.LastVehicle ) AS `odometer`,
            d.LastVehicle,
            IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS Driver
        FROM driver d
            LEFT JOIN famreport f on f.vin=d.LastVehicle
           ".$str3."
        WHERE
            d.tachoDriverIdentification='".$id."'".$str1;
	$Q      	= $db->query($query);
	$Driver 	= $Q->first();
	$LastVehicle= $Driver->LastVehicle;
    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Driver,'Current DriverInfo',true);
    }
	$query1="
        SELECT
            DriveDate,
            IF(d.Lastname='',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS Driver ,
            d.id as DriverId,
            count(dt.DriveDate) AS DaysDriving,
            sum(dt.drive) as drive,
            sum(dt.work) as work,
            sum(dt.available) as available,
            sum(86400-dt.drive - dt.work - dt.available) as rest,
            SUM(IF(32400-dt.drive<0,1,0)) AS EXTRATIME,
            SUM(IF(86400-dt.drive-dt.work-dt.available<32400,1,0)) AS SHORTREST,
            IF(SUM(IF(32400-dt.drive<0,1,0))>2,1,0) as INFRIGMENT
        FROM
            drivetimes dt
            LEFT JOIN driver d ON d.tachoDriverIdentification=dt.driverId
            LEFT JOIN FAMReport f ON d.LastVehicle=F.vin
        WHERE
            d.tachoDriverIdentification='".$id."' and
            dt.DriveDate BETWEEN '".$SD." 00:00' AND '".$ED." 23:59'
        GROUP BY
            week(dt.DriveDate,1) ";
	$Q1 = $db->query($query1);
	$DriverTime= $Q1->results();
    if(isset($_GET['SQL'])) {
        echo ShowDebugQuery($query1,$DriverTime,'DriverInfo this week',true);
    }
    //Perform DriveTime regulation calculations
//    $DriverTime=Array_merge($DriverTime1,$DriverTime2);
    $BiWeeklyDriveTime =  $DriverTime[0]->drive + $DriverTime[1]->drive;
    if ( $BiWeeklyDriveTime < 324000 ){
        $RemainDriveWeekly = 324000 - ( $BiWeeklyDriveTime );
        if ( $DriverTime[0]->drive > 201600 ) {
            $DriverTime[0]->INFRIGMENT++;
            $RemainDriveWeekly = 122400 - $DriverTime[1]->drive;
        } else {
            $RemainDriveWeekly = ( 324000 - $DriverTime[0]->drive) - $DriverTime[1]->drive;
        }
        if ( $DriverTime[1]->drive > 201600 ) {
            $DriverTime[1]->INFRIGMENT++;
            $RemainDriveWeekly = 0;
        }
        If ( $RemainDriveWeekly > 201600 ) {
            $RemainDriveWeekly = 201600 - $DriverTime[1]->drive;
        }
    } else {
        $RemainDriveWeekly = 0;
    }
    if ( $DriverTime[0]->EXTRATIME < 2){
        $MaxDriveTimeToday = 36000;
    } else {
        $MaxDriveTimeToday = 32400;
    }
    $Driver->RemainingDriveToday=$MaxDriveTimeToday - $Driver->DriveToday;
    if ($Driver->RemainingDriveToday<=1) {$Driver->RemainingDriveToday=0;}
    $Driver->RemainingDriveToday     = SecToTime( $Driver->RemainingDriveToday );
    $Driver->DriveToday              = SecToTime( $Driver->DriveToday);
    $Driver->DriveBiWeekly           = SecToTime( $BiWeeklyDriveTime );
    $Driver->RemainingBiWeekly       = SecToTime( 324000-$BiWeeklyDriveTime );
    $Driver->RemainDriveWeekly       = SecToTime( $RemainDriveWeekly );
    $Driver->RemainingExtendedDrive  = 2-( $DriverTime[1]->EXTRATIME );
    $Driver->RemainingShortRests     = 3-( $DriverTime[1]->SHORTREST);
    $Driver->Infrigments             = $DriverTime[0]->INFRIGMENT + $DriverTime[1]->INFRIGMENT;
    $Driver->LastWeekDrivetime       = SecToTime( $DriverTime[0]->drive);
    $Driver->ThisWeekDrivetime       = SecToTime( $DriverTime[1]->drive);

    $Driver->DriveDate=$ED;
    if ( $Driver->TrailerName == null)   { $Driver->TrailerName = 'No Trailer available'; }
    if ( $Driver->TrailerVin  == null)   { $Driver->TrailerVin  = 'No Trailer available'; }
    $Monday=strtotime('last monday', strtotime('tomorrow'));
    $Driver->DaysDriving=0;
    foreach ($DriverTime as $val){
        if (strtotime($val->DriveDate) >= $Monday){
            $Driver->DaysDriving++;
        }
    }
    if(isset($_GET['SQL'])) {
        echo ShowDebugQuery('',$Driver,'Showing Result');
    } else {
        echo json_encode($Driver,JSON_NUMERIC_CHECK);
    }
?> 