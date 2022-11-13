<?php
require_once '../users/init.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/header.php'; 
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; 
error_reporting(E_ALL);

if (!securePage($_SERVER['PHP_SELF'])){die();} 
//PHP Goes Here!
$errors = [];
$successes = [];
$Status = 0;
$Damage = 0;
$Maintenance = 0;


$SearchId = Input::get('id');
$validation = new Validate();
//Check if selected user exists
if(!vehicleIdExists($SearchId)){
  Redirect::to("vehicles.php"); die();
}
	if (isset($_SESSION['UGtext'])){$SCNumber=$_SESSION['UGselected'];$SCName=$_SESSION['UGtext'];}
	else {
		if ($user->data()->cust_id!='0'){$SCNumber=$user->data()->cust_id;$SCName=array_search($user->data()->cust_id, array_column($Group, 'name'));;}
		else {$SCName='All Groups';$SCNumber='*';}
	}	
	if ($user->data()->cust_id=='0'){
		$str2="";$str3="";} 
	else {
		$str2="and rel_cust_user.User_ID='".$user->data()->id."'";
		$str3="INNER JOIN REL_CUST_USER ON rel_cust_user.Cust_ID=famreport.client";}
	if ($SCNumber=='*')	{
		$str1="";} 
	else {
		$str1="and famreport.client='".$SCNumber."'";}

$db     = DB::getInstance();
$query  ="	SELECT 
				vehicles.*,customers.name as Name,customers.country_id as country, customers.Service_Homedealer, famreport.obu_serial,famreport.obu_sw_version,
				IF(driver.Lastname='',driver.tachoDriverIdentification,concat(driver.Lastname,', ',driver.Surname)) AS driver,
				(SELECT count(vin) FROM trailers WHERE trailers.vehicleVIN = vehicles.VIN AND trailers.copplingStatus=1 ) AS TrailerCount,
				(SELECT count(vin) FROM pdc_damage WHERE pdc_damage.vin = vehicles.vin  AND pdc_damage.repairStatus=0) AS DamageCount,
				(SELECT count(vin) FROM pdc_damage WHERE pdc_damage.vin = vehicles.vin  AND pdc_damage.repairStatus=0 and pdc_damage.severity=0) AS SevereDamageCount
			FROM vehicles 
				INNER JOIN FAMReport ON FAMReport.vin=vehicles.VIN 
				INNER JOIN customers ON customers.accountnumber=FAMReport.client  
				LEFT JOIN driver ON driver.tachoDriverIdentification=vehicles.LastDriver  ".$str3."
			WHERE  vehicles.id=".$SearchId." ".$str1." ".$str2;
$Q      =$db->query($query);
$vehicledetails =$Q->first();
if ($vehicledetails->id!=$SearchId){ Redirect::to("vehicles.php"); die();}
if ($vehicledetails->TrailerCount>='0'){
	$query2 =$db->query("SELECT trailers.* FROM trailers WHERE trailers.vehicleVIN='".ucfirst($vehicledetails->VIN)."' and trailers.copplingStatus=1");
}
$trailerdetails =$query2->results();
if (($vehicledetails->serviceDistance/1000)<6000)  { $Maintenance++; }
if ($vehicledetails->FuelLevel<7)           { $Status++;$errors[]=lang("VEHEDIT_FUELLEVEL",""); }
if ($vehicledetails->CatalystFuelLevel<7)   { $Status++;$errors[]=lang("VEHEDIT_ADBLUELEVEL",""); }
if ($vehicledetails->SevereDamageCount>0)   { $Damage++; }
if ($Status>0)      {$StatusMSG=' alert-warning';} else {$StatusMSG='';}
if ($Maintenance>0) {$MaintenanceMSG=' alert-warning';$errors[]=lang("VEHEDIT_MAINTENANCE","");} else {$MaintenanceMSG='';}
if ($Damage>0)      {$DamageMSG=' text-danger';$errors[]=lang("VEHEDIT_SEVEREDAMAGE","");} else {$DamageMSG='';}

