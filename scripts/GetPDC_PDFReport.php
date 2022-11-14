<?php 

	require_once '../users/init.php';
	require_once 'lib/scriptHeader.php';

 //   error_reporting(E_ALL);
	date_default_timezone_set('Europe/Amsterdam');
	header("Access-Control-Allow-Origin: *");

    function CreateTemplateHeader($temp,$Result){
        $header='<div class="container border shadow bg-white  p-4">';
        $header.='<table width="100%" class="border"><tbody><tr><td  colspan="4"><div class="bg-primary text-light card-title p-3 h4">Driver Pre Departure Checklist </div></td></tr>';
    	$header.='<tr class="border border-default"><td class="text-right p-2" width="15%">vehicle        </td><td width="30%" class="border p-2"><b>'.$Result->customerVehicleName.'</b><small> ('.$Result->vehicle.')</small></td><td width="15%" class="border p-2 text-right">odometer </td><td class="text-left">&nbsp<b> '.($temp['vehicle_odometer']/1000).' km</b></td></tr>';
    	$header.='<tr class="border border-default"><td class="text-right p-2" width="15%">trailer        </td><td width="30%" class="border p-2"><b>'.$Result->trailer.'     </b></td>        <td width="15%" class="text-right p-2">driver&nbsp</td><td class="border text-left">&nbsp<b> '.$Result->driver.'</b></td></tr>';
    	$header.='<tr class="border border-default"><td class="text-right p-2" width="15%">date created   </td><td width="30%" class="border p-2"><b>'.$Result->createdDate.'  </b></td>       <td width="15%" class="text-right p-2">damages/issues&nbsp </td>  <td class="border text-danger text-left">&nbsp<b> '.$Result->damages.'</b></td></tr>';
    	$header.='<tr class="border border-default"><td class="text-right p-2" width="15%">checklist type </td><td width="30%" class="border p-2"><b>'.$temp['templateName'].'</b></td>        <td width="15%" class="text-right p-2">description&nbsp</td><td class="border text-left">&nbsp<b> '.$temp['templateDescription'].'</b></td></tr>';
	    $header.='</tbody></table>';
        $header.='<div class="row py-4"><div class="col"><div class=" "><div class="card-body p-0 ">';
        return $header;
    }
    function CreateCheckHeader(){
        $header  ='<table width="100%" cellpadding="10px"  >';
        $header .=' <thead>';
        $header .='     <tr class="small border border-default"><td class="" width="5%">Cat.</td><td class="" width="5%">Sub.</td><td class="border-right" width="25%">Check</td><td class="border-right" colspan="2" >Description</td><td width="35%">Status</td></tr>';
        $header .=' </thead>';
        $header .=' <tbody>';
        return $header;
    }
    function CreateCheckFooter($val){
        $header  ='     <tr class="border-top"><td colspan="8">&nbsp;</td></tr>';
        $header .='     <tr class="border border-primary "><td colspan="3">Date printed <b>: '.date('Y-m-d').' </b></td><td class="text-right" colspan="3">printed by : <b>'.$val->FleetManager.'</b></td>';
        $header .=' </tbody>';
        $header .=' <table>';
        return $header;
    }

    function AddCategory($temp){
        $icon = '<i class="fa '.$temp['cat_icon'].' fa-fw"></i> ';
        return '    <tr class="border-top pdc-small-line"><td colspan="8">&nbsp;</td></tr><tr class="border-0 "><td class="border-category" colspan="3"><b>'.$icon.' '.$temp['cat_name'].'</b></td><td class="border-category small" colspan="3">'.$temp['cat_description'].'</td></tr>';
    }
    function CloseCategory(){
//        return '    <tr class="border-0 small" colspan="6"><td >&nbsp</td></tr>';
        return '';
    }
    function AddSubCategory($temp){
        $icon='<i class=" fa '.$temp['subcat_icon'].' fa-fw"></i> ';
        return '    <tr class="border-left border-right "><td class="" ></td><td class="border-subcategory" colspan="5"><b>'.$icon.' '.$temp['subcat_name'].'</b></td></tr>';
    }
    function AddCIRowStart(){
        return '    <tr class="border-left border-right" ><td ></td><td colspan="2" class="border"><b>Perform the following checks</b></td><td class="border-right border-default" colspan="3"><span><ul class="ml-3">';
    }
    function AddCIRowEnd(){
        return '</ul></span></td></tr>';
    }
    function AddCheckItems($temp){
        return '<li class="py-1 small">'.$temp['check'].'</li>';

    }
    function AddDamage($temp){
        $bg='';$extraline='';$advise='';
        if ($temp['registered']==true){
            $bg     = " alert alert-danger ";
            $st     = '<i class="fas fa-tools fa-fw" ></i>';$tt='text-danger fw-bold';
            $advise = '<span class="small fw-normal '.$tt.'">'.$temp['event_description'].'</span>';
            if ($temp['severity'] == 1) { $st = '<i class="fas text-warning fa-circle-exclamation"></i>';}
            if ($temp['severity'] == 2) { $st = '<i class="fas text-danger fa-circle-exclamation"></i>';}
            if (!empty($temp['evidence_picture'])){ $extraline =' <img class="border p-2 fw-normal" alt="evidence picture" ></br>';}
            else { $extraline =' <i>no evidence picture available</i></br>';}
        } else {
            $st = '<i class="fas fa-check text-success"></i>';$tt='';
        }
        return '<tr  class="small p-1 '.$bg.' "><td class="border-left border-default p-1 '.$tt.'" style="text-align:center" ></td><td class="border border-default p-1 '.$tt.'" style="text-align:center">'.$st.'</td><td class="border border-default p-1  '.$tt.'" colspan="2" >'.$temp['title'].'</td><td class="border border-default small '.$tt.'" colspan="2">'.$temp['comments'].$extraline.$advise.'</td></tr>';

    }

	$query  = "
	    SELECT
            v.customerVehicleName, t.TrailerName,
            p.critical_damages,p.damages,p.add_notes,p.signature, p.checked, p.approvedFm, p.approvedDate,
            IF(u.lname='',u.username,concat(u.lname,', ',u.fname)) AS FleetManager,
            p.*,
             IF(d.Lastname = '',d.tachoDriverIdentification,concat(d.Lastname,', ',d.Surname)) AS driver
        FROM
           pdc_register p
           LEFT JOIN driver d ON p.driver = d.tachoDriverIdentification
           LEFT JOIN vehicles v ON p.vehicle = v.VIN
           LEFT JOIN trailers t ON p.trailer = t.VIN
           LEFT JOIN FAMReport f ON p.vehicle = f.vin
           LEFT JOIN rel_cust_user rcu ON rcu.Cust_ID = f.client
           LEFT JOIN USERS u  ON rcu.User_ID =u.id
        WHERE p.id='".$id."' ".$str2." ";
	$Q      = $db->query($query);
	$Result = $Q->first();

    $check = json_decode($Result->checklist,true);
    if (json_last_error()!=0) {
        echo "last JSON error :".json_last_error()."<BR>";
        echo "<small>".$Result->checklist;
        header('HTTP/1.0 405 Unauthorized');die();
    }
	$template=[];$categories=[];$hstr='';$cstr='';$scstr='';$cistr='';$distr='';

	$hstr   = CreateTemplateHeader($check,$Result);
    $cstr   = CreateCheckHeader();
	foreach ($check['categories'] as $val){
        $cstr.=AddCategory($val);
	    $cstr.=AddCIRowStart();
	    if (array_key_exists('checkitems',$val)){
            foreach ($val['checkitems'] as $cival){
                    $cstr.=AddCheckItems($cival);
            }
	    }
        $cstr.=AddCIRowEnd();
        if (array_key_exists('subcategories',$val)){
            foreach ($val['subcategories'] as $scval){
                $cstr.=AddSubCategory($scval);
                foreach ($scval['damageitems'] as $dival){        // showing damage-items within a subcategory
                        $cstr.=AddDamage($dival);
                }
            }
        }
        if (array_key_exists('damageitems',$val)){
            foreach ($val['damageitems'] as $dival){        // showing damage-items without a subcategory
                $cstr.=AddDamage($dival);
            }
        }
        $cstr   .= CloseCategory();
    }
    $cstr       .= CreateCheckFooter($Result);
    $Report     = $hstr.$cstr;
    $Filename   = 'rFMS-Connect_PreDepartureCheck_'.$Result->customerVehicleName.'.pdf';
    if(isset($_GET['SQL'])) {
        echo HTMLHeader();
        echo ShowDebugQuery($query,$Result,'',true);}
    else {
        if(isset($_GET['PDF'])) {
            require_once 'lib/createPDF.php';
            header('Content-Type: application/pdf');
            print_pdf($Report,$Filename);
        } else {
            echo $Report;}
    }
    exit;
?>
