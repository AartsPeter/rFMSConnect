<?php 
require_once '../users/init.php';
require_once 'lib/scriptHeader.php';

	if(isset($_GET['id'])) 			{$tId="'".$_GET['id']."'";} 	else {$tId="'0'";}


	$querytemp="SELECT * FROM pdc_template t WHERE t.id=".$tId." ORDER BY t.id ASC";
	$QTemp = $db->query($querytemp);
	$Temp = $QTemp->first();
//	print_r($Temp);
	$querycat="SELECT * FROM pdc_categories c WHERE c.active='1' AND c.template_id=".$tId." ORDER BY c.id ASC";
	$Qcat = $db->query($querycat);
	$Cat = $Qcat->results();
	$querysubcat="SELECT * FROM pdc_subcategories sc WHERE sc.active='1' AND sc.template_id=".$tId." ORDER BY sc.id ASC";
	$Qscat = $db->query($querysubcat);
	$SCat = $Qscat->results();
	$queryci="SELECT ci.id,ci.`check`,ci.description,ci.cat_id,ci.subcat_id FROM pdc_checkitems ci WHERE ci.template_id=".$tId." ORDER BY ci.id ASC";
	$Qci = $db->query($queryci);
	$ci = $Qci->results();
	$querydi="SELECT di.id,di.title,di.event_description,di.severity,di.type,di.cat_id,di.subcat_id FROM pdc_damageitems di WHERE di.template_id=".$tId." ORDER BY di.id ASC";
	$Qdi = $db->query($querydi);
	$di = $Qdi->results();

	$template=[];$categories=[];
	$template['templateId'] = $Temp->id;
	$template['templateName'] = $Temp->templateName;
    $template['templateDescription'] = $Temp->description;
    $template['vehicle'] = '';
    $template['trailer'] = '';
    $template['driver'] = $user->data()->driver_id;
    $template['name_pdc_executing'] = $user->data()->lname.', '.$user->data()->fname.' ('.$user->data()->driver_id.')';
    $template['createDate'] = '';
    $template['vehicle_odometer'] = '';
    $template['vehicle_warning'] = 0;
    $template['damages'] = 0;
    $template['severitydamages'] = 0;
    $template['status'] = 'draft';
    $template['add_notes'] = '';
    $template['signature'] = '';
	foreach ($Cat as $val){
	    $val->checked=false;
	    $val->damages=0;
	    foreach ($SCat as $scval){
	        if ($scval->cat_id==$val->id){
	            $scval->damages=0;
	            foreach ($di as $dival){
	                $dival->registered=false;
	                $dival->comments='';
	                $dival->evidence_picture='';
            	    if ($dival->subcat_id==$scval->id ){  $scval->damageitems[]=$dival;  }
            	}
	            $val->subcategories[]=$scval;
	        }
	    }
	    foreach ($ci as $cival){
	        if ($cival->cat_id==$val->id){  $val->checkitems[]=$cival;  }
	    }
	    foreach ($di as $dival){
            $dival->registered=false;
            $dival->comments='';
            $dival->evidence_picture='';
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