//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
		die('Token doesn\'t match!');
    } else 
	{
		if ($vehicledetails->customerVehicleName != $_POST['customerVehicleName']){
			$displayname = Input::get("customerVehicleName");
			$fields=array('customerVehicleName'=>$displayname);
			$validation->check($_POST,array('customerVehicleName' => array(	'display' => 'customerVehicleName',	'required' => true,	'min' => 1,	'max' => 50	) ));
			if($validation->passed()){
				$db->update('vehicles',$SearchId,$fields); 
				$successes[] = "VehicleName Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($vehicledetails->LicensePlate != $_POST['LicensePlate']){
			$displayname = Input::get("LicensePlate");
			$fields=array('LicensePlate'=>$displayname);
			$validation->check($_POST,array('LicensePlate' => array('display' => 'LicensePlate','required' => true,	'min' => 0,	'max' =>12) ));
			if($validation->passed()){
				$db->update('vehicles',$SearchId,$fields); 
				$successes[] = "LicensePlate Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($vehicledetails->totalFuelTankVolume != $_POST['totalFuelTankVolume']){
			$displayname = Input::get("totalFuelTankVolume");
			$fields=array('totalFuelTankVolume'=>$displayname);
			$validation->check($_POST,array('totalFuelTankVolume' => array('display' => 'totalFuelTankVolume','required' => true,	'min' => 0,	'max' => 5) ));
			if($validation->passed()){
				$db->update('vehicles',$SearchId,$fields); 
				$successes[] = "totalFuelTankVolume Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		if ($vehicledetails->noOfAxles != $_POST['noOfAxles']){
			$displayname = Input::get("noOfAxles");
			$fields=array('noOfAxles'=>$displayname);
			$validation->check($_POST,array('noOfAxles' => array('display' => 'noOfAxles',	'required' => true,	'min' => 1,	'max' => 24	) ));
			if($validation->passed()){
				$db->update('vehicles',$SearchId,$fields); 
				$successes[] = "noOfAxles Updated";
			} 
			else { ?><div id="form-errors"><?=$validation->display_errors();?></div> <?php  }
		}
		$db = DB::getInstance();
		$query  ="	SELECT 
						vehicles.*,customers.name as Name,customers.country_id as country, customers.Service_Homedealer, famreport.obu_serial,famreport.obu_sw_version,
						IF(driver.Lastname='',driver.tachoDriverIdentification,concat(driver.Lastname,', ',driver.Surname)) AS driver,
						(SELECT count(vin) FROM trailers WHERE trailers.vehicleVIN = vehicles.VIN AND trailers.copplingStatus=1 ) AS TrailerCount,
						(SELECT count(vin) FROM pdc_damage WHERE pdc_damage.vin = vehicles.vin  AND pdc_damage.repairStatus=0) AS DamageCount,
						(SELECT count(vin) FROM pdc_damage WHERE pdc_damage.vin = vehicles.vin  AND pdc_damage.repairStatus=0 and pdc_damage.severity=0) AS SevereDamageCount
					FROM vehicles 
						INNER JOIN FAMReport ON FAMReport.vin=vehicles.VIN 
						INNER JOIN customers ON customers.accountnumber=FAMReport.client  
						LEFT JOIN driver ON driver.tachoDriverIdentification=vehicles.LastDriver  ".$str3."
					WHERE  vehicles.id=".$SearchId." ".$str1." ".$str2;
		$Q      =$db->query($query);
		$vehicledetails =$Q->first();
		if ($vehicledetails->TrailerCount>='0'){
	        $query2 =$db->query("SELECT trailers.* FROM trailers WHERE trailers.vehicleVIN='".ucfirst($vehicledetails->VIN)."' and trailers.copplingStatus=1");
        }
        $trailerdetails =$query2->results();
	}
}
$x=0;
?>

	<main role="main">
		<section class="section section-full ">
			<div class="container-fluid">
				<div class="pagina ">
				    <div class="inner-pagina">
				    <div class="row">
						<div class="col-12 page-title">Resources » Vehicles » details</div>
						<div class="col-12 col-lg-4 ">
							<div class="card shadow mb-3">
								<div class="card-header">
									<div class="row">
										<div class="col-9 info-box-number text-primary large"><i class="fad fa-fw fa-truck gray"></i><?php echo ucfirst($vehicledetails->customerVehicleName);?> </div>
										<?php 
											if($vehicledetails->TrailerCount>'0') {echo '<div class="col-3 ml-auto info-box-number  text-right"><i class="fas fa-link primary"></i>'.ucfirst($vehicledetails->TrailerCount).' </div>';}
											else { echo '<div class="col-3 ml-auto info-box-number text-right"><i class="fas fa-unlink gray" title="'.lang("VEHEDIT_NOTRAILER","").'"></i></div>';}?>
									</div>
							<?php	if($vehicledetails->TrailerCount>'0') { $count=0;?>
									<div class="row px-3">
										<label class="col-12 p-0 small">trailer</label>
										<?php foreach ($trailerdetails as $row){ 
												if ($count==0){?>
													<div class="info-box-number primary "> <i class="fad fa-fw fa-trailer gray"></i> <?php echo ucfirst($row->trailerName);?></div>
										<?php   }else { ?>
													<div class="info-box-number primary mr-auto">&nbsp;-&nbsp;<?php echo ucfirst($row->trailerName);?></div>
										<?php	}
											$count++;
										}	?>
									</div>
							<?php	}?>	
									<div class="row pt-3">
										<div class="col-12">
											<div class="small" ><i class="fad fa-clock fa-fw"></i><i class="fad fa-fw"></i><?php echo ucfirst($vehicledetails->LastActivity);?></div>
										</div>										
										<div class="col-12">
											<div class="small" id="VLAddress" ></div>
										</div>												
									</div>
								</div>
								<div class="card-body d-none d-sm-block">
									<form class="form-signup " action="" method="POST" id="editvehicle-form">
										<div class="row p-0 ">
											<div class="col-12 col-md-6">
												<label for="customerVehicleName">vehicle name</label>
												<input class="form-control" type="text" name="customerVehicleName" id="customerVehicleName"  placeholder="" value="<?=$vehicledetails->customerVehicleName?>" >
												
											</div>
											<div class="col-12 col-md-6">
												<label>license plate</label>
												<input class="form-control" type="text" name="LicensePlate" id="LicensePlate" placeholder="" value="<?php echo ucfirst($vehicledetails->LicensePlate);?>" >
											</div>
											<div class="col-6">
												<label>tank volume</label>
												<input class="form-control" type="text" name="totalFuelTankVolume" id="totalFuelTankVolume" placeholder="" value="<?php echo ucfirst($vehicledetails->totalFuelTankVolume);?>" >
											</div>
											<div class="col-6">
												<label>axles configuration </label>
												<input class="form-control" type="text" name="noOfAxles" id="noOfAxles" placeholder="" value="<?php echo ucfirst($vehicledetails->noOfAxles);?>" >
											</div>														
										</div>
										<div class="row">
											<div class="col-12 pt-3 mr-auto"><?=resultBlock($errors,$successes);?><?=$validation->display_errors();?></div>
										</div>

								</div>
								<div class="card-footer d-flex">
									<input type="hidden" value="<?=Token::generate();?>" name="csrf">
								<?php if (checkMenu(3,$user->data()->id)){  //Links for permission level 3 (default Fleetmanager) ?>
									<input class="btn btn-primary mr-2" type='submit' name='changevehicle' value="<?=lang("VEHEDIT_UPDATE","");?>"/>
								<?php }?>
									<a class="btn btn-secondary" href="vehicles.php"><?=lang("VEHEDIT_CANCEL","");?></a>
									<a class="btn btn-info ml-auto" aria-disabled="true" href="vehiclehistory.php"><?=lang("VEHEDIT_HISTORY","");?></a>
								</div>
                                </form>
							</div>
						</div>
						<div class="col-12 col-md-8 ml-auto  ">
							<div class="row">
								<div class="col-12" id="#VehicleDetails">
								    <div class="nav-tabs-custom "  id="PDCTabs">
                                        <ul class="nav nav-tabs pl-3" >
                                            <li class="nav-item"> <a class="nav-link active" href="#PDCTAB1" data-toggle="tab" onclick=""><i class="fad fa-truck"></i></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB2" data-toggle="tab" onclick="ShowVehicleMOTStatus(<?=$SearchId?>);ShowVehicleMaintenanceHistory(<?=$SearchId?>);" title="maintenance"><i class="fad fa-wrench"></i></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB3" data-toggle="tab" onclick="ShowVehicleActivity(<?=$SearchId?>);" title="trips"><i class="fad fa-route"></i></a></li>
                                            <li class="nav-item "> <a class="nav-link <?=$DamageMSG?>" href="#PDCTAB4" data-toggle="tab" onclick="ShowVehicleRD(<?=$SearchId?>,false);" title="registered damages"><i class="fad fa-tools"></i><span class="badge badge-danger"><?=$vehicledetails->DamageCount?></span></a></li>
                                            <?php if (ucfirst($vehicledetails->TrailerCount)>0){?>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB5" data-toggle="tab" onclick="" title="trailer"><i class="fad fa-trailer"></i></a></li>
                                            <?php }?>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB6" data-toggle="tab"  onclick="" title="tyre pressure monitoring"><i class="fad fa-tire-pressure-warning"></i></a></li>
                                            <li class="nav-item"> <a class="nav-link" href="#PDCTAB7" data-toggle="tab"  onclick="ShowVehicleGeofences(<?=$SearchId?>);" title="geofences"><i class="fad fa-draw-polygon fa-fw " aria-hidden="true"></i></a></li>
                                        </ul>
                                        <div class="tab-content card">
                                            <div class="tab-pane active" id="PDCTAB1" role="tabpanel">
												<div class="card-body">
                                                    <div class="card-title m-0 mb-3">Vehicle details</div>
													<div class="row ">
														<div class="col-6 col-md-3">
															<label>vin </label>
															<div class="form-control-ro" ><?php echo ucfirst($vehicledetails->VIN);?></div>

														</div>
														<div class="col-6 col-md-3">
															<label>brand / model </label>
															<div class="form-control-ro" ><?php echo ucfirst($vehicledetails->brand)." / ".ucfirst($vehicledetails->model);?></div>
														</div>
														<div class="col-6 col-md-3">
															<label>connect box</label>
															<div class="form-control-ro" ><?php echo ucfirst($vehicledetails->obu_serial);?></div>
														</div>
														<div class="col-6 col-md-3">
															<label>version </label>
															<div class="form-control-ro" ><?php echo ucfirst($vehicledetails->obu_sw_version);?></div>
														</div>
													</div>
													<div class="row ">
														<div class="col-6 col-md-3">
															<label>odometer </label>
															<div class="form-control-ro" ><?php echo ucfirst($vehicledetails->OdoMeter/1000);?> km</div>
														</div>
														<div class="col-6 col-md-3">
															<label>service distance </label>
															<div class="form-control-ro" ><?php echo ucfirst($vehicledetails->serviceDistance/1000);?> km</div>
														</div>
													</div>
													<div class="row">
														<div class="col-6 col-md-3">
															<label>total fuel used </label>
															<div class="form-control-ro" ><?=$vehicledetails->TotalFuelUsed/1000?> L</div>
														</div>
														<div class="col-6 col-md-3">
															<label>total engine hours</label>
															<div class="form-control-ro" ><?=$vehicledetails->TotalEngineHours?> h</div>
														</div>
														<div class="col-6 col-md-3">
															<label>total idle fuel used</label>
															<div class="form-control-ro" ><?=$vehicledetails->EngineTotalIdleFuelUsed?> L</div>
														</div>
														<div class="col-6 col-md-3">
															<label>total idle hours</label>
															<div class="form-control-ro" ><?=$vehicledetails->EngineTotalIdleHours?> h</div>
														</div>
													</div>
													<div class="row">
														<div class="col-6 col-md-3">
															<label>ambient air temperature</label>
															<div class="form-control-ro" ><?=$vehicledetails->ambientAirTemperature?> <span>&#176;C</span></div>
														</div>
														<div class="col-6 col-md-3">
															<label>engine collant temperature</label>
															<div class="form-control-ro" ><?=$vehicledetails->engineCoolantTemperature?> <span>&#176;C</span> </div>
														</div>
														<div class="col-6 col-md-3">
															<label>fuel level</label>
															<div class="form-control-ro" ><?=$vehicledetails->FuelLevel?> %</div>
														</div>
														<div class="col-6 col-md-3">
															<label>ad-blue level</label>
															<div class="form-control-ro" ><?=$vehicledetails->CatalystFuelLevel?> %</div>
														</div>
														<div class="col-6 col-md-3">
															<label>service break air pressure</label>
															<div class="form-control-ro" ><?=$vehicledetails->serviceBrakeAirPressureCircuit1/100000?> / <?=$vehicledetails->serviceBrakeAirPressureCircuit2/100000?> bar</div>
														</div>
														<div class="col-6 col-md-3">
															<label>last driver</label>
															<div class="form-control-ro" ><?=$vehicledetails->driver?> </div>
														</div>
														<div class="col-12 ">
															<label>display warning lights</label>
															<div class="d-flex" >
															<?php if (ucfirst($vehicledetails->TT_PAR_BRA)!= 'OFF')		{ echo '<div class="telltale-'.ucfirst($vehicledetails->TT_PAR_BRA).'">parking brake</div>';}?>
															<?php if (ucfirst($vehicledetails->TT_FUE_LEV)!= 'OFF')		{ echo '<div class="telltale-'.ucfirst($vehicledetails->TT_FUE_LEV).'">fuel level</div>';}?>
															<?php if (ucfirst($vehicledetails->TT_ENG_COO_TEM)!= 'OFF')	{ echo '<div class="telltale-'.ucfirst($vehicledetails->TT_ENG_COO_TEM).'">engine coolant temp</div>';}?>
															<?php if (ucfirst($vehicledetails->TT_ENG_OIL)!= 'OFF')		{ echo '<div class="telltale-'.ucfirst($vehicledetails->TT_ENG_OIL).'">engine oil</div>';}?>
															<?php if (ucfirst($vehicledetails->TT_ENG_MIL_IND)!= 'OFF')	{ echo '<div class="telltale-'.ucfirst($vehicledetails->TT_ENG_MIL_IND).'">engine mil indicator</div>';}?>
															<?php if (ucfirst($vehicledetails->TT_ENG_EMI_FAI)!= 'OFF')	{ echo '<div class="telltale-'.ucfirst($vehicledetails->TT_ENG_EMI_FAI).'">engine emmision</div>';}?>
															<?php if (ucfirst($vehicledetails->TT_ADB_LEV)!= 'OFF')		{ echo '<div class="telltale-'.ucfirst($vehicledetails->TT_ADB_LEV).'">ad-blue level</div>';}?>
															</div>
														</div>
													</div>
												</div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB2" role="tabpanel">
												<div class="card-body ">
												    <div class="card-title m-0 mb-3">maintenance</div>
													<div class="row ">
														<div class="col-6 col-md-3">
															<label>last MOT</label>
															<div class="form-control-ro"  id="last_service_date">-</div>
														</div>
														<div class="col-6 col-md-3">
															<label>Expiration MOT</label>
															<div class="form-control-ro" id="next_service_date">-</div>
														</div>
														<div class="col-6 col-md-3">
															<label>maintenance distance</label>
														    <?php if ($vehicledetails->serviceDistance>0){?>
															<div class="form-control-ro" id="service_distance" ><?php echo ucfirst($vehicledetails->serviceDistance/1000);?> km</div>
														    <?php }else { ?><div class="form-control-ro"> - </div><?php }?>
														</div>
													</div>
											        <div class="row">
														<div class="col-6 col-md-3">
															<label>last Tacho calibration</label>
															<div class="form-control-ro"  id="tachograph_last_date">-</div>
														</div>
														<div class="col-6 col-md-3">
															<label>Expiration Tacho Calibration</label>
															<div class="form-control-ro" id="tachograph_revoke_date">-</div>
														</div>
													</div>
													<div class="row pt-4">
														<div class="col-12 p-0">
														    <div class="card-title mb-3">maintenance history</div>
														    <div class="col-12">
															    <table class="display table responsive noWrap" id="tableVehicleMaintenance"></table>
															</div>
														</div>
													</div>
												</div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB3" role="tabpanel">
                                                <div class="card-body ">
                                                    <div class="card-title m-0 mb-3">trip activity</div>
                                                    <table class="display table responsive noWrap" id="tableVehicleActivity"></table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB4" role="tabpanel">
                                                <div class="card-body ">
                                                    <div class="card-title m-0 mb-3">registered damages</div>
                                                    <table class="display table" id="VehicleDamage"></table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB5" role="tabpanel">
										    <?php foreach ($trailerdetails as $value) {?>
												<div class="card-body" id="DetailsTrailer<?php echo $x;?>">
												    <div class="card-title m-0 mb-3">trailer details</div>
													<div class="row">
														<div class="col-6 col-md-3">
															<label>trailer name</label>
															<div class="form-control-ro"> <?php echo ucfirst($value->trailerName);?></div>
														</div>
														<div class="col-6 col-md-3">
															<label>brand</label>
															<div class="form-control-ro" ><?php echo ucfirst($value->brand);?></div >
														</div>
														<div class="col-6 col-md-3">
															<label>model</label>
															<div class="form-control-ro" ><?php echo ucfirst($value->model);?></div >
														</div>
													</div>
													<div class="row">
														<div class="col-6 col-md-3">
															<label>vin </label>
															<div class="form-control-ro" ><?php echo ucfirst($value->vin);?></div>
														</div>
														<div class="col-6 col-md-3">
															<label>license plate</label>
															<div class="form-control-ro" ><?php echo ucfirst($value->LicensePlate);?></div >
														</div>
														<div class="col-6 col-md-3">
															<label>axles configuration </label>
															<div class="form-control-ro"> <?php echo ucfirst($value->noOfAxles);?></div>
														</div>
														<div class="col-6 col-md-3">
															<label>service distance </label>
															<div class="form-control-ro" ><?php echo ucfirst($value->serviceDistance/1000);?> km</div>
														</div>
													</div>
													<div class="row">
														<div class="col-6 col-md-3">
															<label>ambient air temperature</label>
															<div class="form-control-ro" ><?php echo ucfirst($value->ambientAirTemperature);?> </div>
														</div>
													</div>
												</div>
    										<?php
	    										$x++;
											}?>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB6" role="tabpanel">
                                                <div class="card-body ">
                                                    <div class="card-title m-0 mb-3">tyre pressure monitoring</div>
                                                    <table class="display table" id="TPMSTable"></table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="PDCTAB7" role="tabpanel">
                                                <div class="card-body ">
                                                    <div class="card-title m-0 mb-3">geofences</div>
                                                    <table class="display table responsive noWrap" id="tableVehicleGeofence"></table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
							</div>				
						</div>
					</div>
					</div>
				</div>
			</div>
		</section>
	</main>


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>
	<script async src="<?=$us_url_root?>plugins/datatables/datatables.min.js"></script>

<script>
window.onload=function(){
	Convert2Address([<?php echo '"'.ucfirst($vehicledetails->last_Latitude).'"';?>, <?php echo '"'.ucfirst($vehicledetails->last_Longitude).'"';?>,'VLAddress']);
}
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
