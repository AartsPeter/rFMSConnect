<?php
require_once '../users/init.php';
require_once 'lib/scriptHeader.php';


    function CreatePDCHeader($temp){
        $header='<table width="100%" cellpadding="10" ><tbody><tr><td  colspan="4"><h4>'.$temp['templateName'].'</h4></td></tr>';
    	$header.='<tr ><td colspan="4" class="card-header border">'.$temp['templateDescription'].'</td></tr><tr><td colspan="4">&nbsp</td></tr>';
    	$header.='<tr ><td class="pt-4" width="15%">vehicle             </td><td width="35%" class="border m-2">'.$temp['vehicle'].'    </td><td width="15%">odometer      </td><td width="35%" class="border">'.$temp['vehicle_odometer'].'</td></tr>';
    	$header.='<tr ><td class="pt-4" width="15%">trailer             </td><td width="35%" class="border m-2">'.$temp['trailer'].'    </td><td width="15%">driver        </td><td class="border">'.$temp['driver'].'</td></tr>';
    	$header.='<tr ><td class="pt-4" width="15%">registration date   </td><td width="35%" class="border m-2">'.$temp['createDate'].' </td><td colspan="2"></td></tr>';
        $header.='</tbody></table>';
        $header.='<div class="row pt-4"><div class="col"><div class="card "><div class="card-header h4">Driver Pre Departure Checklist </div><div class="card-body p-0 ">';
        return $header;
    }
    function CreateCheckHeader(){
        $header='<table width="100%" cellpadding="10" >';
        $header.='<thead><tr><td class="border-right" width="5%">Categorie</td><td class="border-right" width="15%">SubCategorie</td><td class="border-right" width="25%">Title</td><td class="border-right" >Description</td><td class="border-right" width="2%">Severity</td><td width="20%">Status</td></tr>';
        $header.='</thead>';
        $header.='<tbody>';
        return $header;
    }
    function AddCategory($temp){
        $icon='<i class="'.$temp->cat_icon.' fa-fw"></i> ';
        return '<tr class="border border-secondary bg-secondary text-light"><td colspan="2"><b>'.$icon.' '.$temp->cat_name.'</b></td><td class="small border-left border-secondary" colspan="2">'.$temp->cat_description.'</td><td class="small border-left border-secondary">'.$temp->cat_type_name.'</td><td class="small border-left border-secondary "></td></tr>';
    }
    function AddSubCategory($temp){
        $icon='<i class="'.$temp->subcat_icon.' fa-fw"></i> ';
        return '<tr ><td class="border-0"></td><td class="border" colspan="5"><b>'.$icon.' '.$temp->subcat_name.'</b></td></tr>';
    }
    function AddCIRowStart(){
        return '<tr><td class="border-0" colspan="1"></td><td class="border-left border-bottom"><b>Perform the following checks</b></td><td class="border-left border-bottom" colspan="4"><span><ul>';
    }
    function AddCIRowEnd(){
        return '</ul></span></td></tr>';
    }
    function AddCheckItems($temp){
        return '<li class="small">'.$temp->check.'</li>';

    }
    function AddDamage($temp){
        $st = '<i class="fad fa-tools fa-fw" ></i>';
        if ($temp->severity==1) { $st = '<i class="fad fa-tools fa-fw text-warning" > </i>';}
        if ($temp->severity==2) { $st = '<i class="fad fa-tools fa-fw text-danger" ></i>';}
        return '<tr class="small"><td class="border-0" colspan="2"></td><td class="border-left border-bottom">'.$temp->title.'</td><td class="border-left border-bottom">'.$temp->event_description.'</td><td class="border-left border-bottom"><b>'.$st.'</td><td class="border border-secondary card-header">&nbsp</td></tr>';
    }

	$querytemp="SELECT * FROM pdc_template t WHERE t.id=".$id." ORDER BY t.id ASC";
	$QTemp = $db->query($querytemp);
	$Temp = $QTemp->first();
