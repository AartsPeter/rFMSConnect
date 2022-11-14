<?php

    function Calc_DriveTime($data){
        $l=count($data);
        $d = strtotime($data[0]->createdDateTime);
        $e = strtotime($data[$l-1]->createdDateTime);
        $CDate=$d;$CREST=0;$CDRIVE=0;$CWORK=0;$CDRIVER_AVAILABLE=0;
        $DriverData=['x'=>$d,'x2'=>'','diff'=>0,'status'=>'REST','Driver'=>''] ;
        $state='REST';
        foreach($data as $val) {
            if ($val->workingstate=='NOT_AVAILABLE'){ $val->workingstate = 'REST'; }
            if ($val->workingstate!='')                 { $state = $val->workingstate; }
            if ($DriverData['status']!=$state){					        //determine if $state has changed
                $DriverData['x2']= strtotime($val->createdDateTime);	// set endDateTime
                $DriverData['diff']=($DriverData['x2']-$DriverData['x']);
                if ($DriverData['status']=='WORK')              { $CWORK += $DriverData['diff'];}
                if ($DriverData['status']=='DRIVE')             { $CDRIVE += $DriverData['diff'];}
                if ($DriverData['status']=='REST')              { $CREST += $DriverData['diff'];}
                if ($DriverData['status']=='DRIVER_AVAILABLE')  { $CDRIVER_AVAILABLE += $DriverData['diff'];}
                $DriverData=['x'=> strtotime($val->createdDateTime),'x2'=>'','diff'=>0,'status'=>$state];
            }
        }
        $DriverData['x2']=$e; $DriverData['diff']=($DriverData['x2']-$DriverData['x']);
        if ($DriverData['status']=='WORK')              { $CWORK   += $DriverData['diff']; }
        if ($DriverData['status']=='DRIVE')             { $CDRIVE  += $DriverData['diff']; }
        if ($DriverData['status']=='REST')              { $CREST   += $DriverData['diff']; }
        if ($DriverData['status']=='DRIVER_AVAILABLE')  { $CDRIVER_AVAILABLE += $DriverData['diff']; }
        return $CDRIVE;
    }

    function SetEmptyDriveTime($date,$driver,$user){
        return ['DriveDate'=>$date,'Driver'=>$driver,'DriverId'=>$user,'DaysDriving'=>0,'drive'=>0,'drive'=>0,'work'=>0,'available'=>0,'rest'=>0,'EXTRATIME'=>0,'SHORTREST'=>0,'INFRIGMENT'];
    }

    function TimeToSec($time) {
        $sec = 0;
        foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
        return $sec;
    }

    function SecToTime($seconds) {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds - ($h * 3600) - ($m * 60);
           return sprintf('%02d:%02d', $h, $m);
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function CheckVehicleAuthorisation($Request){
        $db = DB::getInstance();
        $query="SELECT v.customerVehicleName FROM vehicles v LEFT JOIN FAMReport f ON f.vin=v.VIN ".$Request['str3']." WHERE v.id='".$Request['id']."' ".$Request['str1']." ".$Request['str2'];
        $Q = $db->query($query);
        if ($Q->count()==0){ header('HTTP/1.0 403 Unauthorized');die();}
    }


?>