//	print_r($Temp);
	$querycat="SELECT * FROM pdc_categories c WHERE c.template_id=".$id." ORDER BY c.id ASC";
	$Qcat = $db->query($querycat);
	$Cat = $Qcat->results();
	$querysubcat="SELECT * FROM pdc_subcategories sc WHERE sc.template_id=".$id." ORDER BY sc.id ASC";
	$Qscat = $db->query($querysubcat);
	$SCat = $Qscat->results();
	$queryci="SELECT ci.id,ci.`check`,ci.description,ci.cat_id,ci.subcat_id FROM pdc_checkitems ci WHERE ci.template_id=".$id." ORDER BY ci.id ASC";
	$Qci = $db->query($queryci);
	$ci = $Qci->results();
	$querydi="SELECT di.id,di.title,di.event_description,di.severity,di.type,di.cat_id,di.subcat_id FROM pdc_damageitems di WHERE di.template_id=".$id." ORDER BY di.id ASC";
	$Qdi = $db->query($querydi);
	$di = $Qdi->results();

	$template=[];$categories=[];$hstr='';$cstr='';$scstr='';$cistr='';$distr='';
	$template['templateId'] = $Temp->id;
	$template['templateName'] = $Temp->templateName;
    $template['templateDescription'] = $Temp->description;
    $template['vehicle'] = '';
    $template['trailer'] = '';
    $template['driver'] = $user->data()->driver_id;
    $template['createDate'] = '';
    $template['vehicle_odometer'] = '';
    $template['vehicle_warning'] = 0;
    $template['damages'] = 0;
    $template['severitydamages'] = 0;
    $template['status'] = 'draft';
    $template['add_notes'] = '';
    $template['signature'] = '';

    $hstr=CreateTemplateHeader($template);
    $cstr.=CreateCheckHeader();

	foreach ($Cat as $val){
        $cstr.=AddCategory($val);
	    $cstr.=AddCIRowStart();
	    $val->checked=false;
	    $val->damages=0;
	    foreach ($SCat as $scval){
	        if ($scval->cat_id==$val->id){
	            $scval->damages=0;
	            $cstr.=AddSubCategory($scval);
	            foreach ($di as $dival){
	                $dival->registered=false;
	                $dival->comments='';
	                $dival->evidence_picture='';
	                $cstr.=AddDamage($dival);
            	    if ($dival->subcat_id==$scval->id ){  $scval->damageitems[]=$dival;  }
            	}
	            $val->subcategories[]=$scval;
	        }
	    }
	    foreach ($di as $dival){
            $dival->registered=false;
            $dival->comments='';
            $dival->evidence_picture='';
            $cstr.=AddDamage($dival);
	        if ($dival->cat_id==$val->id && $dival->subcat_id==0 ){  $val->damageitems[]=$dival;  }
	    }
    }
    $template['categories']=$Cat;
//    $template['checkitems']=$ci;
//    $template['damageitems']=$di;

if(isset($_GET['SQL']))
{
	echo "<p style='background-color:#eee;padding:1rem;'><B>Query :</b><BR><BR>".$querytemp."</p>";
	echo "<p style='background-color:#eee;padding:1rem;'><B>Query :</b><BR><BR>".$querycat."</p>";
	echo "<p style='background-color:#eee;padding:1rem;'><B>Query :</b><BR><BR>".$querysubcat."</p>";
	echo "<p style='background-color:#eee;padding:1rem;'><B>Query :</b><BR><BR>".$queryci."</p>";
	echo "<p style='background-color:#eee;padding:1rem;'><B>Query :</b><BR><BR>".$querydi."</p>";
	echo "<p style='background-color:#eee;padding:1rem;border:1px solid #999;font-size:80%;'>"._format_json(json_encode($template),true);
}
else
	{
	echo json_encode($template); 
}	
?>
